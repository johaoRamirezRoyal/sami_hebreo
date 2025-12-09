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
	header('Location:login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia = ControlUsuarios::singleton_usuarios();
?>
<div class="container-fluid">
	<div class="row">
		<?php
		if ($_SESSION['rol'] == 6) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'padres.php';
			die();
		}
		if ($_SESSION['rol'] == 11) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'asistentes.php';
		}
		$permisos = $instancia_permiso->permisosUsuarioControl(1, $perfil_log);
		if ($permisos) {
			include_once VISTA_PATH . 'modulos' . DS . 'configuracion' . DS . 'index.php';
		}
		$permisos = $instancia_permiso->permisosUsuarioControl(53, $perfil_log);
		if ($permisos) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'cronograma.php';
		}
		$permisos = $instancia_permiso->permisosUsuarioControl(31, $perfil_log);
		if ($permisos) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'sistemas.php';
		}
		$permisos = $instancia_permiso->permisosUsuarioControl(32, $perfil_log);
		if ($permisos) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'estadisticas.php';
		}
		$permisos = $instancia_permiso->permisosUsuarioControl(64, $perfil_log);
		if ($permisos) {
			include_once VISTA_PATH . 'modulos' . DS . 'inicio' . DS . 'EstadisticasEnfermeria.php';
		}
		?>
	</div>
</div>

<?php
if ($perfil_log != 1) {
	?>
	<!-- firma -->
	<div class="modal fade" id="subir_firma" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-hebreo font-weight-bold" id="exampleModalLabel">Subir firma</h5>
				</div>
				<form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="id_log" id="id_log_inicio" value="<?=$id_log?>">
					<div class="modal-body border-0">
						<p class="text-center text-danger font-weight-bold">Favor escanear su firma y subir el documento.</p>
						<div class="form-group col-lg-12 mt-2">
							<label class="font-weight-bold">Firma <span class="text-danger">*</span></label>
							<div class="custom-file pmd-custom-file-filled">
								<input type="file" class="custom-file-input file_input" name="firma" required accept=".png, .jpg, .jpeg">
								<label class="custom-file-label file_label" for="customfilledFile"></label>
							</div>
						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
							<i class="fa fa-times"></i>
							&nbsp;
							Cerrar
						</button>
						<button type="submit" class="btn btn-hebreo btn-sm">
							<i class="fa fa-upload"></i>
							&nbsp;
							Subir
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php }?>

<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
	$instancia->guardarFirmaUsuarioControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/graficas/funciones.js"></script>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/enfermeria/funcionesGraficas.js"></script>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/cronograma/funcionesCronograma.js"></script>
<script>
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$(".col-md-3").addClass('col-md-6');
	}
</script>