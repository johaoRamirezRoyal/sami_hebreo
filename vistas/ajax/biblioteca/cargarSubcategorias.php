<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia = ControlBiblioteca::singleton_biblioteca();
$datos     = $instancia->cargarSubcategoriasControl($_POST['id']);

echo json_encode($datos);