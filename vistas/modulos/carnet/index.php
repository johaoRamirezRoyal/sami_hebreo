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

if (isset($_POST['buscar'])) {
    $datos          = array('nivel' => $_POST['nivel'], 'curso' => $_POST['curso'], 'buscar' => $_POST['buscar']);
    $todos_usuarios = $instancia->buscarUsuariosNivelControl($datos);
} else {
    $todos_usuarios = $instancia->mostrarUsuariosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(55, $perfil_log);

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
                        Carnet
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4">
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
                            <div class="col-lg-4">
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

                                    $foto_carnet = $usuario['foto_carnet'];

                                    $ver_usuario = ($id_perfil == 1 || $id_perfil == 17 || $id_perfil == 6 || $estado == 'inactivo') ? 'd-none' : '';

                                    $ver_carnet = ($foto_carnet != '') ? '' : 'd-none';

                                    ?>
                                    <tr class="text-center <?=$ver_usuario?>">
                                        <td><?=$documento?></td>
                                        <td class="text-uppercase"><?=$nombre?></td>
                                        <td class="text-lowercase"><?=$correo?></td>
                                        <td><?=$telefono?></td>
                                        <td><?=$nom_nivel?></td>
                                        <td><?=$nom_curso?></td>
                                        <td><?=$user?></td>
                                        <td class="text-uppercase"><?=$perfil?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-sm" type="button" data-tooltip="tooltip" title="Subir Foto" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#subir<?=$id_user?>">
                                                    <i class="fa fa-upload"></i>
                                                </button>
                                                <a href="<?=BASE_URL?>carnet/frente?usuario=<?=base64_encode($id_user)?>" class="btn btn-info btn-sm <?=$ver_carnet?>" data-tooltip="tooltip" title="Imprimir Frente" data-placement="bottom" data-trigger="hover" target="_blank">
                                                    <i class="fas fa-portrait"></i>
                                                </a>
                                                <a href="<?=BASE_URL?>carnet/reverso?usuario=<?=base64_encode($id_user)?>" class="btn btn-secondary btn-sm" data-tooltip="tooltip" title="Imprimir Reverso" target="_blank" data-placement="bottom" data-trigger="hover">
                                                    <i class="fas fa-barcode"></i>
                                                </a><!--
                                                <a href="#" class="btn btn-warning btn-sm <?=$ver_carnet?>" data-tooltip="tooltip" title="Imprimir Reposicion" data-placement="bottom" data-trigger="hover">
                                                    <i class="fas fa-id-card-alt"></i>
                                                </a> -->
                                            </div>
                                        </td>
                                    </tr>


                                    <div class="modal fade" id="subir<?=$id_user?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Subir Foto</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="id_log" value="<?=$id_log?>">
                                                <input type="hidden" name="id_user" value="<?=$id_user?>">
                                                <input type="hidden" name="foto_carnet" value="<?=$usuario['foto_carnet']?>">
                                                <div class="row p-2">
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="documento" value="<?=$usuario['documento']?>" required>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nombre" value="<?=$usuario['nombre']?>" required>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Apellido <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="apellido" value="<?=$usuario['apellido']?>" required>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Curso <span class="text-danger">*</span></label>
                                                        <select name="curso" class="form-control">
                                                            <option value="0" selected>Seleccione una opcion...</option>
                                                            <?php
                                                            foreach ($datos_curso as $curso) {
                                                                $id_curso_sel  = $curso['id'];
                                                                $nom_curso_sel = $curso['nombre'];

                                                                $select = ($id_curso == $id_curso_sel) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?=$id_curso_sel?>" <?=$select?>><?=$nom_curso_sel?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-12 form-group">
                                                        <label class="font-weight-bold">Foto a subir <span class="text-danger">*</span></label>
                                                        <div class="custom-file pmd-custom-file-filled">
                                                            <input type="file" class="custom-file-input file_input" id="<?=$id_user?>" name="foto" required accept=".png, .jpg, .jpeg">
                                                            <label class="custom-file-label file_label_<?=$id_user?>" for="customfilledFile"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 form-group mt-2 text-right">
                                                        <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                                                            <i class="fa fa-times"></i>
                                                            &nbsp;
                                                            Cancelar
                                                        </button>
                                                        <button class="btn btn-primary btn-sm" type="submit">
                                                            <i class="fa fa-upload"></i>
                                                            &nbsp;
                                                            Subir
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

if (isset($_POST['id_log'])) {
    $instancia->subirFotoCarnetControl();
}
?>