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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'contabilidad' . DS . 'ControlContabilidad.php';

$instancia         = ControlContabilidad::singleton_contabilidad();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_usuario = $instancia_usuario->mostrarTodosUsuariosControl();
$mes           = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

$permisos = $instancia_permiso->permisosUsuarioControl(26, $perfil_log);

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
                        <a href="#" onclick="window.history.go(-1); return false;" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Volantes de pago
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 form-inline">
                        </div>
                        <div class="col-lg-4">
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
                        </div>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_usuario as $usuario) {
                                    $id_user    = $usuario['id_user'];
                                    $nombre     = $usuario['nom_user'];
                                    $documento  = $usuario['documento'];
                                    $correo     = $usuario['correo'];
                                    $telefono   = $usuario['telefono'];
                                    $asignatura = $usuario['asignatura'];
                                    $user       = $usuario['user'];
                                    $estado     = $usuario['estado'];

                                    $ver = ($estado == 'activo') ? '' : 'd-none';

                                    $anio_mes_actual = date('Y-n');
                                    $nomina          = $instancia->mostrarNominaMesActualControl($id_user, $anio_mes_actual);

                                    if ($nomina['id'] == '') {
                                        $subir     = '';
                                        $descargar = 'd-none';
                                    } else {
                                        $subir     = 'd-none';
                                        $descargar = '';
                                    }

                                    ?>
                                    <tr class="text-center <?=$ver?>">
                                        <td><?=$documento?></td>
                                        <td class="text-uppercase">
                                            <a href="<?=BASE_URL?>contabilidad/historial?usuario=<?=base64_encode($id_user)?>"><?=$nombre?></a>
                                        </td>
                                        <td><?=$correo?></td>
                                        <td><?=$telefono?></td>
                                        <td class="<?=$subir?>">
                                            <button class="btn btn-success btn-sm" data-tooltip="tooltip" title="Subir archivo" data-placement="bottom" data-toggle="modal" data-target="#subir<?=$id_user?>">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                        </td>
                                        <td class="<?=$descargar?>">
                                            <a href="<?=PUBLIC_PATH?>upload/<?=$nomina['nombre']?>" target="_blank" download class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar archivo" data-placement="bottom">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </td>
                                    </tr>


                                    <!-- Modal -->
                                    <div class="modal fade" id="subir<?=$id_user?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Subir archivo</h5>
                                                </div>
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" value="<?=$id_user?>" name="id_user">
                                                    <input type="hidden" value="<?=$id_log?>" name="id_log">
                                                    <div class="modal-body border-0">
                                                        <div class="form-group col-lg-12">
                                                            <label>Documento</label>
                                                            <input type="text" class="form-control" disabled value="<?=$documento?>">
                                                        </div>
                                                        <div class="form-group col-lg-12">
                                                            <label>Nombre</label>
                                                            <input type="text" class="form-control" disabled value="<?=$nombre?>">
                                                        </div>
                                                        <div class="form-group col-lg-12">
                                                            <label>Correo</label>
                                                            <input type="text" class="form-control" disabled value="<?=$correo?>">
                                                        </div>
                                                        <div class="form-group col-lg-12">
                                                            <label>Telefono</label>
                                                            <input type="text" class="form-control" disabled value="<?=$telefono?>">
                                                        </div>
                                                        <div class="form-group col-lg-12">
                                                            <label>Mes a subir</label>
                                                            <select name="anio_mes" class="form-control" required>
                                                                <option value="" selected>Seleccione una opcion...</option>
                                                                <?php
                                                                for ($i = 1; $i < count($mes); $i++) {
                                                                    ?>
                                                                    <option value="<?=date('Y-') . $i?>"><?=$mes[$i]?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-lg-12">
                                                            <label>Archivo</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input customFileLang" name="archivo" id="customFileLang" lang="es">
                                                                <label class="custom-file-label nom_arch" for="customFileLang">Seleccionar archivo...</label>
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
                                                            <i class="fa fa-upload"></i>
                                                            &nbsp;
                                                            Subir
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

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

if (isset($_POST['id_user'])) {
    $instancia->subirNominaControl();
}

?>