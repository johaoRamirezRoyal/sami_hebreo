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
require_once 'app/lib/phpqrcode/qrlib.php';

$instancia = ControlPerfil::singleton_perfil();

$datos       = $instancia->mostrarDatosPerfilControl($id_log);
$datos_nivel = $instancia->mostrarNivelesControl($id_super_empresa);
$ver_nivel   = ($nivel == 5) ? 'd-none' : '';
$numero_documento = $datos['documento'];

ob_start();
QRcode::png($numero_documento, null, QR_ECLEVEL_L, 4);
$imageData = ob_get_contents();
ob_end_clean();

// Convertir la imagen a base64 para mostrarla en el HTML
$imageData = base64_encode($imageData);
$imageSrc = 'data:image/png;base64,' . $imageData;

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3">
					<h4 class="m-0 font-weight-bold text-hebreo">Perfil</h4>
				</div>
				<div class="card-body">
					<form method="POST" id="form_enviar" enctype="multipart/form-data">
						<input type="hidden" value="<?=$datos['id_user']?>" name="id_user">
						<input type="hidden" value="<?=$datos['pass']?>" name="pass_old">
						<div class="row">
							<div class="col-lg-4 form-group">
								<div class="circular--portrait">
									<img src="<?=PUBLIC_PATH . $foto_perfil?>">
								</div>
							</div>
							<div class="col-lg-8 form-group">
								<div class="row p-2">
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Numero de Documento <span class="text-danger">*</span></label>
										<input type="text" class="form-control numeros" name="documento" maxlength="50" minlength="1" value="<?=$datos['documento']?>" required>
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
										<input type="text" class="form-control letras" maxlength="50" minlength="1" value="<?=$datos['nombre']?>" name="nombre" required>
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Apellido</label>
										<input type="text" class="form-control letras" maxlength="50" minlength="1" value="<?=$datos['apellido']?>" name="apellido">
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
										<input type="email" class="form-control" maxlength="50" minlength="1" value="<?=$datos['correo']?>" name="correo" readonly>
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Telefono</label>
										<input type="text" class="form-control numeros" maxlength="50" minlength="1" value="<?=$datos['telefono']?>" name="telefono">
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
										<input type="text" class="form-control" maxlength="50" minlength="1" value="<?=$datos['user']?>" disabled>
									</div>
								</div>
                                                              <div class="col-lg-4 form-group">
    <label class="font-weight-bold">Código QR</label>
    <img src="<?= $imageSrc ?>" alt="Código QR" style="width: 300px; height: 300px;">
</div>
							</div>
           
							<div class="col-lg-6 form-group <?=$ver_nivel?>">
								<label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
								<select name="nivel" class="form-control" required>
									<option value="0" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_nivel as $nivel) {
										$id_nivel = $nivel['id'];
										$nombre   = $nivel['nombre'];
										$estado   = $nivel['activo'];

										$ver    = ($estado == 1) ? '' : 'd-none';
										$select = ($datos['id_nivel'] == $id_nivel) ? 'selected' : '';
										?>
										<option value="<?=$id_nivel?>" class="<?=$ver?>" <?=$select?>><?=$nombre?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-group col-lg-6">
								<label class="font-weight-bold">Foto de perfil (opcional)</label>
								<div class="custom-file pmd-custom-file-filled">
									<input type="file" class="custom-file-input file_input" name="archivo" required accept=".png, .jpg, .jpeg">
									<label class="custom-file-label file_label" for="customfilledFile"></label>
								</div>
							</div>
							<div class="col-lg-12 form-group mt-4">
								<h4 class="text-hebreo font-weight-bold text-center">Cambiar Contrase&ntilde;a</h4>
								<hr>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Nueva Contrase&ntilde;a</label>
								<input type="password" class="form-control" maxlength="16" minlength="8" name="password" id="password">
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Confirmar Nueva Contrase&ntilde;a</label>
								<input type="password" class="form-control" maxlength="16" minlength="8" name="conf_password" id="conf_password">
							</div>
						</div>
					</div>
       
					<div class="form-group col-lg-12 mt-2 text-right">
						<button type="submit" class="btn btn-hebreo btn-sm" id="enviar_perfil">
							<i class="fa fa-save"></i>
							&nbsp;
							Guardar Cambios
						</button>
						<input type="hidden" name="perfil" value="<?=$datos['perfil']?>">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
if (isset($_POST['nombre'])) {
	$instancia->editarPerfilControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/validaciones.js"></script>