<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$objetClass = ControlUsuarios::singleton_usuarios();
$rs         = $objetClass->validarFirmaControl();

if ($rs == true) {
	$mensaje = 'ok';
} else {
	$mensaje = 'error';
}

echo json_encode(['mensaje' => $mensaje]);
