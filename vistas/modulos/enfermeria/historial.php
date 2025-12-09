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
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlEnfermeria::singleton_enfermeria();
$instancia_perfil = ControlPerfil::singleton_perfil();

$permisos = $instancia_permiso->permisosUsuarioControl(58, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

if (isset($_GET['usuario'])) {
    $id_user = base64_decode($_GET['usuario']);

    $datos_user       = $instancia_perfil->mostrarDatosPerfilControl($id_user);
    $datos_atencion   = $instancia->mostrarAtencionUsuarioControl($id_user);
    $datos_acudientes = $instancia->correosPadresControl($id_user);

    $foto_perfil = ($datos_user['foto_carnet'] != '') ? 'upload/' . $datos_user['foto_carnet'] : 'img/user.svg';

    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <a href="<?=BASE_URL?>enfermeria/listado" class="text-decoration-none">
                                <i class="fa fa-arrow-left text-primary"></i>
                            </a>
                            &nbsp;
                            Historial de atenciones medicas
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-lg-4 form-group">
                                <div class="circular--portrait">
                                    <img src="<?=PUBLIC_PATH . $foto_perfil?>">
                                </div>
                            </div>
                            <div class="col-lg-8 form-group">
                                <div class="row p-2">
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['documento']?>" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Nombre Completo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['nombre'] . ' ' . $datos_user['apellido']?>" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['correo']?>" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['telefono']?>" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['nom_nivel']?>" disabled>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Curso <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="<?=$datos_user['nom_curso']?>" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col" colspan="3">Acudientes relacionados</th>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Documento</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Correo</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar">
                                    <?php
                                    foreach ($datos_acudientes as $acudiente) {
                                        $id_acudiente = $acudiente['id'];
                                        $documento    = $acudiente['documento'];
                                        $nom_completo = $acudiente['nombre'] . ' ' . $acudiente['apellido'];
                                        $correo       = $acudiente['correo'];
                                        ?>
                                        <tr class="text-center">
                                            <td><?=$documento?></td>
                                            <td><?=$nom_completo?></td>
                                            <td><?=$correo?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col" colspan="5">Historial de atenciones</th>
                                    </tr>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">No. Atencion Medica</th>
                                        <th scope="col">Motivo</th>
                                        <th scope="col">Tratamiento</th>
                                        <th scope="col">Fecha de atencion</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar">
                                    <?php
                                    foreach ($datos_atencion as $atencion) {
                                        $id_atencion = $atencion['id'];
                                        $nom_user    = $atencion['nom_user'];
                                        $documento   = $atencion['documento'];
                                        $curso       = $atencion['nom_curso'];
                                        $nivel       = $atencion['nom_nivel'];
                                        $motivo      = $atencion['motivo_cons'];
                                        $tratamiento = $atencion['tratamiento'];

                                        if ($atencion['envio'] == 1) {
                                            $span_envio = '<span class="badge badge-success">Correo Enviado</span>';
                                        }

                                        if ($atencion['envio'] == 2) {
                                            $span_envio = '<span class="badge badge-secondary">Envio de correo programado pendiente</span>';
                                        }

                                        if ($atencion['envio'] == 2 && $atencion['enviado'] == 1) {
                                            $span_envio = '<span class="badge badge-success">Correo Programado Enviado</span>';
                                        }

                                        ?>
                                        <tr class="text-center">
                                            <td><?=$id_atencion?></td>
                                            <td><?=$motivo?></td>
                                            <td><?=$tratamiento?></td>
                                            <td><?=date('Y-m-d', strtotime($atencion['fechareg']))?></td>
                                            <td><?=$span_envio?></td>
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
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
}
