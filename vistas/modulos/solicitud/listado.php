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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$instancia       = ControlSolicitud::singleton_solicitud();
$instancia_areas = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

if ($perfil_log == 26 || $perfil_log == 1) {
	$datos_solicitud = $instancia->mostrarSolicitudesControl(30);
} else {
	$datos_solicitud = $instancia->mostrarSolucitudesNivelControl($nivel, 30);
}

if (isset($_POST['filtrar'])) {
	$datos = array(
		'buscar'  => (isset($_POST['buscar'])) ? $_POST['buscar'] : '',
		'fecha'   => (isset($_POST['fecha'])) ? $_POST['fecha'] : '',
		'area'    => (isset($_POST['area'])) ? $_POST['area'] : '',
		'usuario' => (isset($_POST['usuario'])) ? $_POST['usuario'] : '',
	);
	if ($perfil_log != 26 && $perfil_log != 1) {
		$datos['nivel'] = $nivel;
	}
	$datos_solicitud = $instancia->buscarSolicitudesControl($datos);
}

$permisos = $instancia_permiso->permisosUsuarioControl(60, $perfil_log);
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
						Listado de solicitudes
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
					<form method="POST">
						<div class="row">
							<div class="col-lg-3 form-group">
								<label class="font-weight-bold small text-hebreo">Área</label>
								<select name="area" class="form-control select2" data-tooltip="tooltip" title="Seleccionar area">
									<option value="">Todas las áreas</option>
									<?php
									$datos_areas = $instancia_areas->mostrarAreasControl(1);
									foreach ($datos_areas as $area) {
										$selected = (!empty($_POST['area']) && (int)$_POST['area'] == (int)$area['id']) ? 'selected' : '';
										?>
										<option value="<?=$area['id']?>" <?=$selected?>><?=$area['nombre']?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-2 form-group">
								<label class="font-weight-bold small text-hebreo">Fecha</label>
								<input type="date" class="form-control" name="fecha" value="<?=(isset($_POST['fecha'])) ? $_POST['fecha'] : ''?>" data-tooltip="tooltip" title="Fecha">
							</div>
							<div class="col-lg-3 form-group">
								<label class="font-weight-bold small text-hebreo">Solicitante</label>
								<select name="usuario" class="form-control select2" data-tooltip="tooltip" title="Seleccionar usuario">
									<option value="">Todos los solicitantes</option>
									<?php
									$datos_usuarios = $instancia_usuarios->mostrarTodosUsuariosControl(($perfil_log == 22) ? $nivel : null);
									foreach ($datos_usuarios as $usuario) {
										$selected = (!empty($_POST['usuario']) && (int)$_POST['usuario'] == (int)$usuario['id_user']) ? 'selected' : '';
										?>
										<option value="<?=$usuario['id_user']?>" <?=$selected?>><?=$usuario['nom_user']?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-3 form-group">
								<label class="font-weight-bold small text-hebreo">Buscar</label>
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" name="buscar" value="<?=(isset($_POST['buscar'])) ? $_POST['buscar'] : ''?>" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
									<div class="input-group-append">
										<button class="btn btn-hebreo btn-sm" type="submit">
											<i class="fa fa-search"></i>
											&nbsp;
											Buscar
										</button>
									</div>
								</div>
							</div>
							<div class="col-lg-1 form-group d-flex align-items-end">
								<input type="hidden" name="filtrar" value="1">
								<a href="<?=BASE_URL?>solicitud/listado" class="btn btn-outline-secondary btn-sm" data-tooltip="tooltip" title="Limpiar filtros" data-placement="top">
									<i class="fa fa-eraser"></i>
								</a>
							</div>
						</div>
					</form>
					
					<div class="mt-3 border rounded">
						<table class="table table-hover table-striped table-sm mb-0 table-fixed" width="100%" cellspacing="0">
							<thead class="bg-hebreo text-white">
								<tr class="text-center font-weight-bold">
									<th scope="col" class="align-middle" style="width: 5%;">No.</th>
									<th scope="col" class="align-middle" style="width: 9%;">Area</th>
									<th scope="col" class="align-middle" style="width: 8%;">Grado</th>
									<th scope="col" class="align-middle" style="width: 11%;">Solicitante</th>
									<th scope="col" class="align-middle" style="width: 18%;">Justificacion</th>
									<th scope="col" class="align-middle" style="width: 9%;">Fecha Solicitud</th>
									<th scope="col" class="align-middle" style="width: 11%;">Fecha aprobado / rechazado</th>
									<th scope="col" class="align-middle" style="width: 8%;">Coordinador</th>
									<th scope="col" class="align-middle" style="width: 9%;">Area de compras</th>
									<th scope="col" class="align-middle" style="width: 12%;">Acciones</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								if (empty($datos_solicitud)) {
									?>
									<tr class="text-center">
										<td colspan="10" class="py-4 text-muted">
											<i class="fa fa-inbox fa-2x mb-2 d-block"></i>
											No hay solicitudes registradas
										</td>
									</tr>
									<?php
								}
								foreach ($datos_solicitud as $solicitud) {
									$id_solicitud  = $solicitud['id'];
									$id_area       = $solicitud['id_area'];
									$id_user       = $solicitud['id_user'];
									$nom_user      = $solicitud['nom_usuario'];
									$nom_area      = $solicitud['area_nom'];
									$estado        = $solicitud['estado'];
									$justificacion = $solicitud['justificacion'];
									$activo        = $solicitud['activo'];
									$fecharegistro = $solicitud['fechareg'];
									$motivo        = $solicitud['motivo'];
									$estadocompra = $solicitud['estadocompra'];
									$grado         = (empty($solicitud['nom_curso'])) ? 'N/A' : $solicitud['nom_curso'];

									$texto = ($activo == 0) ? $motivo : $justificacion;

									$fechareg = ($estado == 3 || $estado == 4) ? $solicitud['fecha_aplazado'] : $solicitud['fecha_solicitud'];

									$ver_boton  = ($estado == 1 || $estado == 2) ? 'd-none' : '';
									$ver_anular = ($estado == 1 || $estado == 2) ? 'd-none' : '';

									$ver_revision = (empty($solicitud['id_area_compras']) && $estado == 1) ? '' : 'd-none';
									$ver_pedido   = ($estado == 2 || $estado == 0 || empty($solicitud['id_area_compras'])) ? 'd-none' : '';

									
									/*------------------*/
									$span_compras = (empty($solicitud['id_area_compras'])) ? '<span class="badge badge-secondary"><i class="fa fa-clock"></i>&nbsp;Pendiente</span>' : '<span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Aprobada</span>';
									/*------------------*/
									$span         = ($estado == 0) ? '<span class="badge badge-warning"><i class="fa fa-clock"></i>&nbsp;Pendiente</span>' : '';
									$span         = ($estado == 1) ? '<span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Aprobada</span>' : $span;
									$span         = ($estado == 2) ? '<span class="badge badge-danger"><i class="fa fa-times"></i>&nbsp;Rechazada</span>' : $span;
									$ver_detalles = ($estado == 1 || $estado == 2) ? '' : 'd-none';

									$ver_carta = (empty($solicitud['id_area_compras'])) ? 'd-none' : '';
									/*------------------*/
									$estado_compra_badge = '';

									if ($activo == 1) {
										switch($estadocompra){
											case 0:
												$estado_compra_badge = '<span class="badge badge-warning"><i class="fa fa-clock"></i>&nbsp;Pendiente</span>';
												break;
											case 2:
												$estado_compra_badge = '<span class="badge badge-danger"><i class="fa fa-times"></i>&nbsp;Rechazada</span>';
												break;
											case 3:
												$estado_compra_badge = '<span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Aprobada</span>';
												break;
											default:
												$estado_compra_badge = '<span class="badge badge-secondary">Default</span>';
												break;
											}
										?>
									<tr class="text-center">
										<td class="align-middle font-weight-bold text-hebreo">#<?=$id_solicitud?></td>
										<td class="align-middle"><?=$nom_area?></td>
										<td class="align-middle"><?=$grado?></td>
										<td class="align-middle"><?=$nom_user?></td>
										<td class="align-middle text-justify text-break"><?=$texto?></td>
										<td class="align-middle"><?=date('Y-m-d', strtotime($fecharegistro))?></td>
										<td class="align-middle"><?=date('Y-m-d', strtotime($fechareg))?></td>
										<td class="align-middle"><?=$span?></td>
										<td class="align-middle"><?=$estado_compra_badge?></td>
										<td class="align-middle">
											<div class="btn-group" role="group">
													<?php
													$permisos = $instancia_permiso->permisosUsuarioControl(68, $perfil_log);
													if ($permisos) {
														?>
														<a class="btn btn-success btn-sm <?=$ver_boton?>" href="<?=BASE_URL?>solicitud/confirmar?solicitud=<?=base64_encode($id_solicitud)?>" data-tooltip="tooltip" data-placement="bottom" data-trigger="hover" title="Aprobar solicitud">
															<i class="fas fa-check-double"></i>
														</a>
														<button class="btn btn-danger btn-sm <?=$ver_anular?>" data-toggle="modal" data-target="#anular<?=$id_solicitud?>" data-tooltip="tooltip" data-placement="bottom"data-trigger="hover" title="Anular solicitud">
															<i class="fa fa-times"></i>
														</button>
														<?php
													}
													$permisos = $instancia_permiso->permisosUsuarioControl(69, $perfil_log);
													if ($permisos) {
														?>
														<a href="<?=BASE_URL?>solicitud/revision?solicitud=<?=base64_encode($id_solicitud)?>&ref=listado" class="btn btn-success btn-sm <?=$ver_revision?>" data-tooltip="tooltip" title="Revision de solicitud" data-placement="bottom">
															<i class="fa fa-check"></i>
														</a>
													<?php }
													$permisos = $instancia_permiso->permisosUsuarioControl(70, $perfil_log);
													if ($permisos) {
														?>
														<!-- <a href="<?=BASE_URL?>solicitud/cotizacion?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-hebreo btn-sm <?=$ver_cotizacion?>" data-tooltip="tooltip" title="Revision de cotizaciones" data-placement="bottom">
															<i class="fa fa-list-ul"></i>
														</a> -->
													<?php }
													$permisos = $instancia_permiso->permisosUsuarioControl(70, $perfil_log);
													if ($permisos) {
														?>
														<a href="<?=BASE_URL?>solicitud/pedido?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-secondary btn-sm <?=$ver_pedido?>" data-tooltip="tooltip" title="Realizar Pedido" data-placement="bottom">
															<i class="fas fa-truck-loading"></i>
														</a>
													<?php }
													?>
													<a href="<?=BASE_URL?>solicitud/detalles?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-info btn-sm <?=$ver_detalles?>" data-tooltip="tooltip" title="Ver detalles" data-placement="bottom">
														<i class="fa fa-eye"></i>
													</a>
													<a href="<?=BASE_URL?>imprimir/solicitud/cartaEntrega?solicitud=<?=base64_encode($id_solicitud)?>" target="_blank" class="btn btn-hebreo btn-sm <?=$ver_carta?>" data-tooltip="tooltip" title="Generar Entrega" data-placement="bottom">
														<i class="fa fa-file-pdf"></i>
													</a>
												</div>
											</td>
										</tr>


										<div class="modal fade" id="anular<?=$id_solicitud?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog modal-lg" role="document">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title text-hebreo font-weight-bold" id="exampleModalLabel">Anular solicitud (#<?=$id_solicitud?>)</h5>
													</div>
													<form method="POST">
														<input type="hidden" name="id_solicitud" value="<?=$id_solicitud?>">
														<input type="hidden" name="id_log" value="<?=$id_log?>">
														<div class="modal-body">
															<div class="row">
																<div class="col-lg-12 form-group">
																	<label class="font-weight-bold">Motivo de anulacion</label>
																	<textarea maxlength="1300" required rows="5" class="form-control" cols="4" name="motivo"></textarea>
																</div>
															</div>
														</div>
														<div class="modal-footer border-0">
															<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
																<i class="fa fa-times"></i>
																&nbsp;
																Cerrar
															</button>
															<button type="POST" class="btn btn-hebreo btn-sm"name="modal_anulado">
																<i class="fa fa-save"></i>
																&nbsp;
																Enviar
															</button>
														</div>
													</form>
												</div>
											</div>
										</div>
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

if (isset($_POST['modal_anulado'])) {
	$instancia->anularSolicitudControl();
}
?>