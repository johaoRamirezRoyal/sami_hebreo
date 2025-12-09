<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';

$subtotal  = 0;
$total_iva = 0;
//$iva      = (!empty($_POST['iva'])) ? $_POST['iva'] : 0;

$array_totales = [];

$array_valor = array();
$array_valor = $_POST['valor'];

$array_cantidad = array();
$array_cantidad = $_POST['cantidad'];

$array_producto = array();
$array_producto = $_POST['id_producto'];

$array_iva = array();
$array_iva = $_POST['iva'];

$it = new MultipleIterator();
$it->attachIterator(new ArrayIterator($array_valor));
$it->attachIterator(new ArrayIterator($array_cantidad));
$it->attachIterator(new ArrayIterator($array_producto));
$it->attachIterator(new ArrayIterator($array_iva));

foreach ($it as $datos) {
	if ($datos[3] == 'incluido') {
		$total_unidad       = (str_replace(',', '', $datos[0]) * $datos[1]);
		$total_unidad       = ($total_unidad / 1.19);
		$total_iva_producto = ($total_unidad * 0.19);
        //$total_unidad       = ($total_unidad - $total_iva_producto);

		$total_iva += $total_iva_producto;
		$subtotal += $total_unidad;

		$array_totales[] = array('total_unidad' => round($total_unidad), 'id_producto' => $datos[2]);
	} else {
		$total_unidad       = (str_replace(',', '', $datos[0]) * $datos[1]);
		$total_iva_producto = ($total_unidad * $datos[3]) / 100;
		$total_unidad       = ($total_unidad);

		$total_iva += $total_iva_producto;
		$subtotal += $total_unidad;

		$array_totales[] = array('total_unidad' => $total_unidad, 'id_producto' => $datos[2]);
	}

}

$total_subtotal = round($subtotal);
$total_iva      = round($total_iva);
$total          = ($total_subtotal + $total_iva);

echo json_encode(['totales' => $array_totales, 'subtotal' => $total_subtotal, 'total_final' => $total, 'iva' => $total_iva]);
