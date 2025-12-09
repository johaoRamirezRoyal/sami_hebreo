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



$instancia         = ControlInventario::singleton_inventario();

$instancia_usuario = ControlUsuarios::singleton_usuarios();

$instancia_areas   = ControlAreas::singleton_areas();



$datos_articulos = $instancia->mostrarArticulosLiberadosControl();


$datos_usuario   = $instancia_usuario->mostrarTodosUsuariosControl();

$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);



$permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);



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

                        Re-asignar equipo

                    </h4>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-8"></div>

                        <div class="col-lg-4 mt-3">

                            <form>

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

                            </form>

                        </div>

                        <div class="table-responsive mt-4">

                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">

                                <thead>

                                    <tr class="text-center font-weight-bold">

                                        <th scope="col">ID</th>

                                        <th scope="col">AREA</th>

                                        <th scope="col">DESCRIPCION</th>

                                        <th scope="col">MARCA</th>

                                        <th scope="col">MODELO</th>

                                        <th scope="col">CODIGO</th>

                                        <th scope="col">ESTADO</th>

                                    </tr>

                                </thead>

                                <tbody class="buscar text-uppsercase">

                                    <?php

                                    foreach ($datos_articulos as $articulo) {

                                        $id_inventario = $articulo['id'];

                                        $descripcion   = $articulo['descripcion'];

                                        $marca         = $articulo['marca'];

                                        $modelo        = $articulo['modelo'];

                                        $area          = $articulo['area'];

                                        $codigo        = $articulo['codigo'];

                                        $estado        = $articulo['nombre_estado'];



                                        $liberado   = $instancia->buscarReporteLiberadoControl($id_inventario);

                                        $id_reporte = $liberado['id'];



                                        ?>

                                        <tr class="text-center">

                                            <td><?=$id_inventario?></td>

                                            <td><?=$area?></td>

                                            <td><?=$descripcion?></td>

                                            <td><?=$marca?></td>

                                            <td><?=$modelo?></td>

                                            <td><?=$codigo?></td>

                                            <td><?=$estado?></td>

                                            <td>

                                                <button class="btn btn-success btn-sm" data-tooltip="tooltip" title="Asignar" data-placement="bottom" data-toggle="modal" data-target="#asignar_art<?=$id_inventario?>">

                                                    <i class="fa fa-plus"></i>

                                                </button>

                                            </td>

                                        </tr>



                                        <!-- Reasignar articulo -->

                                        <div class="modal fade" id="asignar_art<?=$id_inventario?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                            <div class="modal-dialog modal-dialog-md" role="document">

                                                <div class="modal-content">

                                                    <div class="modal-header">

                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Reasignar Articulo</h5>

                                                    </div>

                                                    <form method="POST">

                                                        <div class="modal-body border-0">

                                                            <input type="hidden" value="<?=$id_inventario?>" name="id_inventario">

                                                            <input type="hidden" value="<?=$id_log?>" name="id_log">

                                                            <input type="hidden" value="<?=$id_super_empresa?>" name="id_super_empresa">

                                                            <input type="hidden" value="<?=$id_reporte?>" name="id_reporte">

                                                            <div class="row p-2">

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Descripcion <span class="text-danger">*</span></label>

                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$descripcion?>">

                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Marca <span class="text-danger">*</span></label>

                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$marca?>">

                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Modelo <span class="text-danger">*</span></label>

                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$modelo?>">

                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Codigo <span class="text-danger">*</span></label>

                                                                        <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$codigo?>">

                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>

                                                                        <select name="id_user" class="form-control" required>

                                                                            <option value="" selected>Seleccione una opcion...</option>

                                                                            <?php

                                                                            foreach ($datos_usuario as $usuario) {

                                                                                $id_user         = $usuario['id_user'];

                                                                                $nombre_completo = $usuario['nom_user'];

                                                                                $estado          = $usuario['estado'];



                                                                                $ver = ($estado == 'activo') ? '' : 'd-none';



                                                                                ?>

                                                                                <option value="<?=$id_user?>" class="<?=$ver?>"><?=$nombre_completo?></option>

                                                                                <?php

                                                                            }

                                                                            ?>

                                                                        </select>

                                                                    </div>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                    <div class="form-group">

                                                                        <label class="font-weight-bold">Area <span class="text-danger">*</span></label>

                                                                        <select name="id_area" class="form-control" required>

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

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="modal-footer border-0">

                                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">

                                                                <i class="fa fa-times"></i>

                                                                &nbsp;

                                                                Cerrar

                                                            </button>

                                                            <button type="submit" class="btn btn-success btn-sm">

                                                                <i class="fa fa-plus"></i>

                                                                &nbsp;

                                                                Asignar

                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>



                                        <?php

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

</div>

<?php

include_once VISTA_PATH . 'script_and_final.php';



if (isset($_POST['id_user'])) {

    $instancia->reasignarArticuloControl();

}

