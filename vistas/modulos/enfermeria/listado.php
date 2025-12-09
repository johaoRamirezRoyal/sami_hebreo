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
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlEnfermeria::singleton_enfermeria();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_usuarios = $instancia_usuario->mostrarTodosUsuariosControl();

if (isset($_POST['buscar'])) {
    $datos          = array('buscar' => $_POST['buscar'], 'fecha_hasta' => $_POST['fecha_hasta'], 'fecha_desde' => $_POST['fecha_desde']);
    $datos_atencion = $instancia->buscarAtencionMedicaControl($datos);
} else {
    $datos_atencion = $instancia->mostrarLimiteAtencionMedicaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(58, $perfil_log);

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
                        <a href="<?=BASE_URL?>enfermeria/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Listado de atenciones medicas
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <input type="date" name="fecha_desde" class="form-control" data-tooltip="tooltip" title="Fecha desde" data-placement="top">
                            </div>
                            <div class="col-lg-4 form-group">
                                <input type="date" name="fecha_hasta" class="form-control" data-tooltip="tooltip" title="Fecha hasta" data-placement="top">
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
                    <div class="col-lg-12 form-group text-right">
                        <a href="#" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel"></i>
                            &nbsp;
                            Descargar Reporte
                        </a>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">No. Atencion Medica</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Nivel</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Motivo</th>
                                    <th scope="col">Tratamiento</th>
                                    <th scope="col">Fecha de atencion</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_atencion as $atencion) {
                                    $id_atencion = $atencion['id'];
                                    $nom_user    = $atencion['nom_user'];
                                    $documento   = $atencion['documento'];
                                    $curso       = $atencion['nom_curso'];
                                    $nivel       = $atencion['nom_nivel'];
                                    $motivo      = $atencion['motivo_cons'];
                                    $tratamiento = $atencion['tratamiento'];

                                    if ($atencion['envio'] == 1) {
                                        $span_envio = '<span class="badge badge-success">Correo Enviado</span>';
                                    }

                                    if ($atencion['envio'] == 2) {
                                        $span_envio = '<span class="badge badge-secondary">Envio de correo programado pendiente</span>';
                                    }

                                    if ($atencion['envio'] == 2 && $atencion['enviado'] == 1) {
                                        $span_envio = '<span class="badge badge-success">Correo Programado Enviado</span>';
                                    }

                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_atencion?></td>
                                        <td><?=$documento?></td>
                                        <td>
                                            <a href="<?=BASE_URL?>enfermeria/historial?usuario=<?=base64_encode($atencion['id_user'])?>"><?=$nom_user?></a>
                                        </td>
                                        <td><?=$nivel?></td>
                                        <td><?=$curso?></td>
                                        <td><?=$motivo?></td>
                                        <td><?=$tratamiento?></td>
                                        <td><?=date('Y-m-d', strtotime($atencion['fechareg']))?></td>
                                        <td><?=$span_envio?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?=BASE_URL?>enfermeria/historial?usuario=<?=base64_encode($atencion['id_user'])?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver historial" data-placement="bottom">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
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
?>