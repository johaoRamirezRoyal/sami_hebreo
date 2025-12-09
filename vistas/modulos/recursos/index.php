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



$permisos = $instancia_permiso->permisosUsuarioControl(23, $perfil_log);



if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();

}

?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow-sm mb-4">

                <div class="card-header py-3">

                    <h4 class="m-0 font-weight-bold text-primary">

                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Gestión humana y Calidad

                    </h4>

                </div>

                <div class="card-body">

                    <div class="row">

                        <?php

                        $permisos = $instancia_permiso->permisosUsuarioControl(24, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/certificados">

                                <div class="card border-left-success shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Certificados</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-list-alt fa-2x text-success"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(25, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/solicitados">

                                <div class="card border-left-info shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Certificados solicitados</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-poll-h fa-2x text-info"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(26, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>contabilidad/index">

                                <div class="card border-left-yellow shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Volantes de pago</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-money-check-alt fa-2x text-yellow"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(27, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>contabilidad/historial?usuario=<?=base64_encode($id_log)?>">

                                <div class="card border-left-yellow shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Historial volantes pago</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-money-check fa-2x text-yellow"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(45, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/renovacion_recursos">

                                <div class="card border-left-purple shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Listado Maestro</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-th-list  fa-2x text-purple"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        ?>

                        <?php

                        $permisos = $instancia_permiso->permisosUsuarioControl(63, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/listadoAsistencia">

                                <div class="card border-left-danger shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Asistencia</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-list fa-2x text-danger"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(69, $perfil_log);

                        if ($permisos) {

                            ?>


                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(76, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/tramites/index">

                                <div class="card border-left-pink shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Tramites y Servicios</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-certificate fa-2x text-pink"></i>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </a>

                        <?php }

                        $permisos = $instancia_permiso->permisosUsuarioControl(80, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/permisos/index">

                                <div class="card border-left-warning shadow-sm h-100 py-2">

                                    <div class="card-body">

                                        <div class="row no-gutters align-items-center">

                                            <div class="col mr-2">

                                                <div class="h5 mb-0 font-weight-bold text-gray-800">Permisos/Licencias</div>

                                            </div>

                                            <div class="col-auto">

                                                <i class="fas fa-user-clock fa-2x text-warning"></i>

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

<?php

include_once VISTA_PATH . 'script_and_final.php';

?>

<script>

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

        $(".col-md-3").addClass('col-md-6');

    }

</script>