<?php
require_once ("decoder.php");
require_once ("logger.php");
require_once ("db_con.php");

/**
 * @var $decoder_handler
 */
$decoder_handler    = new decoder("/home/norman/Schreibtisch/ESOL0811_org.un");
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

if ($_GET['param'] == 'kopfelemente') {
$result = $decoder_handler->edifact_kopfelemente();
}
elseif ($_GET['param'] == 'unter_rechnungen') {
$result = $decoder_handler->get_unter_rechnungen();
}
else $result = "Falsche oder fehlende Parameter";


//$debug = $decoder_handler->get_ESOL('S05-900050-21');
$server_info = $db_con->db_connect->host_info;
print_r(json_encode($result));
