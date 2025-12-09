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
						<a href="<?=BASE_URL?>biblioteca/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Reportes
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/reportes/prestados">
							<div class="card border-left-brown shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">Libros Prestados</div>
										</div>
										<div class="col-auto">
											<i class="fas fa-book fa-2x text-brown"></i>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<!-- <div class="row">
						<div class="col-lg-3 form-group">
							<select class="form-control" name="estado">
								<option value="" selected>Seleccione un estado...</option>
							</select>
						</div>
						<div class="col-lg-3 form-group">
							<select class="form-control" name="curso">
								<option value="" selected>Seleccione un curso...</option>
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
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>