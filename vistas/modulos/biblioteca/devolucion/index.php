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

$instancia = ControlBiblioteca::singleton_biblioteca();

$datos_devolucion = $instancia->utlimasDevolucionesGeneralControl();

$permisos = $instancia_permiso->permisosUsuarioControl(50, $perfil_log);
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
						<a href="<?=BASE_URL?>biblioteca/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Devoluciones
					</h4>
					<div class="btn-group">
						<a class="btn btn-hebreo btn-sm" href="<?=BASE_URL?>biblioteca/prestamos/index">
							<i class="fas fa-hourglass-end"></i>
							&nbsp;
							Prestamos
						</a>
						<a class="btn btn-success btn-sm" href="<?=BASE_URL?>biblioteca/reportes/index">
							<i class="fas fa-file-excel"></i>
							&nbsp;
							Reportes
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<input type="hidden" id="id_log" value="<?=$id_log?>">
						<div class="col-lg-8"></div>
						<div class="form-group col-lg-4">
							<div class="input-group">
								<input type="text" class="form-control numeros" placeholder="Codigo" aria-describedby="basic-addon2" name="buscar" id="buscar">
								<div class="input-group-append">
									<button class="btn btn-hebreo btn-sm buscar" type="button">
										<i class="fa fa-search"></i>
										&nbsp;
										Buscar
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive mt-2 tabla_devolucion">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th colspan="10">Ultimas 30 Devoluciones</th>
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
									<th scope="col">Fecha Devuelto</th>
									<th scope="col">Observacion</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_devolucion as $devolucion) {
									$id_prestamo      = $devolucion['id_prestamo'];
									$nom_libro        = $devolucion['titulo'];
									$codigo_ejem      = $devolucion['codigo'];
									$nom_categoria    = $devolucion['nom_categoria'];
									$nom_subcategoria = $devolucion['nom_subcategoria'];
									$fecha_prestamo   = $devolucion['fecha_prestamo'];
									$fecha_devolucion = $devolucion['fecha_devolucion'];
									$fecha_devuelto   = date('Y-m-d', strtotime($devolucion['fecha_devuelto']));
									$observacion      = $devolucion['observacion'];
									$nom_user         = $devolucion['nom_user'];

									if($fecha_devuelto > $fecha_devolucion){
										$span_fecha = '<span class="badge badge-danger">Devuelto con retraso</span>';
									}

									if($fecha_devuelto < $fecha_devolucion){
										$span_fecha = '<span class="badge badge-success">Devuelto antes de tiempo</span>';
									}

									if($fecha_devuelto == $fecha_devolucion){
										$span_fecha = '<span class="badge badge-warning">Devuelto justo a tiempo</span>';
									}

									?>
									<tr class="text-center">
										<td><?=$id_prestamo?></td>
										<td class="text-uppercase"><?=$nom_user?></td>
										<td><?=$nom_libro?></td>
										<td><?=$codigo_ejem?></td>
										<td><?=$nom_categoria?></td>
										<td><?=$nom_subcategoria?></td>
										<td><?=$fecha_prestamo?></td>
										<td><?=$fecha_devolucion?></td>
										<td><?=$fecha_devuelto?></td>
										<td><?=$observacion?></td>
										<td><?=$span_fecha?></td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
					<div class="informacion_libro"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once VISTA_PATH . 'script_and_final.php';
?>
<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesDevolucion.js"></script>