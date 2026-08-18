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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia = ControlPerfil::singleton_perfil();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

if(isset($_GET['id'])){
    $id_log = base64_decode($_GET['id']);
}

$formaciones_usuario = $instancia->mostrarFormacionesFormalesUsuarioControl($id_log);
$formaciones_informales = $instancia->mostrarFormacionesInformalesUsuarioControl($id_log);
$informacion_usuario = [];
if(isset($_GET['id'])){
    $informacion_usuario = $instancia->mostrarDatosPerfilControl($id_log);
}

$redir = (isset($_GET['id'])) ? 'hojaVida?id=' . $_GET['id'] . '&link=1' : 'hojaVida';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?= BASE_URL ?>perfil/<?= $redir ?>" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Formación <?php if(isset($_GET['id'])){ ?> (<?= $informacion_usuario['nombre'] ?>) <?php } ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <button class="btn btn-hebreo btn-sm" type="button" data-toggle="modal" data-target="#modal_formacion_formal">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Agregar Nueva Formación Formal
                            </button>
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-hebreo btn-sm" type="button" data-toggle="modal" data-target="#modal_formacion_informal">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Agregar Nueva Formación Informal
                            </button>
                        </div>
                        <!-- Modal para agregar formación formal -->
                        <div class="modal fade" id="modal_formacion_formal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form method="POST" class="form_enviar_formacion_formal" enctype="multipart/form-data">
                                        <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                        <input type="hidden" value="formal" name="tipo_formacion">
                                        <div class="modal-header p-3">
                                            <h4 class="modal-title text-hebreo font-weight-bold">Agregar Formación Formal</h4>
                                        </div>
                                        <div class="modal-body border-0">
                                            <div class="row p-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tipo formación: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="Formal" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold" for="programa_formacion">Programa: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="programa_formacion" required autofocus>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold" for="nombre_institucion">Nombre Institución: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nombre_institucion" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold" for="fecha_grado">Fecha grado <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="fecha_grado" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group mt-2">
                                                        <label class="font-weight-bold" for="certificado_formacion">Certificado: <span class="text-danger">*</span></label>
                                                        <div class="custom-file pmd-custom-file-filled">
                                                            <input type="file" class="custom-file-input file_input" id="certificado_formacion" name="certificado_formacion" required accept=".png, .jpg, .jpeg, .pdf">
                                                            <label class="custom-file-label file_label_certificado_formacion" for="customfilledFile"></label>
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
                                                <button type="submit" class="btn btn-success btn-sm" name="enviar_formacion_formal">
                                                    <i class="fa fa-check"></i>
                                                    &nbsp;
                                                    Guardar
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-2">
                        <hr>
                        <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Formaciones Formales</h5>
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Programa</th>
                                    <th scope="col">Nombre Institución</th>
                                    <th scope="col">Fecha grado</th>
                                    <th scope="col">Certificado</th>
                                    <th scope="col">Eliminar formación</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-center">
                                <?php
                                foreach ($formaciones_usuario as $formacion) {
                                    $id_formacion = $formacion['id'];
                                    $programa = $formacion['programa'];
                                    $institucion = $formacion['institucion'];
                                    $fecha_exp = $formacion['fecha_grado'];

                                    $certificado = $instancia->mostrarInformacionCertificadoFormacionControl($id_formacion);
                                ?>
                                    <tr>
                                        <td><?= $programa ?></td>
                                        <td><?= $institucion ?></td>
                                        <td><?= $fecha_exp ?></td>
                                        <td>
                                            <a href="<?= PUBLIC_PATH ?>upload/<?= $certificado['nombre_archivo'] ?>" download class="btn btn-success btn-sm">
                                                <i class="fa fa-download"></i>
                                                Descargar
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" value="<?= $id_formacion ?>" name="id">
                                                <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                                <input type="hidden" value="<?= $certificado['nombre_archivo'] ?>" name="name_doc">
                                                <button class="btn btn-danger btn-sm" type="submit" name="eliminar_formacion">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-2">
                        <hr>
                        <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Formaciones Informales</h5>
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Programa</th>
                                    <th scope="col">Nombre Institución</th>
                                    <th scope="col">Fecha de expedición del certificado</th>
                                    <th scope="col">Duración</th>
                                    <th scope="col">Certificado</th>
                                    <th scope="col">Eliminar formación</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-center">
                                <?php
                                foreach ($formaciones_informales as $formacion) {
                                    $id_formacion = $formacion['id'];
                                    $programa = $formacion['programa'];
                                    $institucion = $formacion['institucion'];
                                    $fecha_grado = $formacion['fecha_expedicion_certi'];
                                    $duracion = $formacion['duracion'];
                                    $certificado = $instancia->mostrarInformacionCertificadoFormacionControl($id_formacion);
                                ?>
                                    <tr>
                                        <td><?= $programa ?></td>
                                        <td><?= $institucion ?></td>
                                        <td><?= $fecha_grado ?></td>
                                        <td><?= $duracion ?> Horas</td>
                                        <td>
                                            <a href="<?= PUBLIC_PATH ?>upload/<?= $certificado['nombre_archivo'] ?>" download class="btn btn-success btn-sm">
                                                <i class="fa fa-download"></i>
                                                Descargar
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" value="<?= $id_formacion ?>" name="id">
                                                <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                                <input type="hidden" value="<?= $certificado['nombre_archivo'] ?>" name="name_doc">
                                                <button class="btn btn-danger btn-sm" type="submit" name="eliminar_formacion">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
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
    <div class="modal fade" id="modal_formacion_informal" tabindex="-1" role="dialog" aria-labelledby="modalInformalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" class="form_enviar_formacion_informal" enctype="multipart/form-data">
                    <input type="hidden" name="id_log" value="<?= $id_log ?>">
                    <input type="hidden" name="tipo_formacion" value="informal">

                    <div class="modal-header p-3">
                        <h4 class="modal-title text-hebreo font-weight-bold" id="modalInformalLabel">Agregar Formación Informal</h4>
                    </div>

                    <div class="modal-body border-0">
                        <div class="row p-3">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tipo_formacion" class="font-weight-bold">Tipo formación: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="Informal" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="institucion" class="font-weight-bold">Nombre de la Institución: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombre_institucion" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="programa" class="font-weight-bold">Programa: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="programa_formacion" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="duracion_informal" class="font-weight-bold">Duración (horas): <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="duracion" min="1" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="fecha_expedicion" class="font-weight-bold">Fecha expedición de la certificación: </label>
                                    <input type="date" class="form-control" name="fecha_expedicion">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mt-2">
                                    <label class="font-weight-bold" for="certificado_formacion">Certificado: <span class="text-danger">*</span></label>
                                    <div class="custom-file pmd-custom-file-filled">
                                        <input type="file" class="custom-file-input file_input" id="certificado_formacion" name="certificado_formacion" required accept=".png, .jpg, .jpeg, .pdf">
                                        <label class="custom-file-label file_label_certificado_formacion" for="customfilledFile"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-success btn-sm" name="enviar_formacion_informal">
                                <i class="fa fa-check"></i> Guardar
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['enviar_formacion_formal'])) {
    $instancia->agregarFormacionControl();
}

if (isset($_POST['enviar_formacion_informal'])) {
    $instancia->agregarFormacionControl();
}

if (isset($_POST['eliminar_formacion'])) {
    $instancia->eliminarFormacionControl();
}
?>