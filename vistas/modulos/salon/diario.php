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
    $datos_salones = $instancia->mostrarSalonesIdReservaControl($datos);

} else {
    $datos_reserva = $instancia->mostrarDatosDetalleReservaControl();
    $datos_salones = $instancia->mostrarSalonesReservaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(41, $perfil_log);

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
                        Programacion diaria
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select name="salon" id="" class="form-control">
                                    <option value="" selected>Seleccionar una opcion...</option>
                                    <?php
                                    foreach ($datos_salon as $salon) {
                                        $id_salon  = $salon['id'];
                                        $salon_nom = $salon['nombre'];
                                        $activo    = $salon['activo'];

                                        $ver = ($activo == 0) ? 'd-none' : '';
                                        ?>
                                        <option value="<?=$id_salon?>" class="<?=$ver?>"><?=$salon_nom?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <input type="date" class="form-control filtro_change" name="fecha">
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
                                    <div class="input-group-append">
                                        <button class="btn btn-hebreo btn-sm" type="submit">
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
<!--                             <a href="#" class="btn btn-sm btn-success" type="submit">
                                <i class="fa fa-file-excel"></i>
                                &nbsp;
                                Descargar
                            </a> -->
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <?php
                        foreach ($datos_salones as $salon) {
                            $id_salon  = $salon['id'];
                            $nom_salon = $salon['nombre'];
                            ?>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold text-uppercase">
                                        <th scope="col" colspan="6"><?=$nom_salon?></th>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Complementos</th>
                                        <th scope="col">Fecha reserva</th>
                                        <th scope="col">Hora reserva</th>
                                        <th scope="col">Detalle reserva</th>
                                        <th scope="col">Nombre actividad</th>
                                        <th scope="col">Aforo</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar">
                                    <?php
                                    foreach ($datos_reserva as $reserva) {
                                        $id_reserva = $reserva['id_reserva'];
                                        $nom_salon  = $reserva['salon'];
                                        $fecha      = $reserva['fecha_reserva'];
                                        $complementos = $reserva['complementos'];
                                        $portatil   = $reserva['portatil'];
                                        $hora       = $reserva['hora'];
                                        $usuario    = $reserva['usuario'];
                                        $detalle    = $reserva['detalle_reserva'];
                                        $activo     = $reserva['activo'];
                                        $confirmado = $reserva['confirmado'];
                                        $nom_act = $reserva['nombre_actividad'];
                                        $aforo = $reserva['aforo'];

                                        if ($activo == 1 && $confirmado == 2 && $reserva['id_salon'] == $id_salon) {
                                            ?>
                                            <tr class="text-center">
                                                <td class="text-uppercase"><?=$usuario?></td>
                                                <td class="text-uppercase"><?=$complementos?></td>
                                                <td><?=$fecha?></td>
                                                <td><?=$hora?></td>
                                                <td><?=$detalle?></td>
                                                <td><?=$nom_act?></td>
                                                <td><?=$aforo?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
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