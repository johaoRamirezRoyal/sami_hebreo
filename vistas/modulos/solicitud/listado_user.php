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

$datos_solicitud = $instancia->mostrarSolicitudesUsuarioControl($id_log);

$permisos = $instancia_permiso->permisosUsuarioControl(71, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-hebreo">
						<a href="<?=BASE_URL?>solicitud/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Mis solicitudes
					</h4>
					<div class="btn-group">
						<a href="<?=BASE_URL?>solicitud/index" class="btn btn-hebreo btn-sm">
							<i class="fa fa-plus"></i>
							&nbsp;
							Registrar nueva solicitud
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-8 form-inline">
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<div class="input-group mb-3">
									<input type="text" class="form-control filtro" placeholder="Buscar">
									<div class="input-group-prepend">
										<span class="input-group-text rounded-right" id="basic-addon1">
											<i class="fa fa-search"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">No.</th>
									<th scope="col">Area</th>
									<th scope="col">Grado</th>
									<th scope="col">Solicitante</th>
									<th scope="col">Justificacion</th>
									<th scope="col">Fecha aprobado / rechazado</th>
									<th scope="col">Coordinador</th>
									<th scope="col">Area de compras</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_solicitud as $solicitud) {
									$id_solicitud  = $solicitud['id'];
									$id_area       = $solicitud['id_area'];
									$id_user       = $solicitud['id_user'];
									$nom_user      = $solicitud['nom_usuario'];
									$nom_area      = $solicitud['area_nom'];
									$estado        = $solicitud['estado'];
									$justificacion = $solicitud['justificacion'];
									$activo        = $solicitud['activo'];
									$motivo        = $solicitud['motivo'];
									$grado         = (empty($solicitud['nom_curso'])) ? 'N/A' : $solicitud['nom_curso'];

									$texto = ($activo == 0) ? $motivo : $justificacion;

									$fechareg = ($estado == 3 || $estado == 4) ? $solicitud['fecha_aplazado'] : $solicitud['fecha_solicitud'];

									$ver_boton  = ($estado == 1 || $estado == 2) ? 'd-none' : '';
									$ver_anular = ($estado == 1 || $estado == 2) ? 'd-none' : '';

									$ver_revision = (empty($solicitud['id_area_compras']) && $estado == 1) ? '' : 'd-none';
									$ver_pedido   = ($estado == 0 && empty($solicitud['id_pedido'])) ? 'd-none' : '';
									$ver_pedido   = ($estado == 1 && empty($solicitud['id_area_compras'])) ? 'd-none' : $ver_pedido;

									$ver_pedido = ($estado == 1 && !empty($solicitud['id_area_compras']) && !empty($solicitud['id_pedido'])) ? 'd-none' : $ver_pedido;

									/*------------------*/
									$span_compras = (empty($solicitud['id_area_compras'])) ? '<span class="badge badge-warning">Pendiente de revision</span>' : '<span class="badge badge-success">Aprobada</span>';
									/*------------------*/
									$span         = ($estado == 0) ? '<span class="badge badge-warning">Pendiente de aprobacion</span>' : '';
									$span         = ($estado == 1) ? '<span class="badge badge-success">Aprobada</span>' : $span;
									$span         = ($estado == 2) ? '<span class="badge badge-danger">Rechazada</span>' : $span;
									$ver_detalles = ($estado == 1 || $estado == 2) ? '' : 'd-none';

									$ver_carta = (empty($solicitud['id_area_compras'])) ? 'd-none' : '';
									/*------------------*/
									if ($activo == 1) {

										?>
										<tr class="text-center">
											<td><?=$id_solicitud?></td>
											<td><?=$nom_area?></td>
											<td><?=$grado?></td>
											<td><?=$nom_user?></td>
											<td><?=$texto?></td>
											<td><?=date('Y-m-d', strtotime($fechareg))?></td>
											<td><?=$span?></td>
											<td><?=$span_compras?></td>
											<!-- <td>
												<div class="btn-group" role="group">
													<a href="<?=BASE_URL?>solicitud/detalles?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-info btn-sm <?=$ver_detalles?>" data-tooltip="tooltip" title="Ver detalles" data-placement="bottom">
														<i class="fa fa-eye"></i>
													</a>
													<a href="<?=BASE_URL?>imprimir/solicitud/cartaEntrega?solicitud=<?=base64_encode($id_solicitud)?>" target="_blank" class="btn btn-hebreo btn-sm <?=$ver_carta?>" data-tooltip="tooltip" title="Generar Entrega" data-placement="bottom">
														<i class="fa fa-file-pdf"></i>
													</a>
												</div>
											</td> -->
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';