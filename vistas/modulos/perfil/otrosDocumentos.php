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

$instancia = ControlPerfil::singleton_perfil();

if(isset($_GET['id'])){
    $id_log = base64_decode($_GET['id']);
}

$informacion_usuario = $instancia->mostrarDatosPerfilControl($id_log);

$redir = (isset($_GET['id'])) ? 'hojaVida?id=' . $_GET['id'] . '&link=1' : 'hojaVida';

$documentos_varios_guardados = $instancia->mostrarDocumentosVariosUsuarioControl($id_log);
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
                        Otros Documentos de Interes (<?= trim($informacion_usuario['nombre']) ?>)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="font-weight-bold">Tipo de documento: <span class="text-danger">*</span></label>
                                        <select name="tipo_doc" id="tipo_doc" onchange="mostrarOtroDocumento(this)">
                                            <option value="">Seleccione una opcion...</option>
                                            <option value="licencia de conducción"> Licencia de Conducción</option>
                                            <option value="certificado de examen de idiomas">Certificado de Examen de Idiomas</option>
                                            <option value="certificado de idiomas">Certificado de Idiomas</option>
                                            <option value="tarjeta profesional">Tarjeta Profesional</option>
                                            <option value="resolucion docente">Resolución Docente</option>
                                            <option value="certificado de vacunacion">Certificado de Vacunación</option>
                                            <option value="licencia en seguridad y salud en el trabajo">Licencia en Seguridad y Salud en el Trabajo</option>
                                            <option value="Otro:">Otro: </option>
                                        </select>
                                    </div>


                                    <div class="form-group mt-2 col-lg-8">
                                        <label class="font-weight-bold" for="documento_variado">Documento adjunto: (PDF, JPG, PNG, JPEG) <span class="text-danger">*</span></label>
                                        <div class="custom-file pmd-custom-file-filled">
                                            <input type="file" class="custom-file-input file_input" id="documento_variado" name="documento_variado" required accept=".png, .jpg, .jpeg, .pdf">
                                            <label class="custom-file-label file_label_documento_variado" for="customfilledFile"></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group pt-2" id="otroDocumentoContainer" style="display: none;">
                                            <label class="font-weight-bold">Nombre del documento: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="otro_documento">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 d-flex align-items-center justify-content-end">
                                        <button class="btn btn-success btn-md" type="submit" name="enviar_otros_documentos">
                                            <i class="fa fa-check"></i>
                                            &nbsp;
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <hr>
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Otros Documentos</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead class="text-center font-weight-bold">
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Tipo de documento</th>
                                        <th scope="col">Nombre del documento</th>
                                        <th scope="col">Fecha de registro</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center text-uppercase">
                                    <?php
                                    foreach ($documentos_varios_guardados as $documento) {
                                        $id = $documento['id'];
                                        $tipo_doc = $documento['tipo_doc'];
                                        $nombre_doc = $documento['nombre_doc'];
                                        $fecha_reg = $documento['fechareg'];
                                    ?>
                                        <tr class="text-center">
                                            <td><?= $tipo_doc ?></td>
                                            <td><?= $nombre_doc ?></td>
                                            <td><?= $fecha_reg ?></td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $nombre_doc ?>" download class="btn btn-info btn-sm mb-2">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <form method="POST">
                                                    <input type="hidden" value="<?= $id ?>" name="id">
                                                    <input type="hidden" value="<?= $nombre_doc ?>" name="nombre_doc">
                                                    <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                                    <button class="btn btn-danger btn-sm" type="submit" name="eliminar_documento">
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
    </div>
</div>

<?php

include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['enviar_otros_documentos'])) {
    $instancia->agregarNuevoDocumentoVariadoControl();
}

if (isset($_POST['eliminar_documento'])) {
    $instancia->eliminarDocumentoVariosControl();
}
?>

<script>
    function mostrarOtroDocumento(select) {
        const otro = document.getElementById("otroDocumentoContainer");
        if (select.value.includes("Otro")) {
            otro.style.display = "block";
        } else {
            otro.style.display = "none";
            document.getElementById("otro_documento").value = "";
        }
    }
</script>