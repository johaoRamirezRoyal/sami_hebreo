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
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';

$instancia = ControlProveedor::singleton_proveedor();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['proveedor'])) {

	$id_proveedor = base64_decode($_GET['proveedor']);

	$datos_proveedor = $instancia->mostrarDatosProveedorIdControl($id_proveedor);
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-hebreo">
							<a href="<?=BASE_URL?>proveedor/index" class="text-decoration-none">
								<i class="fa fa-arrow-left text-hebreo"></i>
							</a>
							&nbsp;
							Documentos proveedor (<?=$datos_proveedor['nombre'] . ' - ' . $datos_proveedor['num_identificacion']?>)
						</h4>
						<p class="m-0 font-weight-bold float-right text-danger">Campos obligatorios (<span class="text-danger">*</span>)</p>
					</div>
					<form method="POST" enctype="multipart/form-data">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<input type="hidden" name="id_proveedor" value="<?=$id_proveedor?>">
						<div class="card-body">
							<div class="row p-3">
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Rut <span class="text-danger">*</span></label>
									<div class="custom-file">
										<input type="file" class="file input-sm"  accept=".jpg,.png,.pdf,.jpeg" required name="rut">
									</div>
								</div>
								
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Cedula del representante legal <span class="text-danger">*</span></label>
									<div class="custom-file">
										<input type="file" class="file input-sm"  accept=".jpg,.png,.pdf,.jpeg" required name="cedula_representante">
									</div>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Certificacion bancaria <span class="text-danger">*</span></label>
									<div class="custom-file">
										<input type="file" class="file input-sm"  accept=".jpg,.png,.pdf,.jpeg" required name="cert_bancaria">
									</div>
								</div>
							</div>
							<div class="form-group col-lg-12">
								<button class="btn btn-hebreo btn-sm float-right mb-4" type="submit">
									<i class="fa fa-save"></i>
									&nbsp;
									Finalizar registro
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
	include_once VISTA_PATH . 'script_and_final.php';

	if (isset($_POST['id_log'])) {
		$instancia->documentosProveedorControl();
	}
}