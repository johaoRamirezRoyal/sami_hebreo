<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'renovacion' . DS . 'ControlRenovacion.php';

$instancia = ControlRenovacion::singleton_renovacion();
$dato      = $instancia->eliminarDocumentoControl($_POST['id'], $_POST['log']);

echo json_encode($dato);
