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

$instancia        = ControlAsistencia::singleton_asistencia();
$instancia_perfil = ControlPerfil::singleton_perfil();

$datos_perfil = $instancia_perfil->mostrarPerfilesControl();

$permisos = $instancia_permiso->permisosUsuarioControl(63, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_POST['buscar'])) {
    $datos          = array('buscar' => $_POST['buscar'], 'perfil' => $_POST['perfil'], 'fecha' => $_POST['fecha']);
    $datos_usuarios = $instancia->buscarUsuarioAsistenciaGestionControl($datos);

} else {
    $datos_usuarios = $instancia->mostrarAsistenciaListadoControl();


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
                            <div class="col-lg-4 form-group">
                                <select name="perfil" class="form-control" id="">
                                    <option value="" selected>Seleccione un perfil...</option>
                                    <?php
                                    foreach ($datos_perfil as $perfiles) {
                                        $id_perfil  = $perfiles['id_perfil'];
                                        $nom_perfil = $perfiles['nombre'];

                                        if ($id_perfil != 1) {
                                            ?>
                                            <option value="<?=$id_perfil?>"><?=$nom_perfil?></option>
                                            <?php
                                        }
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


                
                <div class="table-responsive mt-2">
                    <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center font-weight-bold">

                                <th scope="col">Documento</th>
                                <th scope="col">Nombre</th>
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

                                ?>
                                <tr class="text-center">
                                    <td><?=$documento?></td>
                                    <td><?=$nombre_completo?></td>
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