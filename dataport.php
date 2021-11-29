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
$result = $decoder_handler->edifact_kopfelemente();;
}
elseif ($_GET['param'] == 'kassen_kz') {
    $result = $decoder_handler->get_arzw_kasse_kz('90050');
}
else $result = "Falsche oder fehlende Parameter";


print_r(json_encode($result));
