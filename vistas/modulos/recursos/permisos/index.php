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



$datos_motivos = $instancia->mostrarMotivosPermisoControl();

$datos_tipo    = $instancia->mostrarTipoPermisoControl();


$motivos_personales = $instancia->mostrarPermisosPersonalesControl();
$motivos_legales = $instancia->mostrarPermisosLegalesControl();
$datos_motivos_institucion = $instancia->mostrarPermisosInstitucionalesControl();


$permisos = $instancia_permiso->permisosUsuarioControl(80, $perfil_log);

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

                        Permisos/Licencias

                    </h4>

                    <div class="btn-group">

                        <?php

                        $permisos = $instancia_permiso->permisosUsuarioControl(82, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a href="<?=BASE_URL?>recursos/permisos/misSolicitudes" class="btn btn-secondary btn-sm">

                                <i class="fa fa-eye"></i>

                                &nbsp;

                                Mis solicitudes

                            </a>

                            <?php

                        }

                        $permisos = $instancia_permiso->permisosUsuarioControl(81, $perfil_log);

                        if ($permisos) {

                            ?>

                            <a href="<?=BASE_URL?>recursos/permisos/listado" class="btn btn-primary btn-sm">

                                <i class="fa fa-eye"></i>

                                &nbsp;

                                Listado de solicitudes

                            </a>

                            <?php

                        }

                        ?>

                    </div>

                </div>

                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id_log" value="<?=$id_log?>">

                        <input type="hidden" name="nivel" value="<?=$nivel?>">

                        <input type="hidden" name="perfil" value="<?=$perfil_log?>">

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

                                <label class="font-weight-bold">Motivo del permiso <span class="text-danger">*</span></label>

                                <select name="motivo_permiso" class="form-control" id="motivo_permiso" required>

                                    <option value="" selected>Seleccione una opcion...</option>

                                    <?php

                                    foreach ($datos_motivos as $motivo) {

                                        $id_motivo  = $motivo['id'];

                                        $nom_motivo = $motivo['nombre'];

                                        ?>

                                        <option value="<?=$id_motivo?>"><?=$nom_motivo?></option>

                                    <?php }?>

                                </select>

                            </div>

                            <div class="col-lg-4 form-group" id="select_ley_container" style="display: none;">
                                <label class="font-weight-bold">Selecciona el motivo legal: <span class="text-danger">*</span></label>
                                <select name="permiso_ley" class="form-control">
                                    <option value="">Seleccione una opción...</option>
                                    <?php 
                                    foreach ($motivos_legales as $motivo_ley){
                                        $id = $motivo_ley['id'];
                                        $nombre = $motivo_ley['nombre_permiso'];
                                        ?>
                                        <option value="<?=$nombre?>"><?=$nombre?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group" id="select_personal_container" style="display: none;">
                                <label class="font-weight-bold">Selecciona el motivo personal: <span class="text-danger">*</span></label>
                                <select name="permiso_personal" class="form-control">
                                    <option value="">Seleccione una opción...</option>
                                    <?php
                                    foreach ($motivos_personales as $motivo_personal){
                                        $id = $motivo_personal['id'];
                                        $nombre = $motivo_personal['nombre_permiso'];
                                        ?>
                                        <option value="<?=$nombre?>"><?=$nombre?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group" id="select_institucion_container" style="display: none;">
                                <label class="font-weight-bold">Selecciona el motivo institucional: <span class="text-danger">*</span></label>
                                <select name="permiso_institucion" class="form-control">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_motivos_institucion as $motivo_institucion){
                                        $id = $motivo_institucion['id'];
                                        $nombre = $motivo_institucion['nombre_permiso'];
                                        ?>
                                        <option value="<?=$nombre?>"><?=$nombre?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Tipo de permiso <span class="text-danger">*</span></label>

                                <select name="tipo_permiso" class="form-control tipo_permiso" required>

                                    <option value="" selected>Seleccione una opcion...</option>

                                    <?php

                                    foreach ($datos_tipo as $tipo) {

                                        $id_tipo  = $tipo['id'];

                                        $nom_tipo = $tipo['nombre'];

                                        ?>

                                        <option value="<?=$id_tipo?>"><?=$nom_tipo?></option>

                                    <?php }?>

                                </select>

                            </div>

                        </div>

                        <div class="row p-2 formulario_permiso">



                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<script>
    $(document).ready(function() {
        $('#motivo_permiso').on('change', function() {
            let selectedValue = $(this).val();
            
            if (selectedValue == '3') {
                $('#select_ley_container').show();
            } else {
                $('#select_ley_container').hide();
            }
        });
    });

    $(document).ready(function(){
        $('#motivo_permiso').on('change', function(){
            let selectedValue = $(this).val();
            
            if (selectedValue == '1') {
                $('#select_personal_container').show();
            } else {
                $('#select_personal_container').hide();
            }
        })
    })

    $(document).ready(function(){
        $('#motivo_permiso').on('change', function(){
            let selectedValue = $(this).val();
            
            if (selectedValue == '6') {
                $('#select_institucion_container').show();
            } else {
                $('#select_institucion_container').hide();
            }
        })
    })

</script>

<?php

include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {

    $instancia->solicitarPermisoControl();

}

?>

<script src="<?=PUBLIC_PATH?>js/recursos/funcionesRecursos.js"></script>