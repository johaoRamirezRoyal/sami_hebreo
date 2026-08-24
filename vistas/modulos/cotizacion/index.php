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

$instancia          = ControlSolicitud::singleton_solicitud();
$instancia_areas    = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$datos = array(
		'buscar'  => (isset($_POST['buscar'])) ? $_POST['buscar'] : '',
		'fecha'   => (isset($_POST['fecha'])) ? $_POST['fecha'] : '',
		'area'    => (isset($_POST['area'])) ? $_POST['area'] : '',
		'usuario' => (isset($_POST['usuario'])) ? $_POST['usuario'] : '',
	);
	$datos_solicitud = $instancia->buscarSolicitudesCotizacionControl($datos);
} else {
	$datos_solicitud = $instancia->mostrarSolicitudesControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(59, $perfil_log);
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
						<a href="<?=BASE_URL?>compras/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Cotizaciones / Orden de compra
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-3 form-group">
								<label class="font-weight-bold small text-hebreo">Fecha</label>
								<input type="date" name="fecha" class="form-control" value="<?=(isset($_POST['fecha'])) ? $_POST['fecha'] : ''?>" data-tooltip="tooltip" title="Fecha" data-placement="top">
							</div>
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
							<div class="col-lg-3 form-group">
								<label class="font-weight-bold small text-hebreo">Solicitante</label>
								<select name="usuario" class="form-control select2" data-tooltip="tooltip" title="Seleccionar solicitante">
									<option value="">Todos los solicitantes</option>
									<?php
									$datos_usuarios = $instancia_usuarios->mostrarTodosUsuariosControl(1);
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
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" value="<?=(isset($_POST['buscar'])) ? $_POST['buscar'] : ''?>" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
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
					<div class="mt-3 border rounded">
						<table class="table table-hover table-striped table-sm mb-0 table-fixed" width="100%" cellspacing="0">
							<thead class="bg-hebreo text-white">
								<tr class="text-center font-weight-bold">
									<th scope="col" class="align-middle" style="width: 6%;">No.</th>
									<th scope="col" class="align-middle" style="width: 10%;">Area</th>
									<th scope="col" class="align-middle" style="width: 10%;">Grado</th>
									<th scope="col" class="align-middle" style="width: 12%;">Solicitante</th>
									<th scope="col" class="align-middle">Justificacion</th>
									<th scope="col" class="align-middle" style="width: 10%;">Fecha aprobado</th>
									<th scope="col" class="align-middle" style="width: 12%;">Cotizacion / Orden de compra</th>
									<th scope="col" class="align-middle" style="width: 12%;">Acciones</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								if (empty($datos_solicitud)) {
									?>
									<tr class="text-center">
										<td colspan="8" class="py-4 text-muted">
											<i class="fa fa-inbox fa-2x mb-2 d-block"></i>
											No hay cotizaciones registradas
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
									$motivo        = $solicitud['motivo'];
									$grado         = (empty($solicitud['nom_curso'])) ? 'N/A' : $solicitud['nom_curso'];

									$texto = ($activo == 0) ? $motivo : $justificacion;

									$fechareg = ($estado == 3 || $estado == 4) ? $solicitud['fecha_aplazado'] : $solicitud['fecha_solicitud'];

									$cotizacion_aprobada = ($solicitud['cotizacion'] == 0) ? '<span class="badge badge-secondary"><i class="fa fa-clock"></i>&nbsp;Falta subir</span>' : '<span class="badge badge-success"><i class="fa fa-check"></i>&nbsp;Subida</span>';

									$ver_subir     = ($solicitud['cotizacion'] == 0 && $solicitud['id_area_compras'] != '') ? '' : 'd-none';
									$ver_detalles  = ($solicitud['cotizacion'] == 0) ? 'd-none' : '';
									$ver_carta     = ($solicitud['id_recibido'] == '') ? 'd-none' : '';
									$ver_verificar = ($solicitud['id_pedido'] == '') ? 'd-none' : '';
									$ver_verificar = ($solicitud['id_pedido'] != 0 && $solicitud['id_recibido'] == '') ? '' : $ver_verificar;
									$ver_verificar = ($solicitud['id_pedido'] != 0 && $solicitud['id_recibido'] != 0) ? 'd-none' : '';
									?>
									<tr class="text-center">
										<td class="align-middle font-weight-bold text-hebreo">#<?=$id_solicitud?></td>
										<td class="align-middle"><?=$nom_area?></td>
										<td class="align-middle"><?=$grado?></td>
										<td class="align-middle"><?=$nom_user?></td>
										<td class="align-middle text-justify text-break"><?=$texto?></td>
										<td class="align-middle"><?=date('Y-m-d', strtotime($fechareg))?></td>
										<td class="align-middle"><?=$cotizacion_aprobada?></td>
										<td class="align-middle">
											<div class="btn-group" role="group">
<!-- 												<a href="<?=BASE_URL?>cotizacion/verificar?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-success btn-sm <?=$ver_verificar?>" data-tooltip="tooltip" title="Verificar entrega" data-placement="bottom">
													<i class="fa fa-check-double"></i>
												</a> -->
												<a href="<?=BASE_URL?>cotizacion/detalle?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-success btn-sm <?=$ver_subir?>" data-tooltip="tooltip" title="Subir Cotizaciones" data-placement="bottom">
													<i class="fa fa-upload"></i>
												</a>
												<a href="<?=BASE_URL?>cotizacion/detalle?solicitud=<?=base64_encode($id_solicitud)?>" class="btn btn-info btn-sm <?=$ver_detalles?>" data-tooltip="tooltip" title="Ver informacion" data-placement="bottom">
													<i class="fa fa-eye"></i>
												</a>
												<a href="<?=BASE_URL?>imprimir/solicitud/cartaEntrega?solicitud=<?=base64_encode($id_solicitud)?>" target="_blank" class="btn btn-hebreo btn-sm" data-tooltip="tooltip" title="Generar Entrega" data-placement="bottom">
													<i class="fa fa-file-pdf"></i>
												</a>
											</div>
										</td>
									</tr>
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