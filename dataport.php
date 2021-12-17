<?php
require_once ("decoder.php");

if ($_GET['mode'] == 'patient_data') {
    $decoder_handler    = new decoder($_GET['rechnungsnr']);
    echo json_encode($decoder_handler->get_patient_data($_GET['versnr']));


}
else exit("Falsche oder fehlende Parameter");



