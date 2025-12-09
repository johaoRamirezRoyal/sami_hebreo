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

require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';

require_once CONTROL_PATH . 'categorias' . DS . 'ControlCategorias.php';



$instancia           = ControlInventario::singleton_inventario();

$instancia_hoja_vida = ControlHojaVida::singleton_hoja_vida();



$permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);
$instancia_categoria = ControlCategorias::singleton_categorias();



if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();
}



if (isset($_GET['inventario'])) {



    $id_inventario   = base64_decode($_GET['inventario']);

    $datos_articulo  = $instancia->mostrarDatosArticuloIdControl($id_inventario);

    $categoria_id = $datos_articulo['id_categoria'];

    $datos_historial = $instancia->historialArticuloControl($id_inventario);

    $datos_categoria = $instancia_categoria->mostrarCategoriasControl($id_super_empresa);

    $codigo = $datos_articulo['id'];



    $evidencia = (empty($datos_articulo['evidencia'])) ? 'disabled' : '';



?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow-sm mb-4">

                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                        <h4 class="m-0 font-weight-bold text-primary">

                            <a href="<?= BASE_URL ?>listado/index" class="text-decoration-none">

                                <i class="fa fa-arrow-left text-primary"></i>

                            </a>

                            &nbsp;

                            Detalle articulo (<?= $datos_articulo['descripcion'] ?>)

                        </h4>

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <form method="POST">
                                <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario">
                                <div class="row">

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Nombre del equipo</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" value="<?= $datos_articulo['descripcion'] ?>" name="descripcion">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Marca</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" value="<?= $datos_articulo['marca'] ?>" name="marca">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Modelo</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" value="<?= $datos_articulo['modelo'] ?>" name="modelo">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Codigo</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" disabled value="<?= $codigo ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Area</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" disabled value="<?= $datos_articulo['area'] ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Usuario responsable</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control text-uppercase" maxlength="50" disabled value="<?= $datos_articulo['usuario'] ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">

                                        <div class="form-group">

                                            <label class="font-weight-bold">Fecha asignado</label>

                                            <div class="input-group mb-3">

                                                <input type="text" class="form-control" maxlength="50" disabled value="<?= $datos_articulo['fechareg'] ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="categoria" class="font-weight-bold">Categoria</label>
                                            <div class="input-group mb-3">
                                                <select name="categoria" class="form-control" data-tooltip="tooltip" title="Categoria">
                                                    <option value="" selected>Seleccione una opcion...</option>
                                                    <?php
                                                    foreach ($datos_categoria as $categoria) {
                                                        $id_categoria = $categoria['id'];
                                                        $nombre       = $categoria['nombre'];
                                                        $selected     = ($id_categoria == $categoria_id) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= $id_categoria ?>" <?= $selected ?>><?= $nombre ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if ($categoria_id == 1): ?>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="frecuencia_mantenimiento" class="font-weight-bold">Frecuencia de mantenimiento</label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" id="frecuencia_mantenimiento" name="frecuencia_mantenimiento" value="<?= $datos_articulo['frecuencia_mantenimiento'] ?>" placeholder="Numero de meses">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="codigo_serial" class="font-weight-bold">Código Serial</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="codigo_serial" name="codigo_serial" value="<?= ($datos_articulo['codigo'] == '') ? '' : $datos_articulo['codigo'] ?>" placeholder="Código Serial">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 text-right">
                                        <button class="btn btn-warning btn-md" type="submit" name="actualizar_datos" onclick="return confirm('¿Está seguro de actualizar el artículo?')">
                                            <i class="fas fa-save"></i>
                                            Actualizar
                                    </div>

                                </div>


                            </form>

                        </div>

                        <div class="col-lg-4">

                            <div class="form-group mt-2">

                                <form method="POST" action="<?= BASE_URL ?>imprimir/codigo">

                                    <input type="hidden" value="<?= $codigo ?>" name="codigo">

                                    <button type="submit" class="btn btn-primary btn-sm mt-4">

                                        <i class="fas fa-barcode"></i>

                                        &nbsp;

                                        Descargar codigo

                                    </button>


                                    <a href="<?= BASE_URL ?>imprimir/listado_hoja_de_vida/hojaDeVida?inventario=<?= base64_encode($id_inventario) ?>" class="btn btn-info btn-sm mt-4">
                                        <i class="fa fa-file-pdf"></i>
                                        &nbsp;
                                        Descargar hoja de vida
                                    </a>


                                    <!-- <a href="<?= PUBLIC_PATH ?>upload/<?= $datos_articulo['evidencia'] ?>" class="btn btn-info btn-sm mt-4 ml-2 <?= $evidencia ?>" target="_blank">

                                        <i class="fa fa-eye"></i>

                                        &nbsp;

                                        Ver evidencia

                                    </a> -->

                                </form>

                            </div>

                        </div>

                        <div class="table-responsive mt-4">

                            <table class="table table-hover border mt-2 table-sm" width="100%" cellspacing="0">

                                <thead>

                                    <tr class="text-center font-weight-bold border">

                                        <td scope="col" colspan="9">Historial de reportes/mantenimientos</td>

                                    </tr>

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

                                        $id_historial    = $historial['id_reporte'];

                                        $usuario         = $historial['usuario'];

                                        $area            = $historial['area'];

                                        $descripcion     = $historial['descripcion'];

                                        $marca           = $historial['marca'];

                                        $reporte         = $historial['estado_nombre'];

                                        $fecha           = $historial['fecha_reporte'];

                                        $observacion     = $historial['observacion_reporte'];

                                        $fecha_respuesta = $historial['fecha_respuesta'];

                                        $id_reporte      = $historial['id_reporte'];

                                        $estado          = $historial['estado_reporte'];



                                        $ver       = ($estado == 3) ? '' : 'd-none';

                                        $fecha_ver = ($estado == 3) ? $fecha_respuesta : $fecha;



                                        if ($fecha_respuesta == '') {

                                            $respuesta = '';
                                        } else {

                                            $fecha_reporte = $instancia_hoja_vida->mostrarFechaReportadoControl($id_reporte);

                                            $datetime1     = new DateTime($fecha_reporte['fechareg']);

                                            $datetime2     = new DateTime($fecha_respuesta);

                                            $interval      = $datetime1->diff($datetime2);

                                            $respuesta     = $interval->format('%d Dias %h Horas %i Minutos %s Segundos');
                                        }



                                    ?>

                                        <tr class="text-center">

                                            <td><?= $usuario ?></td>

                                            <td><?= $area ?></td>

                                            <td><?= $descripcion ?></td>

                                            <td><?= $marca ?></td>

                                            <td><?= $reporte ?></td>

                                            <td><?= $observacion ?></td>

                                            <td><?= $fecha_ver ?></td>

                                            <td><?= $respuesta ?></td>

                                            <?php

                                            if ($perfil_log == 2 || $perfil_log == 1) {

                                            ?>

                                                <td class="<?= $ver ?>">

                                                    <a href="<?= BASE_URL ?>imprimir/solucion?reporte=<?= base64_encode($historial['id_reportado']) ?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte">

                                                        <i class="fa fa-download"></i>

                                                    </a>

                                                </td>

                                            <?php

                                            }

                                            ?>

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
    if (isset($_POST['actualizar_datos'])) {
        $instancia->actualizarDatosInventarioControl();
    }

    include_once VISTA_PATH . 'script_and_final.php';
}
