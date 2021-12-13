<?php
require_once ("decoder.php");
require_once ("logger.php");
require_once ("db_con.php");


/**
 * @var $logger_handler
 */
$logger_handler     = new logger();
/**
 * @var $db_con
 */
$db_con = new  db_con();


#logger_handler->log_console($decoder_handler->edifact_kopfelemente());       //UNB + UNZ
#logger_handler->log_console($decoder_handler->get_actk());
#$result = $db_con->db_connect->query('SELECT kunde, kasse, rechnung_nr_org, rekla_grund FROM reklas');


if ($_GET['mode'] == 'patient_data') {
    $decoder_handler    = new decoder($_GET['rechnungsnr']);
    echo json_encode($decoder_handler->get_patient_data($_GET['versnr']));


}
else exit("Falsche oder fehlende Parameter");



