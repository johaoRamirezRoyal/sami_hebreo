<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';

$instancia = ControlEnfermeria::singleton_enfermeria();
$rs        = $instancia->MostrarGraficaEnfermeriaControl($_POST['fecha_desde_enfermeria'], $_POST['fecha_hasta_enfermeria']);

$cantidad = [];
$nombre   = [];
$color    = [];

foreach ($rs as $dato) {
	$cantidad[] = $dato['cantidad'];
	$nombre[]   = $dato['nombre'] . ' (' . $dato['cantidad'] . ')';
	$color[]    = 'rgb(' . rand(0, 255) . ', ' . rand(0, 200) . ', ' . rand(0, 230) . ', 0.7)';
}

$resultado = array(
	'nombre'   => $nombre,
	'cantidad' => $cantidad,
	'color'    => $color,
);

echo json_encode($resultado);
