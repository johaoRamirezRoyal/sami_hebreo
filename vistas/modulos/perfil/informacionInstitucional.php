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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia = ControlPerfil::singleton_perfil();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$niveles_academicos = $instancia->mostrarNivelesAcademicosModel();

//Nivel del usuario y también el perfil: ->
$nivel_usuario = $instancia->mostrarNivelUsuarioModel($id_log);




?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?= BASE_URL ?>perfil/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Información Institucional
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Sección <span class="text-danger">*</span></label>
                                <select name="perfil" class="form-control" disabled>
                                    <option value="">Seleccione una opción ...</option>
                                    <?php
                                    $niveles = $instancia_usuarios->mostrarNivelesUsuarioControl();
                                    foreach ($niveles_academicos as $nivel) {
                                        $id_nivel = $nivel['id'];
                                        $nombre   = $nivel['nombre'];
                                        ?>
                                        <option value="<?= $id_nivel ?>" <?=($id_nivel == $nivel_usuario['id_nivel']) ? 'selected' : '' ?>>
                                            <?= $nombre ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Cargo <span class="text-danger">*</span></label>
                                <select name="nivel" class="form-control" disabled>
                                    <option value="">Seleccione una opción ...</option>
                                    <?php
                                    $perfiles = $instancia->mostrarTodosPerfilesUsuariosControl();
                                    foreach ($perfiles as $perfil) {
                                        $id_perfil = $perfil['id_perfil'];
                                        $nombre   = $perfil['nombre'];
                                        ?>
                                        <option value="<?= $id_perfil ?>" <?=($id_perfil == $nivel_usuario['perfil']) ? 'selected' : '' ?>>
                                            <?= $nombre ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>