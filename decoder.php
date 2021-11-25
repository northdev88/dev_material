<?php

require_once("helper.php");
require_once("logger.php");

class decoder
{
    private $handler;
    private $helper;

    public function __construct($path) {

        $this->handler = file($path);
        $this->helper = new helper();
        $this->logger = new logger();
    }


    public function get_ESOL($rechnungsnummer)
    {
        $rechnungsnummer =trim($rechnungsnummer);
        $month = substr($rechnungsnummer, 1,2);
        $year = substr($rechnungsnummer, -2);
        return $rechnungsnummer . ' ' . $month . " $year";
    }

    public function get_unter_rechnungen() : array
    {
        $unter_rechnungen[] = array();
        $cnt = 0;
        foreach ($this->handler as $segment) {
            $segment = explode("+", $segment);
            if($segment[0] == "REC")
            {
                $unter_rechnungen[] = array_merge($unter_rechnungen[$cnt], array(
                   "rechnung_nr"        => $segment[1],
                   "rechnung_datum"     => $segment[2]
                ));
                $cnt++;
            }
        }
        return $unter_rechnungen;
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
}