<?php
ini_set('memory_limit', '-1');
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

$datos_perfil    = $instancia_perfil->mostrarPerfilesControl();
$datos_usuario   = $instancia_usuarios->mostrarTodosUsuariosControl();
$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);
$datos_categoria = $instancia->mostrarCategoriasControl($id_super_empresa);

$permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);

if (isset($_POST['area_buscar'])) {

    $area     = $_POST['area_buscar'];
    $usuario  = $_POST['usuario_buscar'];
    $articulo = $_POST['articulo'];

    $datos = array(
        'area'     => $area,
        'usuario'  => $usuario,
        'articulo' => $articulo,
    );

    $buscar = $instancia->buscarInventarioDescontinuadoDetalleControl($datos);
} else {

    $area    = '';
    $usuario = '';

    $buscar = $instancia->mostrarInventarioDescontinuadoDetalleControl();
}

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
                        <a href="<?=BASE_URL?>inventario/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Descontinuados
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select name="area_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Area">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_areas as $areas) {
                                        $id_area = $areas['id'];
                                        $area    = $areas['nombre'];

                                        $ver = ($areas['activo'] == 0) ? 'd-none' : '';
                                        ?>
                                        <option value="<?=$id_area?>" class="<?=$ver?>"><?=$area?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select name="usuario_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Usuario">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_usuario as $usuarios) {
                                        $id_usuario  = $usuarios['id_user'];
                                        $nombre_user = $usuarios['nom_user'];

                                        $ver = ($areas['estado'] == 'inactivo') ? 'd-none' : '';
                                        ?>
                                        <option value="<?=$id_usuario?>" class="<?=$ver?>"><?=$nombre_user?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="articulo">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="submit">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    ?>
                    <div class="table-responsive mt-4">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">CODIGO</th>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">AREA</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">MARCA</th>
                                    <th scope="col">MODELO</th>
                                    <th scope="col">ESTADO</th>
                                    <th scope="col">No. SERIE</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                $url = base64_encode('2');
                                foreach ($buscar as $inventario) {
                                    $id_inventario = $inventario['id'];
                                    $nombre        = $inventario['descripcion'];
                                    $usuario       = $inventario['nom_user'];
                                    $marca         = $inventario['marca'];
                                    $modelo        = $inventario['modelo'];
                                    $estado        = $inventario['estado_nombre'];
                                    $id_area       = $inventario['id_area'];
                                    $area          = $inventario['nom_area'];
                                    $codigo        = $inventario['codigo'];
                                    $id_estado     = $inventario['estado'];
                                    $id_user       = $inventario['id_user'];

                                    $trabajo_casa = '';
                                    $remover_casa = '';
                                    $visible_lib  = '';
                                    $visible_mant = '';
                                    $visible_rep  = '';
                                    $visible_desc = '';
                                    $ver_articulo = '';

                                    $hoja_vida = $nombre;
                                    if ($inventario['confirmado'] == 0) {
                                        $visible_group   = 'd-none';
                                        $span_confirmado = '<span class="badge badge-danger">No Confirmado</span>';
                                    } else if ($inventario['confirmado'] == 1) {
                                        $span_confirmado = '<span class="badge badge-success">Confirmado</span>';
                                    } else if ($inventario['confirmado'] == 2) {
                                        $trabajo_casa    = 'd-none';
                                        $remover_casa    = 'd-none';
                                        $visible_lib     = '';
                                        $visible_mant    = 'd-none';
                                        $visible_rep     = 'd-none';
                                        $span_confirmado = '<span class="badge badge-warning">Pendiente de revision</span>';
                                    }

                                    if ($id_estado == 5) {
                                        ?>
                                        <tr class="text-center <?=$ver_articulo?>">
                                            <td><?=$id_inventario?></td>
                                            <td><?=$usuario?></td>
                                            <td><?=$area?></td>
                                            <td><?=$hoja_vida?></td>
                                            <td><?=$marca?></td>
                                            <td><?=$modelo?></td>
                                            <td><?=$estado?></td>
                                            <td><?=$codigo?></td>
                                            <td><?=$span_confirmado?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?=BASE_URL?>listado/historial?inventario=<?=base64_encode($id_inventario)?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Ver historial">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>


                                        <!-- Liberar inventario -->
                                        <div class="modal fade" id="liberar_inv<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Liberar articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa_lib">
                                                                <input type="hidden" value="<?=$id_log?>" name="id_log_lib">
                                                                <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_lib">
                                                                <input type="hidden" value="<?=$id_user?>" name="id_user_lib">
                                                                <input type="hidden" value="<?=$id_area?>" name="id_area_lib">
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
                                                                <i class="fab fa-telegram-plane"></i>
                                                                &nbsp;
                                                                Liberar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!------------------------------------------------------->




                                        <!-- Descontinuar inventario -->
                                        <div class="modal fade" id="desc_inv<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Descontinuar articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa_desc">
                                                                <input type="hidden" value="<?=$id_log?>" name="id_log_desc">
                                                                <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_desc">
                                                                <input type="hidden" value="<?=$id_user?>" name="id_user_desc">
                                                                <input type="hidden" value="<?=$id_area?>" name="id_area_desc">
                                                                <input type="hidden" value="1" name="inicio">
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
                                                                    <label class="font-weight-bold">Responsable</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">No. Serie</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$codigo?>">
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Observacion</label>
                                                                    <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
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
                                                                <i class="fas fa-minus-circle"></i>
                                                                &nbsp;
                                                                Descontinuar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!------------------------------------------------------->


                                        <!-- Reportar inventario -->
                                        <div class="modal fade" id="rep_inv<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Reportar articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa_rep">
                                                                <input type="hidden" value="<?=$id_inventario?>" name="id_inventario_rep">
                                                                <input type="hidden" value="<?=$id_log?>" name="id_log_rep">
                                                                <input type="hidden" value="<?=$id_user?>" name="id_user_rep">
                                                                <input type="hidden" value="<?=$id_area?>" name="id_area_rep">
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
                                                                    <label class="font-weight-bold">Responsable</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">No. Serie</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$codigo?>">
                                                                </div>
                                                                <div class="col-lg-6 form-group">
                                                                    <label class="font-weight-bold">Fecha de reporte <span class="text-danger">*</span></label>
                                                                    <input type="date" name="fecha_reporte" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Observacion</label>
                                                                    <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
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
                                                                <i class="fas fa-clipboard-check"></i>
                                                                &nbsp;
                                                                Reportar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!------------------------------------------------------->



                                        <!-- Reportar inventario -->
                                        <div class="modal fade" id="trab_home<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
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

/*if (isset($_POST['id_log_lib'])) {
    $instancia->liberarArticuloControl();
}

if (isset($_POST['id_log_desc'])) {
    $instancia->descontinuarArticuloControl();
}

if (isset($_POST['id_inventario_rep'])) {
    $instancia->reportarArticuloControl();
}

if (isset($_POST['id_inventario_trab_home'])) {
    $instancia->trabajoCasaArticuloControl();
}

if (isset($_POST['id_inventario_rem_home'])) {
    $instancia->removerTrabajoCasaArticuloControl();
}*/