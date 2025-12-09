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
require_once CONTROL_PATH . 'renovacion' . DS . 'ControlRenovacion.php';

$instancia = ControlRenovacion::singleton_renovacion();

if (isset($_POST['buscar'])) {
	$datos_renovacion = $instancia->buscarRenovacionesControl($_POST['buscar'], $_POST['anio']);
} else {
	$datos_renovacion = $instancia->mostrarRenovacionesControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(44, $perfil_log);

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
						Renovaciones
					</h4>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
							<div class="dropdown-header">Acciones:</div>
							<a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_renovacion">Agregar Renovacion</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group"></div>
							<div class="col-lg-4 form-group">
								<select class="form-control" name="anio">
									<option value="" selected>Seleccione un a&ntilde;o...</option>
									<?php
									foreach ($anio_lectivo as $anio) {
										$id_anio     = $anio['id'];
										$anio_inicio = $anio['anio_inicio'];
										$anio_fin    = $anio['anio_fin'];
										$activo      = $anio['activo'];

										$sms_activo = ($activo == 0) ? '' : '(Activo)';

										?>
										<option value="<?=$id_anio?>"><?=$anio_inicio . ' - ' . $anio_fin . ' ' . $sms_activo?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
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
					</form>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">Nombre</th>
									<th scope="col">Fecha Renovado</th>
									<th scope="col">Fecha Proxima Renovacion</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_renovacion as $renovacion) {
									$id_renovacion    = $renovacion['id'];
									$nombre           = $renovacion['nombre'];
									$archivo          = $renovacion['archivo'];
									$fecha_renovacion = $renovacion['fecha_renovacion'];
									$fecha_proxima    = $renovacion['fecha_proxima'];
									$activo           = $renovacion['activo'];

									$fecha_actual = date('Y-m');

									$fecha_proxima_comparar = date("Y-m", strtotime($fecha_proxima));
									$fecha_proxima_antes    = date("Y-m", strtotime($fecha_proxima . "- 2 month"));

									if ($fecha_actual < $fecha_proxima_antes) {
										$span = '<span class="badge badge-success">Vigente</span>';
									} else if ($fecha_actual == $fecha_proxima_antes) {
										$span = '<span class="badge badge-warning">Por expirar</span>';
									} else if ($fecha_actual >= $fecha_proxima_comparar) {
										$span = '<span class="badge badge-danger">Expirado</span>';
									}

									$ver_archivo = (empty($archivo)) ? 'disabled' : '';

									?>
									<tr class="text-center">
										<td><?=$nombre?></td>
										<td><?=$fecha_renovacion?></td>
										<td><?=$fecha_proxima?></td>
										<td><?=$span?></td>
										<td>
											<a href="<?=PUBLIC_PATH?>upload/<?=$archivo?>" class="btn btn-primary btn-sm <?=$ver_archivo?>" target="_blank" data-tooltip="tooltip" title="Ver evidencia" data-placement="bottom" data-trigger="hover">
												<i class="fa fa-eye"></i>
											</a>
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
require_once VISTA_PATH . 'script_and_final.php';
require_once VISTA_PATH . 'modulos' . DS . 'renovacion' . DS . 'agregarRenovacion.php';

if (isset($_POST['nombre'])) {
	$instancia->agregarRenovacionControl();
}
?>