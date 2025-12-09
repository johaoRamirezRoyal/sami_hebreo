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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlUsuarios::singleton_usuarios();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_perfil = $instancia_perfil->mostrarPerfilesControl();
$datos_nivel  = $instancia->mostrarNivelesUsuarioControl();
$datos_curso  = $instancia->mostrarCursosUsuarioControl();

if (isset($_POST['buscar'])) {
    $datos          = array('nivel' => $_POST['nivel'], 'curso' => $_POST['curso'], 'buscar' => $_POST['buscar']);
    $todos_usuarios = $instancia->buscarUsuariosNivelControl($datos);
} else {
    $todos_usuarios = $instancia->mostrarUsuariosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);

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
                        Usuarios
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-hebreo btn-sm" type="button" data-toggle="modal" data-target="#agregar_usuario">
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

                                    if ($id_perfil > 1) {
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
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-hebreo btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Editar usuario" data-toggle="modal" data-target="#editar_usuario<?=$id_user?>">
                                                        <i class="fa fa-user-edit"></i>
                                                    </button>
                                                    <button class="btn btn-success btn-sm activar_user <?=$ver_activo?>" id="activar_<?=$id_user?>" data-id="<?=$id_user?>" data-tooltip="tooltip" data-placement="bottom" title="Activar usuario" data-trigger="hover">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm inactivar_user <?=$ver_inactivo?>" id="inactivar_<?=$id_user?>" data-id="<?=$id_user?>" data-tooltip="tooltip" data-placement="bottom" title="Inactivar usuario" data-trigger="hover">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!--Agregar usuario-->
                                        <div class="modal fade" id="editar_usuario<?=$id_user?>" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="exampleModalLabel">
                                            <div class="modal-dialog modal-lg p-2" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" class="form_enviar_editar<?=$id_user?>">
                                                        <input type="hidden" value="<?=$id_user?>" name="id_user">
                                                        <input type="hidden" value="<?=$pass_old?>" name="pass_old">
                                                        <div class="modal-header p-3">
                                                            <h4 class="modal-title text-hebreo font-weight-bold">Editar Usuario</h4>
                                                        </div>
                                                        <div class="modal-body border-0">
                                                            <div class="row  p-3">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control numeros" name="documento_edit" value="<?=$documento?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control letras"   name="nombre_edit" value="<?=$usuario['nombre']?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Apellido</label>
                                                                        <input type="text" class="form-control letras"   name="apellido_edit" value="<?=$usuario['apellido']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Telefono</label>
                                                                        <input type="text" class="form-control numeros"   name="telefono_edit" value="<?=$telefono?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
                                                                        <input type="email" class="form-control"  name="correo_edit" value="<?=$correo?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Asignatura</label>
                                                                        <input type="text" class="form-control"   name="asignatura_edit" value="<?=$asignatura?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"   value="<?=$user?>" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Contrase&ntilde;a</label>
                                                                        <input type="password" class="form-control pass_editar<?=$id_user?>"minlength="8" name="pass_editar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Confirmar Contrase&ntilde;a</label>
                                                                        <input type="password" class="form-control conf_pass_editar<?=$id_user?>" minlength="8" name="conf_pass_editar">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                                                                    <select name="nivel_edit" class="form-control" required>
                                                                        <option selected value="">Seleccione una opcion...</option>
                                                                        <?php
                                                                        foreach ($datos_nivel as $nivel) {
                                                                            $id_nivel_sel = $nivel['id'];
                                                                            $nom_nivel    = $nivel['nombre'];

                                                                            $sel = ($id_nivel == $id_nivel_sel) ? 'selected' : '';
                                                                            ?>
                                                                            <option value="<?=$id_nivel_sel?>" <?=$sel?>><?=$nom_nivel?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label class="font-weight-bold">Curso</label>
                                                                    <select name="curso_edit" class="form-control">
                                                                        <option selected value="0">Seleccione una opcion...</option>
                                                                        <?php
                                                                        foreach ($datos_curso as $curso) {
                                                                            $id_curso_sel = $curso['id'];
                                                                            $nom_curso    = $curso['nombre'];

                                                                            $sel = ($id_curso == $id_curso_sel) ? 'selected' : '';
                                                                            ?>
                                                                            <option value="<?=$id_curso_sel?>" <?=$sel?>><?=$nom_curso?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Perfil <span class="text-danger">*</span></label>
                                                                        <select class="form-control" name="perfil_edit" required>
                                                                            <option selected value="">Seleccione una opcion...</option>
                                                                            <?php
                                                                            foreach ($datos_perfil as $perfiles) {
                                                                                $id_perfil_sel = $perfiles['id_perfil'];
                                                                                $nom_perfil    = $perfiles['nombre'];

                                                                                $sel = ($id_perfil == $id_perfil_sel && $id_perfil_sel != 1) ? 'selected' : '';
                                                                                ?>
                                                                                <option value="<?=$id_perfil_sel?>" <?=$sel?>><?=$nom_perfil?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                <i class="fa fa-times"></i>
                                                                &nbsp;
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" class="btn btn-success btn-sm enviar_editar" data-id="<?=$id_user?>">
                                                                <i class="fa fa-edit"></i>
                                                                &nbsp;
                                                                Guardar cambios
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
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
include_once VISTA_PATH . 'modulos' . DS . 'usuarios' . DS . 'agregarUsuario.php';

if (isset($_POST['nombre'])) {
    $instancia->guardarUsuarioControl();
}

if (isset($_POST['nombre_edit'])) {
    $instancia->editarUsuarioControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/validaciones.js"></script>
<script src="<?=PUBLIC_PATH?>js/usuario/funcionesUsuario.js"></script></script>