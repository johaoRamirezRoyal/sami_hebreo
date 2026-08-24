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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

if(isset($_GET['id'])){
    $id_log = base64_decode($_GET['id']);
}

$instancia = ControlPerfil::singleton_perfil();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();



$informacion_usuario = $instancia->mostrarDatosPerfilControl($id_log);

$redir = (isset($_GET['id'])) ? 'hojaVida?id=' . $_GET['id'] . '&link=1' : 'hojaVida';

$producciones_intelectuales_usuario = $instancia->mostrarProduccionIntelectualUsuarioControl($id_log);

$tipo_produccion = array(
    'En aula',
    'Por grado',
    'De sección',
    'Transversal',
    'Administrativo',
    'Institucional'
);

$denominacion = array(
    'Proyecto',
    'Actividad',
    'Proceso',
    'Capacitación',
    'Aplicación (Software/App)',
    'Aplicación (Hardware/Hardware)'
);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?= BASE_URL ?>perfil/<?= $redir ?>" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Producción Intelectual (<?= trim($informacion_usuario['nombre']) ?>)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-2">
                        <hr>
                        <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Producción Intelectual</h5>
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Denominación</th>
                                    <th scope="col">Objetivo</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Duración</th>
                                    <th scope="col">Lugar</th>
                                    <th scope="col">Evidencia</th>
                                    <th scope="col">Eliminar</th>
                                    <th scope="col">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-center">
                                <?php
                                foreach($producciones_intelectuales_usuario as $produccion){
                                    $id_produccion = $produccion['id'];
                                    $nombre_produccion = $produccion['nombre'];
                                    $tipo_produccion = $produccion['tipo_produccion'];
                                    $denominacion_produccion = $produccion['denominacion'];
                                    $objetivo_produccion = $produccion['objetivo'];
                                    $descipcion_produccion = $produccion['descripcion_actividades'];
                                    $duracion = $produccion['duracion'];
                                    $lugar = $produccion['lugar'];
                                    $evidencia_produccion = $produccion['evidencia_pdf'];
                                    $observaciones = $produccion['observacion'];
                                ?>
                                <tr>
                                    <td><?=$nombre_produccion?></td>
                                    <td><?=$tipo_produccion?></td>
                                    <td><?=$denominacion_produccion?></td>
                                    <td><?=$objetivo_produccion?></td>
                                    <td><?=$descipcion_produccion?></td>
                                    <td><?=$duracion?></td>
                                    <td><?=$lugar?></td>
                                    <td>
                                        <a href="<?=PUBLIC_PATH?>upload/<?=$evidencia_produccion?>" download class="btn btn-info btn-sm">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST">
                                            <input type="hidden" value="<?=$id_produccion?>" name="id">
                                            <input type="hidden" value="<?=$id_log?>" name="id_log">
                                            <input type="hidden" value="<?= $evidencia_produccion ?>" name="name_doc">
                                            <button class="btn btn-danger btn-sm" type="submit" name="eliminar_produccion">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td><?=$observaciones?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <br>
                    <br>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_log" value="<?= $id_log ?>">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row p-2">
                                    <div class="col-lg-4 form-group">
                                        <label class="font-weight-bold" for="nombre_produccion">Nombre de la Producción <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nombre_produccion" id="nombre_produccion" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="font-weight-bold">Tipo de Producción <span class="text-danger">*</span></label>
                                        <select name="tipo_produccion" id="tipo_produccion" required>
                                            <option value="">Seleccione una opción ...</option>
                                            <?php
                                            $tipo_produccion = array(
                                                                    'En aula',
                                                                    'Por grado',
                                                                    'De sección',
                                                                    'Transversal',
                                                                    'Administrativo',
                                                                    'Institucional'
                                                                );
                                            foreach ($tipo_produccion as $produccion) {
                                                ?>
                                                <option value="<?= $produccion ?>"><?= $produccion ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="font-weight-bold">Denominación de Producción <span class="text-danger">*</span></label>
                                        <select name="denominacion_produccion" id="denominacion_produccion" required>
                                            <option value="">Seleccione una opción ...</option>
                                            <?php
                                            foreach ($denominacion as $d) {
                                                ?>
                                                <option value="<?= $d ?>"><?= $d ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="objetivo_produccion" class="font-weight-bold">Objetivo de la Producción <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="objetivo_produccion" required></textarea>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="descipcion_produccion" class="font-weight-bold">Descripción de la Producción <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="descipcion_produccion" required></textarea>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="duracion" class="font-weight-bold">Duración de la Producción (En horas)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="duracion" id="duracion" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="lugar" class="font-weight-bold">Lugar de la Producción <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lugar" id="lugar" required>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="evidencia_produccion" class="font-weight-bold">Evidencias de la Producción (Junta todas las imagenes o archivos en un solo documento PDF) <span class="text-danger">*</span></label>
                                <div class="custom-file pmd-custom-file-filled">
                                    <input type="file" class="custom-file-input file_input" id="evidencia_produccion" name="evidencia_produccion" accept=".pdf">
                                    <label class="custom-file-label file_label_evidencia_produccion" for="customfilledFile"></label>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group mt-2">
                                <label for="observaciones" class="font-weight-bold">Observaciones <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="observaciones" required placeholder="¿Qué hice bien? ¿Qué puedo mejorar? ¿Qué aprendizaje obtuve?"></textarea>
                            </div>
                            <div class="col-lg-12 form-group mt-2 text-right">
                                <button class="btn btn-hebreo btn-md" type="submit" name="enviar_produccion_intelectual">
                                    <i class="fa fa-save"></i>
                                    &nbsp;
                                    Guardar Producción
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once VISTA_PATH . 'script_and_final.php';

if(isset($_POST['enviar_produccion_intelectual'])){
    $instancia->agregarProduccionIntelectualControl();
}

if(isset($_POST['eliminar_produccion'])){
    $instancia->eliminarProduccionIntelectualControl();
}
?>