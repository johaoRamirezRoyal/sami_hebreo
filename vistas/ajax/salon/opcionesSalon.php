<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$objetClass = ControlSalon::singleton_salon();
$rs         = $objetClass->mostrarDatosSalonIdControl();

$sonido   = $rs['sonido'];
$portatil = $rs['portatil'];

echo json_encode(['portatil' => $portatil, 'sonido' => $sonido]);
