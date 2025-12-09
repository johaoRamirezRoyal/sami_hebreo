<?php
header('Content-Type: application/json');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';
require_once CONTROL_PATH . 'renovacion' . DS . 'ControlRenovacion.php';

$instancia            = ControlInventario::singleton_inventario();
$instancia_renovacion = ControlRenovacion::singleton_renovacion();
/*----------------------------------Mantenimientos----------------------*/
$datos_mantenimientos_fechas = $instancia->mostrarFechasMantenimientosControl();
$mantenimientos_realizados   = $instancia->mostrarFechasMantenimientosTodosControl();
/*-----------------------------------------------------------------------*/
/*------------------------Copias de Seguridad--------------------------*/
$copias_seguridad = $instancia->mostrarFechasCopiasSeguridadControl();
/*---------------------------------------------------------------------*/
/*-------------------------------Renovaciones-------------------------------*/
$fecha_renovacion = $instancia_renovacion->mostrarTodasRenovacionesControl();
/*----------------------------------------------------------------------------*/

$fechas = [];

$it = new MultipleIterator();

//$it->attachIterator(new ArrayIterator($datos_mantenimientos_fechas));
//$it->attachIterator(new ArrayIterator($mantenimientos_realizados));
//$it->attachIterator(new ArrayIterator($fecha_renovacion));

foreach ($datos_mantenimientos_fechas as $dato) {
	/*---------------------------------*/
	$frecuencia = $dato['frecuencia'];

	/*--------------------------------*/
	$fecha_mant         = date('Y-m', strtotime($dato['ultimo_mant']));
	$fecha_proximo_mant = strtotime('+' . $frecuencia . ' month', strtotime($fecha_mant));
	$fecha_proximo_mant = date('Y-m', $fecha_proximo_mant);
	/*-------------------------------*/
	$fecha_mant_inicio = strtotime('first day of this month', strtotime($fecha_mant));
	$fecha_mant_inicio = date('Y-m-d', $fecha_mant_inicio);
	$fecha_mant_fin    = strtotime('last day of this month', strtotime($fecha_mant));
	$fecha_mant_fin    = date('Y-m-d', $fecha_mant_fin);
	/*-----------------------------------*/

	$fechas[] = array(
		'title' => 'Ultimo Mantenimiento Preventivo - Realizado',
		'start' => $fecha_mant_inicio,
		'end'   => $fecha_mant_fin,
		'color' => '#229954',
	);

	/*-------------------------------*/
	$fecha_proximo_mant_inicio = strtotime('first day of this month', strtotime($fecha_proximo_mant));
	$fecha_proximo_mant_inicio = date('Y-m-d', $fecha_proximo_mant_inicio);
	$fecha_proximo_mant_fin    = strtotime('last day of this month', strtotime($fecha_proximo_mant));
	$fecha_proximo_mant_fin    = date('Y-m-d', $fecha_proximo_mant_fin);
	/*-----------------------------------*/

	$fechas[] = array(
		'title' => 'Mantenimientos Preventivos - Proximos',
		'start' => $fecha_proximo_mant_inicio,
		'end'   => $fecha_proximo_mant_fin,
		'color' => '#1a5276',
	);

}

foreach ($mantenimientos_realizados as $realizado) {
	/*------------------------------------*/
	$fecha_mant_pasado = date('Y-m', strtotime($realizado['fechareg']));
	/*-------------------------------*/
	$fecha_pasado_mant_inicio = strtotime('first day of this month', strtotime($fecha_mant_pasado));
	$fecha_pasado_mant_inicio = date('Y-m-d', $fecha_pasado_mant_inicio);
	$fecha_pasado_mant_fin    = strtotime('last day of this month', strtotime($fecha_mant_pasado));
	$fecha_pasado_mant_fin    = date('Y-m-d', $fecha_pasado_mant_fin);
	/*-----------------------------------*/

	if (date('Y') == date('Y', strtotime($realizado['fechareg']))) {} else {

		$fechas[] = array(
			'title' => 'Mantenimientos Preventivos - Pasados',
			'start' => $fecha_pasado_mant_inicio,
			'end'   => $fecha_pasado_mant_fin,
			'color' => '#af601a',
		);
	}
}

foreach ($fecha_renovacion as $renovacion) {
	/*-------------------------------*/
	$fecha_renovacion         = date('Y-m', strtotime($renovacion['fecha_renovacion']));
	$fecha_proxima_renovacion = date('Y-m', strtotime($renovacion['fecha_proxima']));
	$fecha_ultima_renovacion  = date('Y-m', strtotime($renovacion['ultima_renovacion']));
	/*----------------------------------*/
	/*-------------------------------*/
	$fecha_pasado_renovacion_inicio = strtotime('first day of this month', strtotime($fecha_renovacion));
	$fecha_pasado_renovacion_inicio = date('Y-m-d', $fecha_pasado_renovacion_inicio);
	$fecha_pasado_renovacion_fin    = strtotime('last day of this month', strtotime($fecha_renovacion));
	$fecha_pasado_renovacion_fin    = date('Y-m-d', $fecha_pasado_renovacion_fin);
	/*-----------------------------------*/

	$fechas[] = array(
		'title' => 'Renovacion de ' . $renovacion['nombre'],
		'start' => $fecha_pasado_renovacion_inicio,
		'end'   => $fecha_pasado_renovacion_fin,
		'color' => '#117a65',
	);

	/*-------------------------------*/
	$fecha_proxima_renovacion_inicio = strtotime('first day of this month', strtotime($fecha_proxima_renovacion));
	$fecha_proxima_renovacion_inicio = date('Y-m-d', $fecha_proxima_renovacion_inicio);
	$fecha_proxima_renovacion_fin    = strtotime('last day of this month', strtotime($fecha_proxima_renovacion));
	$fecha_proxima_renovacion_fin    = date('Y-m-d', $fecha_proxima_renovacion_fin);
	/*-----------------------------------*/
	$fechas[] = array(
		'title' => 'Renovacion proxima de ' . $renovacion['nombre'],
		'start' => $fecha_proxima_renovacion_inicio,
		'end'   => $fecha_proxima_renovacion_fin,
		'color' => '#2e86c1',
	);
}

foreach ($copias_seguridad as $copia) {

	/*-------------------------------------------*/
	$fecha_actual        = $copia['fecha'];
	$fecha_copia_hosting = strtotime('first day of this month', strtotime($fecha_actual));
	$fecha_copia_hosting = date('Y-m-d', $fecha_copia_hosting);

	$fechas[] = array(
		'title' => 'Copia de seguridad HOSTINGS',
		'start' => $fecha_copia_hosting,
		'end'   => $fecha_copia_hosting,
		'color' => '#34495e',
	);

	/*-------------------------------------------*/
	/*$fecha_copia_proxima_host = date('Y-m-d', strtotime($fecha_copia_hosting . "+ 1 month"));

	if ($fecha_copia_proxima_host != $fecha_copia_hosting) {
		$fecha_copia_prox_host = strtotime('first day of this month', strtotime($fecha_copia_proxima_host));
		$fecha_copia_prox_host = date('Y-m-d', $fecha_copia_prox_host);

		$fechas[] = array(
			'title' => 'Copia de seguridad Proxima HOSTINGS',
			'start' => $fecha_copia_prox_host,
			'end'   => $fecha_copia_prox_host,
			'color' => '#d68910',
		);
	}*/
	/*--------------------------------------------*/

	/*-------------------------------------------*/
	$fecha_actual = $copia['fecha'];
	$fecha_copia  = strtotime('last day of this month', strtotime($fecha_actual));
	$fecha_copia  = date('Y-m-d', $fecha_copia);

	$fechas[] = array(
		'title' => 'Copia de seguridad EQUIPOS DE COMPUTO',
		'start' => $fecha_copia,
		'end'   => $fecha_copia,
		'color' => '#76448a',
	);

	/*-------------------------------------------*/
	$fecha_copia_proxima = date('Y-m-d', strtotime($fecha_copia . "+ 1 month"));

	if ($fecha_copia == $fecha_copia_proxima) {} else {

		$fecha_copia_prox = strtotime('last day of this month', strtotime($fecha_copia_proxima));
		$fecha_copia_prox = date('Y-m-d', $fecha_copia_prox);

		$fechas[] = array(
			'title' => 'Copia de seguridad Proxima EQUIPOS DE COMPUTO',
			'start' => $fecha_copia_prox,
			'end'   => $fecha_copia_prox,
			'color' => '#1abc9c',
		);
	}
	/*--------------------------------------------*/

}

echo json_encode($fechas);
