<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$fecha = $_POST['fecha'];

$objetClass = ControlSalon::singleton_salon();
$rs         = $objetClass->contarPortatilDisponibleControl($fecha);
$cantidad   = $rs['cantidad'];

echo json_encode(['cantidad' => $cantidad]);
