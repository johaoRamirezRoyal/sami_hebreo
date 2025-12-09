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
						<a href="<?=BASE_URL?>inicio" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Proceso de compra
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<?php
						$permisos = $instancia_permiso->permisosUsuarioControl(61, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>proveedor/index">
								<div class="card border-left-warning shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Proveedores</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-truck-loading fa-2x text-warning"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>solicitud/index">
								<div class="card border-left-info shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Realizar Solicitud</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-money-check fa-2x text-info"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$permisos = $instancia_permiso->permisosUsuarioControl(60, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>solicitud/listado">
								<div class="card border-left-primary shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Listado Solicitud General</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-clipboard-list fa-2x text-primary"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$permisos = $instancia_permiso->permisosUsuarioControl(71, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>solicitud/listado_user">
								<div class="card border-left-orange shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Mis Solicitudes</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-clipboard-list fa-2x text-orange"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$permisos = $instancia_permiso->permisosUsuarioControl(59, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>cotizacion/index">
								<div class="card border-left-success shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Cotizaciones / Orden Compra</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						$permisos = $instancia_permiso->permisosUsuarioControl(83, $perfil_log);
						if ($permisos) {
							?>
							<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>solicitud/listado_updated">
								<div class="card border-left-danger shadow-sm h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												<div class="h5 mb-0 font-weight-bold text-gray-800">Solicitudes Aprobadas por Coordinacion</div>
											</div>
											<div class="col-auto">
												<i class="fas fa-clipboard-list fa-2x text-danger"></i>
											</div>
										</div>
									</div>
								</div>
							</a>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>