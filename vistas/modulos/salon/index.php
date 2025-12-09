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

$permisos = $instancia_permiso->permisosUsuarioControl(40, $perfil_log);

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
                        Salones
                    </h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-hebreo btn-sm" data-toggle="modal" data-target="#agregar_salon">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar Salon
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 form-inline">
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control filtro" placeholder="Buscar">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-right" id="basic-addon1">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No.</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Portatil</th>
                                    <th scope="col">Sonido</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_salon as $salon) {
                                    $id_salon = $salon['id'];
                                    $nombre   = $salon['nombre'];
                                    $portatil = $salon['portatil'];
                                    $sonido   = $salon['sonido'];
                                    ?>
                                    <tr class="text-center text-uppercase">
                                        <td><?=$id_salon?></td>
                                        <td><?=$nombre?></td>
                                        <td><?=$portatil?></td>
                                        <td><?=$sonido?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-hebreo btn-sm" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-trigger="hover">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Desactivar" data-placement="bottom" data-trigger="hover">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
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
include_once VISTA_PATH . 'modulos' . DS . 'salon' . DS . 'agregarSalon.php';

if (isset($_POST['id_log'])) {
    $instancia->guardarSalonControl();
}
?>