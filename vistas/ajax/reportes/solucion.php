<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'reportes' . DS . 'ControlReportes.php';

$instancia = ControlReporte::singleton_reporte();
$solucion  = $instancia->solucionarReporteControl();

if ($solucion == true) {
    $rs = 'ok';
} else {
    $rs = 'no';
}


echo json_encode(['mensaje' => $rs]);
