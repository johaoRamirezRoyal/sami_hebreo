<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';

$valor_1 = $_POST['valor_1'];
$valor_2 = $_POST['valor_2'];
$valor_3 = $_POST['valor_3'];
$valor_4 = $_POST['valor_4'];
$valor_5 = $_POST['valor_5'];

$suma = $valor_1 + $valor_2 + $valor_3 + $valor_4 + $valor_5;
$total = $suma / 5;

echo json_encode(['total' => $total]);