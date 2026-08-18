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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlEnfermeria::singleton_enfermeria();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_categoria = $instancia->mostrarCategoriasEnfermeriaControl();

$permisos = $instancia_permiso->permisosUsuarioControl(57, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['usuario'])) {

    $id_usuario    = base64_decode($_GET['usuario']);
    $datos_usuario = $instancia_perfil->mostrarDatosPerfilControl($id_usuario);

    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="<?=BASE_URL?>enfermeria/listadoUsuario" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Atencion medica
                        </h4>
                        <div class="btn-group">
                            <a href="<?=BASE_URL?>enfermeria/listado" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Listado de atencion
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id_log" value="<?=$id_log?>">
                            <input type="hidden" name="id_user" id="id_user">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Numero de documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled id="documento" value="<?=$datos_usuario['documento']?>">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Nombre Completo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" disabled>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nivel" disabled>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Curso <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="curso" disabled>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Motivo de consulta <span class="text-danger">*</span></label>
                                    <select class="form-control" name="motivo" id="motivo_consulta" required>
                                        <option value="">Selecciona una opcion...</option>
                                        <?php
                                        foreach ($datos_categoria as $categoria) {
                                            $id_categoria  = $categoria['id'];
                                            $nom_categoria = $categoria['nombre'];
                                            ?>
                                            <option value="<?=$id_categoria?>"><?=$nom_categoria?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Tratamiento <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" name="tratamiento" id="tratamiento_atencion"></textarea>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Envio de correo a padres <span class="text-danger">*</span></label>
                                    <div class="form-inline mt-2">
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" type="radio" name="envio" id="envio1" required value="1" />
                                            <label class="form-check-label" for="envio1"> Envio Inmediato </label>
                                        </div>
                                        <div class="form-check ml-4">
                                            <input class="form-check-input" type="radio" name="envio" id="envio2" required value="2" />
                                            <label class="form-check-label" for="envio2">Envio Programado </label>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                      <p class="text-danger"><span class="font-weight-bold">Nota:</span> Los envios programados se realizaran a partir de las 1 PM del dia actual.</p>
                                  </div>
                              </div>
                              <div class="col-lg-12 form-group text-right mt-2">
                                <button type="reset" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                    &nbsp;
                                    Limpiar
                                </button>
                                <button class="btn btn-success btn-sm" type="submit">
                                    <i class="fa fa-save"></i>
                                    &nbsp;
                                    Guardar
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

if (isset($_POST['motivo'])) {
    $instancia->registrarAtencionMedicaControl();
}

}
?>
<script src="<?=PUBLIC_PATH?>js/enfermeria/funcionesEnfermeria.js"></script>