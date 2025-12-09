<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'padres' . DS . 'ControlPadres.php';

$instancia = ControlPadres::singleton_padres();
$guardar = $instancia->guardarHermanoControl();

$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$tipo_rel = $_POST['tipo_rel'];
$id_log = $_POST['id_log'];

if ($guardar == TRUE) {
    $tabla = '
        <tr class="text-center">
            <td>' . $nombre . '</td>
            <td>' . $edad . '</td>
            <td>' . $tipo_rel . '</td>
        </tr>
        ';
} else {
    $tabla = '
    <tr class="text-center">
        <td colspan="3">Error al guardar</td>
    </tr> 
    ';
}
echo $tabla;
