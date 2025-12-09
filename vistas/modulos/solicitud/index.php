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

$instancia         = ControlSolicitud::singleton_solicitud();
$instancia_usuario = ControlUsuarios::singleton_usuarios();
$instancia_areas   = ControlAreas::singleton_areas();

$rand = rand(5, 15);

$datos_areas = $instancia_areas->mostrarAreasNoInventarioControl();
$datos_curso = $instancia_usuario->mostrarCursosUsuarioControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
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
						Solicitudes
					</h4>
					<div class="btn-group">
						<?php
						$permisos = $instancia_permiso->permisosUsuarioControl(71, $perfil_log);
						if ($permisos) {
							?>
							<a href="<?=BASE_URL?>solicitud/listado_user" class="btn btn-secondary btn-sm">
								<i class="fa fa-list-ul"></i>
								&nbsp;
								Mis solicitudes
							</a>
						<?php }
						$permisos = $instancia_permiso->permisosUsuarioControl(60, $perfil_log);
						if ($permisos) {
							?>
							<a href="<?=BASE_URL?>solicitud/listado" class="btn btn-info btn-sm">
								<i class="fa fa-list-ul"></i>
								&nbsp;
								Listado de solicitudes
							</a>
						<?php }
						?>
					</div>
				</div>
				<form method="POST">
					<input type="hidden" name="id_user" value="<?=$id_log?>">
					<input type="hidden" name="id_log" value="<?=$id_log?>">
					<div class="card-body">
						<div class="row">
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Fecha de solicitud</label>
								<input type="date" name="fecha_solicitud" class="form-control" value="<?=date('Y-m-d')?>">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Area solicitante <span class="text-danger">*</span></label>
								<select class="form-control" name="area" required>
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_areas as $area) {
										$id_area = $area['id'];
										$nombre  = $area['nombre'];
										$activo  = $area['activo'];
										?>
										<option value="<?=$id_area?>"><?=$nombre?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Grado</label>
								<select name="grado" class="form-control">
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_curso as $curso) {
										$id_curso  = $curso['id'];
										$nom_curso = $curso['nombre'];
										?>
										<option value="<?=$id_curso?>"><?=$nom_curso?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Nombre de quien solicita <span class="text-danger">*</span></label>
								<input type="text" disabled class="form-control" value="<?=$_SESSION['nombre_admin'] . ' ' . $_SESSION['apellido']?>" required>
							</div>
						</div>
						<div class="table-responsive mt-2">
							<table class="table table-hover border table-sm" width="100%" cellspacing="0">
								<thead>
									<tr class="text-center font-weight-bold bg-light">
										<th scope="col" colspan="2">TABLA DE PRODUCTOS</th>
										<th scope="col">
											<button type="button" class="btn btn-hebreo btn-sm agregar_producto" data-tooltip="tooltip" title="Agregar" data-trigger="hover">
												<i class="fa fa-plus"></i>
											</button>
										</th>
									</tr>
									<tr class="text-center font-weight-bold">
										<th scope="col">DESCRIPCION DEL PRODUCTO O SERVICIO SOLICITADA</th>
										<th scope="col">CANTIDAD</th>
									</tr>
								</thead>
								<tbody class="buscar">
									<tr>
										<td>
											<input type="text" class="form-control" name="producto[]">
										</td>
										<td>
											<input type="text" class="form-control numeros text-center" name="cantidad[]">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="form-group col-lg-12 mt-2">
								<label class="font-weight-bold">Justificacion de la solicitud</label>
								<textarea class="form-control" rows="5" name="justificacion"></textarea>
							</div>
							<div class="form-group col-lg-12 text-right">
								<button class="btn btn-hebreo btn-sm" type="submit">
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
	$instancia->registrarSolicitudControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/solicitud/funcionesSolicitud.js"></script>