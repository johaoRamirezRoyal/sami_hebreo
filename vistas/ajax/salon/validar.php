<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$instancia = ControlSalon::singleton_salon();

$horas_disponibles = $instancia->mostrarHorasDisponiblesControl();

$horas_activas = [];

foreach ($horas_disponibles as $disponible) {
	$id_hora = $disponible['id'];
	$hora    = $disponible['horas'];

	$horas_activas[] = array('horas' => $hora, 'id_hora' => $id_hora);
}

echo json_encode(['horas_activas' => $horas_activas]);
