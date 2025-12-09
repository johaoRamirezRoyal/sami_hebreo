<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

$objetClass = ControlInventario::singleton_inventario();

if (!isset($_POST['id_categoria'])) {
    echo json_encode([]);
    exit;
}

$id_categoria = (int) $_POST['id_categoria'];
$inventario = $objetClass->obtenerInventarioPorIdCategoriaControl($id_categoria);

echo json_encode($inventario ?: []);
exit;