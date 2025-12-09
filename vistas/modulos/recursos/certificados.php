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

$datos_solicitudes = $instancia->mostrarSolicitudesIdControl($id_log);
$datos_tipo        = $instancia->mostrarTipoDocumentoControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl(24, $perfil_log);

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
                        Certificados
                    </h4>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                            <div class="dropdown-header">Acciones:</div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#sol_cert">Solicitar certificado</a>
                        </div>
                    </div>
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
                        <button class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#sol_cert">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Solicitar certificado
                        </button>
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No. solicitud</th>
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
                                    $fecha_sol      = $solicitud['fechareg'];

                                    $archivo = $instancia->certificadoMostrarControl($id_solicitud);

                                    if ($solicitud['estado'] == 1) {
                                        $estado        = '<span class="badge badge-warning">Pendiente</span>';
                                        $ver_descargar = 'd-none';
                                    }

                                    if ($solicitud['estado'] == 2) {
                                        $estado        = $archivo['fechareg'];
                                        $ver_descargar = '';
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_solicitud?></td>
                                        <td><?=$tipo_documento?></td>
                                        <td><?=$fecha_sol?></td>
                                        <td> <?=$estado?></td>
                                        <td class="<?=$ver_descargar?>">
                                            <a class="btn btn-primary btn-sm" href="<?=PUBLIC_PATH?>upload/<?=$archivo['nombre']?>" target="_blank" download data-tooltip="tooltip" data-placement="bottom" title="Descargar">
                                                <i class="fa fa-download"></i>
                                            </a>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'recursos' . DS . 'solicitar.php';

if (isset($_POST['lugar'])) {
    $instancia->solicitarCertificadoControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/recursos/funcionesRecursos.js"></script>