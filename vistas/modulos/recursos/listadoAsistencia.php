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

require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';



$instancia         = ControlAsistencia::singleton_asistencia();

$instancia_perfil  = ControlPerfil::singleton_perfil();

$instancia_usuario = ControlUsuarios::singleton_usuarios();



//$datos_grupo = $instancia_usuario->mostrarGruposControl();



if (isset($_POST['buscar'])) {



    $datos = array('buscar' => $_POST['buscar'], 'grupo' => $_POST['grupo'], 'fecha' => $_POST['fecha']);



    $fecha = $_POST['fecha'];



    $datos_usuarios    = $instancia->buscarUsuarioAsistenciaGestionControl($datos);

    $descargar_reporte = '';

} else {



    $fecha = date('Y-m-d');



    $datos_usuarios    = $instancia->mostrarAsistenciaListadoControl();

    $descargar_reporte = 'd-none';

}



$permisos = $instancia_permiso->permisosUsuarioControl(63, $perfil_log);

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

                        Asistencia

                    </h4>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="row">

                            <div class="col-lg-4 form-group">

                                <select name="grupo" class="form-control" id="">

                                    <option value="" selected>Seleccione un grupo...</option>

                                    <?php

                                    foreach ($datos_grupo as $grupo) {

                                        $id_grupo  = $grupo['id'];

                                        $nom_grupo = $grupo['nombre'];

                                        ?>

                                        <option value="<?=$id_grupo?>"><?=$nom_grupo?></option>

                                        <?php

                                    }

                                    ?>

                                </select>

                            </div>

                            <div class="col-lg-4 form-group">

                                <input type="date" class="form-control" name="fecha">

                            </div>

                            <div class="col-lg-4 form-group">

                                <div class="input-group mb-3">

                                  <input type="text" class="form-control filtro" placeholder="Buscar" name="buscar">

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

                <div class="col-lg-12 form-group mt-2 text-right <?=$descargar_reporte?>">

                    <a href="<?=BASE_URL?>imprimir/recursos/ReporteAsistencia?buscar=<?=$_POST['buscar']?>&grupo=<?=$_POST['grupo']?>&fecha=<?=$fecha?>" target="_blank" class="btn btn-success btn-sm">

                        <i class="fa fa-file-excel"></i>

                        &nbsp;

                        Descargar Reporte

                    </a>

                </div>

                <div class="table-responsive mt-2">

                    <table class="table table-hover border table-sm" width="100%" cellspacing="0">

                        <thead>

                            <tr class="text-center font-weight-bold">



                                <th scope="col">Documento</th>

                                <th scope="col">Nombre</th>

                                <th scope="col">Grupo</th>

                                <th scope="col">Perfil</th>

                                <th scope="col">Fecha</th>

                                <th scope="col">Hora</th>

                            </tr>

                        </thead>

                        <tbody class="buscar">

                            <?php

                            foreach ($datos_usuarios as $usuario) {

                                $documento        = $usuario['documento'];

                                $nombre_completo  = $usuario['nom_user'];

                                $perfil           = $usuario['perfil'];

                                $fecha_asistencia = $usuario['fecha_asistencia'];

                                $hora             = $usuario['hora_asistencia'];

                                $nom_grupo        = $usuario['nom_grupo'];

                                ?>

                                <tr class="text-center">

                                    <td><?=$documento?></td>

                                    <td><?=$nombre_completo?></td>

                                    <td><?=$nom_grupo?></td>

                                    <td><?=$perfil?></td>

                                    <td><?=$fecha_asistencia?></td>

                                    <td><?=$hora?></td>

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