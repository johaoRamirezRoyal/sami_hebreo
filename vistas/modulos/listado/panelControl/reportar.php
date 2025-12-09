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

$permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}

$instancia_inventario = ControlInventario::singleton_inventario();

if (isset($_GET['id_inventario'])) {
    $id_inventario = base64_decode($_GET['id_inventario']);
} else {
    include_once VISTA_PATH . 'modulos' . DS . '404.php';
    exit();
}

$info_inventario = $instancia_inventario->mostrarDatosArticuloIdControl($id_inventario);
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
                        Panel de control - Reportar (<?= $info_inventario['descripcion'] . ' - ' . $info_inventario['id'] ?>)
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Código inventario</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['id'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Descripción</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['descripcion'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Marca</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['marca'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Modelo</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['modelo'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Responsable</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['usuario'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Area</label>
                                    <input type="text" class="form-control letras" disabled value="<?= $info_inventario['area'] ?>">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label class="font-weight-bold">Fecha de reporte <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_reporte" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label class="font-weight-bold">Observacion</label>
                                    <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
                                </div>
                                <div class="col-lg-12 text-right">
                                    <input type="hidden" value="<?= $id_log ?>" name="id_log_rep">
                                    <input type="hidden" value="<?= $info_inventario['descripcion'] ?>" name="nom_inventario_rep">
                                    <input type="hidden" value="<?= $info_inventario['id_user'] ?>" name="id_user_rep">
                                    <input type="hidden" value="<?= $info_inventario['id_area'] ?>" name="id_area_rep">
                                    <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario_rep">
                                    <input type="hidden" value="<?= $id_super_empresa ?>" name="super_empresa_rep">
                                    <button class="btn btn-danger btn-sm mt-2" type="submit" name="reportar" onclick="return confirm('¿Está seguro de reportar <?= $info_inventario['descripcion'] ?>?')">
                                        <i class="fas fa-clipboard-check"></i>
                                        Reportar
                                    </button>
                                </div>
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

if (isset($_POST['reportar'])) {
    $instancia_inventario->reportarArticuloIdControl();
}

?>