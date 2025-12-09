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
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia = ControlBiblioteca::singleton_biblioteca();

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
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?=BASE_URL?>biblioteca/paquete/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Agregar Paquete
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Titulo del paquete <span class="text-danger">*</span></label>
                                <input type="text" name="nom_paquete" class="form-control" required>
                            </div>
                            <div class="col-lg-12 form-group text-left mt-2">
                                <button class="btn btn-success btn-sm agregar" type="button">
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
                                        <th scope="col" colspan="2">Contenido del paquete</th>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Titulo</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar">
                                    <tr class="text-center">
                                        <td>
                                            <input type="text" class="form-control" name="nom_contenido[]" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 form-group text-left mt-2">
                            <button class="btn btn-success btn-sm agregar" type="button">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Agregar Contenido
                            </button>
                        </div>
                        <div class="col-lg-12 form-group text-right mt-2">
                            <button class="btn btn-success btn-sm" type="submit">
                                <i class="fa fa-save"></i>
                                &nbsp;
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
    $instancia->guardarPaqueteControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesPaquete.js"></script>