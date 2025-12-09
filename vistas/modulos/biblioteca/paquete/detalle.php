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

$permisos = $instancia_permiso->permisosUsuarioControl(65, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['paquete'])) {

    $id_paquete = base64_decode($_GET['paquete']);

    $datos_paquete = $instancia->moatrarDatosPaqueteControl($id_paquete);
    $datos_detalle = $instancia->mostrarDetallePaqueteControl($id_paquete);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="<?=BASE_URL?>biblioteca/paquete/index" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Detalles de paquete (<?=$datos_paquete['nombre']?> No. <?=$id_paquete?>)
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id_log" value="<?=$id_log?>">
                            <input type="hidden" name="id_paquete" value="<?=$id_paquete?>">
                            <div class="row p-2">
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Titulo del paquete <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required value="<?=$datos_paquete['nombre']?>" disabled>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Codigo</label>
                                    <input type="text" class="form-control" value="<?=$datos_paquete['codigo']?>" disabled>
                                </div>
                                <div class="col-lg-12 form-group mt-2 text-right">
                                    <div class="btn-group">
                                        <a href="<?=BASE_URL?>imprimir/biblioteca/paquete/codigo?paquete=<?=base64_encode($id_paquete)?>" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fa fa-print"></i>
                                            &nbsp;
                                            Codigo - Paquete
                                        </a>
                                        <a href="<?=BASE_URL?>imprimir/biblioteca/paquete/codigos?paquete=<?=base64_encode($id_paquete)?>" class="btn btn-secondary btn-sm" target="_blank">
                                            <i class="fa fa-print"></i>
                                            &nbsp;
                                            Codigos - Contenido
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 form-group mt-2">
                                    <button class="btn btn-danger btn-sm float-left" type="submit">
                                        <i class="fa fa-trash"></i>
                                        &nbsp;
                                        Eliminar Contenido
                                    </button>
                                    <button class="btn btn-success btn-sm float-right" type="button" data-toggle="modal" data-target="#agregar_contenido">
                                        <i class="fa fa-plus"></i>
                                        &nbsp;
                                        Agregar Contenido
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="text-center font-weight-bold">
                                            <th scope="col" colspan="4">Contenido del paquete</th>
                                        </tr>
                                        <tr class="text-center font-weight-bold">
                                            <th></th>
                                            <th scope="col">Titulo</th>
                                            <th scope="col">Codigo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="buscar">
                                     <?php
                                     foreach ($datos_detalle as $detalle) {
                                        $id_detalle  = $detalle['id'];
                                        $nom_detalle = $detalle['titulo'];
                                        $codigo      = $detalle['codigo'];
                                        ?>
                                        <tr class="text-center">
                                            <td>
                                                <input type="checkbox" value="<?=$id_detalle?>" name="check_detalle[]" class="custom-checkbox check">
                                            </td>
                                            <td><?=$nom_detalle?></td>
                                            <td><?=$codigo?></td>
                                            <td>
                                                <a href="<?=BASE_URL?>imprimir/biblioteca/paquete/codigoDetalle?detalle=<?=base64_encode($id_detalle)?>" class="btn btn-secondary btn-sm" target="_blank">
                                                    <i class="fas fa-barcode"></i>
                                                    &nbsp;
                                                    Codigo
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                           <div class="col-lg-12 form-group text-left mt-2">
                            <button class="btn btn-danger btn-sm" type="submit">
                                <i class="fa fa-trash"></i>
                                &nbsp;
                                Eliminar Contenido
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
include_once VISTA_PATH . 'modulos' . DS . 'biblioteca' . DS . 'paquete' . DS . 'agregarContenido.php';

if (isset($_POST['check_detalle'])) {
    $instancia->inactivarContenidoControl();
}

if (isset($_POST['nom_contenido'])) {
    $instancia->agregarContenidoPaqueteControl();
}

}
