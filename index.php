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
    <link rel="stylesheet" type="text/css" href="/DataTables/datatables.css">

</head>


<body class="g-sidenav-show bg-gray-100">

<!-------------CONTENT------------------------------------------------------------------------------------------------->
<!-- <span style="width: 100%" class="badge bg-gradient-success" size="200px"><?//=$server_info?></span>
<br>
-->
<div class="alert alert-info" role="alert">
    <p><strong>Debug Ausgabe Javascript</strong></p>
    <p id="pDebug"></p>
</div>
<div class="alert alert-info" role="alert">
    <p><strong><?//=var_dump($debug_php)?></strong></p>
    <p id="pDebugPHP"></p>
</div>

<div class="container-fluid py-4">            <!--Dashboard-->
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <button id="btnAkt" type="button" class="btn btn-success btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Tooltip">
                        <i class="material-icons-round">Aktualisieren</i>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative   btn-success opacity-5 rounded-3">
                    <input type="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-sm-0 mt-4">
            <div class="card">
                <div class="card-body p-3 position-relative">
                    <button id="btnAkt2" type="button" class="btn btn-danger btn-rounded btn-outline-secondary mb-0 ms-2 btn-sm d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Tooltip">
                        <i class="material-icons-round">Irgendetwas anderes</i>
                </div>
            </div>
        </div>
    </div>                      <!--ROW mit Buttons-->
    <div class="row mt-4">
        <div class="col-lg-4 col-sm-6">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <!--CARD HEADER!-->
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-0">Enthaltene Unter-Rechnungen</h6>
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0 p-3 mt-4">
                    <!-- CARD BODY-->
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
                        <h6 class="mb-0">Übersicht Kopfelement UNB / UNZ</h6>
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">IK Absender</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">IK Empfänger</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Zeit der Erstellung</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Datenaustauschreferenz</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Leistungsbereich</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Anwendungsreferenz</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Testindikator</th>
                                <th class="text-uppercase text-lg-center text-xxs font-weight-bolder opacity-7" scope="col">Anzahl UNH</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th id="tdIkAbsender" scope="row" class="text-lg-center"></th>
                                <td id="tdIKEmpf" scope="row" class="text-lg-center"></h6></td>
                                <td id="tdZeit" scope="row" class="text-lg-center"></td>
                                <td id="tdRef" scope="row" class="text-lg-center"></td>
                                <td id="tdLeistung"scope="row" class="text-lg-center"></td>
                                <td id="tdAnw" scope="row" class="text-lg-center"></td>
                                <td id="tdTest" scope="row" class="text-lg-center"></td>
                                <td id="tdAnzUNH" scope="row" class="text-lg-center"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>                 <!--ROW mit Rechnungsübersicht und Kopfelemente-->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">

                    </div>
                </div>
                <div class="card-body p-3">

                </div>
            </div>
        </div>
        <div class="col-lg-4 mt-lg-0 mt-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body p-3">

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                <div class="card-body px-0 pt-0 pb-2">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<table id="devtable" class="table-bordered" style="width: 100%">
    <thead>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="container">
    <div class="btn"><a href="#">Krankenhäuser</a></div>
    <div class="btn"><a href="#">Hilfsmitter</a></div>
    <div class="btn"><a href="#">Heilmittel</a></div>
</div>


<!-------------END CONTENT--------------------------------------------------------------------------------------------->
<!--   Core JS Files   -->

<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script src="/assets/js/core/popper.min.js"></script>

<script src="/assets/js/core/bootstrap.min.js"></script>

<!-- Plugin for the charts, full documentation here: https://www.chartjs.org/ -->
<script src="/assets/js/plugins/chartjs.min.js"></script>
<script src="/assets/js/plugins/Chart.extension.js"></script>

<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/assets/js/material-dashboard.min.js"></script>
<script src="edifakt.js"></script>
<script type="text/javascript" charset="utf8" src="/DataTables/datatables.js"></script>
</body>

</html>