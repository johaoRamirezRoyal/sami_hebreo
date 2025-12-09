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

$permisos = $instancia_permiso->permisosUsuarioControl(54, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Enfermeria
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row p-2">
                        <?php
                        $permisos = $instancia_permiso->permisosUsuarioControl(56, $perfil_log);
                        if ($permisos) {
                            ?>
                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>enfermeria/procedimiento">
                                <div class="card border-left-success shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Procedimientos</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-procedures fa-2x text-success"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php }
                        $permisos = $instancia_permiso->permisosUsuarioControl(57, $perfil_log);
                        if ($permisos) {
                            ?>
                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>enfermeria/listadoUsuario">
                                <div class="card border-left-orange shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Atencion Medica</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-notes-medical fa-2x text-orange"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php }
                        $permisos = $instancia_permiso->permisosUsuarioControl(58, $perfil_log);
                        if ($permisos) {
                            ?>
                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>enfermeria/listado">
                                <div class="card border-left-purple shadow-sm h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Listado de atencion</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-book-medical fa-2x text-purple"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>