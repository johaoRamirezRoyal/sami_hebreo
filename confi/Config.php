<?php
clearstatcache();
header('Content-Type: text/html; charset=utf-8');
$url_actual = "https://" . $_SERVER["SERVER_NAME"] . '/';
//$url_actual = 'http://localhost:8080/plantilla/';
define('BASE_URL', $url_actual);
define('PUBLIC_PATH', BASE_URL . 'public/');
define('VISTA_PATH', ROOT . DS . 'vistas' . DS);
define('CONTROL_PATH', ROOT . DS . 'app' . DS . 'controlador' . DS);
define('MODELO_PATH', ROOT . DS . 'app' . DS . 'modelo' . DS);
define('LIB_PATH', ROOT . DS . 'app' . DS . 'lib' . DS);
/*-------------------Ruta para archivos---------------------------*/
define('PUBLIC_PATH_ARCH', ROOT . DS . 'public' . DS);
