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
require_once CONTROL_PATH . 'reportes' . DS . 'ControlReportes.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$instancia            = ControlReporte::singleton_reporte();
$instancia_usuario    = ControlUsuarios::singleton_usuarios();
$instancia_inventario = ControlInventario::singleton_inventario();
$instancia_areas      = ControlAreas::singleton_areas();
$datos_usuario = $instancia_usuario->mostrarTodosUsuariosControl();
$datos_areas   = $instancia_areas->mostrarAreasControl($id_super_empresa);
if (isset($_POST['buscar'])) {
    $datos         = array('buscar' => $_POST['buscar'], 'usuario' => $_POST['usuario'], 'area' => $_POST['area']);
    $datos_reporte = $instancia->buscarReportesControl($datos);
} else {
    $datos_reporte = $instancia->mostrarReportesControl();
}


$validar_firma_log = $instancia_usuario->validarFirmaIdControl($id_log);
$firma_log = ($validar_firma_log['id'] == '') ? '<div class="custom-file pmd-custom-file-filled">
<input type="file" class="custom-file-input" id="customfilledFile1" name="firma_solucionado">
<label class="custom-file-label" for="customfilledFile1" id="firma_sol">Examinar...</label>
</div>' : '<br><span class="badge badge-success">Firma subida</span>';
$permisos = $instancia_permiso->permisosUsuarioControl(7, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
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
                        Reportes
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select name="area" class="form-control" >
                                    <option value="" selected>Seleccione un area...</option>
                                    <?php
                                    foreach ($datos_areas as $area) {
                                        $id_area  = $area['id'];
                                        $nom_area = $area['nombre'];
                                        ?>
                                        <option value="<?=$id_area?>"><?=$nom_area?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">
                                <select class="form-control" name="usuario">
                                    <option value="" selected>Seleccione un usuario...</option>
                                    <?php
                                    foreach ($datos_usuario as $usuario) {
                                        $id_usu   = $usuario['id_user'];
                                        $nom_user = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                        ?>
                                        <option value="<?=$id_usu?>"><?=$nom_user?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
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

                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Id Inventario</th>
                                    <th scope="col">Responsable</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Fecha reportado</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col"></th>
                                    <th scope="col">Tipo de reporte</th>
                                </tr>
                            </thead>

                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_reporte as $reporte) {
                                    $id_inventario = $reporte['id'];
                                    $id_reporte    = $reporte['reporte_id'];
                                    $descripcion   = $reporte['descripcion'];
                                    $marca         = $reporte['marca'];
                                    $modelo        = $reporte['modelo'];
                                    $fecha_reporte = $reporte['fecha_reporte'];
                                    $estado        = $reporte['nom_estado'];
                                    $observacion   = $reporte['observacion'];
                                    $codigo        = $reporte['codigo'];
                                    $id_user       = $reporte['id_user'];
                                    $id_reporte    = $reporte['id_reporte'];
                                    $id_area       = $reporte['id_area'];
                                    $area          = $reporte['nom_area'];
                                    $nom_usuario   = $reporte['usuario'];
                                    $cantidad      = $reporte['cantidad_reportado'];
                                    $categoria      = $reporte['id_categoria'];
                                    $fecha      = $fecha_reporte;
                                    $nuevafecha = strtotime('-1 hour', strtotime($fecha));
                                    $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
                                    $span_estado = ($reporte['estado'] == 2) ? '<span class="badge badge-danger">' . $estado . '</span>' : '<span class="badge badge-warning">' . $estado . '</span>';
                                    $validar_firma_user = $instancia_usuario->validarFirmaIdControl($id_user);
                                    $firma_user = ($validar_firma_user['id'] == '') ? '<div class="custom-file pmd-custom-file-filled">
                                    <input type="file" class="custom-file-input customfilledFile" id="' . $id_inventario . '" name="firma_responsable">
                                    <label class="custom-file-label" for="customfilledFile" id="firma_resp' . $id_inventario . '">Examinar...</label>
                                    </div>' : '<br><span class="badge badge-success">Firma subida</span>';
                                    $tipo_reporte = ($reporte['estado'] == 2) ? '1' : '2';

                                    ?>

                                    <tr class="text-center">
                                        <td><?=$id_inventario?></td>
                                        <td class="text-uppercase"><?=$nom_usuario?></td>
                                        <td class="text-uppercase"><?=$area?></td>
                                        <td><?=$descripcion?></td>
                                        <td><?=$marca?></td>
                                        <td><?=$fecha_reporte?></td>
                                        <td><?=$observacion?></td>
                                        <td><?=($categoria== 1 || $categoria == 5) ? '<span class="badge badge-info">Sistemas</span>' : '<span class="badge badge-secondary">General</span>'?></td> <!-- Etiqueta de categoría -->
                                        <td><?=$span_estado?></td>
                                     
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">

                                                <a href="<?=BASE_URL?>imprimir/reporte?id=<?=base64_encode($id_reporte)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte" data-placement="bottom">
                                                    <i class="fa fa-download"></i>
                                                </a>

                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#sol_reporte<?=$id_inventario?>" data-tooltip="tooltip" title="Solucionar" data-placement="bottom">
                                                    <i class="fas fa-wrench"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Descontinuar" data-toggle="modal" data-target="#desc_inv<?=$id_inventario?>">
                                                    <i class="fas fa-minus-circle"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Descontinuar inventario -->
                                    <div class="modal fade" id="desc_inv<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Descontinuar articulo</h5>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body border-0">
                                                        <div class="row p-2">

                                                            <input type="hidden" value="<?=$descripcion?>" name="nom_inventario_desc">
                                                            <input type="hidden" value="<?=$id_log?>" name="id_log_desc" id="id_log">
                                                            <input type="hidden" value="<?=$id_user?>" name="id_user_desc" id="id_user">
                                                            <input type="hidden" value="<?=$id_area?>" name="id_area_desc" id="id_area">
                                                            <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_desc" id="id_inventario">
                                                            <input type="hidden" value="2" name="inicio">
                                                            <input type="hidden" value="2" name="estado">

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Area</label>
                                                                <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$area?>">
                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Descripcion</label>
                                                                <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$descripcion?>">
                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Marca</label>
                                                                <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$marca?>">
                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Cantidad Reportada</label>
                                                                <input type="text" class="form-control" disabled value="<?=$cantidad?>">
                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Cantidad a Descontinuar</label>
                                                                <input type="number" class="form-control numeros" name="cantidad" value="" max="<?=$cantidad?>">
                                                            </div>

                                                            <div class="form-group col-lg-6">
                                                                <label class="font-weight-bold">Responsable</label>
                                                                <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$reporte['usuario']?>">
                                                            </div>

                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Fecha <span class="text-danger">*</span></label>
                                                                <input type="date" name="fecha" class="form-control" value="<?=date('Y-m-d')?>">
                                                            </div>

                                                            <div class="form-group col-lg-12">
                                                                <label class="font-weight-bold">Observacion</label>
                                                                <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
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
                                                            <i class="fas fa-minus-circle"></i>
                                                            &nbsp;
                                                            Descontinuar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!------------------------------------------------------->
                                    <!-- Solucionar Reporte -->
                                    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="sol_reporte<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Solucionar reporte</h5>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" value="<?=$descripcion?>" name="nom_inventario_sol">
                                                    <input type="hidden" value="<?=$cantidad?>" name="cantidad">
                                                    <input type="hidden" value="<?=$id_log?>" name="resp" id="id_log">
                                                    <input type="hidden" value="<?=$id_user?>" name="user" id="id_user">
                                                    <input type="hidden" value="<?=$id_area?>" name="id_area" id="id_area">
                                                    <input type="hidden" value="<?=$tipo_reporte?>" id="tipo_reporte" name="tipo_reporte">
                                                    <div class="modal-body border-0">
                                                        <div class="row p-2">
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Descripcion</label>
                                                                <input type="text" class="form-control" disabled value="<?=$descripcion?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Marca</label>
                                                                <input type="text" class="form-control" disabled value="<?=$marca?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Cantidad Reportada</label>
                                                                <input type="text" class="form-control" disabled value="<?=$cantidad?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Usuario</label>
                                                                <input type="text" class="form-control" disabled value="<?=$reporte['usuario']?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Estado</label>
                                                                <input type="text" class="form-control" disabled value="<?=$estado?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Fecha reportado</label>
                                                                <input type="text" class="form-control" disabled value="<?=$fecha_reporte?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Fecha respuesta <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" name="fecha_respuesta" required>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <div class="custom-control custom-switch mt-2">
                                                                    <input type="checkbox" class="custom-control-input" id="enviar<?=$id_inventario?>" name="enviar_correo" value="si">
                                                                    <label class="custom-control-label" for="enviar<?=$id_inventario?>">
                                                                        Enviar correo
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Observacion</label>
                                                                <textarea class="form-control" rows="5" name="observacion"></textarea>
                                                            </div>
                                                            <div class="col-lg-6 form-group mt-2">
                                                                <label class="font-weight-bold">Firma responsable</label>
                                                                <?=$firma_user?>
                                                            </div>
                                                            <div class="col-lg-6 form-group mt-2">
                                                                <label class="font-weight-bold">Firma solucionado</label>
                                                                <?=$firma_log?>
                                                            </div>
                                                            <div class="col-lg-12 form-group mt-4 text-right">
                                                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                    <i class="fa fa-times"></i>
                                                                    &nbsp;
                                                                    Cerrar
                                                                </button>
                                                                <button type="submit" class="btn btn-primary btn-sm solucionar">
                                                                    <i class="fa fa-check"></i>
                                                                    &nbsp;
                                                                    Solucionar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
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
if (isset($_POST['nom_inventario_sol'])) {

    $instancia->solucionarReporteControl();

}
if (isset($_POST['nom_inventario_desc'])) {
    $instancia_inventario->descontinuarArticuloControl();
}
?>
<script type="text/javascript">
    $("#customfilledFile1").change(function(){
        let valor = $(this).val().split('\\').pop();
        $("#firma_sol").text(valor);
    });

    $(".customfilledFile").change(function(){
        let id = $(this).attr('id');
        let valor = $(this).val().split('\\').pop();
        $("#firma_resp" + id).text(valor);
    });
</script>