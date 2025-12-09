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

$datos_prestamo = $instancia->mostrarUltimosPrestamosControl();

$permisos = $instancia_permiso->permisosUsuarioControl(66, $perfil_log);
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
                        <a href="<?=BASE_URL?>biblioteca/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Paquetes Prestamo
                    </h4>
                </div>
                <div class="card-body">
                    <input type="hidden" value="<?=$id_log?>" id="id_log">
                    <div class="row">
                        <div class="col-lg-8 form-group"></div>
                        <div class="form-group col-lg-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Codigo" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="hover" data-placement="top" title="Presione ENTER para buscar" id="buscar">
                                <div class="input-group-append">
                                    <button class="btn btn-hebreo btn-sm buscar" type="button">
                                        <i class="fa fa-search"></i>
                                        &nbsp;
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2 tabla_prestamos">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col" colspan="10">Ultimos 30 prestamos</th>
                                </tr>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No. Prestamo</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Paquete</th>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Fecha Prestamo</th>
                                    <th scope="col">Fecha Devolucion</th>
                                    <th scope="col">Observacion</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_prestamo as $prestamo) {
                                    $id_prestamo      = $prestamo['id'];
                                    $nom_paquete      = $prestamo['nom_paquete'];
                                    $nom_usuario      = $prestamo['nom_user'];
                                    $codigo           = $prestamo['codigo'];
                                    $fecha_prestamo   = $prestamo['fecha_prestamo'];
                                    $fecha_devolucion = $prestamo['fecha_devolucion'];
                                    $observacion      = $prestamo['observacion'];
                                    $devuelto         = $prestamo['id_devuelto'];
                                    $fecha_devuelto   = $prestamo['fecha_devuelto'];

                                    if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {
                                        $span_fecha = '<span class="badge badge-warning">Por vencer</span>';
                                    }

                                    if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {
                                        $span_fecha = '<span class="badge badge-success">A tiempo para devolucion</span>';
                                    }

                                    if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {
                                        $span_fecha = '<span class="badge badge-danger">Retrasado</span>';
                                    }

                                    if ($fecha_devuelto > $fecha_devolucion) {
                                        $span_fecha = '<span class="badge badge-danger">Devuelto con retraso</span>';
                                    }

                                    if ($fecha_devuelto < $fecha_devolucion && !empty($fecha_devuelto)) {
                                        $span_fecha = '<span class="badge badge-success">Devuelto antes de tiempo</span>';
                                    }

                                    if ($fecha_devuelto == $fecha_devolucion) {
                                        $span_fecha = '<span class="badge badge-warning">Devuelto justo a tiempo</span>';
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_prestamo?></td>
                                        <td class="text-uppercase"><?=$nom_usuario?></td>
                                        <td><?=$nom_paquete?></td>
                                        <td><?=$codigo?></td>
                                        <td><?=$fecha_prestamo?></td>
                                        <td><?=$fecha_devolucion?></td>
                                        <td><?=$observacion?></td>
                                        <td><?=$span_fecha?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <div class="informacion_paquete"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_paquete'])) {
    $instancia->prestarPaqueteControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesPaquetePrestamo.js"></script>