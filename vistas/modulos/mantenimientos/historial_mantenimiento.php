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

//require_once CONTROL_PATH . 'mantenimiento' . DS . 'MantenimientoProgContr.php';

require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';



$instancia = ControlInventario::singleton_inventario();

//$products = $instancia->getProducts();


if (isset($_POST['buscar'])) {
    /*
        if(isset($_POST['periodo'])){
            $period=$_POST['periodo'];
        }else{
            $period="";
        }
        */

    $data = array('anio' => $_POST['anio'], 'periodo' => $_POST['periodo']);
    $historial = $instancia->getHistorialMantenimientoBusquedaModel();

    //$getProgramacion = $instancia->getProgramacionSearch($data);


} else {
    $historial = $instancia->getHistorialMantenimiento();
    //$getProgramacion = $instancia->getProgramacion();

}



$permisos = $instancia_permiso->permisosUsuarioControl(2, $perfil_log);

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

                        <a href="<?= BASE_URL ?>mantenimientos/index" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Historial mantenimientos

                    </h4>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="row">
                            <!--

                            <div class="col-lg-3">
                                <div class="form-group">

                                    <label class="font-weight-bold">Articulo <span class="text-danger"></span></label>

                                    <select name="anio" required>
                                        <option value="" selected disabled hidden>Seleccione una
                                            opcion</option>
                                        <option value="2023/2024">2023/2024</option>
                                        <option value="2024/2025">2024/2025</option>
                                        <option value="2025/2026">2025/2026</option>
                                        <option value="2026/2027">2026/2027</option>
                                        <option value="2027/2028">2027/2028</option>
                                        <option value="2028/2029">2028/2029</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 form-group">

                                <label class="font-weight-bold">Año<span class="text-danger"></span></label>
                                <select name="periodo">
                                    <option value="" selected disabled hidden>Seleccione una
                                        opcion</option>
                                    <option value="1">Periodo 1</option>
                                    <option value="2">Periodo 2</option>
                                </select>

                            </div>

                            -->
                            
                            <div class="col-lg-3 form-group"></div>
                            <div class="col-lg-3 form-group"></div>
                            <div class="col-lg-3 form-group"></div>

                            <div class="col-lg-3 form-group">
                                <label class="font-weight-bold">Busqueda<span class="text-danger"></span></label>

                                <div class="input-group">

                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">

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
                                        $id = $getHistorial['id'];
                                        $articulo = $getHistorial['articulo'];
                                        $tipo = $getHistorial['tipo'];
                                        $descripcion = $getHistorial['descripcion'];
                                        $estado = $getHistorial['estado'];
                                        $fecha = $getHistorial['fecha'];

                                        if ($estado == 3) {
                                            $span_estado = '<span class="badge badge-success">Realizado</span>';
                                        }
                                ?>
                                        <tr class="text-center">
                                            <td><?= $id ?></td>
                                            <td><?= $articulo ?></td>
                                            <td><?= $tipo ?></td>
                                            <td><?= $descripcion ?></td>
                                            <td><?= $fecha ?></td>
                                            <td><?= $span_estado ?></td>
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

include_once VISTA_PATH . 'script_and_final.php';

include_once VISTA_PATH . 'modulos' . DS . 'usuarios' . DS . 'agregarUsuario.php';


?>