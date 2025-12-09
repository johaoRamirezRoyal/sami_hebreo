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



$datos_perfil    = $instancia_perfil->mostrarPerfilesControl();

$datos_usuario   = $instancia_usuarios->mostrarTodosUsuariosInventarioControl();

$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);

$datos_categoria = $instancia->mostrarCategoriasControl($id_super_empresa);



$permisos = $instancia_permiso->permisosUsuarioControl(3, $perfil_log);



$ver_botones = (isset($_POST['area_buscar'])) ? '' : 'd-none';



$cantidad = '';



if (isset($_POST['area_buscar'])) {



    $area_buscar     = $_POST['area_buscar'];

    $usuario_buscar  = $_POST['usuario_buscar'];

    $articulo_buscar = $_POST['articulo'];



    $datos = array(

        'area'     => $area_buscar,

        'usuario'  => $usuario_buscar,

        'articulo' => $articulo_buscar,

    );



    $buscar   = $instancia->buscarInventarioControl($datos);

    $cantidad = $instancia->cantidadesInventarioControl($datos);

} else {



    $area_buscar     = '';

    $usuario_buscar  = '';

    $articulo_buscar = '';



    $buscar = $instancia->mostrarInventarioControl();

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

                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Inventario

                    </h4>

                    <div class="btn-group">

                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_inventario">

                            <i class="fa fa-plus"></i>

                            &nbsp;

                            Agregar articulo

                        </button>
                        <!--
                        <a class="btn btn-danger btn-sm" href="<?=BASE_URL?>inventario/panelControl">

                            <i class="fas fa-terminal"></i>

                            &nbsp;

                            Panel de control

                        </a>
                        -->
                        <a class="btn btn-warning btn-sm" href="<?=BASE_URL?>inventario/descontinuados">

                            <i class="fas fa-minus-circle"></i>

                            &nbsp;

                            Descontinuados

                        </a>

                        <a class="btn btn-secondary btn-sm" href="<?=BASE_URL?>inventario/reasignar">

                            <i class="fas fa-sync-alt"></i>

                            &nbsp;

                            Re-asignar

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="row">

                            <div class="col-lg-4 form-group">

                                <select name="area_buscar" class="form-control filtro_change" data-tooltip="tooltip" title="Area">

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

                                <select name="usuario_buscar" class="form-control filtro_change" data-tooltip="tooltip" title="Usuario">

                                    <option value="" selected>Seleccione una opcion...</option>

                                    <?php

                                    foreach ($datos_usuario as $usuarios) {

                                        $id_usuario  = $usuarios['id_user'];

                                        $nombre_user = $usuarios['nom_user'];

                                        ?>

                                        <option value="<?=$id_usuario?>"><?=$nombre_user?></option>

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

                    <div class="row <?=$ver_botones?>">

                        <?php

                        if ($cantidad != '') {

                            ?>

                            <div class="col-lg-6 form-group mt-2">

                                <h5 class="text-primary font-weight-bold">Cantidades - <?=$cantidad['descripcion']?>: <?=$cantidad['cantidad']?></h5>

                            </div>

                        <?php } else {

                            ?>

                            <div class="col-lg-6 form-group"></div>

                            <?php

                        }

                        ?>

                        <div class="col-lg-6 form-group mt-2 text-right">

                            <div class="btn-group">

                                <a href="<?=BASE_URL?>imprimir/imprimirInventario?area=<?=base64_encode($area_buscar)?>&usuario=<?=base64_encode($usuario_buscar)?>&articulo=<?=base64_encode($articulo_buscar)?>" class="btn btn-secondary btn-sm" target="_blank">

                                    <i class="fa fa-print"></i>

                                    &nbsp;

                                    Imprimir

                                </a>

                                <a href="<?=BASE_URL?>imprimir/cartaEntregaOficial?usuario=<?=base64_encode($buscar[0]['id_user'])?>&area=<?=base64_encode($buscar[0]['id_area'])?>" target="_blank" class="btn btn-primary btn-sm mr-auto">

                                    <i class="fas fa-file-alt" id="carta_entrega"></i>

                                    &nbsp;

                                    Generar Carta de entrega

                                </a>

                            </div>

                        </div>

                    </div>

                    <div class="table-responsive mt-4">

                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">

                            <thead>

                                <tr class="text-center font-weight-bold">

                                    <th scope="col">USUARIO</th>

                                    <th scope="col">AREA</th>

                                    <th scope="col">DESCRIPCION</th>

                                    <th scope="col">CANTIDAD</th>

                                    <th scope="col">ESTADO</th>

                                    <th scope="col">MARCA</th>

                                    <th scope="col">MODELO</th>

                                    <th scope="col">OBSERVACION</th>

                                </tr>

                            </thead>

                            <tbody class="buscar text-uppercase">

                                <?php

                                foreach ($buscar as $inventario) {

                                    $id_inventario = $inventario['id'];

                                    $nombre        = $inventario['descripcion'];

                                    $cantidad      = $inventario['cantidad'];

                                    $estado      = $inventario['estado'];

                                    $usuario       = $inventario['usuario'];

                                    $marca         = $inventario['marca'];

                                    $modelo        = $inventario['modelo'];

                                    $observacion   = $inventario['observacion'];

                                    $id_area       = $inventario['id_area'];

                                    $area          = $inventario['area_nom'];



                                    $ver = '';

                                    
                                    /*
                                    if ($inventario['estado'] == 5) {

                                        $ver = 'd-none';

                                    } else if ($inventario['estado'] == 4) {

                                        $ver = 'd-none';

                                    } else if ($inventario['confirmado'] != 1) {

                                        $ver = 'd-none';

                                        ?>

                                        <tr class="text-center">

                                            <td colspan="7">Articulo no confirmado</td>

                                        </tr>

                                        <?php
                                        asignados
                                        preventivos
                                        correctivos
                                        solucionado 

                                    }
                                    */

                                    if($estado == 1){
                                        $ver_estado="Asignado";

                                    }else if($estado == 2){
                                        $ver_estado="Mant correctivo";

                                    }else if($estado == 6){
                                        $ver_estado="Mant preventivo";
                                    }else if($estado == 3){
                                        $ver_estado="solucionado";
                                    }
                                    
                                    ?>

                                    <tr class="text-center">

                                        <td><?=$usuario?></td>

                                        <td><?=$area?></td>

                                        <td><?=$nombre?></td>

                                        <td><?=$cantidad?></td>

                                        <td><?=$ver_estado?></td>

                                        <td><?=$marca?></td>

                                        <td><?=$modelo?></td>

                                        <td><?=$observacion?></td>

                                    </tr>

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

include_once VISTA_PATH . 'modulos' . DS . 'inventario' . DS . 'agregarInvetario.php';



if (isset($_POST['id_log'])) {

    $instancia->guardarInventarioDirecto();
    
    }

?>

<script src="<?=PUBLIC_PATH?>js/inventario/funcionesInventario.js"></script>