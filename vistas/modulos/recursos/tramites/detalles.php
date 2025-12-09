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

$permisos = $instancia_permiso->permisosUsuarioControl(78, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['id'])) {

    $id_tramite = base64_decode($_GET['id']);
    $url        = base64_decode($_GET['enlace']);

    $enlace = ($url == 0) ? 'listado' : 'misTramites';

    $datos_tramite        = $instancia->mostrarDetallesTramiteControl($id_tramite);
    $datos_documentos     = $instancia->mostrarDocumentosTramiteControl($id_tramite);
    $datos_grupo_familiar = $instancia->mostrarTramiteFamiliarControl($id_tramite);

    $grupo_familiar = ($datos_tramite['eps_grupo'] == 1) ? 'No tengo grupo familiar asociado a mi EPS' : 'Si tengo grupo familiar en mi EPS';

    if ($datos_tramite['estado'] == 0) {
        $bg  = 'bg-warning';
        $txt = 'Pendiente';
    }

    if ($datos_tramite['estado'] == 1) {
        $bg  = 'bg-success';
        $txt = 'Finalizado';
    }

    if ($datos_tramite['estado'] == 2) {
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
                        <a href="<?=BASE_URL?>recursos/tramites/<?=$enlace?>" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Detalles del tramite No. <?=$id_tramite?>
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_tramite" value="<?=$id_tramite?>">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <input type="hidden" name="motivo" value="">
                        <input type="hidden" name="estado" value="1">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_tramite['documento']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_tramite['nom_user']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_tramite['telefono']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Tipo Tramite <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" disabled value="<?=$datos_tramite['nom_tipo']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Estado del Tramite <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-white <?=$bg?>" disabled value="<?=$txt?>">
                            </div>
                            <div class="col-lg-12 form-group mt-2 text-center">
                                <h5 class="text-primary font-weight-bold text-uppercase">Detalle de la solicitud</h5>
                                <hr>
                            </div>
                            <?php
                            if ($datos_tramite['tipo_tramite'] == 1) {
                                ?>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">¿Cuál es el motivo por el cual solicita el certificado laboral? <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['motivo']?>">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Desea que su certificado laboral, mencione <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['mencion_certificado']?>">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Otra</label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['otra']?>">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Nombre de la persona o entidad que desea aparezca en el certificado <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['entidad_certificado']?>">
                                </div>
                                <?php
                                if ($datos_tramite['modo_entrega'] == 'Correo electronico') {
                                    ?>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" disabled value="<?=$datos_tramite['correo']?>">
                                    </div>
                                <?php }?>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Modo de entrega del Certificado Laboral <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['modo_entrega']?>">
                                </div>
                                <?php
                                if ($datos_tramite['estado'] == 0) {
                                    ?>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Subir certificado <span class="text-danger">*</span></label>
                                        <div class="custom-file pmd-custom-file-filled">
                                            <input type="file" class="custom-file-input file_input" id="certificado_laboral" name="certificado_laboral" required accept=".png, .jpg, .jpeg, .pdf">
                                            <label class="custom-file-label file_label_certificado_laboral" for="customfilledFile"></label>
                                        </div>
                                    </div>
                                    <?php
                                }

                                if ($datos_tramite['estado'] == 1) {
                                    ?>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Certificado Laboral</label>
                                        <br>
                                        <a href="<?=PUBLIC_PATH?>upload/<?=$datos_documentos[0]['archivo']?>" class="btn btn-info btn-sm" target="_blank">
                                            <i class="fa fa-eye"></i>
                                            &nbsp;
                                            Ver certificado
                                        </a>
                                    </div>
                                    <?php
                                }

                            }
                            if ($datos_tramite['tipo_tramite'] == 2) {
                                ?>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">A&ntilde;o Grabable <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['anio_grabable']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Otra</label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['otra']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Modo de entrega del Certificado Laboral <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['modo_entrega']?>">
                                </div>
                                <?php
                            }
                            if ($datos_tramite['tipo_tramite'] == 3) {
                                ?>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">En cual EPS se encuentra actualmente <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['nom_eps_actual']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Especifique a cual EPS desea trasladarse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$datos_tramite['nom_eps_traslado']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Grupo familiar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled value="<?=$grupo_familiar?>">
                                </div>
                                <div class="col-lg-12 form-group" style="margin-bottom: -0.3%;">
                                    <label class="font-weight-bold">Describa grupo familiar vinculado a su EPS actual <span class="text-danger">*</span></label>
                                </div>
                                <?php
                                foreach ($datos_grupo_familiar as $familiar) {
                                    $nom_familiar = $familiar['nombre'];
                                    ?>
                                    <div class="col-lg-4 form-group">
                                        <input type="text" class="form-control" disabled value="<?=$nom_familiar?>">
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-lg-12 form-group mt-2">
                                    <h5 class="text-primary font-weight-bold text-uppercase text-center">Documentos requeridos</h5>
                                    <hr>
                                    <?php
                                    if ($datos_tramite['eps_grupo'] == 2) {
                                        $cont = 1;
                                        foreach ($datos_documentos as $documento) {
                                            ?>
                                            <div class="btn-group">
                                                <a href="<?=PUBLIC_PATH?>upload/<?=$documento['archivo']?>" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                    &nbsp;
                                                    Documento No. <?=$cont?>
                                                </a>
                                            </div>
                                            <?php
                                            $cont++;
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            if ($datos_tramite['tipo_tramite'] == 4) {
                                ?>
                                <div class="col-lg-12 form-group" style="margin-bottom: -0.3%;">
                                    <label class="font-weight-bold">Beneficiario <span class="text-danger">*</span></label>
                                </div>
                                <?php
                                foreach ($datos_grupo_familiar as $familiar) {
                                    $nom_familiar = $familiar['nombre'];
                                    ?>
                                    <div class="col-lg-4 form-group">
                                        <input type="text" class="form-control" disabled value="<?=$nom_familiar?>">
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-lg-12 form-group mt-2">
                                    <h5 class="text-primary font-weight-bold text-uppercase text-center">Documentos requeridos</h5>
                                    <hr>
                                    <?php
                                    $cont = 1;
                                    foreach ($datos_documentos as $documento) {
                                        ?>
                                        <div class="btn-group">
                                            <a href="<?=PUBLIC_PATH?>upload/<?=$documento['archivo']?>" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                                &nbsp;
                                                Documento No. <?=$cont?>
                                            </a>
                                        </div>
                                        <?php
                                        $cont++;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            if ($datos_tramite['tipo_tramite'] == 5) {
                                ?>
                                <div class="col-lg-12 form-group">
                                    <p class="text-center">Esta solcitud no tiene detalles de informaci&oacute;n</p>
                                </div>
                                <div class="col-lg-12 form-group mt-2">
                                    <h5 class="text-primary font-weight-bold text-uppercase text-center">Documentos requeridos</h5>
                                    <hr>
                                    <?php
                                    $cont = 1;
                                    foreach ($datos_documentos as $documento) {
                                        ?>
                                        <div class="btn-group">
                                            <a href="<?=PUBLIC_PATH?>upload/<?=$documento['archivo']?>" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                                &nbsp;
                                                Documento No. <?=$cont?>
                                            </a>
                                        </div>
                                        <?php
                                        $cont++;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            if ($datos_tramite['estado'] == 2) {
                                ?>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Motivo de rechazo</label>
                                    <textarea class="form-control" rows="5" disabled><?=$datos_tramite['motivo_rechazo']?></textarea>
                                </div>
                                <?php
                            }
                            $permisos = $instancia_permiso->permisosUsuarioControl(79, $perfil_log);
                            if ($datos_tramite['estado'] == 0 && $permisos) {
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
                                        Finalizar Tramite
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
            <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Motivo de rechazo del tramite No. <?=$id_tramite?></h5>
        </div>
        <div class="modal-body">
            <form method="POST">
                <input type="hidden" name="id_tramite" value="<?=$id_tramite?>">
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

if (isset($_POST['id_tramite'])) {
    $instancia->estadoTramiteControl();
}