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
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';

$instancia           = ControlInventario::singleton_inventario();
$instancia_usuarios  = ControlUsuarios::singleton_usuarios();
$instancia_areas     = ControlAreas::singleton_areas();
$instancia_hoja_vida = ControlHojaVida::singleton_hoja_vida();

$datos_usuarios = $instancia_usuarios->mostrarTodosUsuariosControl();
$datos_areas    = $instancia_areas->mostrarAreasControl($id_super_empresa);

if (isset($_POST['buscar'])) { 
    $datos           = array('buscar' => $_POST['buscar'], 'usuario' => $_POST['usuario'], 'area' => $_POST['area']);
    $datos_historial = $instancia->buscarHistorialReportesControl($datos);
} else {
    $datos_historial = $instancia->historialReportesControl($id_super_empresa);
}

$permisos = $instancia_permiso->permisosUsuarioControl(14, $perfil_log);

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
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Historial inventario
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <select name="area" class="form-control filtro_change select2" id="" data-tooltip="tooltip" title="Area">
                                        <option value="" selected>Seleccione un area...</option>
                                        <?php
                                        foreach ($datos_areas as $area) {
                                            $id_area = $area['id'];
                                            $nombre  = $area['nombre'];
                                            $estado  = $area['activo'];
                                            ?>
                                            <option value="<?=$id_area?>"><?=$nombre?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <select name="usuario" class="form-control filtro_change select2" id="" data-tooltip="tooltip" title="Usuario">
                                        <option value="" selected>Seleccione un usuario...</option>
                                        <?php
                                        foreach ($datos_usuarios as $usuario) {
                                            $id_user         = $usuario['id_user'];
                                            $nombre_completo = $usuario['nom_user'];
                                            $estado          = $usuario['estado'];
                                            ?>
                                            <option value="<?=$id_user?>"><?=$nombre_completo?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
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
                    <div class="table-responsive mt-3">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Reporte</th>
                                    <th scope="col">Observacion</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Respuesta</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_historial as $historial) {
                                    $usuario         = $historial['usuario'];
                                    $area            = $historial['area'];
                                    $descripcion     = $historial['descripcion'];
                                    $marca           = $historial['marca'];
                                    $reporte         = $historial['estado_nombre'];
                                    $fecha           = $historial['fecha_reporte'];
                                    $observacion     = $historial['observacion_reporte'];
                                    $fecha_respuesta = $historial['fecha_respuesta'];
                                    $id_reporte      = $historial['id_reporte'];
                                    $estado          = $historial['estado'];

                                    $fecha_ver = ($estado == 3) ? $fecha_respuesta : $fecha;

                                    $fecha_reporte = $instancia_hoja_vida->mostrarFechaReportadoControl($id_reporte);
                                    $datetime1     = new DateTime($fecha_reporte['fechareg']);
                                    $datetime2     = new DateTime($fecha_respuesta);
                                    $interval      = $datetime1->diff($datetime2);
                                    $respuesta     = $interval->format('%d Dias %h Horas %i Minutos %s Segundos');

                                    $respuesta = ($fecha_respuesta == '') ? '' : $respuesta;

                                    $ver = ($historial['estado_reporte'] == 3) ? '' : 'd-none';

                                    ?>
                                    <tr class="text-center">
                                        <td><?=$usuario?></td>
                                        <td><?=$area?></td>
                                        <td><?=$descripcion?></td>
                                        <td><?=$marca?></td>
                                        <td><?=$reporte?></td>
                                        <td><?=$observacion?></td>
                                        <td><?=$fecha_ver?></td>
                                        <td><?=$respuesta?></td>
                                        <td class="<?=$ver?>">
                                            <a href="<?=BASE_URL?>imprimir/solucion?reporte=<?=base64_encode($historial['id_reporte_inicio'])?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte">
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