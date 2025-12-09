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
$estados_citacion = $instancia_citaciones->mostrarEstadosCitacionControl();

if(isset($_POST['buscar'])){
    $datos_busqueda = array('buscar' => $_POST['buscar'], 'curso' => $_POST['curso'], 'nivel' => $_POST['nivel']);
    $datos_estudiantes = $instancia_citaciones->filtrarCitacionesControl($datos_busqueda); 
}else{
    $datos_estudiantes = $instancia_citaciones->mostrarTodasLasCitacionesControl(); //cambiar a mostrarTodasLasCitacionesControl, se debe filtrar por coordinacion (nivel)
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
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>citaciones/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Estados de Citaciones 
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
                                        <th>Estudiante citado</th>
                                        <th>Citación generada por</th>
                                        <th>Nivel</th>
                                        <th>Curso</th>
                                        <th>Motivo</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Aprobación</th>
                                    </tr>
                                    
                                </thead>
                                <tbody class="buscar">
                                    <?php
                                    foreach($datos_estudiantes as $cita){
                                        $id_cita = $cita['id'];
                                        $id_estudiante = $cita['estudiantes_id'];
                                        $id_profesor = $cita['profesor_id'];
                                        $correo_profesor = $cita['correo_profesor'];
                                        $nom_estudiante = $cita['nombre_estudiante'];
                                        $nom_docente = $cita['nombre_docente'];
                                        $nom_nivel = $cita['nom_nivel'];
                                        $motivo = $cita['motivo'];
                                        $fecha_formateada = date('Y-m-d', strtotime($cita['fecha_citacion']));
                                        $hora_formateada = date('h:i A', strtotime($cita['hora_citacion']));
                                        $fecha_citacion = $fecha_formateada . ' - ' . $hora_formateada;
                                        $estado_citacion = $cita['estado_citacion'];
                                        $nom_curso = $cita['nom_curso'];
                                        $estado_id = $cita['estado_id'];

                                        $disabled_boton = '';
                                        if($estado_id != 1){
                                            $disabled_boton = 'disabled';
                                        }

                                        $estado_badge = 'badge badge-success';
                                        if($estado_id == 3){
                                            $estado_badge = 'badge badge-danger';
                                        }else if($estado_id == 4){
                                            $estado_badge = 'badge badge-info';
                                        }else if($estado_id == 1){
                                            $estado_badge = 'badge badge-warning';
                                        }else if($estado_id == 5){
                                            $estado_badge = 'badge badge-secondary';
                                        }else if($estado_id == 6){
                                            $estado_badge = 'badge badge-dark';
                                        }

                                        ?>
                                        <tr class="text-center">
                                            <td><?=$nom_estudiante?></td>
                                            <td><?=$nom_docente?></td>
                                            <td><?=$nom_nivel?></td>
                                            <td><?=$nom_curso?></td>
                                            <td><?=$motivo?></td>
                                            <td><?=$fecha_citacion?></td>
                                            <td><span class="<?=$estado_badge?>"><?=$estado_citacion?></span></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-hebreo btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Aprobar" data-toggle="modal" data-target="#aprobar_<?=$id_cita?>" <?=$disabled_boton?>>
                                                        <i class="fa fa-book"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!--Aprobar Modal-->
                                        <div class="modal fade" id="aprobar_<?=$id_cita?>" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="exampleModalLabel">
                                            <div class="modal-dialog modal-lg p-2" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title text-hebreo font-weight-bold">Citación del estudiante: <?=$nom_estudiante?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" class="form_enviar_citar<?=$id_cita?>">
                                                            <input type="hidden" name="id_citacion" value="<?=$id_cita?>">
                                                            <input type="hidden" name="id_estudiante" value="<?=$id_estudiante?>">
                                                            <input type="hidden" name="id_profesor" value="<?=$id_profesor?>">
                                                            <input type="hidden" name="correo_profesor" value="<?=$correo_profesor?>">
                                                            <input type="hidden" name="nombre_estudiante" value="<?=$nom_estudiante?>">
                                                            <input type="hidden" name="curso" value="<?=$nom_curso?>">
                                                            <input type="hidden" name="motivo" value="<?=$motivo?>">
                                                            <input type="hidden" name="fecha_citacion" value="<?=$fecha_citacion?>">
                                                            <input type="hidden" name="nombre_docente" value="<?=$nom_docente?>">
                                                            <div class="row p-3">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="nombre_estudiante" class="font-weight-bold">Estudiante</label>
                                                                        <input type="text" class="form-control" name="nombre_estudiante" value="<?=$nom_estudiante?>" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="nombre_docente" class="font-weight-bold">Docente</label>
                                                                        <input type="text" class="form-control" name="nombre_docente" value="<?=$nom_docente?>" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="fecha_citacion" class="font-weight-bold">Fecha de citación</label>
                                                                        <input type="text" class="form-control" name="fecha_citacion" value="<?=$fecha_citacion?>" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="nombre_nivel" class="font-weight-bold">Nivel</label>
                                                                        <input type="text" class="form-control" name="nombre_nivel" value="<?=$nom_nivel?>" disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="curso" class="font-weight-bold">Curso</label>
                                                                        <input type="text" name="curso" class="form-control" value="<?=$nom_curso?>" disabled>
                                                                    </div>
                                                                </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="motivo" class="font-weight-bold">Motivo</label>
                                                                            <textarea class="form-control" name="motivo" rows="5" disabled><?=$motivo?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Correo del acudiente</label>
                                                                        <input type="email" class="form-control" name="correo_acudiente">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <br>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="font-weight-bold">Estado de citación<span class="text-danger">*</span></label>
                                                                        <select name="estado_citacion" class="form-control" required>
                                                                            <option value="">Seleccione una opcion...</option>
                                                                            <?php
                                                                            foreach ($estados_citacion as $estado) {
                                                                                $id_estado = $estado['id'];
                                                                                $nom_estado = $estado['nombre'];
                                                                                ?>
                                                                                <option value="<?=$id_estado?>"><?=$nom_estado?></option>
                                                                                <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="">  
                                                                        <h5 class="text-danger">Observaciones</h5>
                                                                        <p>Al seleccionar el estado de citación: <span class="badge badge-success">Aprobada</span>, se enviará al correo electronico del acudiente. De no ingresar el correo electronico, se enviará al correo de la persona que realizó la citación.</p>
                                                                    </div>
                                                                </div>
                                                                    <div class="form-group d-flex justify-content-end">
                                                                        <button class="btn btn-success btn-md" type="submit">
                                                                            <i class="fa fa-check"></i>
                                                                            &nbsp;
                                                                            Enviar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
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
</div>

<?php
include_once VISTA_PATH . 'script_and_final.php';

if(isset($_POST['id_citacion'])){
    $instancia_citaciones->cambiarEstadoCitacionControl();
}
?>
