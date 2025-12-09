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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$instancia          = ControlInventario::singleton_inventario();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();
$instancia_areas    = ControlAreas::singleton_areas();

$datos_perfil  = $instancia_perfil->mostrarPerfilesControl();
$datos_usuario = $instancia_usuarios->mostrarTodosUsuariosControl();
$datos_areas   = $instancia_areas->mostrarAreasControl($id_super_empresa);

$datos_material = $instancia->mostrarMaterialDidacticoControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl(18, $perfil_log);

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
                        <a href="<?=BASE_URL?>configuracion/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Material did&aacute;ctico
                    </h4>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                            <div class="dropdown-header">Acciones:</div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#agregar_material">Agregar articulo</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <select name="area" class="form-control filtro_change select2" data-tooltip="tooltip" title="Area">
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_areas as $area) {
                                    $id_area     = $area['id'];
                                    $nombre_area = $area['nombre'];
                                    $estado      = $area['activo'];

                                    $ver = ($estado == 1) ? '' : 'd-none';
                                    ?>
                                    <option value="<?=$nombre_area?>" <?=$ver?>><?=$nombre_area?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <select name="usuario" class="form-control filtro_change select2" data-tooltip="tooltip" title="Usuario">
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_usuario as $usuario) {
                                    $id_usuario      = $usuario['id_user'];
                                    $nombre_completo = $usuario['nom_user'];
                                    $estado          = $usuario['estado'];

                                    $ver = ($estado == 'activo') ? '' : 'd-none';
                                    ?>
                                    <option value="<?=$nombre_completo?>" <?=$ver?>><?=$nombre_completo?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control filtro" placeholder="Buscar...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">No. serie</th>
                                    <th scope="col">Observacion</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-lowercase">
                                <?php
                                $sum  = 0;
                                $trab = 0;
                                foreach ($datos_material as $material) {
                                    $id_inventario = $material['id'];
                                    $descripcion   = $material['descripcion'];
                                    $codigo        = $material['codigo'];
                                    $usuario       = $material['usuario'];
                                    $area          = $material['area'];
                                    $observacion   = $material['observacion'];
                                    $cantidad      = $material['cantidad'];
                                    $id_user       = $material['id_user'];
                                    $id_area       = $material['id_area'];

                                    if ($material['estado'] == 8) {
                                        $span         = '<span class="badge badge-primary">Trabajo en casa</span>';
                                        $remover_trab = '';
                                        $trab_casa    = 'd-none';
                                        $trab += $cantidad;
                                    }

                                    if ($material['estado'] != 8) {
                                        $span         = '';
                                        $remover_trab = 'd-none';
                                        $trab_casa    = '';
                                    }

                                    $sum += $cantidad;

                                    ?>
                                    <tr class="text-center text-uppercase">
                                        <td><?=$id_inventario?></td>
                                        <td><?=$descripcion?></td>
                                        <td><?=$cantidad?></td>
                                        <td><?=$usuario?></td>
                                        <td><?=$area?></td>
                                        <td><?=$codigo?></td>
                                        <td><?=$observacion?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm <?=$trab_casa?>" data-tooltip="tooltip" data-placement="bottom" title="Trabajo en casa" data-toggle="modal" data-target="#trab_home<?=$id_inventario?>">
                                                <i class="fas fa-briefcase"></i>
                                            </button>
                                            <?=$span?>
                                        </td>
                                    </tr>


                                    <!-- Reportar inventario -->
                                    <div class="modal fade" id="trab_home<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Trabajo en casa</h5>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body border-0">
                                                        <div class="row p-2">
                                                            <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa_trab_home">
                                                            <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_trab_home">
                                                            <input type="hidden" value="<?=$id_log?>" name="id_log_trab_home">
                                                            <input type="hidden" value="<?=$id_user?>" name="id_user_trab_home">
                                                            <input type="hidden" value="<?=$id_area?>" name="id_area_trab_home">
                                                            <div class="form-group col-lg-6">
                                                                <label>Area</label>
                                                                <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$area?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Descripcion</label>
                                                                <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$descripcion?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Responsable</label>
                                                                <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>No. Serie</label>
                                                                <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$codigo?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                            <i class="fa fa-times"></i>
                                                            &nbsp;
                                                            Cerrar
                                                        </button>
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-briefcase"></i>
                                                            &nbsp;
                                                            Aceptar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!------------------------------------------------------->

                                    <?php
                                }
                                ?>
                            </tbody>
                            <div class="form-inline">
                                <h6 class="text-primary ml-4 mb-2">Cantidad de articulos: <span class="text-dark"><?=$sum?></span></h6>
                                <h6 class="text-danger ml-4 mb-2">Cantidad de articulos (trabajo en casa): <span class="text-dark"><?=$trab?></span></h6>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'material' . DS . 'agregar.php';

if (isset($_POST['cantidad'])) {
    $instancia->agregarMaterialControl();
}

if (isset($_POST['id_inventario_trab_home'])) {
    $instancia->trabajoCasaMaterialControl();
}
