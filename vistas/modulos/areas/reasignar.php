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
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlAreas::singleton_areas();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_areas   = $instancia->mostrarAreasControl($id_super_empresa);
$datos_usuario = $instancia_usuario->mostrarTodosUsuariosControl();

$permisos = $instancia_permiso->permisosUsuarioControl(4, $perfil_log);

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
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Re-asignar area
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="font-weight-bold">Area <spa class="text-danger">*</spa></label>
                                <select name="id_area" id="id_area" required class="form-control select2">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_areas as $areas) {
                                        $id_area = $areas['id'];
                                        $nombre  = $areas['nombre'];
                                        $estado  = $areas['activo'];

                                        $ver = ($estado == 1) ? '' : 'd-none';
                                        ?>
                                        <option value="<?=$id_area?>" class="<?=$ver?>"><?=$nombre?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="font-weight-bold">Usuario responsable <spa class="text-danger">*</spa></label>
                                <input type="text" disabled id="usuario" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label class="font-weight-bold">Usuario a asignar <spa class="text-danger">*</spa></label>
                                <select name="usuario" required class="form-control select2">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_usuario as $usuarios) {
                                        $id_user         = $usuarios['id_user'];
                                        $nombre_completo = $usuarios['nom_user'];
                                        $estado          = $usuarios['estado'];

                                        $ver = ($estado == 'activo') ? '' : 'd-none';

                                        ?>
                                        <option value="<?=$id_user?>" class="<?=$ver?>"><?=$nombre_completo?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <button class="btn btn-primary btn-sm float-right">
                                    <i class="fa fa-plus"></i>
                                    &nbsp;
                                    Asignar
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
require_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_area'])) {
    $instancia->asignarAreaControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/areas/funcionesArea.js"></script>