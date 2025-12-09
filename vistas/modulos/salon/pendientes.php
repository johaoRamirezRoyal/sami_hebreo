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
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$instancia = ControlSalon::singleton_salon();

$datos_salon = $instancia->mostrarSalonesControl();

$ver_botones = (isset($_POST['salon'])) ? '' : 'd-none';

if (isset($_POST['salon'])) {

    $buscar_salon = $_POST['salon'];
    $buscar_fecha = $_POST['fecha'];
    $buscar_user  = $_POST['buscar'];

    $datos = array('salon' => $buscar_salon, 'fecha' => $buscar_fecha, 'user' => $buscar_user);

    $datos_reserva = $instancia->buscarDatosDetalleReservaControl($datos);
} else {
    $datos_reserva = $instancia->mostrarDatosDetalleReservaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(43, $perfil_log);

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
                        Reservas pendientes
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <input type="hidden" name="salon" value="9">
                            </div>
                            <div class="col-lg-4 form-group">
                                <input type="date" class="form-control filtro_change" name="fecha">
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="submit">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row <?=$ver_botones?>">
                        <div class="col-lg-12 form-group mt-2 text-right">
                            <a href="<?=BASE_URL?>imprimir/programacion?salon=<?=base64_encode($buscar_salon)?>&fecha=<?=base64_encode($buscar_fecha)?>&user=<?=base64_encode($buscar_user)?>" class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fa fa-print"></i>
                                &nbsp;
                                Imprimir
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No. Reserva</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Salon</th>
                                    <th scope="col">Portatil</th>
                                    <th scope="col">Sonido</th>
                                    <th scope="col">Fecha reserva</th>
                                    <th scope="col">Hora reserva</th>
                                    <th scope="col">Detalle reserva</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_reserva as $reserva) {
                                    $id_reserva = $reserva['id_reserva'];
                                    $nom_salon  = $reserva['salon'];
                                    $fecha      = $reserva['fecha_reserva'];
                                    $portatil   = $reserva['portatil'];
                                    $sonido     = $reserva['sonido'];
                                    $hora       = $reserva['hora'];
                                    $usuario    = $reserva['usuario'];
                                    $detalle    = $reserva['detalle_reserva'];
                                    $activo     = $reserva['activo'];
                                    $confirmado = $reserva['confirmado'];
                                    $id_salon   = $reserva['id_salon'];

                                    $confirmado = ($reserva['confirmado'] == 0) ? '<span class="badge badge-danger">Rechazada</span>' : '<span class="badge badge-warning">Pendiente</span>';
                                    $confirmado = ($reserva['confirmado'] == 2) ? '<span class="badge badge-success">Aprobada</span>' : $confirmado;
                                    /*------------------*/
                                    $ver_botones = ($reserva['confirmado'] == 0) ? 'd-none' : '';
                                    $ver_botones = ($reserva['confirmado'] == 1) ? '' : $ver_botones;
                                    $ver_botones = ($reserva['confirmado'] == 2) ? 'd-none' : $ver_botones;

                                    if ($activo == 1 && $id_salon == 9) {
                                        ?>
                                        <tr class="text-center">
                                            <td><?=$id_reserva?></td>
                                            <td><?=$usuario?></td>
                                            <td><?=$nom_salon?></td>
                                            <td><?=$portatil?></td>
                                            <td><?=$sonido?></td>
                                            <td><?=$fecha?></td>
                                            <td><?=$hora?></td>
                                            <td><?=$detalle?></td>
                                            <td class="span_<?=$id_reserva?>"><?=$confirmado?></td>
                                            <td>
                                                <div class="btn-group <?=$ver_botones?> botones_<?=$id_reserva?>">
                                                    <button class="btn btn-success btn-sm aprobar" id="<?=$id_reserva?>" data-tooltip="tooltip" title="Aprobar" data-placement="bottom" data-trigger="hover">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm rechazar" id="<?=$id_reserva?>" data-tooltip="tooltip" title="Rechazar" data-placement="bottom" data-trigger="hover">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php }}?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
    ?>
    <script src="<?=PUBLIC_PATH?>js/salon/funcionesSalon.js"></script>