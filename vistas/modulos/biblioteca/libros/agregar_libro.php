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

$datos_categoria = $instancia->mostrarCategoriasBibliotecaControl();

$permisos = $instancia_permiso->permisosUsuarioControl(48, $perfil_log);
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
					<h4 class="m-0 font-weight-bold text-primary">
						<a href="<?=BASE_URL?>biblioteca/libros/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Agregar libro
					</h4>
				</div>
				<div class="card-body">
					<form method="POST" enctype="multipart/form-data">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<div class="row p-2">
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Titulo <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="titulo" required>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Autor</label>
								<input type="text" class="form-control" name="autor">
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Editorial</label>
								<input type="text" class="form-control" name="editorial">
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Edicion o a&ntilde;o publicacion</label>
								<input type="text" class="form-control" name="edicion">
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Categoria <span class="text-danger">*</span></label>
								<select class="form-control" name="categoria" id="categoria" required>
									<option value="" selected>Seleccione una categoria...</option>
									<?php
									foreach ($datos_categoria as $categoria) {
										$id_categoria  = $categoria['id'];
										$nom_categoria = $categoria['nombre'];
										?>
										<option value="<?=$id_categoria?>"><?=$nom_categoria?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Sub-Categoria</label>
								<select class="form-control" name="subcategoria" id="subcategoria">
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Cantidad de Ejemplares <span class="text-danger">*</span></label>
								<input type="text" class="form-control numeros" name="ejemplares" required>
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Foto de portada</label>
								<input type="file" class="form-control file" accept="image/*" id="file" name="portada">
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Observacion</label>
								<textarea name="observacion" class="form-control" rows="5"></textarea>
							</div>
							<div class="col-lg-12 form-group text-right mt-2">
								<button class="btn btn-primary btn-sm">
									<i class="fa fa-save"></i>
									&nbsp;
									Guardar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['titulo'])) {
	$instancia->registrarLibroControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesBiblioteca.js"></script>