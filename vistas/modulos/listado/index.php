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

$instancia         = ControlInventario::singleton_inventario();
$instancia_usuario = ControlUsuarios::singleton_usuarios();
$instancia_areas   = ControlAreas::singleton_areas();

$datos_usuario   = $instancia_usuario->mostrarTodosUsuariosControl();
$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);
$datos_categoria = $instancia->mostrarCategoriasControl($id_super_empresa);

if (isset($_POST['area_buscar'])) {

    $area     = $_POST['area_buscar'];
    $usuario  = $_POST['usuario_buscar'];
    $articulo = $_POST['articulo'];

    $datos = array(
        'area'     => $area,
        'usuario'  => $usuario,
        'articulo' => $_POST['articulo'],
    );

    $datos_articulo = $instancia->buscarInventarioDetalleControl($datos);
} else {

    $area     = '';
    $usuario  = '';
    $articulo = '';

    $datos_articulo = $instancia->mostrarInventarioDetalleControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);
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
                        <a href="<?= BASE_URL ?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Listado
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select name="area_buscar" id="" class="form-control select2" data-tooltip="tooltip" title="Area">
                                    <option value="" selected>Seleccione un area...</option>
                                    <?php
                                    foreach ($datos_areas as $areas) {
                                        $id_area     = $areas['id'];
                                        $nombre_area = $areas['nombre'];

                                        $ver      = ($areas['activo'] == 0) ? 'd-none' : '';
                                        $selected = ($id_area == $area) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id_area ?>" class="<?= $ver ?> text-uppercase" <?= $selected ?>><?= $nombre_area ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select name="usuario_buscar" id="" class="form-control select2" data-tooltip="tooltip" title="Usuario">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_usuario as $usuarios) {
                                        $id_usuario  = $usuarios['id_user'];
                                        $nombre_user = $usuarios['nom_user'];

                                        $ver      = ($areas['estado'] == 'inactivo') ? 'd-none' : '';
                                        $selected = ($id_usuario == $usuario) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id_usuario ?>" class="<?= $ver ?> text-uppercase" <?= $selected ?>><?= $nombre_user ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="articulo" value="<?= $articulo ?>">
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
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">AREA</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">MARCA</th>
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">Inspeccionar</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                $url = base64_encode('1');
                                foreach ($datos_articulo as $inventario) {
                                    $id_inventario = $inventario['id'];
                                    $descripcion   = $inventario['descripcion'];
                                    $marca         = $inventario['marca'];
                                    $modelo        = $inventario['modelo'];
                                    $usuario       = $inventario['nom_user'];
                                    $area          = $inventario['nom_area'];
                                    $estado        = $inventario['estado'];
                                    $observacion   = $inventario['observacion'];
                                    $id_user       = $inventario['id_user'];
                                    $id_area       = $inventario['id_area'];
                                    $precio        = $inventario['precio'];
                                    $cantidad      = $inventario['cantidad'];

                                    $codigo = ($inventario['codigo'] == '') ? $inventario['id'] : $inventario['codigo'];
                                    $hoja_vida = $descripcion;
                                    $ver             = ($estado == 5 || $inventario['confirmado'] == 2) ? 'd-none' : '';
                                    //

                                ?>
                                    <tr class="text-center <?= $ver ?>">
                                        <td><?= $usuario ?></td>
                                        <td><?= $area ?></td>
                                        <td><?= $hoja_vida ?></td>
                                        <td><?= $marca ?></td>
                                        <td><?= $cantidad ?></td>
                                        <!-- Boton de span confirmado para mostrar el estado del inventario! -->
                                        <!-- <td><?= $span_confirmado ?></td> -->
                                        <td>
                                            <div class="align-items-center">
                                                <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#modalInventario_<?= $id_inventario ?>" data-id="<?= base64_encode($id_inventario) ?>">
                                                    Inspeccionar
                                                </button>
                                            </div>

                                            <!-- Model para ver los elementos del inventario de manera individual -->
                                            <div class="modal fade" id="modalInventario_<?= $id_inventario ?>" tabindex="-1" role="dialog" aria-labelledby="modalInventario">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-tittle font-weight-bold" id="modalTitulo">Inventario: <?= $descripcion ?></h2>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive mt-2">
                                                                <table class="table table-hover border table-sm" width="100%" cellspacing="1">
                                                                    <thead>
                                                                        <tr class="text-center font-weight-bold">
                                                                            <th>ID inventario</th>
                                                                            <th>Descripción</th>
                                                                            <th>Usuario</th>
                                                                            <th>Area</th>
                                                                            <th>Estado</th>
                                                                            <th>Historial</th>
                                                                            <th>Acciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $datos_inventario = $instancia->obtenerInventarioDesagrupadoControl($descripcion, $id_area, $id_user);
                                                                        foreach ($datos_inventario as $dato) {

                                                                            $id_inventario_modal = $dato['id'];
                                                                            $descripcion_modal = $dato['descripcion'];
                                                                            $marca_modal = $dato['marca'];
                                                                            $modelo_modal = $dato['modelo'];
                                                                            $usuario_modal = $dato['nom_user'];
                                                                            $area_modal = $dato['nom_area'];
                                                                            $estado_modal = $dato['estado'];
                                                                            $estado_nombre = $dato['estado_nombre'];
                                                                            $observacion_modal = $dato['observacion'];
                                                                            $id_user_modal = $dato['id_user'];
                                                                            $id_area_modal = $dato['id_area'];
                                                                            $precio_modal = $dato['precio'];
                                                                            $cantidad_modal = $dato['cantidad'];
                                                                            $span_confirmado_modal = '<span class="badge badge-success">Asignado</span>';
                                                                            $reporte_inv = $instancia->obtenerReporteDeInventarioControl($id_inventario_modal);
                                                                            $id_reporte_inv = $reporte_inv['id'];
                                                                            if ($dato['estado'] != 1) {
                                                                                if ($dato['estado'] == 2) {
                                                                                    $span_confirmado_modal = '<span class="badge badge-warning">Mantenimiento correctivo</span>';
                                                                                } elseif ($dato['estado'] == 3) {
                                                                                    $span_confirmado_modal = '<span class="badge badge-info">Solucionado</span>';
                                                                                } else {
                                                                                    $span_confirmado_modal = '<span class="badge badge-info">' . $estado_nombre . '</span>';
                                                                                }
                                                                            }
                                                                            if ($estado_modal == 6 || $estado_modal == 2) {
                                                                                $visible_group     = 'd-none';
                                                                                $visible_descargar = '';
                                                                                $remover_casa      = 'd-none';
                                                                            } else {
                                                                                $visible_group     = '';
                                                                                $visible_descargar = 'd-none';
                                                                                $remover_casa      = 'd-none';
                                                                            }

                                                                            if ($estado_modal == 5) {
                                                                                $ver_articulo = 'd-none';
                                                                            }

                                                                            if ($estado_modal == 4) {
                                                                                $visible_lib  = 'd-none';
                                                                                $visible_mant = 'd-none';
                                                                                $visible_rep  = 'd-none';
                                                                                $visible_desc = '';
                                                                                $remover_casa = 'd-none';
                                                                                $trabajo_casa = '';
                                                                            }

                                                                            if ($estado_modal == 1) {
                                                                                $visible_group = '';
                                                                                $visible_lib   = '';
                                                                                $visible_mant  = '';
                                                                                $visible_rep   = '';
                                                                                $visible_desc  = '';
                                                                                $remover_casa  = 'd-none';
                                                                                $trabajo_casa  = '';
                                                                            }

                                                                            if ($estado_modal == 8) {
                                                                                $trabajo_casa = 'd-none';
                                                                                $remover_casa = '';
                                                                                $visible_lib  = '';
                                                                                $visible_mant = '';
                                                                                $visible_rep  = '';
                                                                                $visible_desc = '';
                                                                            }



                                                                            if ($estado_modal == 9) {
                                                                                $trabajo_casa = '';
                                                                                $remover_casa = 'd-none';
                                                                                $visible_lib  = '';
                                                                                $visible_mant = '';
                                                                                $visible_rep  = '';
                                                                                $visible_desc = '';
                                                                            }

                                                                            if ($inventario['confirmado'] == 0) {
                                                                                $visible_group   = 'd-none';
                                                                                $span_confirmado = '<span class="badge badge-danger">No Confirmado</span>';
                                                                            } else if ($inventario['confirmado'] == 1) {
                                                                                $span_confirmado = '<span class="badge badge-success">Confirmado</span>';
                                                                            } else if ($inventario['confirmado'] == 2) {
                                                                                $trabajo_casa    = 'd-none';
                                                                                $remover_casa    = 'd-none';
                                                                                $visible_lib     = '';
                                                                                $visible_mant    = 'd-none';
                                                                                $visible_rep     = 'd-none';
                                                                                $span_confirmado = '<span class="badge badge-warning">Pendiente de revision</span>';
                                                                            }
                                                                        ?>

                                                                            <tr class="">
                                                                                <td><?= $id_inventario_modal ?></td>
                                                                                <td><?= $descripcion_modal ?></td>
                                                                                <td><?= $usuario_modal ?></td>
                                                                                <td><?= $area_modal?></td>
                                                                                <td><?= $span_confirmado_modal?></td>
                                                                                <td>
                                                                                    <a href="<?= BASE_URL ?>listado/historial?inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-info btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Ver historial">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                </td>
                                                                                <td class="<?=$visible_group?>">
                                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                                        <a href="<?= BASE_URL ?>listado/panelControl/liberar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-success btn-sm <?=$visible_lib?>" data-tooltip="tooltip" data-placement="bottom" title="Liberar">
                                                                                            <i class="fab fa-telegram-plane"></i>
                                                                                        </a>
                                                                                        <a href="<?= BASE_URL?>listado/panelControl/reportar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-danger btn-sm <?=$visible_rep?>" data-tooltip="tooltip" data-placement="bottom" title="Reportar">
                                                                                            <i class="fas fa-clipboard-check"></i>
                                                                                        </a>
                                                                                        <a href="<?=BASE_URL?>listado/panelControl/descontinuar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-sm btn-warning <?=$visible_desc?>" data-tooltip="tooltip" data-placement="bottom" title="Descontinuar">
                                                                                            <i class="fas fa-minus-circle"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="<?=$visible_descargar?>">
                                                                                    <a href="<?=BASE_URL?>imprimir/reporte?id=<?=base64_encode($id_reporte_inv)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte">
                                                                                        <i class="fa fa-download"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                    </tbody>
                                                                <?php } ?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Botón que redirigue al historial del inventario -->
                                            <!-- <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= BASE_URL ?>listado/historial?inventario=<?= base64_encode($id_inventario) ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Ver historial">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div> -->
                                        </td>
                                    </tr>

                                <?php } ?>
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
