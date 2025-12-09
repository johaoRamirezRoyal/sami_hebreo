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

$instancia = ControlAreas::singleton_areas();

$datos_areas = $instancia->mostrarAreasControl($id_super_empresa);

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
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Areas
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-hebreo btn-sm" type="button" data-toggle="modal" data-target="#agregar_area">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar Area
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 form-inline">
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control filtro" placeholder="Buscar">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text rounded-right" id="basic-addon1">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Invetario</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_areas as $areas) {
                                    $id_area    = $areas['id'];
                                    $nombre     = $areas['nombre'];
                                    $estado     = $areas['activo'];
                                    $inventario = $areas['inventario'];

                                    $check_si = ($inventario == 1) ? 'checked' : '';
                                    $check_no = ($inventario == 0) ? 'checked' : '';

                                    $ver_activo   = 'd-none';
                                    $ver_inactivo = 'd-none';

                                    if ($estado == 1) {
                                        $ver_activo   = 'd-none';
                                        $ver_inactivo = '';
                                    } else {
                                        $ver_activo   = '';
                                        $ver_inactivo = 'd-none';
                                    }
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$nombre?></td>
                                        <td><?=($inventario == 1) ? 'SI' : 'NO'?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-hebreo btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Editar area" data-toggle="modal" data-target="#editar_area<?=$id_area?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-success btn-sm activar_area <?=$ver_activo?>" id="activar_<?=$id_area?>" data-id="<?=$id_area?>" data-tooltip="tooltip" data-placement="bottom" title="Activar area" data-trigger="hover">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm inactivar_area <?=$ver_inactivo?>" id="inactivar_<?=$id_area?>" data-id="<?=$id_area?>" data-tooltip="tooltip" data-placement="bottom" title="Inactivar area" data-trigger="hover">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>


                                    <!-- Editar Area -->
                                    <div class="modal fade" id="editar_area<?=$id_area?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-hebreo font-weight-bold" id="exampleModalLabel">Editar Area</h5>
                                                </div>
                                                <form method="POST">
                                                    <input type="hidden" name="id_area" value="<?=$id_area?>">
                                                    <div class="modal-body border-0">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Nombre</label>
                                                            <input type="text" class="form-control" required name="nom_edit" maxlength="50" minlength="1" value="<?=$nombre?>">
                                                        </div>
                                                        <div class="col-lg-12 form-group">
                                                            <label class="font-weight-bold">¿Esta area es designada para inventario? <span class="text-danger">*</span></label>
                                                            <div class="form-inline">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" id="si<?=$id_area?>" name="inventario" value="1" <?=$check_si?>>
                                                                    <label class="custom-control-label" for="si<?=$id_area?>">Si</label>
                                                                </div>
                                                                <div class="custom-control custom-radio m-3">
                                                                    <input type="radio" class="custom-control-input" id="no<?=$id_area?>" name="inventario" value="0" <?=$check_no?>>
                                                                    <label class="custom-control-label" for="no<?=$id_area?>">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-hebreo btn-sm">
                                                            <i class="fa fa-edit"></i>
                                                            &nbsp;
                                                            Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

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
include_once VISTA_PATH . 'modulos' . DS . 'areas' . DS . 'agregarArea.php';

if (isset($_POST['nombre'])) {
    $instancia->guradarAreaControl();
}

if (isset($_POST['nom_edit'])) {
    $instancia->editarAreaControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/areas/funcionesArea.js"></script>