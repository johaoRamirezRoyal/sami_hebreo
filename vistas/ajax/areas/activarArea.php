<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$objetClass = ControlAreas::singleton_areas();
$rs         = $objetClass->activarAreaControl();
echo $rs;
