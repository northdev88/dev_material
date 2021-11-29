<?php

require_once("helper.php");
require_once("logger.php");

class decoder
{
    private $handler;
    private $helper;
    private $CONFIG;

    public function __construct($rechnungsnummer) {

        $this->helper = new helper();
        $this->logger = new logger();
        $this->CONFIG = require_once("config.inc.php");
        $this->handler = file($this->get_ESOL($rechnungsnummer));
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
                exit("Pfad zur ESOL konnte anhand der Kassennummer nicht ermittelt werden");
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
                    else continue;
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


    public function edifact_kopfelemente() : array
    {

        $unb_unz = array();
        foreach ($this->handler as $segment_key => $segment)
        {
            $segment = explode("+", $segment);

            //Kopfelement UNB + UNZ
            if($segment[0] == "UNB")
            {
                $unb_unz = array_merge($unb_unz, array
                (
                    "syntax"                    =>  $segment[1],
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
            if($segment[0] == "UNZ") {
                $unb_unz = array_merge($unb_unz, array(
                    "anzahl_unh"                =>  (int)$this->helper->num2db($segment[1])
                ));
            }
        }
        return $unb_unz;
    }

    public function get_element($param) : array
    {
        $element = array();
        foreach ($this->handler as $segment_key => $segment) {

            $segment = explode("+", $segment);
            if ($segment[0] == "$param") {
                $element = array_merge($element, $segment);
            }
        }
        return $element;
    }

    public function get_actk() : array
    {
        $actk = array();
        foreach ($this->handler as $segment_key => $segment)
        {
            $segment = explode('+', $segment);
            if ($segment[0] == 'EHE')
            {
                $actk = array_merge($actk, array(
                    $segment[1] => $segment[5]
                ));
            }
        }

        return $actk;
    }

    public function get_patient_data($patient_number) {
        $patient_data = array();
        $match = false;
        $cnt_patient = 0;
        $cnt_taxe = 0;
        foreach ($this->handler as $segment_key => $segment)
        {
            $segment = explode('+', $segment);
            if ($segment[0] == 'INV')
            {
                if (trim($segment[1]) == trim($patient_number)) {
                    $match = true;
                    $cnt_patient++;
                    $patient_data = array_merge($patient_data, array(
                        'vers_nr' => $segment[1],
                        'status' => $segment[2]
                    ));
                }
            }
            if (trim($segment[0] == 'EHE') && $match == true) {
                $cnt_taxe++;
                echo $segment[1] . PHP_EOL;
                array_push($patient_data, array(
                    'actk'          => $segment[1],
                    'pzn'           => $segment[2],
                    'faktor'        => $segment[3],
                    'preis'         => $segment[4],
                    'datum'         => $segment[5],
                    'zuzahlung'     => $segment[6]
                ));
            }
            if (trim($segment[0] == 'BES') && $match == true) {
                $match = false;
            }
        }

        return $patient_data;
    }
}

$decoder_handler    = new decoder('N04-9000501-21');
print_r($decoder_handler ->edifact_kopfelemente());
print_r($decoder_handler->get_patient_data('B011178306'));
