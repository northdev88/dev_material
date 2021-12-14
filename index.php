<?php
require_once ("import_edefakt.php");
/**
 * @var $import_handler
 */
$import_handler = new import_edefakt("/home/norman/Schreibtisch/ESOL0811_org.un");
//$debug_php = $import_handler->prepare_for_db();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <title>
        Edefakt - Auswertung
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="./assets/css/material-dashboard.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <script src="./assets/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/jquery.inputmask.min.js"></script>


</head>


<body class="g-sidenav-show bg-gray-100">

<!-------------CONTENT------------------------------------------------------------------------------------------------->
<!-- <span style="width: 100%" class="badge bg-gradient-success" size="200px"><?//=$server_info?></span>
<br>
-->
<div class="alert alert-warning" role="alert" hidden="true">
    <p><strong>Debug Ausgabe Javascript</strong></p>
    <p id="pDebug"></p>
</div>


<div id="divHead" class="container-fluid py-4">                <!--Dashboard-->
    <form id="formPatientData" name="formPatientData">
        <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Rechnungsnummer</label>
                        <input  name="rechnungs_nr" id="rechnungs_nr" type="text" class="form-control" autofocus form="formPatientData" minlength="4" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Versichertennummer</label>
                        <input name="vnr" id="vnr" type="text" class="form-control" form="formPatientData" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <button id="btnSearchPatienData" type="submit" class="btn btn-primary btn-lg w-100">Suche beginnen</button>
                </div>
            </div>
        </div>
    </div>                          <!--ROW mit Buttons-->
    </form>
    <div id="rowVerordnung" hidden="true" class="row mt-4">
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <!--CARD HEADER!-->
                    <div class="d-flex justify-content-between">
                        <h6 class="text-primary mb-0">Auswahl der Verordnung</h6>
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0 p-3 mt-4">
                    <div id="insertBtn" class="text-center">
                        <h5 class="text-primary" id="errorVerordnung" hidden="true"></h5>
                        <button id="btnVerordnung1" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 1<br><span id="spanKunde1" style="color: black"></span></button>
                        <button id="btnVerordnung2" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 2<br><span id="spanKunde2" style="color: black"></span></button>
                        <button id="btnVerordnung3" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 3<br><span id="spanKunde3" style="color: black"></span></button>
                        <button id="btnVerordnung4" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 4<br><span id="spanKunde4" style="color: black"></span></button>
                        <button id="btnVerordnung5" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 5<br><span id="spanKunde5" style="color: black"></span></button>
                        <button id="btnVerordnung6" type="button" class="btn btn-outline-primary" hidden="true">Verordnung 6<br><span id="spanKunde6" style="color: black"></span></button>
                    </div>
                </div>
                <div class="card-footer pt-0 pb-0 p-3 d-flex align-items-center">
                    <!-- CARD FOOTER-->
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-6 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-primary mb-0">Übersicht ESOL Kopfelement</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="tableHeader">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">IK Absender</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">IK Empfänger</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Zeit der Erstellung</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Datenaustauschreferenz</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Leistungsbereich</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Anwendungsreferenz</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Testindikator</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                     <!--ROW mit Verordnungen und Kopfelemente-->
    <div id="rowPatient" hidden="true" class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row">
                            <div class="col-lg-3 col-6 text-center">
                                <div class="border border-light border-1 border-radius-md py-3">
                                    <h6 class="text-primary text-gradient mb-0">Name, Vorname</h6>
                                    <h4><p class="font-weight-bolder" id="patientName" style="text-transform: capitalize"></p></h4>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6 text-center">
                                <div class="border border-light border-1 border-radius-md py-3">
                                    <h6 class="text-primary text-gradient mb-0">Versichertennummer</h6>
                                    <h4><p class="font-weight-bolder" id="patientVers" style="text-transform: capitalize"></p></h4>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6 text-center mt-4 mt-lg-0">
                                <div class="border border-light border-1 border-radius-md py-3">
                                    <h6 class="text-primary text-gradient mb-0">Status</h6>
                                    <h4><p class="font-weight-bolder" id="patientStatus" style="text-transform: capitalize"></p></h4>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6 text-center mt-4 mt-lg-0">
                                <div class="border border-light border-1 border-radius-md py-3">
                                    <h6 class="text-primary text-gradient mb-0">Geburtsdatum</h6>
                                    <h4><p class="font-weight-bolder" id="patientGeb" style="text-transform: capitalize"></p></h4>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6 text-center mt-4 mt-lg-0">
                                <div class="border border-light border-1 border-radius-md py-3">
                                    <h6 class="text-primary text-gradient mb-0">Anschrift</h6>
                                    <h6><p class="font-weight-bolder" id="patientStr" style="text-transform: capitalize"></p></h6>
                                    <h5><p class="font-weight-bolder" id="patientOrt" style="text-transform: capitalize"></p></h5>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                      <!--ROW mit Patientendaten-->
    <div id="rowTaxen" hidden="true" class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-primary text-gradient mb-0">Übersicht der Taxen</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="tableTaxen">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Taxe Nr.</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Positionsnummer</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Faktor</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Preis / €</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Zuzahlung / €</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">AC : TK</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Behandlungsdatum</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!--ROW mit Taxen-->
    <div id="rowSpinner" hidden="true" class="row mt-4">  <!--ROW mit Spinner!-->
        <div class="d-flex justify-content-center" style="margin-top: 50px">
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
            <div class="spinner-grow text-primary" style="margin-right: 20px"></div>
        </div>
    </div>
</div>






<!-------------END CONTENT--------------------------------------------------------------------------------------------->
<!--   Core JS Files   -->


<script src="./assets/js/core/popper.min.js"></script>

<script src="./assets/js/core/bootstrap.min.js"></script>

<!-- Plugin for the charts, full documentation here: https://www.chartjs.org/ -->
<script src="./assets/js/plugins/chartjs.min.js"></script>
<script src="./assets/js/plugins/Chart.extension.js"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/material-dashboard.min.js"></script>
<script src="edifakt.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
</body>

</html>