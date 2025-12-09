<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';

$instancia = ControlSolicitud::singleton_solicitud();
$datos     = $instancia->removerProductoControl();

$mensaje = ($datos == true) ? 'ok' : 'vacio';

echo json_encode(['mensaje' => $mensaje]);
