<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';

$instancia = ControlSolicitud::singleton_solicitud();
$datos     = $instancia->agregarProductoControl();

$mensaje     = ($datos == true) ? 'ok' : 'vacio';
$id_producto = $datos['id'];

$html = '
<tr class="text-center art' . $id_producto . '">
<td>
<div class="input-group input-group-sm mb-3">
<input type="text" name="nom_producto[]" value="" class="form-control" required>
</div>
</td>
<td>
<div class="input-group input-group-sm mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-sm">#</span>
</div>
<input type="text" class="form-control numeros text-center cantidad" name="cantidad[]" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="0" required>
</div>
</td>
<td>
<div class="input-group input-group-sm mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
</div>
<input type="hidden" name="id_producto[]" value="' . $id_producto . '">
<input type="text" class="form-control precio numeros valor_unt" name="valor[]" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="0">
</div>
</td>
<td>
<div class="row">
<div class="col-lg-12 form-inline">
<label>
<input type="checkbox" value="0" name="iva[]" class="form-control iva_no" id="iva_no" data-log="' . $id_producto . '" required>
&nbsp;
0%
</label>
&nbsp;
<label class="">
<input type="checkbox" value="19" name="iva[]" class="form-control iva_si" id="iva_si" data-log="' . $id_producto . '" required>
&nbsp;
19%
</label>
&nbsp;
<label class="">
<input type="checkbox" value="incluido" name="iva[]" class="form-control iva_incluido" id="iva_incluido" data-log="' . $id_producto . '" required>
&nbsp;
Iva incluido
</label>
</div>
</div>
</td>
<td>
<div class="input-group input-group-sm mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
</div>
<input type="text" class="form-control" disabled id="' . $id_producto . '" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
</div>
</td>
<td>
<button type="button" class="btn btn-danger btn-sm remover_articulo" data-tooltip="tooltip" title="Remover articulo" data-trigger="hover" id="' . $id_producto . '">
<i class="fa fa-minus"></i>
</button>
</td>
</tr>';
echo json_encode(['mensaje' => $mensaje, 'html' => $html]);
