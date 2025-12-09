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
require_once CONTROL_PATH . 'admisiones' . DS . 'ControlAdmisiones.php';
require_once CONTROL_PATH . 'padres' . DS . 'ControlPadres.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlAdmisiones::singleton_admisiones();
$instancia_padres = ControlPadres::singleton_padres();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_acudiente = $instancia->mostrarAcudientesControl($id_super_empresa);
$datos_nivel     = $instancia_perfil->mostrarNivelesControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl(22, $perfil_log);

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
                        <a href="<?=BASE_URL?>configuracion/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Admisiones
                    </h4>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                            <div class="dropdown-header">Acciones:</div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_acudiente">Agregar acudiente</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control filtro" placeholder="Buscar...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <button class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#agregar_acudiente">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar acudiente
                        </button>
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Estudiante</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-lowercase">
                                <?php
                                foreach ($datos_acudiente as $usuario) {
                                    $id_user    = $usuario['id_user'];
                                    $nombre     = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                    $documento  = $usuario['documento'];
                                    $correo     = $usuario['correo'];
                                    $telefono   = $usuario['telefono'];
                                    $user       = $usuario['user'];
                                    $estado     = $usuario['estado'];
                                    $estudiante = $usuario['estudiante'];
                                    $id_nivel   = $usuario['id_nivel'];

                                    $formato_lleno = $instancia_padres->consultarFormatoLlenoControl($id_user);

                                    if ($formato_lleno['id'] == '') {
                                        $ver           = 'd-none';
                                        $ver_confirmar = 'd-none';
                                        $span          = '<span class="badge badge-warning">Pendiente</span>';
                                    }

                                    if ($formato_lleno['id'] != '' && $formato_lleno['confirmado'] == 'no') {
                                        $ver           = 'd-none';
                                        $ver_confirmar = '';
                                        $span          = '';
                                    }

                                    if ($formato_lleno['id'] != '' && $formato_lleno['confirmado'] == 'si') {
                                        $ver           = '';
                                        $ver_confirmar = 'd-none';
                                        $span          = '';
                                    }

                                    if ($id_nivel == 2) {
                                        $nombre_nivel = 'Bloque infantil';
                                    }

                                    if ($id_nivel == 3) {
                                        $nombre_nivel = 'Bloque primaria';
                                    }

                                    if ($id_nivel == 4) {
                                        $nombre_nivel = 'Bloque secundaria';
                                    }

                                    $ver_nivel = ($nivel == $id_nivel) ? '' : 'd-none';
                                    $ver_admin = ($_SESSION['rol'] == 1 && $nivel == 1) ? $ver_nivel = '' : $ver_nivel;

                                    ?>
                                    <tr class="text-center <?=$ver_admin?>">
                                        <td><?=$documento?></td>
                                        <td><?=$nombre?></td>
                                        <td><?=$correo?></td>
                                        <td><?=$telefono?></td>
                                        <td><?=$user?></td>
                                        <td><?=$nombre_nivel?></td>
                                        <td><?=$estudiante?></td>
                                        <td>
                                            <?=$span?>
                                            <div class="btn-group">
                                                <a href="<?=BASE_URL?>imprimir/formato_clinico?id_acudiente=<?=base64_encode($id_user)?>" target="_blank" class="btn btn-primary btn-sm <?=$ver?>" data-tooltip="tooltip" title="Descargar formato" data-placement="bottom">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a href="<?=BASE_URL?>confirmar/padres?id_formato=<?=base64_encode($formato_lleno['id'])?>" class="btn btn-success btn-sm <?=$ver_confirmar?>" data-tooltip="tooltip" title="Confirmar formato" data-placement="bottom">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </a>
                                                <button class="btn btn-success btn-sm habilitar_form <?=$ver?>" data-tooltip="tooltip" title="Habilitar formato" data-placement="bottom" id="<?=$id_user?>">
                                                    <i class="fa fa-check"></i>
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
include_once VISTA_PATH . 'modulos' . DS . 'admisiones' . DS . 'agregar.php';

if (isset($_POST['documento'])) {
    $instancia->guardarAcudienteControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/admisiones/funcionesAdmisiones.js"></script>