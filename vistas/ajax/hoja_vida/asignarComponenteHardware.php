<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';

$objetClass = ControlHojaVida::singleton_hoja_vida();
$rs = $objetClass->asignarComponenteHardwareControl();

echo $rs;
