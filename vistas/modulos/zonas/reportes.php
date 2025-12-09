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

$datos_reporte = $instancia->mostraReportesZonaControl();

$permisos = $instancia_permiso->permisosUsuarioControl(34, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Reportes
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 form-inline">
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control filtro" placeholder="Buscar">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-right" id="basic-addon1">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">#</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Zona</th>
                                    <!-- <th scope="col">Usuario que reporto</th> -->
                                    <th scope="col">Fecha inicial de programacion</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Observacion re-programado</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_reporte as $reporte) {
                                    $id_reporte        = $reporte['id'];
                                    $zona              = $reporte['zona_nom'];
                                    $usuario           = $reporte['nom_usuario'];
                                    $observacion       = $reporte['observacion'];
                                    $fecha             = $reporte['fechareg'];
                                    $estado            = $reporte['estado'];
                                    $area              = $reporte['area_nom'];
                                    $id_user           = $reporte['id_log'];
                                    $id_zona           = $reporte['id_zona'];
                                    $observacion_repro = $reporte['observacion_repro'];

                                    $fecha = ($estado == 6 || $estado == 10) ? $reporte['fecha_mantenimiento'] : $fecha;

                                    $ver_repro = '';

                                    if ($estado == 6) {
                                        $span      = '<span class="badge badge-warning">Mantenimiento</span>';
                                        $ver_repro = '';
                                    }

                                    if ($estado == 2) {
                                        $ver_repro = 'd-none';
                                        $span      = '<span class="badge badge-danger">Correctivo</span>';
                                    }

                                    if ($estado == 10) {
                                        $span = '<span class="badge badge-warning">Mantenimiento re-programado</span>';
                                    }

                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_reporte?></td>
                                        <td><?=$zona?></td>
                                        <td><?=$area?></td>
                                        <!-- <td><?=$usuario?></td> -->
                                        <td><?=$fecha?></td>
                                        <td><?=$observacion?></td>
                                        <td><?=$observacion_repro?></td>
                                        <td>
                                            <?=$span?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-success btn-sm" data-tooltip="tooltip" title="Solucionar" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#solucion<?=$id_reporte?>">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm <?=$ver_repro?>" data-tooltip="tooltip" data-placement="bottom" title="Reprogramar reporte" data-trigger="hover" data-toggle="modal" data-target="#repro<?=$id_reporte?>">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                                <a href="<?=BASE_URL?>imprimir/zonas/reporteZona?reporte=<?=base64_encode($id_reporte)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte" data-placement="bottom" data-trigger="hover" >
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>



                                    <!-- Modal -->
                                    <div class="modal fade" id="solucion<?=$id_reporte?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Solucionar reporte</h5>
                                        </div>
                                        <form method="POST">
                                            <input type="hidden" value="<?=$id_reporte?>" name="id_reporte">
                                            <input type="hidden" value="<?=$id_log?>" name="id_log">
                                            <input type="hidden" value="<?=$id_user?>" name="id_user">
                                            <input type="hidden" value="<?=$id_zona?>" name="id_zona">
                                            <div class="modal-body">
                                                <div class="row p-2">
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="<?=$zona?>" disabled>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Area <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="<?=$area?>" disabled>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Fecha reportado <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="<?=$fecha?>" disabled>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label class="font-weight-bold">Fecha respuesta <span class="text-danger">*</span></label>
                                                        <input type="date" name="fecha_respuesta" class="form-control" required>
                                                    </div>
                                                    <div class="col-lg-12 form-group">
                                                        <label class="font-weight-bold">Observacion</label>
                                                        <textarea class="form-control" rows="5" name="observacion"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                    &nbsp;
                                                    Cerrar
                                                </button>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa fa-check"></i>
                                                    &nbsp;
                                                    Solucionar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="repro<?=$id_reporte?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title text-primary font-weight-bold">Reprogramar reporte</h5>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" value="<?=$id_reporte?>" name="id_reporte">
                                        <input type="hidden" value="<?=$id_log?>" name="id_log_re">
                                        <input type="hidden" value="<?=$id_user?>" name="id_user">
                                        <input type="hidden" value="<?=$id_zona?>" name="id_zona">
                                        <input type="hidden" name="observacion" value="<?=$observacion?>">
                                        <div class="modal-body">
                                            <div class="row p-2">
                                                <div class="col-lg-6 form-group">
                                                    <label class="font-weight-bold">Area</label>
                                                    <input type="text" class="form-control" value="<?=$zona?>" disabled>
                                                </div>
                                                <div class="col-lg-6 form-group">
                                                    <label class="font-weight-bold">Zona</label>
                                                    <input type="text" class="form-control" value="<?=$area?>" disabled>
                                                </div>
                                                <div class="col-lg-6 form-group">
                                                    <label class="font-weight-bold">Fecha a reprogramar</label>
                                                    <input type="date" class="form-control" name="fecha_mant" value="<?=date('Y-m-d', strtotime($fecha))?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <label class="font-weight-bold">Observacion</label>
                                                <textarea class="form-control" rows="5" disabled><?=$observacion?></textarea>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <label class="font-weight-bold">Por qu&eacute; se reprograma ? (opcional)</label>
                                                <textarea class="form-control" rows="5" name="observacion_repro"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                <i class="fa fa-times"></i>
                                                &nbsp;
                                                Cerrar
                                            </button>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fa fa-save"></i>
                                                &nbsp;
                                                Guardar
                                            </button>
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
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
    $instancia->solucionarReporteZonaControl();
}

if (isset($_POST['fecha_mant'])) {
    $instancia->reprogramarFechaReporteControl();
}