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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'permisos' . DS . 'ControlPermisos.php';

$instancia        = ControlPermisos::singleton_permisos();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_opciones = $instancia->mostrarOpcionesPermisosControl();
$datos_perfil   = $instancia_perfil->mostrarPerfilesTodosControl();

$permisos = $instancia_permiso->permisosUsuarioControl(28, $perfil_log);

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
					<h4 class="m-0 font-weight-bold text-hebreo">
						<a href="<?=BASE_URL?>inicio" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Permisos
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-8 form-inline">
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
									<div class="input-group-append">
										<button class="btn btn-hebreo btn-sm" type="submit">
											<i class="fa fa-search"></i>
											&nbsp;
											Buscar
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">Nombre</th>
									<th scope="col">Modulos activos</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_perfil as $perfil) {
									$id_perfil = $perfil['id_perfil'];
									$nombre    = $perfil['nombre'];
									$modulos   = $perfil['cant_modulos'];
									?>
									<tr class="text-center text-uppercase">
										<td><?=$nombre?></td>
										<td><?=$modulos?></td>
										<td>
											<button class="btn btn-success btn-sm" data-tooltip="tooltip" title="Agregar Permiso" data-placement="bottom"
											data-toggle="modal" data-target="#permisos<?=$id_perfil?>">
											<i class="fa fa-plus"></i>
										</button>
									</td>
								</tr>


								<!-- Modal -->
								<div class="modal fade" id="permisos<?=$id_perfil?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
									<div class="modal-dialog modal-dialog-scrollable" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title font-weight-bold text-hebreo" id="exampleModalLabel">Permisos - <?=$nombre?></h5>
												<a href="<?=BASE_URL?>permisos/index" class="btn btn-sm">
													<i class="fa fa-times"></i>
												</a>
											</div>
											<form method="POST">
												<input type="hidden" name="id_log" value="<?=$id_log?>">
												<div class="modal-body border-0 permisos_body">
													<div class="row p-2">
														<?php
														foreach ($datos_opciones as $opcion) {
															$id_opcion = $opcion['id'];
															$opcion    = $opcion['opcion'];

															$opcions_activos_perfil = $instancia->opcionsIdActivosPerfilControl($id_perfil, $id_opcion);

															if ($opcions_activos_perfil['id'] != "") {
																$activo = 'active';
																$icon   = '<i class="fa fa-times float-right"></i>';
																$class  = 'lista_inactivar';
															} else {
																$activo = '';
																$icon   = '<i class="fa fa-check float-right"></i>';
																$class  = 'lista_permiso';
															}

															?>
															<div class="col-lg-12 mb-2">
																<div class="list-group">
																	<a href="#" class="list-group-item list-group-item-action <?=$class?>  <?=$activo;?> opcion_<?=$id_opcion?>" data-perfil="<?=$id_perfil;?>" data-user="<?=$id_log;?>" id="<?=$id_opcion;?>" data-nombre="<?=$opcion?>">
																		<?=$opcion;?>
																		<?=$icon;?>
																	</a>
																</div>
															</div>
															<?php
														}
														?>
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
?>
<script src="<?=PUBLIC_PATH?>/js/permisos/funcionesPermisos.js"></script>