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

$permisos = $instancia_permiso->permisosUsuarioControl(17, $perfil_log);

$datos_articulos = $instancia->trabajoCasaControl($id_super_empresa);
$datos_usuario   = $instancia_usuarios->mostrarTodosUsuariosControl();
$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);

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
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Trabajo en casa
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <select class="form-control filtro_change select2" data-tooltip="tooltip" title="Area">
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_areas as $area) {
                                    $id_area = $area['id'];
                                    $nombre  = $area['nombre'];
                                    $estado  = $area['activo'];

                                    $ver = ($estado == 1) ? '' : 'd-none';
                                    ?>
                                    <option value="<?=$nombre?>" <?=$ver?>><?=$nombre?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <select class="form-control filtro_change select2" data-tooltip="tooltip" title="Usuario">
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_usuario as $usuario) {
                                    $id_usuario = $usuario['id_user'];
                                    $nombre_com = $usuario['nom_user'];
                                    $estado     = $usuario['estado'];

                                    $ver = ($estado == 'activo') ? '' : 'd-none';
                                    ?>
                                    <option value="<?=$nombre_com?>" <?=$ver?>><?=$nombre_com?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control filtro" placeholder="Buscar..." data-tooltip="tooltip" title="Otro">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">ID</th>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">AREA</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">MARCA</th>
                                    <th scope="col">MODELO</th>
                                    <th scope="col">CODIGO</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-lowercase">
                                <?php
                                foreach ($datos_articulos as $articulo) {
                                    $id_inventario = $articulo['id'];
                                    $descripcion   = $articulo['descripcion'];
                                    $marca         = $articulo['marca'];
                                    $modelo        = $articulo['modelo'];
                                    $usuario       = $articulo['usuario'];
                                    $area          = $articulo['area'];
                                    $codigo        = $articulo['codigo'];
                                    $estado        = $articulo['nombre_estado'];
                                    $id_estado     = $articulo['estado'];
                                    $observacion   = $articulo['observacion'];
                                    $id_area       = $articulo['id_area'];
                                    $id_user       = $articulo['id_user'];

                                    if ($articulo['id_categoria'] == 1) {
                                        $hoja_vida = '<a href="' . BASE_URL . 'hoja_vida/index?inventario=' . base64_encode($id_inventario) . '">' . $descripcion . '</a>';
                                    } else {
                                        $hoja_vida = $descripcion;
                                    }

                                    if ($id_estado == 8) {
                                        ?>
                                        <tr class="text-center text-uppercase">
                                            <td><?=$id_inventario?></td>
                                            <td><?=$usuario?></td>
                                            <td><?=$area?></td>
                                            <td><?=$hoja_vida?></td>
                                            <td><?=$marca?></td>
                                            <td><?=$modelo?></td>
                                            <td><?=$codigo?></td>
                                            <td>
                                                <button class="btn btn-secondary btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Remover trabajo en casa" data-toggle="modal" data-target="#rem_home<?=$id_inventario?>">
                                                    <i class="fas fa-briefcase"></i>
                                                </button>
                                            </td>

                                            <!-- Remover Trabajo en casa -->
                                            <div class="modal fade" id="rem_home<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Remover trabajo en casa</h5>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="modal-body border-0">
                                                                <div class="row p-2">
                                                                    <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa_rem_home">
                                                                    <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_rem_home">
                                                                    <input type="hidden" value="<?=$id_log?>" name="id_log_rem_home">
                                                                    <input type="hidden" value="<?=$id_user?>" name="id_user_rem_home">
                                                                    <input type="hidden" value="<?=$id_area?>" name="id_area_rem_home">
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Area</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$area?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Descripcion</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$nombre?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Marca</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$marca?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Modelo</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$modelo?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Codigo</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$codigo?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">Responsable</label>
                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <label class="font-weight-bold">No. Serie</label>
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
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
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

if (isset($_POST['id_inventario_rem_home'])) {
    $instancia->removerTrabajoCasaArticuloControl();
}