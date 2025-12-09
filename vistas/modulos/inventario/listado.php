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



$instancia = ControlInventario::singleton_inventario();



if (isset($_POST['buscar'])) {

    $buscar          = $_POST['buscar'];

    $datos_articulos = $instancia->mostrarArticulosBuscarUsuarioControl($id_log, $_POST['buscar']);
} else {

    $buscar          =  '';

    $datos_articulos = $instancia->mostrarArticulosUsuarioControl($id_log);
}



$cantidades = $instancia->mostrarCantidadesControl($id_log);



$ver_confirmar = ($cantidades['cantidad_confirmada'] == $cantidades['cantidad']) ? 'd-none' : '';



$permisos = $instancia_permiso->permisosUsuarioControl(16, $perfil_log);



if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '405.php';

    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0 font-weight-bold text-hebreo">Inventario</h4>
                    <a class="btn btn-success btn-sm <?= $ver_confirmar ?>" href="<?= BASE_URL ?>inventario/confirmar">
                        <i class="fas fa-check-double"></i> Confirmar inventario
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-8"></div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar artículo..." name="buscar">
                                    <div class="input-group-append">
                                        <button class="btn btn-hebreo btn-sm" type="submit">
                                            <i class="fa fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <?php if ($cantidades['cantidad_confirmada'] != $cantidades['cantidad']) : ?>
                        <p class="text-danger mt-2">
                            <b>Advertencia:</b> Algunos artículos están <b>NO CONFIRMADOS</b> o <b>PENDIENTES DE REVISIÓN</b>. Favor confirmar los artículos o comunicarse con el área de <b>SISTEMAS</b>.
                        </p>
                    <?php endif; ?>
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-hover border table-sm">
                            <thead class="text-center text-uppercase bg-light">
                                <tr><th colspan="7">Listado Inventario</th></tr>
                                <tr class="font-weight-bold">
                                    <th>Área</th>
                                    <th>Descripción</th>
                                    <th>Marca</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($datos_articulos as $articulo) : 
                                    $id_usuario = $articulo['id_user'];
                                    ?>
                                    <tr class="text-center">
                                        <td><?= $articulo['area'] ?></td>
                                        <td><?= $articulo['descripcion'] ?></td>
                                        <td><?= $articulo['marca'] ?></td>
                                        <td><?= $articulo['cantidad'] ?></td>
                                        <td><?= $articulo['nombre_estado'] ?></td>
                                        <td>
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal_<?= $articulo['id'] ?>">
                                                <i class="fas fa-clipboard-check"></i>
                                            </button>
                                            <div class="modal fade" id="modal_<?= $articulo['id'] ?>" tabindex="-1" aria-labelledby="modalInventario" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"><?= $articulo['descripcion'] ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <table class="table table-hover border table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Id</th>
                                                                    <th>Descripción</th>
                                                                    <th>Usuario</th>
                                                                    <th>Estado</th>
                                                                    <th>Observacion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <?php
                                                                   $descripcion = $articulo['descripcion'];
                                                                   $idarea = $articulo['id_area'];

                                                                    $datos_inventario = $instancia->mostrarArticulosAgrupadosModalControl($descripcion,$idarea,$id_usuario);

                                                                    foreach($datos_inventario as $dato){
                                                                        $id_inventario_modal = $dato['id'];
                                                                        $descripcion_modal = $dato['descripcion'];
                                                                        $cantidad_modal = $dato['cantidad'];
                                                                        $id_user_modal = $dato['usuario'];
                                                                        $estado_modal = $dato['nombre_estado'];
                                                                        $id_area_modal = $dato['area'];
                                                                        $cantidad_modal = $dato['cantidad'];
                                                                        $obs_report = $dato['observacion'];
                                                                        $span_confirmado_modal = '<span class="badge badge-success">Asignado</span>';
                                                                ?>

                                                                <tr class="">
                                                                    <td><?=$id_inventario_modal?></td>
                                                                    <td><?=$descripcion_modal?></td>
                                                                    <td><?=$id_user_modal?></td>
                                                                    <td>
                                                                        <?php
                                                                            if ($estado_modal == "Mantenimiento Correctivo") {
                                                                                echo "Reportado";
                                                                            } else {
                                                                                echo $estado_modal;
                                                                            }
                                                                            
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <form method="post" onsubmit="return confirmarEnvio(this)">
                                                                            <?php
                                                                                if ($estado_modal == "Mantenimiento Correctivo") {
                                                                                    ?>
                                                                                    <input class="form-control form-control-sm text-center" type="text" disabled value="<?= $obs_report ?>">
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <input class="form-control form-control-sm text-center" name="desc_rep" required type="text" placeholder="Observacion para reporte" name="observacion_reporte">
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                    </td>
                                                                    <td>
                                                                            <?php
                                                                                if ($estado_modal == "Mantenimiento Correctivo") {
                                                                                    ?>
                                                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                                                        <i class="fas fa-clipboard-check"></i>
                                                                                    </button>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                    <input hidden type="text" name="nom_art" value="<?= $descripcion_modal ?>">
                                                                                    <input hidden type="text" name="id_ser" value="<?= $id_user_modal ?>">
                                                                                    <input hidden type="text" name="id_inv" value="<?= $id_inventario_modal ?>">
                                                                                    <button type="submit" class="btn btn-primary btn-sm" name="reportar">
                                                                                        <i class="fas fa-clipboard-check"></i>
                                                                                    </button>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                                <?php }?>
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="button" class="btn btn-primary">Guardar</button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEnvio(form) {
    return confirm("¿Seguro que desea reportar este artículo?");
}
</script>


<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['reportar'])) {
    $id_inv = $_POST['id_inv'];
    $desc_report = $_POST['desc_rep'];
    $nom_art = $_POST['nom_art'];
    $instancia->reportarArticuloListadoControl($id_inv, $desc_report, $id_user_modal, $nom_art, $id_log);
}

if (isset($_POST['id_inventario_trab_home'])) {
    $instancia->trabajoCasaListadoArticuloControl();
}