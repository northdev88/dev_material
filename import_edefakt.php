<?php
require_once ("db_con.php");
require_once("helper.php");
require_once("logger.php");

/**
 * @var $db_con_handler
 */
$db_con_handler = new db_con();

class import_edefakt {

    private $handler;
    private $helper;

    public function __construct($path) {

        $this->handler = file($path);
        $this->helper = new helper();
        $this->logger = new logger();
    }

    public function prepare_for_db() {
        $blocks = array();
        foreach ($this->handler as $segment_key => $segment) {
            $segment = explode("+", $segment);
            $elements =array();
            foreach ($segment as $element_key => $element) {
                if($element_key === array_key_last($segment)) {

                }
                array_push($elements, $element);
            }
            array_push($blocks,
                array(
                    'block_type' => $segment[0],
                    'line'       => $segment_key,
                    'elements'   => $elements
                )
            );
        }

        return $blocks;
    }

    private function map_elements($segment, $elements) {
        $mapped = array();
        var_dump($elements);
        if ($segment[0] == 'UNB')
            $mapped = array_merge($mapped, array
            (
                "syntax"                    =>  $elements[1],
                "ik_absender"               =>  $elements[2],
                "ik_empfaenger"             =>  $elements[3],
                "zeit_erstellung"           =>  $elements[4],
                "datenaustauschreferenz"    =>  $elements[5], //analog Segment[2] in UNZ
                "leistungsbereich"          =>  $elements[6],
                "anwendungsreferenz"        =>  $elements[7],
                "testindikator"             =>  $elements[8]
            ));
        return $mapped;
    }

}

//$import_handler = new import_edefakt("/home/norman/Schreibtisch/ESOL0811_short.un");
//$debug_php = $import_handler->prepare_for_db();
//$import_handler->logger->log_console($debug_php);

//HANDY NEUSTART