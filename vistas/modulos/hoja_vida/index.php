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
require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';

$instancia = ControlHojaVida::singleton_hoja_vida();

$permisos = $instancia_permiso->permisosUsuarioControl(5, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['inventario'])) {

    $id_inventario = base64_decode($_GET['inventario']);
    $datos_articulo = $instancia->mostrarDatosArticuloControl($id_inventario);
    $datos_componentes = $instancia->mostrarComponentesHardwareControl($datos_articulo['id_user']);

    //aqui va el if para verificar si $datos_articulo['id_hoja_vida'] tiene algun valor

    $datos_hardware    = $instancia->mostrarComponentesAsignadoHardwareControl($datos_articulo['id_hoja_vida']);
    $datos_software    = $instancia->mostrarComponentesAsignadoSoftwareControl($datos_articulo['id_hoja_vida']);
    $datos_reporte     = $instancia->mostrarReportesArticuloControl($id_inventario);
    $datos_copia       = $instancia->mostrarCopiasSeguridadArticuloControl($id_inventario);

    $selected_wifi  = '';
    $selected       = '';
    $selected_cable = '';

    if ($datos_articulo['tipo_conexion'] == 1) {
        $selected_cable = 'selected';
    }

    if ($datos_articulo['tipo_conexion'] == 2) {
        $selected_wifi = 'selected';
    }

    if ($datos_articulo['tipo_conexion'] == 0) {
        $selected = 'selected';
    }

    $codigo_inv   = $datos_articulo['codigo'];
    $fecha_update = (empty($datos_articulo['fecha_update'])) ? $datos_articulo['fechareg'] : $datos_articulo['fecha_update'];

    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-hebreo">
                            <a href="<?=BASE_URL?>inventario/index"  class="text-decoration-none">
                                <i class="fa fa-arrow-left text-hebreo"></i>
                            </a>
                            &nbsp;
                            Hoja de vida (<?=$datos_articulo['descripcion']?>)
                        </h4>
                        <h6 class="text-right mt-2 font-weight-bold  text-hebreo">Fecha de ultima actualizacion: <?=$fecha_update?></h6>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" value="<?=$id_super_empresa?>" name="id_super_empresa">
                            <input type="hidden" value="<?=$id_log?>" name="id_log" id="id_log">
                            <input type="hidden" value="<?=$datos_articulo['id_hoja_vida']?>" name="id_hoja_vida" id="id_hoja">
                            <input type="hidden" value="<?=$id_inventario?>" name="id_inventario">
                            <input type="hidden" value="<?=$datos_articulo['id_hoja_vida']?>">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nombre del equipo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" maxlength="50" required name="descripcion" value="<?=$datos_articulo['descripcion']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Marca</label>
                                        <input type="text" class="form-control" maxlength="50" name="marca" value="<?=$datos_articulo['marca']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Modelo</label>
                                        <input type="text" class="form-control" maxlength="50" name="modelo" value="<?=$datos_articulo['modelo']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Numero de serie</label>
                                        <input type="text" class="form-control" name="numero_serie" value="<?=$datos_articulo['numero_serie']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Codigo inventario</label>
                                        <input type="text" class="form-control" disabled value="<?=$codigo_inv?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 d-none">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Direccion IP</label>
                                        <input type="text" class="form-control" maxlength="50" name="ip" value="<?=$datos_articulo['direccion_ip']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 d-none">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Grupo de trabajo</label>
                                        <input type="text" class="form-control" maxlength="50" name="grupo" value="<?=$datos_articulo['grupo_trabajo']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 d-none">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Tipo de conexion</label>
                                        <select name="tipo_con" id="" class="form-control">
                                            <option value="0" <?=$selected?>>Seleccione...</option>
                                            <option value="1" <?=$selected_cable?>>Cable</option>
                                            <option value="2" <?=$selected_wifi?>>Wifi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Fecha de adquisicion</label>
                                        <input type="date" class="form-control" name="fecha_ad" value="<?=$datos_articulo['fecha_adquisicion']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Frecuencia de <span class="text-uppercase">mantenimiento</span> (meses)</label>
                                        <input type="text" class="form-control numeros" name="frec_mant" maxlength="2" minlength="1" value="<?=$datos_articulo['frecuencia_mantenimiento']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Frecuencia de <span class="text-uppercase">COPIA DE SEGURIDAD</span> (meses)</label>
                                        <input type="text" class="form-control numeros" name="frec_copia" maxlength="2" minlength="1" value="<?=$datos_articulo['frecuencia_copia']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Fecha de vencimiento garantia</label>
                                        <input type="date" class="form-control" name="fecha_gant" value="<?=$datos_articulo['fecha_garantia']?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Contacto garantia</label>
                                        <input type="text" class="form-control" maxlength="50" name="contacto" value="<?=$datos_articulo['contacto_garantia']?>">
                                    </div>
                                </div>
                                <div class="col-lg-8 mt-4 mb-4 text-right mb-2">
                                    <div class="form-group" style="margin-top: 1%;">
                                        <input type="hidden" value="<?=$codigo_inv?>" name="codigo" id="codigo">
                                        <a href="<?=PUBLIC_PATH?>upload/<?=$codigo_inv?>.png" class="btn btn-hebreo btn-sm" download="<?=$codigo_inv?>" id="desc_codigo">
                                            <i class="fas fa-barcode"></i>
                                            &nbsp;
                                            Descargar codigo
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn-hebreo btn-sm float-right mb-4" type="button" data-toggle="modal" data-target="#agregar_componente_hard">
                                        <i class="fa fa-plus"></i>
                                        &nbsp;
                                        Agregar Componente
                                    </button>
                                    <div class="table-responsive">
                                        <table class="table table-hover border" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th colspan="5">
                                                        Componentes de hardware
                                                    </th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Descripcion</th>
                                                    <th>Modelo</th>
                                                    <th>Marca</th>
                                                    <th>Codigo</th>
                                                    <th>Observacion</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar text-uppercase">
                                                <?php
                                                foreach ($datos_hardware as $componente_hard) {
                                                    $id_hardware      = $componente_hard['id'];
                                                    $descripcion_hard = $componente_hard['descripcion'];
                                                    $marca_hard       = $componente_hard['marca'];
                                                    $modelo_hard      = $componente_hard['modelo'];
                                                    $codigo_hard      = $componente_hard['codigo'];
                                                    $observacion_hard = $componente_hard['observacion'];
                                                    ?>
                                                    <tr class="text-center">
                                                        <td><?=$descripcion_hard?></td>
                                                        <td><?=$marca_hard?></td>
                                                        <td><?=$modelo_hard?></td>
                                                        <td><?=$codigo_hard?></td>
                                                        <td><?=$observacion_hard?></td>
                                                        <td>
                                                            <button class="btn  btn-danger btn-sm" id="<?=$id_hardware?>" data-tooltip="tooltip" title="Des-asignar">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover border" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th colspan="6">
                                                        Ubicacion y asignacion del equipo
                                                    </th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Area</th>
                                                    <th>Usuario o responsable</th>
                                                    <th>Fecha de asignacion</th>
                                                    <th>Observacion</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar text-uppercase">
                                                <tr class="text-center">
                                                    <td><?=$datos_articulo['area']?></td>
                                                    <td><?=$datos_articulo['usuario']?></td>
                                                    <td><?=$datos_articulo['fechareg']?></td>
                                                    <td><?=$datos_articulo['observacion']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover border" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th colspan="6">
                                                        Control de mantenimientos
                                                    </th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Observacion</th>
                                                    <th>Reporte</th>
                                                    <th>Fecha</th>
                                                    <th>Respuesta</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar text-uppercase">
                                                <?php
                                                foreach ($datos_reporte as $reporte) {
                                                    $id_reporte      = $reporte['id'];
                                                    $observacion     = $reporte['observacion'];
                                                    $fecha           = $reporte['fechareg'];
                                                    $estado          = $reporte['nombre_estado'];
                                                    $fecha_respuesta = $reporte['fecha_respuesta'];

                                                    $fecha_nueva = $fecha;
                                                    $nuevafecha  = strtotime('-1 hour', strtotime($fecha));
                                                    $nuevafecha  = date('Y-m-d H:i:s', $nuevafecha);

                                                    $fecha_ver = ($reporte['estado'] == 3) ? $fecha_respuesta : $fecha_nueva;

                                                    $ver          = ($reporte['estado'] == 3) ? '' : 'd-none';
                                                    $ver_solucion = ($reporte['estado'] != 3) ? '' : 'd-none';

                                                    if ($fecha_respuesta == '') {
                                                        $respuesta = '';
                                                    } else {
                                                        $fecha_reporte = $instancia->mostrarFechaReportadoControl($reporte['id_reporte']);
                                                        $datetime1     = new DateTime($fecha_reporte['fechareg']);
                                                        $datetime2     = new DateTime($fecha_respuesta);
                                                        $interval      = $datetime1->diff($datetime2);
                                                        $respuesta     = $interval->format('%d Dias %h Horas %i Minutos %s Segundos');
                                                    }

                                                    ?>
                                                    <tr class="text-center">
                                                        <td><?=$observacion?></td>
                                                        <td><?=$estado?></td>
                                                        <td><?=$fecha_ver?></td>
                                                        <td><?=$respuesta?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="<?=BASE_URL?>imprimir/solucion?reporte=<?=base64_encode($id_reporte)?>" target="_blank" class="btn btn-hebreo btn-sm <?=$ver?>" data-tooltip="tooltip" title="Descargar reporte">
                                                                    <i class="fa fa-download"></i>
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
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover border" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="text-center font-weight-bold">
                                                    <th colspan="6">
                                                        Control de copias de seguridad
                                                    </th>
                                                </tr>
                                                <tr class="text-center font-weight-bold">
                                                    <th>Usuario Responsable</th>
                                                    <th>Area</th>
                                                    <th>Observacion</th>
                                                    <th>Fecha</th>
                                                </tr>
                                            </thead>
                                            <tbody class="buscar text-uppercase">
                                                <?php
                                                foreach ($datos_copia as $copia) {
                                                    $id_copia    = $copia['id'];
                                                    $nom_usuario = $copia['nom_user'];
                                                    $nom_area    = $copia['nom_area'];
                                                    $observacion = $copia['observacion'];
                                                    $fecha       = date('Y-m-d', strtotime($copia['fecha']));
                                                    ?>
                                                    <tr class="text-center">
                                                        <td><?=$nom_usuario?></td>
                                                        <td><?=$nom_area?></td>
                                                        <td><?=$observacion?></td>
                                                        <td><?=$fecha?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-2 text-right">
                                    <a href="<?=BASE_URL?>imprimir/hoja_vida?inventario=<?=base64_encode($id_inventario)?>" target="_blank" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-print"></i>
                                        &nbsp;
                                        Imprimir
                                    </a>
                                    <button type="submit" class="btn btn-hebreo btn-sm">
                                        <i class="fa fa-save"></i>
                                        &nbsp;
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
    include_once VISTA_PATH . 'modulos' . DS . 'hoja_vida' . DS . 'agregarComponente.php';
    include_once VISTA_PATH . 'modulos' . DS . 'hoja_vida' . DS . 'agregarComponenteSoft.php';

    if (isset($_POST['descripcion'])) {
        $instancia->actualizarHojaVidaControl();
    }

    if (isset($_POST['descripcion_soft'])) {
        $instancia->guardarComponenteSoftwareControl();
    }
}
?>
<script src="<?=PUBLIC_PATH?>js/hoja_vida/funcionesHojaVida.js"></script>