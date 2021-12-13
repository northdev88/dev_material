<?php
$GLOBALS['debug_mode'] = true;

require_once("config.inc.php");

class decoder
{
    private $handler;
    public $CONFIG;

    public function __construct($rechnungsnummer) {

        $this->CONFIG = $GLOBALS['config'];
        if ($GLOBALS['debug_mode'] == false) {
           $this->handler = file($this->get_ESOL($rechnungsnummer));
        }
        else {
            $this->handler = file($this->CONFIG['path_test_esol']);
        }
    }


    public function get_ESOL($rn)
    {
        $rechnungsnummer = explode('-', $rn);
        $month = substr($rechnungsnummer[0], -2);
        $year  = $rechnungsnummer[2];
        $kassen_kz = $this->get_arzw_kasse_kz($rechnungsnummer[1]);
        switch ($kassen_kz) {
            case 29:
                $path_esol = $this->CONFIG['dta302_aok'] . "/" . $year . $month . "*/*.un";
                break;
            case '2c':
                $path_esol = $this->CONFIG['dta302_aok'] . "/" . $year . $month . "*/*.un";
                break;
            default:
                exit("Fatal Error: Kassenkennzeichen nicht gefunden. Für Kasse $rechnungsnummer[1] wurde folgendens Kennzeichen ermittelt: $kassen_kz");
        }
        foreach (glob($path_esol) as $files) {
            $file = file($files);
            foreach ($file as $segment) {
                $segment = explode("+", $segment);
                if($segment[0] == "REC")
                {
                    $search = explode(':', $segment[1]);
                    if ($search[0] == $rn) {
                        return realpath($files);
                    }
                }
            }
        }

        exit('Rechnungsnummer nicht gefunden');
    }

    public function get_arzw_kasse_kz($kasse) {
        $kasse = substr($kasse, 0,6);
        $dbase_handler = dbase_open($this->CONFIG['path_kasse_dbf'], 0);
        $cnt = 0;
        $size = dbase_numrecords($dbase_handler);

        while ($cnt < $size) {
            $cnt++;
            $row = dbase_get_record_with_names($dbase_handler, $cnt);
            if($row['KASSE'] == $kasse) {
                return trim($row['IKS']);
            }
        }
        return '';
    }

    public function get_patient_data($patient_number) {
        $data['patient_data'] = array();
        $data['header'] = array();
        $data['taxen'] = array();
        $match = false;
        $cnt_patient = 0;
        $cnt_taxe = 0;
        foreach ($this->handler as $segment)
        {
            $segment = explode('+', $segment);

            //Kopfelement UNB + UNZ
            if($segment[0] == "UNB")
            {
                $data['header'] = array_merge($data['header'], array
                (
                    //"syntax"                    =>  $segment[1],  //für Anzeige im Template entfernt
                    "ik_absender"               =>  $segment[2],
                    "ik_empfaenger"             =>  $segment[3],
                    "zeit_erstellung"           =>  $segment[4],
                    "datenaustauschreferenz"    =>  $segment[5], //analog Segment[2] in UNZ
                    "leistungsbereich"          =>  $segment[6],
                    "anwendungsreferenz"        =>  $segment[7],
                    "testindikator"             =>  $segment[8]
                ));
            }
            //Schlusselement UNZ
            /*if($segment[0] == "UNZ") {
                $data['header'] = array_merge($data['header'], array(
                    "anzahl_unh"                =>  (int)$this->helper->num2db($segment[1])
                ));
            }
            */
            if ($segment[0] == 'INV')
            {
                if (trim($segment[1]) == trim($patient_number)) {
                    $match = true;
                    $patient_data = array();
                    $cnt_patient++;
                    $patient_data = array_merge($patient_data, array(
                        'verornung' => $cnt_patient,
                        'vers_nr'   => $segment[1],
                        'status'    => $segment[2]
                    ));
                }
            }
            if (trim($segment[0] == 'NAD') && $match == true) {
                    $patient_data = array_merge($patient_data, array(
                    'nachname'          => $segment[1],
                    'vorname'           => $segment[2],
                    'geburtsdatum'      => $segment[3],
                    'strasse'           => $segment[4],
                    'plz'               => $segment[5],
                    'ort'               => $segment[6]
                ));

                array_push($data['patient_data'], $patient_data);
            }

            if (trim($segment[0] == 'EHE') && $match == true) {
                $cnt_taxe++;
                $data['taxen'][$cnt_taxe] = array();
                array_push($data['taxen'], array(
                    'taxe_nr'       => $cnt_taxe,
                    'pzn'           => $segment[2],
                    'faktor'        => $segment[3],
                    'preis'         => $segment[4],
                    'zuzahlung'     => $segment[6],
                    'actk'          => $segment[1],
                    'datum'         => $segment[5]
                ));
            }
            if (trim($segment[0] == 'BES') && $match == true) {
                $match = false;
                $cnt_taxe = 0;
            }
        }

        return $data;
    }
}

$decoder_handler    = new decoder($_GET['rechnungsnr']);
echo json_encode($decoder_handler->get_patient_data($_GET['$decoder_handler    = new decoder($_GET['rechnungsnr']);
    echo json_encode($decoder_handler->get_patient_data($_GET['versnr']));']));
