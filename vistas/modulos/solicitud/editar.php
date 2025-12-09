<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
	$er    = '2';
	$error = base64_encode($er);
	$salir = new Session;
	$salir->iniciar();
	$salir->outsession();
	header('Location:../login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia          = ControlSolicitud::singleton_solicitud();
$instancia_areas    = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$datos_usuarios = $instancia_usuarios->mostrarTodosProveedoresControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['solicitud'])) {

	$id_solicitud    = base64_decode($_GET['solicitud']);
	$datos_solicitud = $instancia->mostrarDatosSolicitudIdControl($id_solicitud);
	$productos       = $instancia->mostrarProdcutosSolicitudControl($id_solicitud);

	$valor  = '';
	$opcion = 'Seleccione una opcion...';

	/*--------------------------*/
	if ($datos_solicitud['estado'] == 1) {
		$valor  = 1;
		$opcion = 'Aprobada';
	}

	if ($datos_solicitud['estado'] == 2) {
		$valor  = 2;
		$opcion = 'Rechazada';
	}

	if ($datos_solicitud['estado'] == 3) {
		$valor  = 3;
		$opcion = 'Aplazada';
	}

	if ($datos_solicitud['estado'] == 4) {
		$valor  = 4;
		$opcion = 'Aprobada - Pendiente';
	}

	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-primary">
							<a href="<?=BASE_URL?>solicitud/listado" class="text-decoration-none">
								<i class="fa fa-arrow-left text-primary"></i>
							</a>
							&nbsp;
							Editar solicitud #<?=$datos_solicitud['id']?>
						</h4>
					</div>
					<form id="prefactura" method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="id_solicitud" value="<?=$datos_solicitud['id']?>">
						<div class="card-body">
							<div class="row mt-4 p-2">
								<div class="form-group col-lg-12">
									<h5 class="font-weight-bold text-center">ESTUDIO DE LA SOLICITUD</h5>
									<hr>
								</div>
								<div class="form-group col-lg-6">
									<label class="font-weight-bold">Estado de la solicitud <span class="text-danger">*</span></label>
									<select class="form-control" required name="estado" id="estado">
										<option value="<?=$valor?>" class="d-none" selected><?=$opcion?></option>
										<option value="">Seleccione una opcion...</option>
										<option value="1">Aprobada</option>
										<option value="2">Rechazada</option>
										<option value="3">Aplazada</option>
										<option value="4">Aprobada - Pendiente</option>
									</select>
								</div>
								<div class="form-group col-lg-6">
									<label class="font-weight-bold" id="label_fecha">Fecha</label>
									<input type="date" class="form-control" name="fecha_aplazado" value="<?=$datos_solicitud['fecha_aplazado']?>" id="fecha_aplazado">
								</div>
								<div class="form-group col-lg-12">
									<label class="font-weight-bold">Observaciones</label>
									<textarea class="form-control" rows="5" name="observacion"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-lg-12">
									<h5 class="font-weight-bold text-center">DETALLES DE LA SOLICITUD</h5>
									<hr>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Proveedor <span class="text-danger">*</span></label>
									<div class="input-group mb-3">
										<select class="form-control" name="proveedor" required>
											<option value="<?=$datos_solicitud['id_proveedor']?>" class="d-none" selected><?=$datos_solicitud['nom_proveedor']?></option>
											<?php
											foreach ($datos_usuarios as $usuario) {
												$id_user   = $usuario['id_user'];
												$nombre    = $usuario['nombre'] . ' ' . $usuario['apellido'];
												$documento = $usuario['documento'];

												if ($usuario['estado'] == 'activo' && $usuario['perfil'] == 17) {
													?>
													<option value="<?=$id_user?>"><?=$nombre . '(' . $documento . ')'?></option>
													<?php
												}
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Area</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['area_nom']?>">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Usuario</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['nom_usuario']?>">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha solicitado <span class="text-danger">*</span></label>
									<div class="input-group mb-3">
										<input type="date" class="form-control" required name="fecha_solicitado" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['fecha_solicitud']?>">
									</div>
								</div>
								<!-- <div class="col-lg-4 form-group">
									<label class="font-weight-bold">IVA</label>
									<div class="col-lg-12 form-inline">
										<label>
											<input type="radio" value="0" name="iva" class="form-control" id="iva_no">
											&nbsp;
											0%
										</label>
										<label class="ml-4">
											<input type="radio" value="19" name="iva" class="form-control" id="iva_si">
											&nbsp;
											19%
										</label>
									</div>
								</div> -->
							</div>
							<div class="table-responsive mt-2">
								<table class="table border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold bg-light">
											<th scope="col" colspan="4">TABLA DE PRODUCTOS</th>
											<th scope="col">
												<button type="button" class="btn btn-primary btn-sm" id="agregar_articulo" data-tooltip="tooltip" title="Agregar articulo" data-trigger="hover" data-id="<?=$datos_solicitud['id']?>" data-log="<?=$id_log?>">
													<i class="fa fa-plus"></i>
												</button>
											</th>
										</tr>
										<tr class="text-center font-weight-bold">
											<th scope="col">DESCRIPCION DEL PRODUCTO O SERVICIO SOLICITADA</th>
											<th scope="col">CANTIDAD</th>
											<th scope="col">Vr. UNIT</th>
											<th scope="col">IVA</th>
											<th scope="col">Vr. Total</th>
										</tr>
									</thead>
									<tbody class="buscar">
										<?php
										$subtotal  = 0;
										$total_iva = 0;
										foreach ($productos as $producto) {
											$id_producto = $producto['id'];
											$nombre      = $producto['producto'];
											$cantidad    = $producto['cantidad'];
											$precio      = $producto['precio'];
											$iva         = $producto['iva'];

											if ($iva == 'incluido') {

												$total_unidad       = ($precio * $cantidad);
												$total_unidad       = ($total_unidad / 1.19);
												$total_iva_producto = ($total_unidad * 0.19);
            //$total_unidad       = ($total_unidad - $total_iva_producto);

												$subtotal += $total_unidad;
												$total_iva += $total_iva_producto;
											} else {
												$total_unidad       = ($precio * $cantidad);
												$total_iva_producto = ($total_unidad * 19) / 100;
												$total_unidad       = ($total_unidad);

												$subtotal += $total_unidad;
												$total_iva += $total_iva_producto;
											}

											if ($iva == 'incluido') {
												$check_incluido = 'checked';
												$check_no       = 'disabled';
												$check_si       = 'disabled';
											}

											if ($iva == '0') {
												$check_incluido = 'disabled';
												$check_no       = 'checked';
												$check_si       = 'disabled';
											}

											if ($iva == '19') {
												$check_incluido = 'disabled';
												$check_no       = 'disabled';
												$check_si       = 'checked';
											}
											?>
											<tr class="text-center art<?=$id_producto?>">
												<td>
													<div class="input-group input-group-sm mb-3">
														<input type="text" name="nom_producto[]" value="<?=$nombre?>" class="form-control" required>
													</div>
												</td>
												<td>
													<!-- <input type="text" name="cantidad[]" value="<?=$cantidad?>" class="form-control numeros text-center"> -->
													<div class="input-group input-group-sm mb-3">
														<div class="input-group-prepend">
															<span class="input-group-text" id="inputGroup-sizing-sm">#</span>
														</div>
														<input type="text" class="form-control numeros text-center cantidad" name="cantidad[]" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$cantidad?>" required>
													</div>
												</td>
												<td>
													<div class="input-group input-group-sm mb-3">
														<div class="input-group-prepend">
															<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
														</div>
														<input type="hidden" name="id_producto[]" value="<?=$id_producto?>">
														<input type="text" class="form-control precio numeros valor_unt" name="valor[]" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=number_format($precio)?>">
													</div>
												</td>
												<td>
													<div class="row">
														<div class="col-lg-12 form-inline">
															<label>
																<input type="checkbox" value="0" <?=$check_no?> name="iva[]" class="form-control iva_no" id="iva_no" data-log="<?=$id_producto?>" required>
																&nbsp;
																0%
															</label>
															&nbsp;
															<label class="">
																<input type="checkbox" value="19" <?=$check_si?> name="iva[]" class="form-control iva_si" id="iva_si" data-log="<?=$id_producto?>" required>
																&nbsp;
																19%
															</label>
															&nbsp;
															<label class="">
																<input type="checkbox" value="incluido" name="iva[]" class="form-control iva_incluido" id="iva_incluido" <?=$check_incluido?> data-log="<?=$id_producto?>" required>
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
														<input type="text" class="form-control" disabled id="<?=$id_producto?>" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=number_format($total_unidad)?>">
													</div>
												</td>
												<td>
													<button type="button" class="btn btn-danger btn-sm remover_articulo" data-tooltip="tooltip" title="Remover articulo" data-trigger="hover" id="<?=$id_producto?>">
														<i class="fa fa-minus"></i>
													</button>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
								<!-- <table class="table border table-sm" width="100%" cellspacing="0">
									<tbody>
										<tr>
											<td colspan="2" rowspan="3"></td>
											<td class="text-center font-weight-bold">Subtotal</td>
											<td>
												<div class="input-group input-group-sm mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
													</div>
													<input type="text" class="form-control" disabled id="subtotal" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td class="text-center font-weight-bold">IVA</td>
											<td>
												<div class="input-group input-group-sm mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
													</div>
													<input type="text" class="form-control" disabled id="iva" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
												</div>
											</td>
										</tr>
										<tr>
											<td class="text-center font-weight-bold">TOTAL</td>
											<td>
												<div class="input-group input-group-sm mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
													</div>
													<input type="text" class="form-control" disabled id="total_final" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
												</div>
											</td>
										</tr>
									</tbody>
								</table> -->
							</div>
							<div class="row p-2">
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Subtotal</label>
									<div class="input-group input-group-sm mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
										</div>
										<input type="text" class="form-control" disabled value="<?=number_format($subtotal)?>" id="subtotal" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">IVA</label>
									<div class="input-group input-group-sm mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
										</div>
										<input type="text" class="form-control" value="<?=number_format($total_iva)?>" disabled id="iva" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">TOTAL</label>
									<div class="input-group input-group-sm mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text" id="inputGroup-sizing-sm">$</span>
										</div>
										<input type="text" class="form-control" value="<?=number_format($subtotal + $total_iva)?>" disabled id="total_final" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
									</div>
								</div>
								<div class="form-group col-lg-12 mt-2">
									<label class="font-weight-bold">Justificacion</label>
									<textarea class="form-control" disabled><?=$datos_solicitud['justificacion']?></textarea>
								</div>
								<div class="col-lg-12 form-group text-right mt-3">
									<button class="btn btn-primary btn-sm">
										<i class="fa fa-edit"></i>
										&nbsp;
										Editar
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	include_once VISTA_PATH . 'script_and_final.php';

	if (isset($_POST['id_log'])) {
		$instancia->confirmarSolicitudControl();
	}
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/solicitud/funcionesSolicitud.js"></script>