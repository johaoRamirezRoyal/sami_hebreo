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

$permisos = $instancia_permiso->permisosUsuarioControl(20, $perfil_log);

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
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Biblioteca
                    </h4>
                </div>
                <div class="card-body">
                   <div class="row">
                    <?php
                    $permisos = $instancia_permiso->permisosUsuarioControl(48, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/libros/index">
                            <div class="card border-left-brown shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Libros</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-brown"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(49, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/prestamos/index">
                            <div class="card border-left-green shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Prestamos</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-hourglass-end fa-2x text-green"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(50, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/devolucion/index">
                            <div class="card border-left-danger shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Devoluciones</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-undo-alt fa-2x text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(65, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/paquete/index">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Paquetes</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-box-open fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(66, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/paquete/prestamo">
                            <div class="card border-left-orange shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Prestamos Paquetes</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-archive fa-2x text-orange"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(67, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/paquete/devolucion">
                            <div class="card border-left-red shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Devolucion Paquetes</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-box fa-2x text-red"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(51, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/reportes/index">
                            <div class="card border-left-success shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Reportes</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-excel fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php }
                    $permisos = $instancia_permiso->permisosUsuarioControl(52, $perfil_log);
                    if ($permisos) {
                        ?>
                        <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/usuarios/index">
                            <div class="card border-left-blue shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Grupo usuarios</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-blue"></i>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<script>
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(".col-md-3").addClass('col-md-6');
    }
</script>