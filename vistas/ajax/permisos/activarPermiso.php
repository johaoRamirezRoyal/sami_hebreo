<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'permisos' . DS . 'ControlPermisos.php';

$instancia = ControlPermisos::singleton_permisos();
$activar   = $instancia->activarPermisoControl();

$m = ($activar == true) ? 'ok' : 'error';

echo json_encode(['mensaje' => $m]);
