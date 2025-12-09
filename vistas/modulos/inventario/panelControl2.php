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

    $buscar = $instancia->buscarInventarioDetalleControl($datos);

} else {
    $area     = '';
    $usuario  = '';
    $articulo = '';
    $buscar = $instancia->mostrarInventarioDetalleControl();
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
                        Panel de control
                    </h4>

                    <div class="btn-group">
                     <a class="btn btn-warning btn-sm" href="<?=BASE_URL?>inventario/descontinuados">
                        <i class="fas fa-minus-circle"></i>
                        &nbsp;
                        Descontinuados
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <select name="area_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Area">
                                <option value="" selected>Seleccione un area...</option>
                                <?php
                                foreach ($datos_areas as $areas) {
                                    $id_area = $areas['id'];
                                    $nom_area    = $areas['nombre'];
                                    $ver = ($areas['activo'] == 0) ? 'd-none' : '';
                                    $selected = ($id_area == $area) ? 'selected' : '';
                                    ?>
                                    <option value="<?=$id_area?>" class="<?=$ver?>" <?=$selected?>><?=$nom_area?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-4 form-group">
                            <select name="usuario_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Usuario">
                                <option value="" selected>Seleccione un usuario...</option>
                                <?php

                                foreach ($datos_usuario as $usuarios) {
                                    $id_usuario  = $usuarios['id_user'];
                                    $nombre_user = $usuarios['nom_user'];

                                    $ver = ($areas['estado'] == 'inactivo') ? 'd-none' : '';
                                    $selected = ($id_usuario == $usuario) ? 'selected' : '';
                                    ?>
                                    <option value="<?=$id_usuario?>" class="<?=$ver?>" <?=$selected?>><?=$nombre_user?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-4 form-group">
                            <div class="input-group">
                                <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="articulo" value="<?=$articulo?>">
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
                                <th scope="col">USUARIO</th>
                                <th scope="col">AREA</th>
                                <th scope="col">DESCRIPCION</th>
                                <th scope="col">MARCA</th>
                                <th scope="col">CANTIDAD</th>
                                <th scope="col">ESTADO</th>
                            </tr>
                        </thead>

                        <tbody class="buscar text-uppercase">
                            <?php
                            foreach ($buscar as $inventario) {
                                $id_inventario = $inventario['id'];
                                $nombre        = $inventario['descripcion'];
                                $usuario       = $inventario['nom_user'];
                                $marca         = $inventario['marca'];
                                $modelo        = $inventario['modelo'];
                                $estado        = $inventario['estado_nombre'];
                                $id_area       = $inventario['id_area'];
                                $area          = $inventario['nom_area'];
                                $codigo        = $inventario['id'];
                                $id_estado     = $inventario['estado'];
                                $id_user       = $inventario['id_user'];
                                $cantidad      = $inventario['cantidad'];

                                $trabajo_casa = '';
                                $remover_casa = '';
                                $visible_lib  = '';
                                $visible_mant = '';
                                $visible_rep  = '';
                                $visible_desc = '';
                                $ver_articulo = '';

                                if ($inventario['id_categoria'] == 1) {
                                    $hoja_vida = '<a href="' . BASE_URL . 'hoja_vida/index?inventario=' . base64_encode($id_inventario) . '">' . $nombre . '</a>';
                                } else {
                                    $hoja_vida = $nombre;
                                }

                                if ($id_estado == 6 || $id_estado == 2) {
                                    $visible_group     = 'd-none';
                                    $visible_descargar = '';
                                    $remover_casa      = 'd-none';

                                } else {
                                    $visible_group     = '';
                                    $visible_descargar = 'd-none';
                                    $remover_casa      = 'd-none';
                                }

                                if ($id_estado == 5) {
                                    $ver_articulo = 'd-none';
                                }

                                if ($id_estado == 4) {
                                    $visible_lib  = 'd-none';
                                    $visible_mant = 'd-none';
                                    $visible_rep  = 'd-none';
                                    $visible_desc = '';
                                    $remover_casa = 'd-none';
                                    $trabajo_casa = '';
                                }

                                if ($id_estado == 1) {
                                    $visible_group = '';
                                    $visible_lib   = '';
                                    $visible_mant  = '';
                                    $visible_rep   = '';
                                    $visible_desc  = '';
                                    $remover_casa  = 'd-none';
                                    $trabajo_casa  = '';
                                }

                                if ($id_estado == 8) {
                                    $trabajo_casa = 'd-none';
                                    $remover_casa = '';
                                    $visible_lib  = '';
                                    $visible_mant = '';
                                    $visible_rep  = '';
                                    $visible_desc = '';
                                }



                                if ($id_estado == 9) {
                                    $trabajo_casa = '';
                                    $remover_casa = 'd-none';
                                    $visible_lib  = '';
                                    $visible_mant = '';
                                    $visible_rep  = '';
                                    $visible_desc = '';
                                }

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

                                ?>

                                <tr class="text-center <?=$ver_articulo?>">
                                    <td><?=$usuario?></td>
                                    <td><?=$area?></td>
                                    <td><?=$hoja_vida?></td>
                                    <td><?=$marca?></td>
                                    <td><?=$cantidad?></td>
                                    <td><?=$estado?></td>
                                    <td><?=$span_confirmado?></td>

                                    <td class="<?=$visible_group?>">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-warning btn-sm <?=$visible_lib?>" data-tooltip="tooltip" data-placement="bottom" title="Liberar" data-toggle="modal" data-target="#liberar_inv<?=$id_inventario?>">
                                                <i class="fab fa-telegram-plane"></i>
                                            </button>

                                            <button class="btn btn-success btn-sm <?=$visible_rep?>" data-tooltip="tooltip" data-placement="bottom" title="Reportar" data-toggle="modal" data-target="#rep_inv<?=$id_inventario?>">
                                                <i class="fas fa-clipboard-check"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm <?=$visible_desc?>" data-tooltip="tooltip" data-placement="bottom" title="Descontinuar" data-toggle="modal" data-target="#desc_inv<?=$id_inventario?>">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>

                                            <button class="btn btn-primary btn-sm <?=$trabajo_casa?>" data-tooltip="tooltip" data-placement="bottom" title="Trabajo en casa" data-toggle="modal" data-target="#trab_home<?=$id_inventario?>">
                                                <i class="fas fa-briefcase"></i>
                                            </button>

                                            <button class="btn btn-secondary btn-sm <?=$remover_casa?>" data-tooltip="tooltip" data-placement="bottom" title="Remover trabajo en casa" data-toggle="modal" data-target="#rem_home<?=$id_inventario?>">
                                                <i class="fas fa-briefcase"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="<?=$visible_descargar?>">
                                        <a href="<?=BASE_URL?>imprimir/reporte?nombre=<?=base64_encode($nombre)?>&area=<?=base64_encode($id_area)?>&id_user=<?=base64_encode($id_user)?>&estado=<?=base64_encode($id_estado)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte">
                                            <i class="fa fa-download"></i>
                                        </a>
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

                                                        <input type="hidden" value="<?=$nombre?>" name="nom_inventario_desc">
                                                        <input type="hidden" value="<?=$id_log?>" name="resp" id="id_log">
                                                        <input type="hidden" value="<?=$id_user?>" name="user" id="id_user">
                                                        <input type="hidden" value="<?=$id_area?>" name="id_area" id="id_area">
                                                        <input type="hidden" value="1" name="inicio">
                                                        <input type="hidden" value="<?=$id_estado?>" name="estado">

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Area</label>
                                                            <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$area?>">
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Descripcion</label>
                                                            <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$nombre?>">
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Marca</label>
                                                            <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$marca?>">
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Cantidad Reportada</label>
                                                            <input type="text" class="form-control" disabled value="<?=$cantidad?>">
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Cantidad a Descontinuar</label>
                                                            <input type="number" class="form-control numeros" name="cantidad" value="" max="<?=$cantidad?>">
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label class="font-weight-bold">Responsable</label>
                                                            <input type="text" class="form-control" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                        </div>

                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Fecha Descontinuado <span class="text-danger">*</span></label>
                                                            <input type="date" name="fecha" class="form-control" required>
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

                                                        <input type="hidden" value="<?=$id_log?>" name="id_log_rep">
                                                        <input type="hidden" value="<?=$id_user?>" name="id_user_rep">
                                                        <input type="hidden" value="<?=$id_area?>" name="id_area_rep">
                                                        <input type="hidden" value="<?=$nombre?>" name="nom_inventario_rep">

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
                                                            <label class="font-weight-bold">Responsable</label>
                                                            <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?=$usuario?>">
                                                        </div>

                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Cantidad Actual</label>
                                                            <input type="text" class="form-control" disabled value="<?=$cantidad?>">
                                                        </div>

                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Cantidad a Reportar</label>
                                                            <input hidden type="number" class="form-control numeros" name="cantidad"> 
                                                            <input disabled placeholder="1" type="number" class="form-control numeros"> 
                                                            <!-- <input type="number" value="1" placeholder="1" class="form-control numeros" name="cantidad" disabled required max="<?=$cantidad?>"> -->
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

if (isset($_POST['id_log_lib'])) {
    $instancia->liberarArticuloControl();
}

if (isset($_POST['nom_inventario_desc'])) {
    $instancia->descontinuarArticuloControl();
}

if (isset($_POST['nom_inventario_rep'])) {
    $instancia->reportarArticuloControl();
}

if (isset($_POST['id_inventario_trab_home'])) {
    $instancia->trabajoCasaArticuloControl();
}

if (isset($_POST['id_inventario_rem_home'])) {
    $instancia->removerTrabajoCasaArticuloControl();
}