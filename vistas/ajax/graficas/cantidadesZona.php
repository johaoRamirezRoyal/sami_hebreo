<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'zonas' . DS . 'ControlZonas.php';

$instancia = ControlZonas::singleton_zonas();
$rs        = $instancia->cantidadesSolucionadosZonasControl();

echo json_encode(['solucion' => $rs['solucionados'], 'pendiente' => $rs['pendientes']]);
