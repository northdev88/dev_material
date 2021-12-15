<?php
$GLOBALS['debug_mode'] = false;

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

    public function get_path_esol_heilmi($kassen_kz, $rechnungsnummer) {
        $month = substr($rechnungsnummer[0], -2);
        $year  = substr($rechnungsnummer[2], 0, 2);
        if (substr($kassen_kz, 0, 1) == 2)
        {
            $path_esol = $this->CONFIG['dta302_aok'] . "/" . $year . $month . "*/*.un";
        }
        else
        {
            switch ($kassen_kz) {
                case 6:
                    $path_esol = $this->CONFIG['dta302_knapp'] . "/*.un";
                    break;
                case 8:
                    $path_esol = $this->CONFIG['dta302_lkk'] . "/*.un";
                    break;
                case 9:
                    $path_esol = $this->CONFIG['dta302_dak'] . "/*.un";
                    break;
                case 1:
                    $path_esol = $this->CONFIG['dta302_ddg'] . "/*.un";
                    break;
                case 30:
                    $path_esol = $this->CONFIG['dta302_mobil_isc'] . "/*.un";
                    break;
                case 33:
                    $path_esol = $this->CONFIG['dta302_convema'] . "/*.un";
                    break;
                case 34:
                    $path_esol = $this->CONFIG['dta302_condates'] . "/*.un";
                    break;
                case 4:
                    $path_esol = $this->CONFIG['dta302_bkk'] . "/*.un";
                    break;
                case 5:
                    $path_esol = $this->CONFIG['dta302_ikk'] . "/*.un";
                    break;
                case 'c':
                    $path_esol = $this->CONFIG['dta302_bund'] . "/*.un";
                    break;
                case 'Ö':
                    $path_esol = $this->CONFIG['dta302_kv_service'] . "/*.un";
                    break;
                case 'r':
                    $path_esol = $this->CONFIG['dta302_emmend'] . "/*.un";
                    break;
                case 'ß':
                    $path_esol = $this->CONFIG['dta302_mobil_syntela'] . "/*.un";
                    break;
                case 'Ä':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/AOKN_Ä" . "/*.un";
                    break;
                case 'q':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/*.un";
                    break;
                case 'q1':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/DAK_TKK_Q1" . "/*.un";
                    break;
                case 'q2':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/IKK_Q2" . "/*.un";
                    break;
                case 'q3':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/BKK_Q3" . "/*.un";
                    break;
                case 'q4':
                    $path_esol = $this->CONFIG['dta302_interforum'] . "/HEK%20Q4" . "/*.un";
                    break;
                default:
                    exit("Fatal Error: Kassenkennzeichen nicht gefunden.");
            }
        }
        return $path_esol;
    }

    public function get_ESOL($rn)
    {
        $rechnungsnummer = explode('-', $rn);
        $kassen_kz = $this->get_arzw_kasse_kz($rechnungsnummer[1]);
        $path_esol = $this->get_path_esol_heilmi($kassen_kz, $rechnungsnummer);
        echo $path_esol;
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
                print_r($row);
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
        $akt_kunde_ik = '';
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
                    "testindikator"             =>  substr($segment[8], 0, -3)
                ));
            }
            //Schlusselement UNZ
            /*if($segment[0] == "UNZ") {
                $data['header'] = array_merge($data['header'], array(
                    "anzahl_unh"                =>  (int)$this->helper->num2db($segment[1])
                ));
            }
            */
            if ($segment[0] == 'FKT')
            {
                $akt_kunde_ik = trim($segment[3]);
            }

            if ($segment[0] == 'INV')
            {
                if (strtolower(trim($segment[1])) == strtolower(trim($patient_number))) {
                    $match = true;
                    $patient_data = array();
                    $taxen_data = array();
                    $data['taxen'][$cnt_patient] = array();
                    $cnt_patient++;
                    $patient_data = array_merge($patient_data, array(
                        'verornung' => $cnt_patient,
                        'kunde_ik'  => $akt_kunde_ik,
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
                    'ort'               => substr($segment[6], 0, -3)
                ));

                array_push($data['patient_data'], $patient_data);
            }

            if (trim($segment[0] == 'EHE') && $match == true) {
                $cnt_taxe++;
                $taxen_data = array_merge($taxen_data, array(
                    'taxe_nr'       => $cnt_taxe,
                    'pzn'           => $segment[2],
                    'faktor'        => $segment[3],
                    'preis'         => $segment[4],
                    'zuzahlung'     => substr($segment[6], 0, -3),
                    'actk'          => $segment[1],
                    'datum'         => $segment[5]
                ));
                array_push($data['taxen'][$cnt_patient - 1], $taxen_data);

            }
            if (trim($segment[0] == 'BES') && $match == true) {
                $match = false;
                $cnt_taxe = 0;
            }
        }

        return $data;
    }
}

$decoder_handler    = new decoder('P10-800294-21');
print_r($decoder_handler->get_patient_data('L700517706'));
//print_r($decoder_handler->get_patient_data('D490545437'));
//echo json_encode($decoder_handler->get_patient_data('D490545437'));
