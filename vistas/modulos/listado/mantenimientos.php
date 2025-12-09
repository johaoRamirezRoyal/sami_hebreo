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
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

$instancia = ControlInventario::singleton_inventario();

$permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);

$datos_articulo = $instancia->mostrarDatosArticulosControl($id_super_empresa);

if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['inventario'])) {
    $id_inventario = base64_decode($_GET['id_inventario']);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="<?=BASE_URL?>listado/index" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Mantenimientos articulo ()
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
