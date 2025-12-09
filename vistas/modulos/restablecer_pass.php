<?php
require_once CONTROL_PATH . 'ControlSession.php';  
$ingreso = ingresoClass::singleton_ingreso();
include_once VISTA_PATH . 'cabeza.php';
?>
<div class="container">
	<!-- Outer Row -->
	<div class="row justify-content-center">
		<div class="col-lg-12 mt-15">
			<div class="card o-hidden w-responsive border-0 shadow-sm m-auto">
				<div class="card-body p-0">
					<!-- Nested Row within Card Body -->
					<div class="row">
						<div class="col-lg-10 m-auto">
							<div class="p-5">
								<div class="text-center">
									<h1 class="h4 text-success-900 mb-4">
										<i class="fas fa-seedling"></i>
										Ecogreen
									</h1>
								</div>
								<form class="user" method="POST">
									<div class="form-group">
										<input type="email" required class="form-control form-control-user" id="mail" aria-describedby="emailHelp" placeholder="Correo electronico" name="mail">
									</div>
									<input type="submit" class="btn btn-success btn-user btn-block" value="Restablecer contrase&ntilde;a">
									<hr>
								</form>
								<div class="text-center">
									<a class="small" href="<?= BASE_URL?>login">Ya tengo cuenta</a>
								</div>
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
include_once VISTA_PATH . 'script_and_final.php';
if(isset($_POST['mail'])){
	$ingreso->restablecerPassword();
}
?>