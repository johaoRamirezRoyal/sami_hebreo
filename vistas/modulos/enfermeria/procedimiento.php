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
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';

$instancia = ControlEnfermeria::singleton_enfermeria();

if (isset($_POST['buscar'])) {
    $datos_categoria = $instancia->buscarCategoriaControl($_POST['buscar']);
} else {
    $datos_categoria = $instancia->mostrarLimiteCategoriaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(56, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>enfermeria/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Procedimientos
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_categoria">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar Procedimiento
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row p-2">
                            <div class="col-lg-8"></div>
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
                                    <th scope="col">No. Procedimiento</th>
                                    <th scope="col">Nombre</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_categoria as $categoria) {
                                    $id_categoria  = $categoria['id'];
                                    $nom_categoria = $categoria['nombre'];
                                    ?>
                                    <tr class="text-center text-uppercase">
                                        <td><?=$id_categoria?></td>
                                        <td><?=$nom_categoria?></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Eliminar Procedimiento">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>

                        <?php }?>
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
include_once VISTA_PATH . 'modulos' . DS . 'enfermeria' . DS . 'agregarCategoria.php';

if (isset($_POST['nom_categoria'])) {
    $instancia->registrarCategoriaControl();
}
?>