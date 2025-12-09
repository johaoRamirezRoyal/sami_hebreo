<?php
require_once CONTROL_PATH . 'ControlSession.php';

$objss = new Session;
$objss->iniciar();
if (!empty($_SESSION['rol'])) {
	header('Location:inicio');
	exit();
}

$ingreso = ingresoClass::singleton_ingreso();
$ingreso->ingresaruser();
$desenc = base64_decode(@$_GET['er']);
if ($err = isset($desenc) ? $desenc : null);
include_once VISTA_PATH . 'cabeza.php';
?>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-xl-9 col-lg-12 col-md-9 mt-6">
			<div class="card o-hidden border-0 shadow-lg my-5">
				<div class="card-body p-0 bg-semi-transparent">
					<div class="row">
						<div class="col-lg-6 d-none d-lg-block">
							<img src="<?=PUBLIC_PATH?>img/engranajes.gif" alt="" class="img-fluid ml-4 mt-5">
						</div>
						<div class="col-lg-6">
							<div class="p-5 mt-10">
								<div class="text-center">
									<h1 class="h2 mb-4 font-weight-bold">S.A.M.I</h1>
								</div>
								<form class="user" method="POST">
									<div class="form-group">
										<input type="text" class="form-control form-control-user user" name="user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Usuario" autocomplete="off">
									</div>
									<div class="form-group">
										<input type="password" class="form-control form-control-user" name="pass" id="exampleInputPassword" placeholder="Contrase&ntilde;a" autocomplete="off">
									</div>

							
									<button href="index.html" class="btn btn-primary btn-user btn-block">
										<i class="fas fa-sign-in-alt"></i>
										&nbsp;
										Ingresar
									</button>
									<br>
									<a href="<?=BASE_URL?>ayuda" class="text-decoration-none"><h5 class="text-center text-primary font-weight-bold">¿Necesitas ayuda?</h5></a>
								</form>
							</div>
							<?php
							if ($err == 1) {
								echo '<p class="text-danger text-center">Usuario o Contrase&ntilde;a Incorrecta</p>';
							} else if ($err == 2) {
								echo '<p class="text-danger text-center">Debes iniciar sesion para acceder</p>';
							} else if ($err == 3) {
								echo '<p class="text-danger text-center">Usuario o Contrase&ntilde;a Incorrecta</p>';
							} else if ($err == 4) {
								echo '<p class="text-danger text-center">No esta permitido iniciar sesion</p>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<!-- Incluir la librería Qrlector -->

