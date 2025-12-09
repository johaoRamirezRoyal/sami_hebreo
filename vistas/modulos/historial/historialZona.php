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
require_once CONTROL_PATH . 'zonas' . DS . 'ControlZonas.php';

$instancia = ControlZonas::singleton_zonas();

if (isset($_POST['fecha_fin'])) {
	$fecha_inicio = ($_POST['fecha_inicio'] == '') ? '0000-00-00' : $_POST['fecha_inicio'];
	$fecha_fin    = ($_POST['fecha_fin'] == '') ? '0000-00-00' : $_POST['fecha_fin'];
	$zona         = $_POST['zona'];

	$datos_historial = $instancia->buscarHistorialZonaControl($fecha_inicio, $fecha_fin, $zona);
} else {
	$datos_historial = $instancia->mostrarHistorialZonaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(35, $perfil_log);

if (!$permisos) {
	include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
	exit();
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-primary">
						<a href="<?=BASE_URL?>inicio" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Historial de reportes de zona (Correctivos)
					</h4>
					<a href="<?=BASE_URL?>historial/historialZonaMant" class="btn btn-warning btn-sm">
						<i class="fa fa-eye"></i>
						&nbsp;
						Ver mantenimientos
					</a>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row p-2">
							<div class="col-lg-4 form-group">
								<input type="date" name="fecha_inicio" class="form-control" data-tooltip="tooltip" title="Fecha inicio" data-placement="top" data-trigger="hover">
							</div>
							<div class="col-lg-4 form-group">
								<input type="date" name="fecha_fin" class="form-control" data-tooltip="tooltip" title="Fecha fin" data-placement="top" data-trigger="hover" value="<?=date('Y-m-d')?>">
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="zona"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
									<div class="input-group-append">
										<button class="btn btn-primary btn-sm" type="submit">
											<i class="fa fa-search"></i>
											&nbsp;
											Buscar
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">#</th>
									<th scope="col">Area</th>
									<th scope="col">Zona</th>
									<!-- <th scope="col">Usuario que reporto</th> -->
									<th scope="col">Observacion</th>
									<th scope="col">Observacion re-programado</th>
									<th scope="col">Observacion respuesta</th>
									<th scope="col">Fecha inicial de programacion</th>
									<th scope="col">Fecha de terminacion</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_historial as $historial) {
									$id_historial      = $historial['id'];
									$nom_area          = $historial['nom_area'];
									$nom_zona          = $historial['nom_zona'];
									$observacion       = $historial['observacion'];
									$observacion_repro = $historial['observacion_repro'];
									$estado            = $historial['estado'];
									$estado_respuesta  = $historial['estado_respuesta'];
									$observacion_resp  = $historial['observacion_resp'];

									$fecha           = ($estado == 6) ? $historial['fecha_mantenimiento'] : $historial['fechareg'];
									$fecha_respuesta = $historial['fecha_respuesta_con'];

									$datetime1 = new DateTime($fecha);
									$datetime2 = new DateTime($fecha_respuesta);
									$interval  = $datetime1->diff($datetime2);
									$respuesta = $interval->format('%m Meses %d Dias %h Horas %i Minutos %s Segundos');

									$respuesta = ($fecha_respuesta == '') ? '' : $respuesta;

									$span_repor = "";
									$span_resp  = "";

									if ($estado == 6) {
										$span_repor = '<span class="badge badge-warning">Mantenimiento</span>';
									}

									if ($estado == 10) {
										$span_repor = '<span class="badge badge-warning">Mantenimiento re-programado</span>';
									}

									if ($estado == 2) {
										$span_repor = '<span class="badge badge-danger">Correctivo</span>';
									}

									if (is_null($estado_respuesta)) {
										$span = "";
									}

									if ($estado_respuesta == 10) {
										$span_resp = '<span class="badge badge-warning">Mantenimiento re-programado</span>';
									}

									if ($estado_respuesta == 3) {
										$span_resp = '<span class="badge badge-success">Solucionado</span>';
									}

									$ver_editar = (empty($historial['id_respuesta'])) ? 'd-none' : '';

									?>
									<tr class="text-center">
										<td><?=$id_historial?></td>
										<td class="text-uppercase"><?=$nom_zona?></td>
										<td class="text-uppercase"><?=$nom_area?></td>
										<td><?=$observacion?></td>
										<td><?=$observacion_repro?></td>
										<td><?=$observacion_resp?></td>
										<td><?=$fecha?></td>
										<td><?=$fecha_respuesta?></td>
										<!-- <td><?=$respuesta?></td> -->
										<td><?=$span_repor . ' | ' . $span_resp?></td>
										<td class="<?=$ver_editar?>">
											<div class="btn-group">
												<button class="btn btn-primary btn-sm" type="button" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-toggle="modal" data-target="#editar<?=$id_historial?>">
													<i class="fa fa-edit"></i>
												</button>
												<a href="<?=BASE_URL?>imprimir/zonas/solucion?reporte=<?=base64_encode($id_historial)?>" target="_blank" class="btn btn-success btn-sm" data-tooltip="tooltip" title="Reporte Solcuionado" data-placement="bottom">
													<i class="fas fa-file-pdf"></i>
												</a>
											</div>
										</td>
									</tr>


									<!-- Modal -->
									<div class="modal fade" id="editar<?=$id_historial?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Editar Reporte Correctivo No. <?=$id_historial?></h5>
												</div>
												<div class="modal-body">
													<form method="POST">
														<input type="hidden" name="id_log" value="<?=$id_log?>">
														<input type="hidden" name="id_reporte" value="<?=$historial['id_respuesta']?>">
														<input type="hidden" name="url" value="0">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Area <span class="text-danger">*</span></label>
																<input type="text" disabled class="form-control" value="<?=$nom_zona?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
																<input type="text" disabled class="form-control" value="<?=$nom_area?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha de inicial de programacion <span class="text-danger">*</span></label>
																<input type="text" disabled class="form-control" value="<?=$fecha?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha de terminacion <span class="text-danger">*</span></label>
																<input type="text" disabled class="form-control" value="<?=$fecha_respuesta?>">
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Observacion Respuesta</label>
																<textarea class="form-control" rows="5" name="observacion_resp"><?=$observacion_resp?></textarea>
															</div>
															<div class="col-lg-12 form-group text-right mt-2">
																<button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
																	<i class="fa fa-times"></i>
																	&nbsp;
																	Cancelar
																</button>
																<button class="btn btn-success btn-sm" type="submit">
																	<i class="fa fa-edit"></i>
																	&nbsp;
																	Editar
																</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>

									<?php
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

if (isset($_POST['id_log'])) {
	$instancia->editarReporteControl();
}
?>