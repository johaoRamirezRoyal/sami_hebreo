<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
	$er = '2';
	$error = base64_encode($er);
	$salir = new Session;
	$salir->iniciar();
	$salir->outsession();
	header('Location:login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
?>
<div class="container-fluid">
	<div class="text-center">
		<div class="error mx-auto" data-text="408">403</div>
		<p class="lead text-gray-800 mb-5">Access Denied!</p>
		<p class="text-gray-500 mb-0">Acceso Denegado</p>
		<a href="<?= BASE_URL ?>inicio">&larr; Volver al inicio</a>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>