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

$instancia = ControlEnfermeria::singleton_enfermeria();

if (isset($_POST['buscar'])) {
    $datos_categoria = $instancia->buscarCategoriaControl($_POST['buscar']);
} else {
    $datos_categoria = $instancia->mostrarLimiteCategoriaControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(56, $perfil_log);

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
                        <a href="<?=BASE_URL?>enfermeria/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Procedimientos
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_categoria">
                            <i class="fa fa-plus"></i>
                            &nbsp;
                            Agregar Procedimiento
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row p-2">
                            <div class="col-lg-8"></div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
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
                                    <th scope="col">No. Procedimiento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Atencion Rapida</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="buscar">
                                <?php
                                foreach ($datos_categoria as $categoria) {
                                    $id_categoria    = $categoria['id'];
                                    $nom_categoria   = $categoria['nombre'];
                                    $atencion_rapida = $categoria['atencion_rapida'];
                                    ?>
                                    <tr class="text-center text-uppercase">
                                        <td><?=$id_categoria?></td>
                                        <td><?=$nom_categoria?></td>
                                        <td><?=nl2br(htmlspecialchars($atencion_rapida))?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Editar Procedimiento" data-toggle="modal" data-target="#editar_categoria<?=$id_categoria?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-tooltip="tooltip" title="Eliminar Procedimiento">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Editar Categoria -->
                                    <div class="modal fade" id="editar_categoria<?=$id_categoria?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Editar Procedimiento</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST">
                                                        <input type="hidden" name="id_categoria" value="<?=$id_categoria?>">
                                                        <div class="row p-2">
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Nombre Categoria <span class="text-danger">*</span></label>
                                                                <input type="text" name="nom_categoria" class="form-control" value="<?=htmlspecialchars($nom_categoria)?>" required>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Atencion Rapida</label>
                                                                <textarea name="atencion_rapida" class="form-control" rows="4" placeholder="Atencion predeterminada para esta categoria..."><?=htmlspecialchars($atencion_rapida)?></textarea>
                                                            </div>
                                                            <div class="col-lg-12 form-group text-right mt-2">
                                                                <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                                                                    <i class="fa fa-times"></i>
                                                                    &nbsp;
                                                                    Cancelar
                                                                </button>
                                                                <button class="btn btn-primary btn-sm" type="submit">
                                                                    <i class="fa fa-save"></i>
                                                                    &nbsp;
                                                                    Guardar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
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
include_once VISTA_PATH . 'modulos' . DS . 'enfermeria' . DS . 'agregarCategoria.php';

if (isset($_POST['nom_categoria']) && !isset($_POST['id_categoria'])) {
    $instancia->registrarCategoriaControl();
}

if (isset($_POST['id_categoria'])) {
    $instancia->editarCategoriaControl();
}
?>