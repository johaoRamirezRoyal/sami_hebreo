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

$datos_area = $instancia->mostrarAreasZonasControl();

if (isset($_POST['buscar'])) {

	$datos      = array('area' => $_POST['area'], 'buscar' => $_POST['buscar']);
	$datos_zona = $instancia->buscarZonaControl($datos);
} else {
	$datos_zona = $instancia->mostrarZonaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(33, $perfil_log);

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
						Areas
					</h4>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
							<div class="dropdown-header">Acciones:</div>
							<a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_zona">Agregar area</a>
							<a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_area">Agregar Zona</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4"></div>
							<div class="form-group col-lg-4">
								<select name="area" class="form-control select2">
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_area as $area) {
										$id_area  = $area['id'];
										$nom_area = $area['nombre'];
										$activo   = $area['activo'];
										?>
										<option value="<?=$id_area?>"><?=$nom_area?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<div class="input-group">
										<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
										<div class="input-group-append">
											<button class="btn btn-primary btn-sm" type="submit">
												<i class="fa fa-search"></i>
												&nbsp;
												Buscar
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">#</th>
									<th scope="col">Area</th>
									<th scope="col">Zona</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_zona as $zona) {
									$id_zona      = $zona['id'];
									$nombre       = $zona['nombre'];
									$activo       = $zona['activo'];
									$nom_area     = $zona['nom_area'];
									$id_area_zona = $zona['id_area'];
									$estado       = $zona['estado'];

									$ver_reporte   = '';
									$ver_mant      = '';
									$ver_editar    = '';
									$ver_inactivar = '';
									$span          = '';

									$ver = ($activo == 0 && $perfil_log != 1) ? 'd-none' : '';

									if ($activo == 1) {
										$icon  = '<i class="fa fa-times"></i>';
										$class = 'btn-danger inactivar_zona';
										$title = "Inactivar";
									} else {
										$icon  = '<i class="fa fa-check"></i>';
										$class = 'btn-success activar_zona';
										$title = "Activar";
									}

									if ($estado == 2) {
										$ver_reporte   = 'd-none';
										$ver_mant      = 'd-none';
										$ver_editar    = 'd-none';
										$ver_inactivar = 'd-none';
										$span          = '<span class="badge badge-danger">Correctivo</span>';
									}

									if ($estado == 6) {
										$ver_reporte   = 'd-none';
										$ver_mant      = 'd-none';
										$ver_editar    = 'd-none';
										$ver_inactivar = 'd-none';
										$span          = '<span class="badge badge-warning">Mantenimiento</span>';
									}

									?>
									<tr class="text-center text-uppercase <?=$ver?>">
										<td><?=$id_zona?></td>
										<td>
											<a href="<?=BASE_URL?>historial/zona?id=<?=base64_encode($id_zona)?>"><?=$nombre?></a>
										</td>
										<td><?=$nom_area?></td>
										<td>
											<?=$span?>
										</td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<button class="btn btn-success btn-sm <?=$ver_reporte?>" data-tooltip="tooltip" title="Reportar zona" data-placement="bottom" data-toggle="modal" data-target="#dano<?=$id_zona?>">
													<i class="fas fa-brush"></i>
												</button>
												<button class="btn btn-warning btn-sm <?=$ver_mant?>" data-tooltip="tooltip" data-placement="bottom" title="Mantenimiento" data-toggle="modal" data-target="#mant<?=$id_zona?>">
													<i class="fas fa-wrench"></i>
												</button>
												<button class="btn btn-primary btn-sm <?=$ver_editar?>" data-tooltip="tooltip" title="Agregar area" data-placement="bottom" data-toggle="modal" data-target="#area<?=$id_zona?>">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn <?=$class?> btn-sm <?=$ver_inactivar?>" data-tooltip="tooltip" title="<?=$title?>" data-placement="bottom">
													<?=$icon?>
												</button>
											</div>
										</td>
									</tr>



									<!-- Modal -->
									<div class="modal fade" id="area<?=$id_zona?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Editar Zona</h5>
												</div>
												<form method="POST">
													<input type="hidden" name="id_log" value="<?=$id_log?>">
													<input type="hidden" name="id_zona" value="<?=$id_zona?>">
													<div class="modal-body">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
																<input type="text" class="form-control" value="<?=$nombre?>" name="nom_zona">
															</div>
															<div class="form-group col-lg-6">
																<label class="font-weight-bold">Area <span class="text-danger">*</span></label>
																<select name="area_edit" class="form-control">
																	<?php
																	foreach ($datos_area as $area) {
																		$id_area  = $area['id'];
																		$nom_area = $area['nombre'];
																		$activo   = $area['activo'];

																		$select_area = ($id_area_zona == $id_area) ? 'selected' : '';

																		?>
																		<option value="<?=$id_area?>" <?=$select_area?>><?=$nom_area?></option>
																		<?php
																	}
																	?>
																</select>
															</div>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
															<i class="fa fa-times"></i>
															&nbsp;
															Cerrar
														</button>
														<button type="submit" class="btn btn-success btn-sm">
															<i class="fa fa-save"></i>
															&nbsp;
															Guardar
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>



									<!-- Modal -->
									<div class="modal fade" id="dano<?=$id_zona?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Reportar zona</h5>
												</div>
												<form method="POST">
													<input type="hidden" name="id_log" value="<?=$id_log?>">
													<input type="hidden" name="id_zona" value="<?=$id_zona?>">
													<input type="hidden" value="2" name="estado">
													<input type="hidden" name="fecha_mant" value="">
													<input type="hidden" name="inicio" value="0">
													<div class="modal-body">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
																<input type="text" class="form-control" disabled value="<?=$nombre?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Area <span class="text-danger">*</span></label>
																<input type="text" class="form-control" disabled value="<?=$nom_area?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha reporte <span class="text-danger">*</span></label>
																<input type="date" name="fecha_reporte" class="form-control" required>
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Observacion</label>
																<textarea class="form-control" rows="5" name="observacion"></textarea>
															</div>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
															<i class="fa fa-times"></i>
															&nbsp;
															Cerrar
														</button>
														<button type="submit" class="btn btn-success btn-sm">
															<i class="fa fa-save"></i>
															&nbsp;
															Guardar
														</button>
													</div>
												</form>
											</div>
										</div>
									</div>


									<!-- Modal -->
									<div class="modal fade" id="mant<?=$id_zona?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Mantenimiento zona</h5>
												</div>
												<form method="POST">
													<input type="hidden" value="6" name="estado">
													<input type="hidden" name="id_log" value="<?=$id_log?>">
													<input type="hidden" name="id_zona" value="<?=$id_zona?>">
													<input type="hidden" name="inicio" value="0">
													<div class="modal-body">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
																<input type="text" class="form-control" disabled value="<?=$nombre?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Area <span class="text-danger">*</span></label>
																<input type="text" class="form-control" disabled value="<?=$nom_area?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha a programar <span class="text-danger">*</span></label>
																<input type="date" class="form-control" name="fecha_mant" value="<?=date('Y-m-d')?>">
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Observacion</label>
																<textarea class="form-control" rows="5" name="observacion"></textarea>
															</div>
														</div>
													</div>
													<div class="modal-footer border-0">
														<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
															<i class="fa fa-times"></i>
															&nbsp;
															Cerrar
														</button>
														<button type="submit" class="btn btn-success btn-sm">
															<i class="fa fa-save"></i>
															&nbsp;
															Guardar
														</button>
													</div>
												</form>
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
include_once VISTA_PATH . 'modulos' . DS . 'zonas' . DS . 'agregarZona.php';
include_once VISTA_PATH . 'modulos' . DS . 'zonas' . DS . 'agregarArea.php';

if (isset($_POST['nombre'])) {
	$instancia->guardarZonasControl();
}

if (isset($_POST['nom_area'])) {
	$instancia->agregarAreaControl();
}

if (isset($_POST['area_edit'])) {
	$instancia->editarZonaControl();
}

if (isset($_POST['estado'])) {
	$instancia->reportarZonaControl();
}
?>