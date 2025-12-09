<?php
header('Content-Type: application/json');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';

$instancia     = ControlEnfermeria::singleton_enfermeria();
$datos_usuario = $instancia->mostrarDatosUsuariosControl($_POST['id']);

echo json_encode($datos_usuario);