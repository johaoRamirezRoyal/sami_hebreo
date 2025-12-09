<?php
define('ROOT', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
require_once 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'EnlacesControl.php';
$vista = new EnlacesControl();
$vista->CargarPlantilla();
?>