<?php

require_once CONTROL_PATH . 'Session.php';

$objss = new Session;

$objss->iniciar();

if (!$_SESSION['rol']) {

    $er = '2';

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

require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

require_once CONTROL_PATH . 'categorias' . DS . 'ControlCategorias.php';



$instancia = ControlInventario::singleton_inventario();
$instancia_categoria = ControlCategorias::singleton_categorias();

//$products = $instancia->getProducts();


if (isset($_POST['buscar'])) {
    $datos = array(
        'anio_inicio' => (isset($_POST['anio_inicio'])) ? $_POST['anio_inicio'] : '',
        'anio_final' => (isset($_POST['anio_final'])) ? $_POST['anio_final'] : '',
        'categoria' => (isset($_POST['categoria'])) ? $_POST['categoria'] : '',
    );

    $historial = $instancia->getHistorialMantenimientoFiltradoControl($datos);

} else {
    $historial = $instancia->getHistorialMantenimientoControl();
}


$permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);

if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();

}

$datos_categorias = $instancia->mostrarCategoriasControl($id_super_empresa);
?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow-sm mb-4">

                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                    <h4 class="m-0 font-weight-bold text-primary">

                        <a href="<?= BASE_URL ?>mantenimientos/index" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Historial mantenimientos

                    </h4>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="row align-items-between">

                            <div class="col-lg-3 form-group">

                                <input type="date" name="anio_inicio" class="form-control">

                            </div>
                            <div class="col-lg-3 form-group">
                                <input type="date" name="anio_final" class="form-control">
                            </div>

                            <div class="col-lg-3 form-group">
                                <select name="categoria" id="categoria" class="form-control">
                                    <option value="" disabled selected>Seleccione una categoria</option>
                                    <?php foreach($datos_categorias as $categoria): ?>
                                    <option value="<?=$categoria['id']?>"><?=$categoria['nombre']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-lg-3 form-group text-right">
                                <button class="btn btn-md btn-info" name="buscar">
                                    <i class="fa fa-filter" aria-hidden="true"></i>
                                    &nbsp;
                                    Filtrar
                                </button>
                            </div>

                        </div>

                    </form>

                    <div class="table-responsive mt-2">

                        <table id="myTable" class="table table-striped" width="100%" cellspacing="0">

                            <thead>

                                <tr class="text-center font-weight-bold">

                                    <th scope="col">Id</th>

                                    <th scope="col">Articulo</th>

                                    <th scope="col">Tipo</th>

                                    <th scope="col">Descripcion</th>

                                    <th scope="col">Fecha</th>

                                    <th scope="col">Estado</th>

                                    

                                </tr>

                            </thead>

                            <tbody class="buscar">

                                <?php

                                if ($historial) {

                                    foreach ($historial as $getHistorial) {

                                        $id_inventario = $getHistorial['id_inventario'];

                                        $inventario = $getHistorial['inventario'];

                                        $tipo = $getHistorial['observacion'];

                                        $descripcion = $getHistorial['descripcion'];

                                        $estado = $getHistorial['estado'];

                                        $fecha = $getHistorial['fechareg'];

                                        $periodo = 'No información';


                                        if ($estado == 6) {

                                            $span_estado = '<span class="badge badge-warning">Pendiente</span>';

                                        } else if($estado == 3) {

                                            $span_estado = '<span class="badge badge-success">Realizado</span>';

                                        } else {
                                            $span_estado = '<span class="badge badge-info">... </span>';
                                        }
                                       


                                        ?>

                                        <tr class="text-center">

                                            <td>
                                                <?= $id_inventario?>
                                            </td>

                                            <td>
                                                <?= $inventario ?>
                                            </td>

                                            <td>
                                                <?= $tipo ?>
                                            </td>


                                            <td>
                                                <?= $descripcion ?>
                                            </td>

                                            <td>
                                                <?= $fecha  ?>
                                            </td>

                                            <td>
                                                <?= $span_estado ?>
                                            </td>

                                        </tr>

                                        <?php

                                    }

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

<!--Agregar programacion-->

<?php

if(isset($_POST['btn_generar_indicador'])){
    $instancia->generarIndicadorDeGestiónControl();
}


include_once VISTA_PATH . 'script_and_final.php';

include_once VISTA_PATH . 'modulos' . DS . 'usuarios' . DS . 'agregarUsuario.php';



?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>