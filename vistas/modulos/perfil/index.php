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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia = ControlPerfil::singleton_perfil();

$datos       = $instancia->mostrarDatosPerfilControl($id_log);
$datos_nivel = $instancia->mostrarNivelesControl($id_super_empresa);
$ver_nivel   = ($nivel == 5) ? 'd-none' : '';
$perfil_info = 'border-left-success';

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 align-items-center justify-content-between d-flex">
					<h4 class="m-0 font-weight-bold text-hebreo">Perfil</h4>
					<a href="<?=BASE_URL?>perfil/hojaVida" class="btn btn-hebreo btn-sm">
						<i class="fa fa-address-card"></i>
						&nbsp;
						Hoja de vida
					</a>
				</div>
				<div class="card-body">
					<div class="row p-2">
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/informacion_personal">
							<div class="card border-left-pink-white shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Información personal
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!--
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/informacionInstitucional">
							<div class="card <?=$perfil_info?> shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Información Institucional
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						-->
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/formacion">
							<div class="card border-left-semi-orange shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Formación
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/experienciaLaboral">
							<div class="card border-left-green-force shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Experiencia Laboral
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/otrosDocumentos">
							<div class="card border-left-red shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Otros Documentos
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/produccionIntelectual">
							<div class="card border-left-purple-dark shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Producción intelectual
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- <a class="col-md-3 mb-4 text-decoration-none" href="">
							<div class="card <?=$perfil_info?> shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Evaluación de desempeño
											</div>
										</div>
									</div>
								</div>
							</div>
						</a> -->
						<a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>perfil/puntualidad">
							<div class="card border-left-blue-ocean shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Asistencia y puntualidad
											</div>
										</div>
									</div>
								</div>
							</div>
						</a> 
						<!-- <a class="col-md-3 mb-4 text-decoration-none" href="">
							<div class="card <?=$perfil_info?> shadow-sm h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="h5 mb-0 font-weight-bold text-gray-800">
												Reconocimientos e infracciones
											</div>
										</div>
									</div>
								</div>
							</div>
						</a> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>