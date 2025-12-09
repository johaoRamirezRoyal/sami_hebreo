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

$instancia = ControlAsistencia::singleton_asistencia();

$permisos = $instancia_permiso->permisosUsuarioControl(38, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_POST['usuario'])) {
    $usuario        = $_POST['usuario'];
    $datos_usuarios = $instancia->buscarUsuarioAsistenciaControl($usuario);
} else {
    $datos_usuarios = $instancia->mostrarAsistenciaControl();
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
                        Asistencia
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-7 form-inline">
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group mb-3">
                                  <input type="text" class="form-control filtro" placeholder="Buscar" name="usuario">
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
                <div class="table-responsive mt-2">
                    <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center font-weight-bold">
                                <th scope="col">Documento</th>
                                <th scope="col">Nombre</th>                                <th scope="col">Curso</th>
                                <th scope="col">Perfil</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="buscar">
                            <?php
                            foreach ($datos_usuarios as $usuario) {
                                $id_user         = $usuario['id_user'];
                                $documento       = $usuario['documento'];
                                $nombre_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                $perfil          = $usuario['perfil_nom'];
                                $fecha_llegada   = $usuario['fecha'];
                                $hora            = $usuario['hora'];

                                if ($encuesta == '') {
                                    $formulario = '<span class="badge badge-danger">No</span>';
                                } else {
                                    $formulario = '<span class="badge badge-success">Si</span>';
                                }

                                ?>
                                <tr class="text-center">
                                    <td><?=$documento?></td>
                                    <td><?=$nombre_completo?></td>
                                    <td><?=$curso?></td>
                                    <td><?=$perfil?></td>
                                    <td><?=$fecha_llegada?></td>
                                    <td><?=$hora?></td>
                                    <td><?=$formulario?></td>
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