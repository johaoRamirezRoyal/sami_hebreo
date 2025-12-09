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

$datos_prestamos = $instancia->utlimosPrestamosGeneralControl();

$datos_nivel = $instancia_usuario->mostrarNivelesUsuarioControl();
$datos_curso = $instancia_usuario->mostrarCursosUsuarioControl();

if (isset($_POST['fecha_inicio'])) {
	$datos             = array('nivel' => $_POST['nivel'], 'curso' => $_POST['curso'], 'fecha_inicio' => $_POST['fecha_inicio'], 'fecha_fin' => $_POST['fecha_fin']);
	$datos_prestamos   = $instancia->reporteLibrosPrestadosControl($datos);
	$descargar_reporte = '';
} else {
	$datos_prestamos   = $instancia->utlimosPrestamosGeneralControl();
	$descargar_reporte = 'd-none';
}

$permisos = $instancia_permiso->permisosUsuarioControl(51, $perfil_log);
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
						<a href="<?=BASE_URL?>biblioteca/reportes/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Reporte - Libros Prestados
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-3 form-group">
								<select class="form-control" name="nivel">
									<option value="" selected>Seleccione un nivel...</option>
									<?php
									foreach ($datos_nivel as $nivel) {
										$id_nivel  = $nivel['id'];
										$nom_nivel = $nivel['nombre'];
										?>
										<option value="<?=$id_nivel?>"><?=$nom_nivel?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-3 form-group">
								<select class="form-control" name="curso">
									<option value="" selected>Seleccione un curso...</option>
									<?php
									foreach ($datos_curso as $curso) {
										$id_curso  = $curso['id'];
										$nom_curso = $curso['nombre'];
										?>
										<option value="<?=$id_curso?>"><?=$nom_curso?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-3 form-group">
								<input type="date" name="fecha_inicio" class="form-control" data-tooltip="tooltip" title="Fecha inicio" data-placement="top" data-trigger="hover">
							</div>
							<div class="form-group col-lg-3">
								<div class="input-group">
									<input type="date" class="form-control" name="fecha_fin" data-tooltip="tooltip" title="Fecha fin" data-placement="top" data-trigger="hover">
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
					<div class="col-lg-12 form-group mt-2 text-right <?=$descargar_reporte?>">
						<a href="<?=BASE_URL?>imprimir/biblioteca/reporteLibrosPrestados?fecha_inicio=<?=$_POST['fecha_inicio']?>&nivel=<?=$_POST['nivel']?>&curso=<?=$_POST['curso']?>&fecha_fin=<?=$_POST['fecha_fin']?>" target="_blank" class="btn btn-success btn-sm">
							<i class="fa fa-file-excel"></i>
							&nbsp;
							Descargar Reporte
						</a>
					</div>
					<div class="table-responsive mt-2 tabla_prestamos">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">No. Prestamo</th>
									<th scope="col">Usuario</th>
									<th scope="col">Nivel</th>
									<th scope="col">Curso</th>
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
									$nivel            = $prestamo['nom_nivel'];
									$curso            = $prestamo['nom_curso'];

									if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {
										$span_fecha = '<span class="badge badge-warning">Por vencer</span>';
									}

									if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {
										$span_fecha = '<span class="badge badge-success">A tiempo para devolucion</span>';
									}

									if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {
										$span_fecha = '<span class="badge badge-danger">Retrasado</span>';
									}

									if ($fecha_devuelto > $fecha_devolucion) {
										$span_fecha = '<span class="badge badge-danger">Devuelto con retraso</span>';
									}

									if ($fecha_devuelto < $fecha_devolucion && !empty($fecha_devuelto)) {
										$span_fecha = '<span class="badge badge-success">Devuelto antes de tiempo</span>';
									}

									if ($fecha_devuelto == $fecha_devolucion) {
										$span_fecha = '<span class="badge badge-warning">Devuelto justo a tiempo</span>';
									}

									?>
									<tr class="text-center">
										<td><?=$id_prestamo?></td>
										<td class="text-uppercase"><?=$nom_user?></td>
										<td><?=$nivel?></td>
										<td><?=$curso?></td>
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
?>