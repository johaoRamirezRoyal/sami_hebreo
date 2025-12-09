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
require_once CONTROL_PATH . 'zonas' . DS . 'ControlZonas.php';

$instancia = ControlZonas::singleton_zonas();

$permisos = $instancia_permiso->permisosUsuarioControl(37, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['id'])) {
    $id_zona = base64_decode($_GET['id']);

    $datos_zona    = $instancia->mostrarDatosIdZonaControl($id_zona);
    $datos_reporte = $instancia->reportesZonaControl($id_zona);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="#" onclick="window.history.go(-1);" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Historial de reportes zona (<?=$datos_zona['nombre']?>)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Zona</label>
                                <input type="text" disabled class="form-control" value="<?=$datos_zona['nombre']?>">
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Area</label>
                                <input type="text" disabled class="form-control" value="<?=$datos_zona['area_nom']?>">
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col" colspan="7">HISTORIAL DE REPORTES</th>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Tipo de reporte</th>
                                        <th scope="col">Observacion</th>
                                        <th scope="col">Observacion re-programada</th>
                                        <th scope="col">Observacion respuesta</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Respuesta</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-uppercase">
                                    <?php
                                    foreach ($datos_reporte as $historial) {
                                        $id_historial      = $historial['id'];
                                        $nom_area          = $historial['nom_area'];
                                        $nom_zona          = $historial['nom_zona'];
                                        $observacion       = $historial['observacion'];
                                        $observacion_repro = $historial['observacion_repro'];
                                        $estado            = $historial['estado'];
                                        $estado_respuesta  = $historial['estado_respuesta'];
                                        $observacion_resp  = $historial['observacion_resp'];

                                        $fecha           = ($estado == 6) ? $historial['fecha_mantenimiento'] : $historial['fechareg'];
                                        $fecha_respuesta = $historial['fecha_respuesta_con'];

                                        $datetime1 = new DateTime($fecha);
                                        $datetime2 = new DateTime($fecha_respuesta);
                                        $interval  = $datetime1->diff($datetime2);
                                        $respuesta = $interval->format('%m Meses %d Dias %h Horas %i Minutos %s Segundos');

                                        $respuesta = ($fecha_respuesta == '') ? '' : $respuesta;

                                        $span_repor = "";
                                        $span_resp  = "";

                                        if ($estado == 6) {
                                            $span_repor = '<span class="badge badge-warning">Mantenimiento</span>';
                                        }

                                        if ($estado == 10) {
                                            $span_repor = '<span class="badge badge-warning">Mantenimiento re-programado</span>';
                                        }

                                        if ($estado == 2) {
                                            $span_repor = '<span class="badge badge-danger">Correctivo</span>';
                                        }

                                        if (is_null($estado_respuesta)) {
                                            $span = "";
                                        }

                                        if ($estado_respuesta == 10) {
                                            $span_resp = '<span class="badge badge-warning">Mantenimiento re-programado</span>';
                                        }

                                        if ($estado_respuesta == 3) {
                                            $span_resp = '<span class="badge badge-success">Solucionado</span>';
                                        }

                                        ?>
                                        <tr class="text-center">
                                            <td><?=$span_repor . ' | ' . $span_resp?></td>
                                            <td><?=$observacion?></td>
                                            <td><?=$observacion_repro?></td>
                                            <td><?=$observacion_resp?></td>
                                            <td><?=$fecha?></td>
                                            <td><?=$fecha_respuesta?></td>
                                            <td><?=$respuesta?></td>
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
}