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
require_once CONTROL_PATH . 'citaciones' . DS . 'ControlCitaciones.php';

$instancia_usuarios = ControlUsuarios::singleton_usuarios();
$instancia_citaciones = ControlCitaciones::singleton_citaciones();

$datos_nivel  = $instancia_usuarios->mostrarNivelesUsuarioControl();
$datos_curso = $instancia_usuarios->mostrarCursosUsuarioControl();
$esRector = $instancia_citaciones->esRectoraControl($id_log);



if(isset($_POST['buscar'])){
    $datos_busqueda = array('buscar' => $_POST['buscar'], 'curso' => $_POST['curso'], 'nivel' => $_POST['nivel']);
    $datos_estudiantes = $instancia_citaciones->buscarEstudiantesNivelControl($datos_busqueda);
}else{
    $datos_estudiantes = $instancia_citaciones->mostrarEstudiantesUsuariosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(72, $perfil_log); //Se deben cambiar los permisos... ¡NO OLVIDAR!

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">

            <?php if($esRector['perfil'] == 24 || $esRector['perfil'] == 1){?>
            <div class="btn-group mb-2">
                <a href="<?=BASE_URL?>citaciones/ver" class="btn btn-primary btn-sm">
                    <i class="fa fa-eye"></i>
                    &nbsp;
                    Ver citaciones
                </a>
            </div>
            <?php }?>
            <div class="col-lg-12">
                <h2 class="text-end text-danger"><strong>Nota:</strong> Este módulo aún no se encuentra en funcionamiento completamente.</h2>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Citaciones
                    </h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <form method="POST">
                        <div class="row p-2 justify-content-between">
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
                                        <button  class="btn btn-hebreo btn-sm" type="submit">
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
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Citar</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach($datos_estudiantes as $estudiante){
                                    $id_usuario = $estudiante['id_user'];
                                    $documento_usuario = $estudiante['documento'];
                                    $nombre_usuario = $estudiante['nombre'];
                                    $apellido_usuario = $estudiante['apellido'];
                                    $nivel_usuario = $estudiante['nom_nivel'];
                                    $curso_usuario = $estudiante['nom_curso'];
                                    $correo_usuario = $estudiante['correo'];
                                    $telefono_usuario = $estudiante['telefono'];
                                    $nivel_id = $estudiante['id_nivel'];
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$documento_usuario?></td>
                                        <td><?=$nombre_usuario?></td>
                                        <td><?=$apellido_usuario?></td>
                                        <td><?=$nivel_usuario?></td>
                                        <td><?=$curso_usuario?></td>
                                        <td><?=$correo_usuario?></td>
                                        <td><?=$telefono_usuario?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-hebreo btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Citar" data-toggle="modal" data-target="#citar<?=$id_usuario?>">
                                                    <i class="fa fa-address-book"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="citar<?=$id_usuario?>" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="citarModalLabel">
                                        <div class="modal-dialog modal-lg p-2" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header p-3">
                                                    <h4 class="modal-title text-hebreo font-weight-bold">Citar estudiante: <?=$nombre_usuario?> <?=$apellido_usuario?></h4>
                                                </div>
                                                <form method="POST" class="form_enviar_citar<?=$id_usuario?>" name="form_citar">
                                                    <input type="hidden" name="id_log" value="<?=$id_log?>">
                                                    <input type="hidden" name="nivel_id" value="<?=$nivel_id?>">
                                                    <input type="hidden" value="<?=$id_usuario?>" name="id_estudiante">
                                                    <div class="modal-header p-3">
                                                        <h4 class="modal-title text-hebreo font-weight-bold">Generar Citación</h4>
                                                    </div>
                                                    <div class="row p-3">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="documento" class="font-weight-bold">Documento</label>
                                                                <input type="text" class="form-control" name="documento" value="<?=$documento_usuario?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="nombre" class="font-weight-bold">Nombre</label>
                                                                <input type="text" class="form-control" name="nombre" value="<?=$nombre_usuario?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="apellido" class="font-weight-bold">Apellido</label>
                                                                <input type="text" class="form-control" name="apellido" value="<?=$apellido_usuario?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Nivel</label>
                                                                <select name="nivel" class="form-control" disabled>
                                                                    <option value="<?=$id_nivel?>" selected><?=$nivel_usuario?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Curso</label>
                                                                <select name="curso" class="form-control" disabled>
                                                                    <option value="<?=$id_curso?>" selected><?=$curso_usuario?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Correo</label>
                                                                <input type="email" class="form-control" name="correo" value="<?=$correo_usuario?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Telefono</label>
                                                                <input type="text" class="form-control" name="telefono" value="<?=$telefono_usuario?>" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="cita" class="font-weight-bold">Motivo</label>
                                                                <textarea class="form-control" name="motivo" rows="5"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="fecha_citacion" class="font-weight-bold">Fecha de citación</label>
                                                                <input type="date" class="form-control" name="fecha_citacion" value="<?= date('Y-m-d', strtotime('+1 week')) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="fecha_citacion" class="font-weight-bold">Hora de citación</label>
                                                                <input type="time" class="form-control" name="hora_citacion" value="<?= date('H:i') ?>">
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-success btn-md" type="submit">
                                                                    <i class="fa fa-check"></i>
                                                                    &nbsp;
                                                                    Enviar
                                                                </button>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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

if(isset($_POST['id_log'])){
    $instancia_citaciones->generarCitacionControl();
}
?>
