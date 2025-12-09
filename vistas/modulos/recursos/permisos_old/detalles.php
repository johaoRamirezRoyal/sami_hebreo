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
    header('Location:../../login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

$instancia = ControlRecursos::singleton_recursos();

$permisos = $instancia_permiso->permisosUsuarioControl(82, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['id'])) {

    $id_permiso = base64_decode($_GET['id']);
    $url        = base64_decode($_GET['enlace']);

    $enlace = ($url == 0) ? 'listado' : 'misSolicitudes';

    $datos_permiso = $instancia->mostrarPermisoIdControl($id_permiso);

    if ($datos_permiso['estado'] == 0) {
        $bg  = 'bg-warning';
        $txt = 'Pendiente';
    }

    if ($datos_permiso['estado'] == 1) {
        $bg  = 'bg-success';
        $txt = 'Aprobado';
    }

    if ($datos_permiso['estado'] == 2) {
        $bg  = 'bg-danger';
        $txt = 'Rechazado';
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>recursos/permisos/<?=$enlace?>" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Detalles del permiso No. <?=$id_permiso?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <input type="hidden" name="motivo" value="">
                        <input type="hidden" name="estado" value="1">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['documento']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_user']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['telefono']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Motivo del permiso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_motivo']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Tipo de permiso <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_tipo']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Estado de la solicitud <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-white <?=$bg?>" disabled value="<?=$txt?>">
                            </div>
                            <?php
                            if ($datos_permiso['tipo_permiso'] == 1) {
                                ?>
                                <div class="col-lg-12 form-group mt-2">
                                    <h5 class="font-weight-bold text-primary text-center text-uppercase">Permiso Parcial</h5>
                                    <hr>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" disabled value="<?=$datos_permiso['fecha_permiso']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Hora de salida <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" disabled value="<?=$datos_permiso['hora_salida']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Tiempo aproximado del permiso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_permiso['tiempo_permiso']?>">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['descripcion']?></textarea>
                                </div>
                                <?php
                            }
                            if ($datos_permiso['tipo_permiso'] == 2) {
                                ?>
                                <div class="col-lg-12 form-group mt-2">
                                    <h5 class="font-weight-bold text-primary text-center text-uppercase">Permiso Parcial</h5>
                                    <hr>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" disabled value="<?=$datos_permiso['fecha_permiso']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Fecha en que retorna a laborar <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" disabled value="<?=$datos_permiso['fecha_retorno']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Cantidad de días de permiso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_permiso['dias_permiso']?>">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['descripcion']?></textarea>
                                </div>
                                <?php
                            }
                            if ($datos_permiso['estado'] == 2) {
                                ?>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Motivo de rechazo</label>
                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['motivo_rechazo']?></textarea>
                                </div>
                                <?php
                            }
                            $permisos = $instancia_permiso->permisosUsuarioControl(75, $perfil_log);
                            if ($datos_permiso['estado'] == 0 && $permisos) {
                                ?>
                                <div class="col-lg-12 form-group mt-4 text-right">
                                    <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#rechazar">
                                        <i class="fa fa-times"></i>
                                        &nbsp;
                                        Rechazar
                                    </button>
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fa fa-check"></i>
                                        &nbsp;
                                        Aprobar
                                    </button>
                                </div>
                            <?php }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rechazar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Motivo de rechazo del permiso No. <?=$id_permiso?></h5>
        </div>
        <div class="modal-body">
            <form method="POST">
                <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">
                <input type="hidden" name="id_log" value="<?=$id_log?>">
                <input type="hidden" name="estado" value="2">
                <div class="row p-2">
                    <div class="col-lg-12 form-group">
                        <label class="font-weight-bold">Motivo de rechazo</label>
                        <textarea class="form-control" rows="5" name="motivo"></textarea>
                    </div>
                    <div class="col-lg-12 form-group mt-2 text-right">
                        <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                            &nbsp;
                            Cancelar
                        </button>
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="fas fa-paper-plane"></i>
                            &nbsp;
                            Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
    $instancia->estadoPermisoControl();
}