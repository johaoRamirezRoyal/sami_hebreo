<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$instancia = ControlSalon::singleton_salon();
$rs        = $instancia->rechazarReservaControl();

echo json_encode($rs);
