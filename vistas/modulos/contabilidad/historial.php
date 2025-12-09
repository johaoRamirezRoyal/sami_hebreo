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
require_once CONTROL_PATH . 'contabilidad' . DS . 'ControlContabilidad.php';

$instancia = ControlContabilidad::singleton_contabilidad();

$permisos = $instancia_permiso->permisosUsuarioControl(27, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['usuario'])) {
    $id_usuario   = base64_decode($_GET['usuario']);
    $datos_user   = $instancia_perfil->mostrarDatosPerfilControl($id_usuario);
    $datos_nomina = $instancia->mostrarNominasControl($id_usuario);
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="#" onclick="window.history.go(-1); return false;" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Volantes de pago
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Documento</label>
                                <input type="text" class="form-control" value="<?=$datos_user['documento']?>" disabled>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Nombre</label>
                                <input type="text" class="form-control" value="<?=$datos_user['nombre'] . ' ' . $datos_user['apellido']?>" disabled>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Correo</label>
                                <input type="text" class="form-control" value="<?=$datos_user['correo']?>" disabled>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Telefono</label>
                                <input type="text" class="form-control" value="<?=$datos_user['telefono']?>" disabled>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Perfil</label>
                                <input type="text" class="form-control" value="<?=$datos_user['nom_perfil']?>" disabled>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-hover table-striped table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <td scope="col" colspan="3">Volantes registradas</td>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Mes</th>
                                        <th scope="col">Usuario responsable</th>
                                        <th scope="col">Fecha subido</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-uppercase">
                                    <?php
                                    if (count($datos_nomina) <= 0) {
                                        ?>
                                        <tr class="text-center">
                                            <td colspan="4">No hay resultados para mostrar</td>
                                        </tr>
                                        <?php
                                    } else {
                                        foreach ($datos_nomina as $nomina) {
                                            $id_nomina = $nomina['id'];
                                            $nombre    = $nomina['nombre'];
                                            $usuario   = $nomina['usuario'];
                                            $anio_mes  = $nomina['anio_mes'];
                                            $fechareg  = $nomina['fechareg'];
                                            ?>
                                            <tr class="text-center">
                                                <td><?=$anio_mes?></td>
                                                <td><?=$usuario?></td>
                                                <td><?=$fechareg?></td>
                                                <td>
                                                    <a href="<?=PUBLIC_PATH?>upload/<?=$nomina['nombre']?>" target="_blank" download class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar archivo" data-placement="bottom">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
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
}
