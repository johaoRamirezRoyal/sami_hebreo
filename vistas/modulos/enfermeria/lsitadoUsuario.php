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
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlEnfermeria::singleton_enfermeria();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_nivel = $instancia_usuario->mostrarNivelesUsuarioControl();
$datos_curso = $instancia_usuario->mostrarCursosUsuarioControl();

if (isset($_POST['buscar'])) {
    $datos          = array('nivel' => $_POST['nivel'], 'curso' => $_POST['curso'], 'buscar' => $_POST['buscar']);
    $todos_usuarios = $instancia_usuario->buscarUsuariosNivelControl($datos);
} else {
    $todos_usuarios = $instancia_usuario->mostrarUsuariosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(57, $perfil_log);
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
                        Usuarios
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_usuario">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar Usuario
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select class="form-control" name="nivel">
                                    <option value="" selected>Seleccione un nivel...</option>
                                    <?php
                                    foreach ($datos_nivel as $nivel) {
                                        $id_nivel  = $nivel['id'];
                                        $nom_nivel = $nivel['nombre'];
                                        ?>
                                        <option value="<?=$id_nivel?>"><?=$nom_nivel?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select class="form-control" name="curso">
                                    <option value="" selected>Seleccione un curso...</option>
                                    <?php
                                    foreach ($datos_curso as $curso) {
                                        $id_curso  = $curso['id'];
                                        $nom_curso = $curso['nombre'];
                                        ?>
                                        <option value="<?=$id_curso?>"><?=$nom_curso?></option>
                                    <?php }?>
                                </select>
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
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Perfil</th>
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

                                    $ver_activo   = 'd-none';
                                    $ver_inactivo = 'd-none';

                                    if ($estado == 'activo') {
                                        $ver_activo   = 'd-none';
                                        $ver_inactivo = '';
                                    } else {
                                        $ver_activo   = '';
                                        $ver_inactivo = 'd-none';
                                    }

                                    ?>
                                    <tr class="text-center">
                                        <td><?=$documento?></td>
                                        <td class="text-uppercase"><?=$nombre?></td>
                                        <td class="text-lowercase"><?=$correo?></td>
                                        <td><?=$telefono?></td>
                                        <td><?=$nom_nivel?></td>
                                        <td><?=$nom_curso?></td>
                                        <td><?=$user?></td>
                                        <td class="text-uppercase"><?=$perfil?></td>
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