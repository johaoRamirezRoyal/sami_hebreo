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

$anio_proximo = date("Y", strtotime($anio_escolar['anio_inicio'] . "+ 6 months")) . '-' . date("Y", strtotime($anio_escolar['anio_fin'] . "+ 1 year"));

?>
<div class="col-lg-12">
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-hebreo">Modulos</h4>
            <?php
            if ($anio_escolar['anio_fin'] == date('Y') && date('m') == 06) {
                ?>
                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmacion_fin" data-popover="popover"  title="Finalizar a&ntilde;o" data-content="Al hacer clic empezara el proceso de confirmacion de inventarios por area del a&ntilde;o escolar <?=$anio_proximo?>." data-trigger="hover">
                    <i class="fas fa-exclamation-triangle"></i>
                    &nbsp;
                    Finalizar a&ntilde;o escolar
                </button>
            <?php }?>
        </div>
        <div class="card-body">
            <div class="row">
                <?php
                $permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>usuarios/index">
                        <div class="card border-left-success shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Usuarios</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }

                $permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>consultausuarios/index">
                        <div class="card border-left-success shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Consulta Usuarios</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }

                $permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>inventario/index">
                        <div class="card border-left-dark shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Inventario</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-barcode fa-2x text-dark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(4, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>areas/index">
                        <div class="card border-left-info shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Areas</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marker-alt fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

        <!-- PRUEBA MIS RESERVAS -->
                  <?php }

                    $permisos = $instancia_permiso->permisosUsuarioControl(41, $perfil_log);

                    if ($permisos) {

                        ?>

                            <a class="col-md-3 mb-4 text-decoration-none" href="<?= BASE_URL ?>salon/reservas">

                            <div class="card border-left-yellow shadow-sm h-100 py-2">

                                <div class="card-body">

                                    <div class="row no-gutters align-items-center">

                                        <div class="col mr-2">

                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Mis Reservas</div>

                                        </div>

                                        <div class="col-auto">

                                            <i class="fas fa-calendar-check fa-2x text-yellow"></i>

                                        </div>

                                    </div>

                                </div>

                            </div>

                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(9, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>areas/reasignar">
                        <div class="card border-left-orange shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Re-asignar area</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-sync-alt fa-2x text-orange"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(10, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>mantenimientos/index">
                        <div class="card border-left-danger shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Mantenimientos</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tools fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(11, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>categorias/index">
                        <div class="card border-left-warning shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Categorias</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-list-ul fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>listado/index">
                        <div class="card border-left-purple shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Listado</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-list-ol fa-2x text-purple"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(14, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>historial/index">
                        <div class="card border-left-secondary shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Historial inventario</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-history fa-2x text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(17, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>inventario/trabajoCasa">
                        <div class="card border-left-blue shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Trabajo en casa</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-briefcase fa-2x text-blue"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(18, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>material/index">
                        <div class="card border-left-pink shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Material didáctico</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cubes fa-2x text-pink"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(19, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>pmb/index">
                        <div class="card border-left-brown shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Libros prestados</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book fa-2x text-brown"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(22, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>admisiones/index">
                        <div class="card border-left-pink-white shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Admisiones</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-notes-medical fa-2x text-pink-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(23, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>recursos/index">
                        <div class="card border-left-green-dark shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Gestión humana</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-id-card-alt fa-2x text-green-dark"></i>
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
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Contabilidad</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-money-check-alt fa-2x text-yellow"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(29, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>reportes/visto">
                        <div class="card border-left-green shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Visto bueno</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-green"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(30, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>confirmar/index">
                        <div class="card border-left-semi-green shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Confirmaciones</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-double fa-2x text-semi-green"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(33, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>zonas/index">
                        <div class="card border-left-semi-orange shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Zonas Comunes</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-marked-alt fa-2x text-semi-orange"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(34, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>zonas/reportes">
                        <div class="card border-left-green-force shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Reportes Zonas</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tools fa-2x text-green-force"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(35, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>historial/historialZona">
                        <div class="card border-left-black shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Historial Zonas</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-history fa-2x text-black"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(38, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>asistencia/index">
                        <div class="card border-left-danger shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Asistencia</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(39, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>extra/index">
                        <div class="card border-left-gray shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Extracurriculares</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-stopwatch fa-2x text-gray"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(40, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>salon/index">
                        <div class="card border-left-info shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Salones</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-map-pin fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(41, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>salon/apartar">
                        <div class="card border-left-success shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Reservar Salon</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(43, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>salon/pendientes">
                        <div class="card border-left-warning shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Reservas Pendientes</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="far fa-calendar-alt fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(42, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>salon/diario">
                        <div class="card border-left-green shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Programacion diaria</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-green"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(44, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>renovacion/index">
                        <div class="card border-left-purple shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Renovaciones</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-sync-alt fa-2x text-purple"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>compras/index">
                        <div class="card border-left-orange shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Proceso de compra</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-shopping-cart fa-2x text-orange"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(20, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>biblioteca/index">
                        <div class="card border-left-brown shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Biblioteca</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book-open fa-2x text-brown"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(54, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>enfermeria/index">
                        <div class="card border-left-pink-white shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Enfermeria</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-nurse fa-2x text-pink-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(55, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>carnet/index">
                        <div class="card border-left-orange shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Carnets</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-id-card-alt fa-2x text-orange"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }
                $permisos = $instancia_permiso->permisosUsuarioControl(62, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>prom/index">
                        <div class="card border-left-blue-dark shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">PROM Night</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-moon fa-2x text-blue-dark"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }

                $permisos = $instancia_permiso->permisosUsuarioControl(93, $perfil_log);
                $perfil_log_cod = base64_encode($perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>eventos/index">
                        <div class="card border-left-blue-dark shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Eventos</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-flag-checkered fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }

                $permisos = $instancia_permiso->permisosUsuarioControl(72, $perfil_log);
                $perfil_log_cod = base64_encode($perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>citaciones/index">
                        <div class="card border-left-warning shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Citaciones</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa fa-address-book fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }

                $permisos = $instancia_permiso->permisosUsuarioControl(28, $perfil_log);
                if ($permisos) {
                    ?>
                    <a class="col-md-3 mb-4 text-decoration-none" href="<?=BASE_URL?>permisos/index">
                        <div class="card border-left-secondary shadow-sm h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">Permisos</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-lock fa-2x text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php }?>
            </div>
        </div>
    </div>
</div>