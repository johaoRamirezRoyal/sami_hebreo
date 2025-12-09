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
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlProveedor::singleton_proveedor();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

//$datos_usuarios = $instancia_usuario->mostrarUsuariosControl();

$datos_usuarios = $instancia_usuario->mostrarUsuariosEvaluarControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['proveedor'])) {

	$id_proveedor = base64_decode($_GET['proveedor']);

	$datos_proveedor = $instancia->mostrarDatosProveedorIdControl($id_proveedor);
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-primary">
							<a href="<?=BASE_URL?>proveedor/index" class="text-decoration-none">
								<i class="fa fa-arrow-left text-primary"></i>
							</a>
							&nbsp;
							Evaluacion proveedor (<?=$datos_proveedor['nombre'] . ' - ' . $datos_proveedor['num_identificacion']?>)
						</h4>
						<!-- <p class="m-0 font-weight-bold float-right text-danger">Campos obligatorios (<span class="text-danger">*</span>)</p> -->
					</div>
					<form method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="id_proveedor" value="<?=$id_proveedor?>">
						<div class="card-body">
							<div class="row p-2">
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha de inicio <span class="text-danger">*</span></label>
									<input type="date" name="fecha_inicio" class="form-control" required>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha de finalizacion <span class="text-danger">*</span></label>
									<input type="date" name="fecha_finalizacion" class="form-control" required>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha de evaluacion <span class="text-danger">*</span></label>
									<input type="date" name="fecha_evaluacion" class="form-control" required>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Evaluador <span class="text-danger">*</span></label>
									<select class="form-control" name="evaluador" required>
										<option value="" selected>Seleccione una opcion...</option>
										<?php
										foreach ($datos_usuarios as $usuario) {
											$id_user         = $usuario['id_user'];
											$documento       = $usuario['documento'];
											$nombre_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
											$correo          = $usuario['correo'];
											$telefono        = $usuario['telefono'];
											$user            = $usuario['user'];
											$perfil          = $usuario['nom_perfil'];
											$activo          = $usuario['estado'];

									$ver_user = ($usuario['perfil'] != 26) ? 'd-none' : '';

											?>
											<option value="<?=$id_user?>" class="<?=$ver_user?>"><?=$nombre_completo?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col-lg-12 form-group mt-2">
									<ul>
										<p class="text-danger">Marque teniendo en cuenta lo siguiente:</p>
										<p>Escala de Calificación:</p>
										<li class="ml-4">5: Entre el 90% y el 100% de las veces</li>
										<li class="ml-4">4: Entre 80% y 89% de las veces</li>
										<li class="ml-4">3: Entre el 70% y el 79% de las veces</li>
										<li class="ml-4">2: Entre el 40% y el 69% de las veces</li>
										<li class="ml-4">1: Menos del 40% de las veces</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="table-responsive p-3">
							<table class="table table-hover table-sm border" width="100%" cellspacing="0">
								<thead>
									<tr class="text-center font-weight-bold">
										<th scope="col" colspan="3">CRITERIOS DE EVALUACION</th>
									</tr>
									<tr class="text-center font-weight-bold">
										<th scope="col">Aspecto evaluado</th>
										<th scope="col">Calificacion</th>
										<th scope="col">Observaciones</th>
									</tr>
								</thead>
								<tbody class="buscar text-uppercase">
									<tr class="text-dark">
										<td>Las productos o servicios comprados o contratados cumplieron con las especificaciones.</td>
										<td>
											<div class="input-group input-group-sm mb-3">
												<input type="text" required class="form-control text-center" id="valor_1" name="pregunta_1">
											</div>
										</td>
										<td>
											<textarea class="form-control" name="observacion_1"></textarea>
										</td>
									</tr>
									<tr class="text-dark">
										<td>El producto y/o servicio cumple los precios acordados en la negociación</td>
										<td>
											<div class="input-group input-group-sm mb-3">
												<input type="text" required class="form-control text-center" id="valor_2" name="pregunta_2">
											</div>
										</td>
										<td>
											<textarea class="form-control" name="observacion_2"></textarea>
										</td>
									</tr>
									<tr class="text-dark">
										<td>El proveedor externo atiende de forma oportuna las solicitudes, quejas y/o reclamos</td>
										<td>
											<div class="input-group input-group-sm mb-3">
												<input type="text" required class="form-control text-center" id="valor_3" name="pregunta_3">
											</div>
										</td>
										<td>
											<textarea class="form-control" name="observacion_3"></textarea>
										</td>
									</tr>
									<tr class="text-dark">
										<td>El proveedor se responsabiliza por las garantias de los productos y/o servicios adquiridos por la institución</td>
										<td>
											<div class="input-group input-group-sm mb-3">
												<input type="text" required class="form-control text-center" id="valor_4" name="pregunta_4">
											</div>
										</td>
										<td>
											<textarea class="form-control" name="observacion_4"></textarea>
										</td>
									</tr>
									<tr class="text-dark">
										<td>El proveedor brinda plazos y facilidades de pago</td>
										<td>
											<div class="input-group input-group-sm mb-3">
												<input type="text" required class="form-control text-center" id="valor_5" name="pregunta_5">
											</div>
										</td>
										<td>
											<textarea class="form-control" name="observacion_5"></textarea>
										</td>
									</tr>
									<tr class="text-dark">
										<td colspan="2">Calificacion obtenida:</td>
										<td id="valor_total"></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="card-footer border-0 bg-transparent">
							<div class="row p-2">
								<div class="form-group col-lg-12 text-right">
									<button type="submit" class="btn btn-primary btn-sm">
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
		$instancia->registrarEvaluacionAnualControl();
	}
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/proveedor/funcionesProveedor.js"></script>