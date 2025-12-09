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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia = ControlUsuarios::singleton_usuarios();

$datos_nivel = $instancia->mostrarNivelesUsuarioControl();
$datos_curso = $instancia->mostrarCursosUsuarioControl();

$todos_usuarios = $instancia->mostrarEstudiantesPROMControl();

$permisos = $instancia_permiso->permisosUsuarioControl(62, $perfil_log);
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
                        Prom Night
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="input-group">
                                <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="button">
                                        <i class="fa fa-search"></i>
                                        &nbsp;
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Cupo Disponible</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($todos_usuarios as $usuario) {
                                    $id_user    = $usuario['id_user'];
                                    $nombre     = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                    $documento  = $usuario['documento'];
                                    $correo     = $usuario['correo'];
                                    $telefono   = $usuario['telefono'];
                                    $asignatura = $usuario['asignatura'];
                                    $user       = $usuario['user'];
                                    $estado     = $usuario['estado'];
                                    $id_perfil  = $usuario['perfil'];
                                    $perfil     = $usuario['nom_perfil'];
                                    $pass_old   = $usuario['pass'];

                                    $id_nivel  = $usuario['id_nivel'];
                                    $id_curso  = $usuario['id_curso'];
                                    $nom_curso = $usuario['nom_curso'];
                                    $nom_nivel = $usuario['nom_nivel'];

                                    $foto_carnet = $usuario['foto_carnet'];

                                    $cupos = $instancia->cuposEstudiantesControl($id_user);

                                    $ver_usuario = ($estado == 'activo') ? '' : 'd-none';
                                    ?>
                                    <tr class="text-center <?=$ver_usuario?>">
                                        <td><?=$documento?></td>
                                        <td class="text-uppercase"><?=$nombre?></td>
                                        <td><?=$nom_nivel?></td>
                                        <td><?=$nom_curso?></td>
                                        <td><?=$cupos['cupo_disponible']?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-secondary btn-sm" href="<?=BASE_URL?>imprimir/prom/codigo?id=<?=base64_encode($id_user)?>" target="_blank" data-tooltip="tooltip" title="Generar Codigo" data-placement="bottom">
                                                    <i class="fas fa-qrcode"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }?>
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