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
require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

$instancia = ControlRecursos::singleton_recursos();

$datos_tramite = $instancia->mostrarTramitesControl();

$permisos = $instancia_permiso->permisosUsuarioControl(76, $perfil_log);
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
                        <a href="<?=BASE_URL?>recursos/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Tramites y servicios
                    </h4>
                    <div class="btn-group">
                        <?php
                        $permisos = $instancia_permiso->permisosUsuarioControl(78, $perfil_log);
                        if ($permisos) {
                            ?>
                            <a href="<?=BASE_URL?>recursos/tramites/misTramites" class="btn btn-secondary btn-sm">
                                <i class="fa fa-eye"></i>
                                &nbsp;
                                Mis tramites
                            </a>
                            <?php
                        }
                        $permisos = $instancia_permiso->permisosUsuarioControl(77, $perfil_log);
                        if ($permisos) {
                            ?>
                            <a href="<?=BASE_URL?>recursos/tramites/listado" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye"></i>
                                &nbsp;
                                Listado de tramites
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="correo" value="<?=$datos_usuario['correo']?>">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?=$nombre_sesion?>" disabled>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?=$datos_usuario['documento']?>" disabled>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?=$datos_usuario['telefono']?>" disabled>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label class="font-weight-bold">Tramite o servicio <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <select name="tramite" class="form-control tipo_tramite" required>
                                        <option value="" selected>Seleccione una opcion...</option>
                                        <?php
                                        foreach ($datos_tramite as $tipo_tramite) {
                                            $id_tipo  = $tipo_tramite['id'];
                                            $nom_tipo = $tipo_tramite['nombre'];
                                            ?>
                                            <option value="<?=$id_tipo?>"><?=$nom_tipo?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row p-2 formulario_tipo ">
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
    $instancia->solicitarTramiteControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/recursos/funcionesRecursos.js"></script>