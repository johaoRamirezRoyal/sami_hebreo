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
	header('Location:../../login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlBiblioteca::singleton_biblioteca();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$permisos = $instancia_permiso->permisosUsuarioControl(52, $perfil_log);

if (!$permisos) {
	include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['usuario'])) {

	$id_usuario      = base64_decode($_GET['usuario']);
	$datos_usuarios  = $instancia_usuario->mostrarUsuariosDatosControl($id_usuario);
	$datos_prestamos = $instancia->prestamosUsuarioControl($id_usuario);

	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-primary">
							<a href="<?=BASE_URL?>biblioteca/usuarios/index" class="text-decoration-none">
								<i class="fa fa-arrow-left text-primary"></i>
							</a>
							&nbsp;
							Historial de prestamos usuario - <span class="text-uppercase"><?='(' . $datos_usuarios['documento'] . ') ' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido']?></span>
						</h4>
					</div>
					<div class="card-body">
						<div class="row p-2">
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Documento</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['documento']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Nombre completo</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Telefono</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['telefono']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Correo</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['correo']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Nivel</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['nom_nivel']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Curso</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['nom_curso']?>" disabled>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Perfil</label>
								<input type="text" class="form-control" value="<?=$datos_usuarios['nom_perfil']?>" disabled>
							</div>
							<div class="col-lg-6 form-group">
								<div class="" style="margin-top:5.5%;">
									<a href="<?=BASE_URL?>imprimir/biblioteca/paz_salvo?usuario=<?=base64_encode($id_usuario)?>" target="_blank" class="btn btn-primary btn-sm" >
										<i class="fas fa-file-pdf"></i>
										&nbsp;
										Paz y salvo
									</a>
									<a href="<?=BASE_URL?>imprimir/biblioteca/listado_prestamo?usuario=<?=base64_encode($id_usuario)?>" class="btn btn-info btn-sm" target="_blank">
										<i class="fas fa-history"></i>
										&nbsp;
										Historial de prestamos
									</a>
									<a href="<?=BASE_URL?>imprimir/biblioteca/paquete/listado_prestamo?usuario=<?=base64_encode($id_usuario)?>" class="btn btn-secondary btn-sm" target="_blank">
										<i class="fas fa-history"></i>
										&nbsp;
										Historial de prestamos Paquetes
									</a>
								</div>
							</div>
						</div>
						<div class="table-responsive mt-4">
							<table class="table table-hover border table-sm" width="100%" cellspacing="0">
								<thead>
									<tr class="text-center text-uppercase">
										<th colspan="10">Historial de prestamos</th>
									</tr>
									<tr class="text-center font-weight-bold">
										<th scope="col">No. Prestamo</th>
										<th scope="col">Usuario</th>
										<th scope="col">Libro</th>
										<th scope="col">#Ejemplar</th>
										<th scope="col">Categoria</th>
										<th scope="col">Subcategoria</th>
										<th scope="col">Fecha Prestamo</th>
										<th scope="col">Fecha Devolucion</th>
										<th scope="col">Observacion</th>
									</tr>
								</thead>
								<tbody class="buscar">
									<?php
									foreach ($datos_prestamos as $prestamo) {
										$id_prestamo      = $prestamo['id_prestamo'];
										$nom_libro        = $prestamo['titulo'];
										$codigo_ejem      = $prestamo['codigo'];
										$nom_categoria    = $prestamo['nom_categoria'];
										$nom_subcategoria = $prestamo['nom_subcategoria'];
										$fecha_prestamo   = $prestamo['fecha_prestamo'];
										$fecha_devolucion = $prestamo['fecha_devolucion'];
										$observacion      = $prestamo['observacion'];
										$nom_user         = $prestamo['nom_user'];
										$devuelto         = $prestamo['id_devuelto'];
										$fecha_devuelto   = $prestamo['fecha_devuelto'];

										if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {
											$span_fecha = '<span class="badge badge-warning">Por vencer</span>';
										}

										if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {
											$span_fecha = '<span class="badge badge-success">A tiempo</span>';
										}

										if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {
											$span_fecha = '<span class="badge badge-danger">Retrasado</span>';
										}

										if ($fecha_devuelto > $fecha_devolucion) {
											$span_fecha = '<span class="badge badge-danger">Devuelto con retraso</span>';
										}

										if ($fecha_devuelto < $fecha_devolucion && $fecha_devuelto != '') {
											$span_fecha = '<span class="badge badge-success">Devuelto antes de tiempo</span>';
										}

										if ($fecha_devuelto == $fecha_devolucion) {
											$span_fecha = '<span class="badge badge-warning">Devuelto justo a tiempo</span>';
										}

										?>
										<tr class="text-center">
											<td><?=$id_prestamo?></td>
											<td><?=$nom_user?></td>
											<td><?=$nom_libro?></td>
											<td><?=$codigo_ejem?></td>
											<td><?=$nom_categoria?></td>
											<td><?=$nom_subcategoria?></td>
											<td><?=$fecha_prestamo?></td>
											<td><?=$fecha_devolucion?></td>
											<td><?=$observacion?></td>
											<td><?=$span_fecha?></td>
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
}
?>