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

$instancia          = ControlSolicitud::singleton_solicitud();
$instancia_areas    = ControlAreas::singleton_areas();

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
						<h4 class="m-0 font-weight-bold text-hebreo">
							<a href="<?=BASE_URL?>solicitud/listado" class="text-decoration-none">
								<i class="fa fa-arrow-left text-hebreo"></i>
							</a>
							&nbsp;
							Revision de solicitud #<?=$datos_solicitud['id']?>
						</h4>
					</div>
					<form id="prefactura" method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="id_solicitud" value="<?=$datos_solicitud['id']?>">
						<div class="card-body">
							<div class="row">
								<div class="form-group col-lg-12">
									<h5 class="font-weight-bold text-center">DETALLES DE LA SOLICITUD</h5>
									<hr>
								</div>


								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Area</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['area_nom']?>">
									</div>
								</div>
								<input type="text" value="Anulado por Compras" name="motivo" hidden>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Grado</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=(empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom']?>">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Solicitante</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['nom_usuario']?>">
									</div>
								</div>
								<div class="form-group col-lg-12 mt-2">
									<label class="font-weight-bold">Justificacion</label>
									<textarea class="form-control" disabled><?=$datos_solicitud['justificacion']?></textarea>
								</div>
							</div>
							<div class="table-responsive mt-2">
								<table class="table border table-sm" width="100%" cellspacing="0">
									<thead>
										<tr class="text-center font-weight-bold">
											<th scope="col" colspan="5">TABLA DE MATERIALES</th>
										</tr>
										<tr class="text-center font-weight-bold">
											<th scope="col">Material</th>
											<th scope="col">Cantidad Solicitada</th>
											<th scope="col">Existencias</th>
											<th scope="col">Cantidad en Existencia</th>
										</tr>
									</thead>
									<tbody class="buscar">
										<?php
										$cont = 1;
										foreach ($productos as $pro) {
											$id_detalle   = $pro['id'];
											$nom_producto = $pro['producto'];
											$cantidad     = $pro['cantidad'];

											$ver_minus = ($cont == 0) ? 'd-none' : '';
											?>
											<input type="hidden" name="id_producto[]" value="<?=$id_detalle?>">
											<tr class="text-center" id="fila<?=$id_detalle?>">
												<td>
													<input type="text" class="form-control text-center" disabled value="<?=$nom_producto?>">
												</td>
												<td>
													<input type="text" class="form-control numeros text-center" value="<?=$cantidad?>" disabled>
												</td>
												<td class="<?=$ver_minus?>">
													<button class="btn btn-danger btn-sm remover_input" id="<?=$id_detalle?>" type="button" data-tooltip="tooltip" title="Eliminar" data-placement="bottom">
														<i class="fa fa-minus"></i>
													</button>
												</td>
												<td>
													<div class="custom-control custom-radio custom-control-inline">
														<input type="radio" class="custom-control-input" id="existenciasi_<?=$id_detalle?>" name="existencia_<?=$id_detalle?>[]" value="1">
														<label class="custom-control-label" for="existenciasi_<?=$id_detalle?>">Si</label>
													</div>
													<div class="custom-control custom-radio custom-control-inline">
														<input type="radio" class="custom-control-input" id="existenciano_<?=$id_detalle?>" name="existencia_<?=$id_detalle?>[]" value="0">
														<label class="custom-control-label" for="existenciano_<?=$id_detalle?>">No</label>
													</div>
												</td>
												<td>
													<input type="text" class="form-control numeros text-center" value="0" name="cantidad_existencia[]">
												</td>
											</tr>
											<?php
											$cont++;
										}
										?>
									</tbody>
								</table>
							</div>
							<div class="row p-2">
								<div class="col-lg-12 form-group">
									<label class="font-weight-bold">Observacion de revision</label>
									<textarea class="form-control" rows="5" name="observacion"></textarea>
								</div>
								<div class="col-lg-12 form-group text-right mt-3">
								<button class="btn btn-danger btn-sm" name="anular">
										<i class="fa fa-times"></i>
										&nbsp;
										Anular Revision
									</button>

									<button class="btn btn-hebreo btn-sm" name="aprobar">
										<i class="fa fa-check"></i>
										&nbsp;
										Aprobar Revision
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

	if (isset($_POST['aprobar'])) {
		$instancia->revisionSolicitudControl();
	}

	if (isset($_POST['anular'])) {
		$instancia->anularSolicitudComprasControl();
	}
}
?>