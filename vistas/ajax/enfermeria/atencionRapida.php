<?php
header('Content-Type: application/json');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';

$instancia       = ControlEnfermeria::singleton_enfermeria();
$datos_categoria = $instancia->mostrarCategoriaPorIdControl($_POST['id_categoria']);

echo json_encode($datos_categoria);
