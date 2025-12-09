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
    header('Location:../../login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia = ControlBiblioteca::singleton_biblioteca();

if (isset($_POST['buscar'])) {
    $datos_paquete = '';
} else {
    $datos_paquete = $instancia->mostrarLimitePaquetesControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(65, $perfil_log);
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
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>biblioteca/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Paquetes
                    </h4>
                    <div class="btn-group">
                    <a href="<?=BASE_URL?>biblioteca/paquete/agregar_paquete" class="btn btn-hebreo btn-sm">
                        <i class="fa fa-plus"></i>
                        &nbsp;
                        Agregar nuevo paquete
                    </a>
                    <a href="<?=BASE_URL?>biblioteca/paquete/prestamo" class="btn btn-success btn-sm">
                        <i class="fas fa-hourglass-end"></i>
                        Prestar Paquete
                    </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-8 form-group"></div>
                            <div class="form-group col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
                                    <div class="input-group-append">
                                        <button class="btn btn-hebreo btn-sm" type="submit">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">ID</th>
                                        <th scope="col">Paquete</th>
                                        <th scope="col">Codigo</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar">
                                    <?php
                                    foreach ($datos_paquete as $paquete) {
                                        $id_paquete  = $paquete['id'];
                                        $nom_paquete = $paquete['nombre'];
                                        $codigo      = $paquete['codigo'];
                                        ?>
                                        <tr class="text-center">
                                            <td><?=$id_paquete?></td>
                                            <td><?=$nom_paquete?></td>
                                            <td><?=$codigo?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-info btn-sm" href="<?=BASE_URL?>biblioteca/paquete/detalle?paquete=<?=base64_encode($id_paquete)?>" data-tooltip="tooltip" title="Ver detalles" data-placement="bottom">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?=BASE_URL?>imprimir/biblioteca/paquete/codigo?paquete=<?=base64_encode($id_paquete)?>" class="btn btn-secondary btn-sm" target="_blank" data-tooltip="tooltip" title="Codigo" data-placement="bottom">
                                                        <i class="fas fa-barcode"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>