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
require_once CONTROL_PATH . 'extra' . DS . 'ControlExtra.php';

$instancia = ControlExtra::singleton_extra();

$datos_extra = $instancia->mostrarDatosExtraControl();

$permisos = $instancia_permiso->permisosUsuarioControl(39, $perfil_log);

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
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Extracurriculares
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
                                    <th scope="col">No.</th>
                                    <th scope="col">Nombre completo</th>
                                    <th scope="col">Curso</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_extra as $extra) {
                                    $id_extra  = $extra['id'];
                                    $nombre    = $extra['nombre'];
                                    $categoria     = $extra['categoria'];
                                    $curso     = $extra['curso'];
                                    $fecha_reg = $extra['fechareg'];
                                    ?>
                                    <tr class="text-center">
                                        <td><?=$id_extra?></td>
                                        <td><?=$nombre?></td>
                                        <td><?=$curso?></td>
                                        <td><?=$categoria?></td>
                                        <td><?=$fecha_reg?></td>
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
?>