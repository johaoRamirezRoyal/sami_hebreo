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
    header('Location:../../login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia = ControlAsistencia::singleton_asistencia();

if (isset($_POST['buscar'])) {

} else {
    $datos_mensaje = $instancia->mensajesGeneralesLimiteControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(69, $perfil_log);
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
                        <a href="<?=BASE_URL?>recursos/news/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        News Royal - Mensaje General (diario)
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_mensaje_mensaje_general">
                            <i class="fas fa-plus"></i>
                            &nbsp;
                            Agregar Mensaje
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                            </div>
                            <div class="col-lg-4 form-group">
                                <input type="date" class="form-control" name="fecha" data-tooltip="tooltip" data-placement="top" title="Fecha">
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
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
                                    <th scope="col">Titulo</th>
                                    <th scope="col">Mensaje</th>
                                    <th scope="col">Fecha  registrado</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_mensaje as $mensaje) {
                                    $id_mensaje   = $mensaje['id'];
                                    $titulo       = $mensaje['titulo'];
                                    $cont_mensaje = $mensaje['mensaje'];
                                    $estado       = $mensaje['activo'];

                                    $span = ($estado == 1) ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$titulo?></td>
                                        <td><?=$cont_mensaje?></td>
                                        <td><?=date('Y-m-d', strtotime($mensaje['fechareg']))?></td>
                                        <td><?=$span?></td>
                                    </tr>
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
include_once VISTA_PATH . 'modulos' . DS . 'recursos' . DS . 'news' . DS . 'agregarMensaje.php';

if (isset($_POST['mensaje_general'])) {
    $instancia->mensajeGeneralControl();
}