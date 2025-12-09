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

$datos_proveedor = $instancia->mostrarProveedoresControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if(isset($_GET['proveedor'])){
    $id_documento = base64_decode($_GET['documento']);
    $id_proveedor = base64_decode($_GET['proveedor']);
    $datos_proveedor = $instancia->mostrarDatosProveedorIdControl($id_proveedor);
    $datos_documento = $instancia->mostrarInformacionDocumentoControl($id_proveedor, $id_documento);
}else{
    var_dump("Problemas con el Documento seleccionado o con el Proveedor");
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>proveedor/hojaRegistro?proveedor=<?=base64_encode($id_proveedor)?>"  class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Editar <?=$datos_documento['tipo_documento_nombre']?> del proveedor: <?=$datos_proveedor['nombre']?>
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <input type="hidden" name="id_proveedor" value="<?=$id_proveedor?>">
                        <input type="hidden" name="id_documento" value="<?=$id_documento?>">
                        <input type="hidden" name="nombre_tipo_doc" value="<?=$datos_documento['tipo_documento_nombre']?>">
                        <input type="hidden" name="tipo_documento" value="<?=$datos_documento['id_tipo_documento']?>">
                        <div class="row p-3">
                            <label class="font-weight-bold">Nombre del documento: <?=$datos_documento['tipo_documento_nombre']?></label>
                            <input type="text" class="form-control" value="<?=$datos_documento['nombre']?>" placeholder="Nombre del documento" disabled readonly>
                        </div>
                        <div class="row p-3">
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Subir <?=$datos_documento['tipo_documento_nombre']?> <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input 
                                        type="file" 
                                        class="custom-file-input" 
                                        id="customFile"
                                        accept=".jpg,.png,.pdf,.jpeg" 
                                        required 
                                        name="archivo"
                                    >
                                    <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-hebreo btn-sm float-right mb-4" name="cambiar_documento">
                                <i class="fa fa-save"></i>
                                &nbsp;
                                Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['cambiar_documento'])){
    $instancia->actualizarDocumentoHojaRegistroControl();
}

?>