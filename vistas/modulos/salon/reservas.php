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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$instancia = ControlSalon::singleton_salon();

$datos_salon = $instancia->mostrarSalonesControl();

if (isset($_POST['salon'])) {

    $buscar_salon = $_POST['salon'];
    $buscar_fecha = $_POST['fecha'];
    $buscar_user  = $_POST['buscar'];

    $datos = array('salon' => $buscar_salon, 'fecha' => $buscar_fecha, 'user' => $buscar_user, 'id_user' => $id_log);

    $datos_reserva = $instancia->buscarDatosDetalleUsuarioReservaControl($datos);
    $datos_salones = $instancia->mostrarSalonesIdUsuarioReservaControl($datos);

} else {

    $buscar_salon = '';
    $buscar_fecha = date('Y-m-d');
    $buscar_user  = '';

    $datos_reserva = $instancia->mostrarDatosDetalleUsuarioReservaControl($id_log);
    $datos_salones = $instancia->mostrarSalonesUsuarioReservaControl($id_log);
}

$ver_botones = (empty($datos_salones)) ? 'd-none' : '';

$permisos = $instancia_permiso->permisosUsuarioControl(84, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
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
                        Mis Reservas
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
                    <form method="POST">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <div class="row <?=$ver_botones?>">
                            <div class="col-lg-12 form-group mt-2">
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fa fa-trash"></i>
                                    &nbsp;
                                    Eliminar Reserva
                                </button>
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
                                            <th scope="col" colspan="7"><?=$nom_salon?></th>
                                        </tr>
                                        <tr class="text-center font-weight-bold">
                                            <th></th>
                                            <th scope="col">Usuario</th>
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

                                            if ($id_log == $reserva['id_user'] && $activo == 1) {
                                                ?>
                                                <tr class="text-center">
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" name="check_reserva[]" type="checkbox" value="<?=$id_reserva?>" id="<?=$id_reserva?>" />
                                                        </div>
                                                    </td>
                                                    <td class="text-uppercase"><?=$usuario?></td>
                                                    <td class="text-uppercase"><?=$portatil?></td>
                                                    <td class="text-uppercase"><?=$sonido?></td>
                                                    <td><?=$fecha?></td>
                                                    <td><?=$hora?></td>
                                                    <td><?=$detalle?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row <?=$ver_botones?>">
                            <div class="col-lg-12 form-group mt-2">
                                <button class="btn btn-danger btn-sm" type="submit">
                                    <i class="fa fa-trash"></i>
                                    &nbsp;
                                    Eliminar Reserva
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if(isset($_POST['check_reserva'])){
    $instancia->eliminarReservasControl();
}
?>