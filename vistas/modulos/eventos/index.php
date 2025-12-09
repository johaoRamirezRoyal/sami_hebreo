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

$permisos = $instancia_permiso->permisosUsuarioControl(93, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}

$perfil_log_cod = base64_encode($perfil_log);
/*href="https://asistenciahebreo.hajsoft.co/?perfil=<?=$perfil_log_cod?>"*/

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
                        Eventos
                    </h4>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <iframe src="https://asistenciahebreo.hajsoft.co/?perfil=<?=$perfil_log_cod?>" 
                        allow="clipboard-write"
                        frameborder="0"
                        style="width: 100%; height: 100%; min-height: 600px;"
                        class="flex-grow-1">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>