<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

$objetClass = ControlInventario::singleton_inventario();
$rs         = $objetClass->guardarInventarioTempControl();

$tabla = '
<tr class="text-center">
<td>' . $rs['descripcion'] . '</td>
<td>' . $rs['marca'] . '</td>
<td>' . $rs['modelo'] . '</td>
<td>' . $rs['precio'] . '</td>
<td>' . $rs['fecha_compra'] . '</td>
<td>' . $rs['cantidad'] . '</td>
</tr>
';

echo $tabla;
