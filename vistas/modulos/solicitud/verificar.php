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

$instancia       = ControlSolicitud::singleton_solicitud();
$instancia_areas = ControlAreas::singleton_areas();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['solicitud'])) {

	$id_solicitud    = base64_decode($_GET['solicitud']);
	$datos_solicitud = $instancia->mostrarDatosSolicitudIdControl($id_solicitud);
	$productos       = $instancia->mostrarProdcutosSolicitudControl($id_solicitud);
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
							Verificacion de solicitud #<?=$datos_solicitud['id']?>
						</h4>
					</div>
					<form id="prefactura" method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="id_solicitud" value="<?=$datos_solicitud['id']?>">
						<div class="card-body">
							<div class="row">
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Area</label>
									<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['area_nom']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Usuario</label>
									<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['nom_usuario']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha solicitado</label>
									<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['fecha_solicitud']?>">
								</div>
							</div>
							<div class="table-responsive mt-2">
								<table class="table border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold bg-light">
											<th scope="col" colspan="4">TABLA DE PRODUCTOS</th>
										</tr>
										<tr class="text-center font-weight-bold">
											<th scope="col">DESCRIPCION DEL PRODUCTO O SERVICIO SOLICITADA</th>
											<th scope="col">CANTIDAD</th>
											<th scope="col">Vr. UNIT</th>
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
												$total_iva_producto = ($total_unidad * 0.19);
												$total_unidad       = ($total_unidad - $total_iva_producto);

												$total_iva += $total_iva_producto;
												$subtotal += $total_unidad;
											} else {
												$total_unidad       = ($precio * $cantidad);
												$total_iva_producto = ($total_unidad * 19) / 100;
												$total_unidad       = ($total_unidad);

												$total_iva += $total_iva_producto;
												$subtotal += $total_unidad;
											}

											?>
											<tr class="text-center">
												<td><?=$nombre?></td>
												<td><?=$cantidad?></td>
												<td>$<?=number_format($precio)?></td>
												<td>$<?=number_format($total_unidad)?></td>
											</tr>
											<?php
										}

										$total_subtotal = $subtotal;
										$total_iva      = $total_iva;
										$total          = ($total_subtotal + $total_iva);
										?>
										<tr>
											<td colspan="2" rowspan="3"></td>
											<td class="text-center font-weight-bold">Subtotal</td>
											<td class="text-center">$<?=number_format($total_subtotal)?></td>
										</tr>
										<tr>
											<td class="text-center font-weight-bold">IVA</td>
											<td class="text-center">$<?=number_format($total_iva)?></td>
										</tr>
										<tr>
											<td class="text-center font-weight-bold">TOTAL</td>
											<td class="text-center">$<?=number_format($total)?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="table-responsive mt-3">
								<table class="table table-hover border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold bg-light">
											<th scope="col" colspan="4">VERIFICACION DE PRODUCTOS Y/O SERVICIOS ADQUIRIDOS</th>
										</tr>
										<tr class="text-center font-weight-bold">
											<td scope="col" style="width: 30%;">ITEM</td>
											<td scope="col" style="width: 10%;">CUMPLE</td>
											<td scope="col" style="width: 10%;">NO CUMPLE</td>
											<td scope="col" style="width: 50%;">OBSERVACIONES</td>
										</tr>
									</thead>
									<tbody class="buscar">
										<tr class="text-center">
											<td>CANTIDAD</td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_cant" value="Si"></td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_cant" value="No"></td>
											<td>
												<textarea name="observacion_cant" rows="3" class="form-control"></textarea>
											</td>
										</tr>
										<tr class="text-center">
											<td>CALIDAD (Bienes y Servicios recibidos con especificaciones requeridas)</td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_calidad" value="Si"></td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_calidad" value="No"></td>
											<td>
												<textarea name="observacion_calidad" rows="3" class="form-control"></textarea>
											</td>
										</tr>
										<tr class="text-center">
											<td>PRECIOS PACTADOOS</td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_precio" value="Si"></td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_precio" value="No"></td>
											<td>
												<textarea name="observacion_precio" rows="3" class="form-control"></textarea>
											</td>
										</tr>
										<tr class="text-center">
											<td>PLAZOS DE ENTREGA</td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_plazo" value="Si"></td>
											<td><input type="radio" class="form-control w-25 text-center ml-5" name="cumple_plazo" value="No"></td>
											<td>
												<textarea name="observacion_plazo" rows="3" class="form-control"></textarea>
											</td>
										</tr>
										<tr class="text-center">
											<td><span class="font-weight-bold">NOMBRE DEL RESPONSABLE: </span></td>
											<td colspan="3"><?=$_SESSION['nombre_admin'] . ' ' . $_SESSION['apellido']?></td>
										</tr>
										<tr class="text-center">
											<td><span class="font-weight-bold">FECHA DE VERFICACION: </span></td>
											<td colspan="3"><input type="date" class="form-control col-lg-4 border-0 text-center m-auto" name="fecha_verificacion" value="<?=date('Y-m-d')?>"></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row">
								<div class="col-lg-12 mt-2">
									<button class="btn btn-primary btn-sm float-right">
										<i class="fa fa-save"></i>
										&nbsp;
										Guardar
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
		$instancia->verificarSolicitudControl();
	}
}