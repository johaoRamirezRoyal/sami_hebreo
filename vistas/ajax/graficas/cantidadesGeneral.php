<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

$instancia = ControlInventario::singleton_inventario();
$rs        = $instancia->cantidadesGeneralSolucionadasControl();

echo json_encode(['solucion' => $rs['solucionados'], 'pendiente' => $rs['pendientes']]);