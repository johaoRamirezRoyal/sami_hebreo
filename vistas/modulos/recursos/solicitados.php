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
require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

$instancia = ControlRecursos::singleton_recursos();

$datos_solicitudes = $instancia->mostrarSolicitudesControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl(25, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>recursos/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Certificados solicitados
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control filtro" placeholder="Buscar...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No. solicitud</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Tipo de documento</th>
                                    <th scope="col">Fecha de solicitud</th>
                                    <th scope="col">Fecha de entrega</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-lowercase">
                                <?php
                                foreach ($datos_solicitudes as $solicitud) {
                                    $id_solicitud   = $solicitud['id'];
                                    $tipo_documento = $solicitud['documento'];
                                    $usuario        = $solicitud['usuario'];
                                    $fecha_sol      = $solicitud['fechareg'];

                                    if ($solicitud['estado'] == 1) {
                                        $ver_subir     = '';
                                        $ver_descargar = 'd-none';
                                    }

                                    if ($solicitud['estado'] == 2) {
                                        $ver_subir     = 'd-none';
                                        $ver_descargar = '';
                                    }

                                    $archivo = $instancia->certificadoMostrarControl($id_solicitud);
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_solicitud?></td>
                                        <td><?=$usuario?></td>
                                        <td><?=$tipo_documento?></td>
                                        <td><?=$fecha_sol?></td>
                                        <td><?=$archivo['fechareg']?></td>
                                        <td class="<?=$ver_subir?>">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#subir<?=$id_solicitud?>">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </td>
                                        <td class="<?=$ver_descargar?>">
                                            <a href="<?=PUBLIC_PATH?>upload/<?=$archivo['nombre']?>" target="_blank" download class="btn btn-primary btn-sm">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Subir archivo -->
                                    <div class="modal fade" id="subir<?=$id_solicitud?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Subir archivo</h5>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id_log" value="<?=$id_log?>">
                                                    <input type="hidden" name="id_sol" value="<?=$id_solicitud?>">
                                                    <div class="modal-body border-0">
                                                        <div class="row p-3">
                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Usuario</label>
                                                                <input type="text" class="form-control" disabled value="<?=$usuario?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Tipo de certificado</label>
                                                                <input type="text" class="form-control" disabled value="<?=$tipo_documento?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Fecha de solicitud</label>
                                                                <input type="text" class="form-control" disabled value="<?=$fecha_sol?>">
                                                            </div>
                                                            <div class="form-group col-lg-12">
                                                                <label class="font-weight-bold">Archivo <span class="text-danger">*</span></label>
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input customFileLang" name="archivo" id="customFileLang" lang="es" required>
                                                                    <label class="custom-file-label nom_arch" for="customFileLang">Seleccionar archivo...</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 mt-4 text-right">
                                                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                    <i class="fa fa-times"></i>
                                                                    &nbsp;
                                                                    Cerrar
                                                                </button>
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-upload"></i>
                                                                    &nbsp;
                                                                    Subir
                                                                </button>
                                                            </div>
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

if (isset($_POST['id_sol'])) {
    $instancia->subirArchivoControl();
}
?>