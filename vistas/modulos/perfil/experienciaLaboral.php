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

$instancia = ControlPerfil::singleton_perfil();

if(isset($_GET['id'])){
    $id_log = base64_decode($_GET['id']);
}

$experiencias_laborales = $instancia->mostrarTodasLasExperienciasLaboralesUserControl($id_log);
$informacion_usuario = $instancia->mostrarDatosPerfilControl($id_log);

$redir = (isset($_GET['id'])) ? 'hojaVida?id=' . $_GET['id'] . '&link=1' : 'hojaVida';
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
                        Experiencia Laboral <?php if(isset($_GET['id'])){ ?> (<?= trim($informacion_usuario['nombre']) ?>) <?php } ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <button class="btn btn-hebreo btn-sm" type="button" data-toggle="modal" data-target="#modal_experiencia_laboral">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Agregar Nueva Experiencia Laboral
                            </button>
                        </div>
                        <div class="modal fade" id="modal_experiencia_laboral" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="exampleModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                        <div class="modal-header p-3">
                                            <h4 class="modal-title text-hebreo font-weight-bold">Agregar Experiencia Laboral</h4>
                                        </div>
                                        <div class="modal-body border-0">
                                            <div class="row p-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nombre de la empresa: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nombre_empresa" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Cargo del trabajo: <span class="text-danger">*</span></label>
                                                        <select name="cargo" class="form-control" required onchange="mostrarOtroCargo(this)">
                                                            <?php
                                                            $cargos = [
                                                                        "Vicerrector",
                                                                        "Coordinador de bilingüismo",
                                                                        "Secretaria académica",
                                                                        "Coordinador de nivel",
                                                                        "Secretaria de nivel",
                                                                        "Psicólogo",
                                                                        "Docente",
                                                                        "Asistente docente",
                                                                        "Coordinador de gestión humana",
                                                                        "Secretaria de gestion humana",
                                                                        "Director administrativo",
                                                                        "Coordinador de sistemas",
                                                                        "Auxiliar de sistemas",
                                                                        "Asistente administrativo",
                                                                        "Supervisor de infraestructura",
                                                                        "Gestor de comunicaciones",
                                                                        "Bibliotecólogo",
                                                                        "Auxiliar de recursos",
                                                                        "Enfermera",
                                                                        "Recepcionista",
                                                                        "Mensajero",
                                                                        "Coordinador de admisiones",
                                                                        "Director financiero",
                                                                        "Contador",
                                                                        "Auxiliar contable",
                                                                        "Tesorero",
                                                                        "Otro:"
                                                                    ];
                                                            ?>
                                                            <option value="" selected>Seleccione una opcion...</option>
                                                            <?php
                                                            foreach ($cargos as $cargo) {
                                                                ?>
                                                                <option value="<?= $cargo ?>"><?= $cargo ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Fecha de Ingreso: <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="fecha_ingreso" required>
                                                    </div>
                                                </div>

                                                <!-- Checkbox para saber si sigue trabajando en ese empleo -->
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Sigue trabajando en este empleo: <span class="text-danger">*</span></label>
                                                        <select name="trabaja_en_este_empleo" id="trabaja_en_este_empleo" required>
                                                            <option value="0" selected>Seleccione una opcion...</option>
                                                            <option value="1">Si</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6" style="display: none;" id="grupo_fecha_certificado">
                                                    <div class="form-group">
                                                            <label class="font-weight-bold">Fecha de expedición del certificado: <span class="text-danger">*</span> </label>
                                                            <input type="date" class="form-control" name="fecha_certificado">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6" style="display: none;" id="grupo_fecha_retiro">
                                                    <div class="form-group">
                                                            <label class="font-weight-bold">Fecha de retiro: <span class="text-danger">*</span> </label>
                                                            <input type="date" class="form-control" name="fecha_retiro">
                                                    </div>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {
                                                        function toggleFechas() {
                                                            const valor = $("#trabaja_en_este_empleo").val();
                                                            if (valor === "1") {
                                                                $("#grupo_fecha_certificado").show();
                                                                $("#grupo_fecha_retiro").hide();
                                                            } else if (valor === "0") {
                                                                $("#grupo_fecha_certificado").hide();
                                                                $("#grupo_fecha_retiro").show();
                                                            } else {
                                                                $("#grupo_fecha_certificado").hide();
                                                                $("#grupo_fecha_retiro").hide();
                                                            }
                                                        }

                                                        toggleFechas(); // Ejecutar por si hay valor cargado
                                                        $("#trabaja_en_este_empleo").on("change", toggleFechas);
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="form-group pt-2" id="otroCargoContainer" style="display: none;">
                                                        <label class="font-weight-bold">Otro cargo: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="otro_cargo">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Certificado de trabajo (JPG, PNG, PDF, JPEG): <span class="text-danger">*</span></label>
                                                        <div class="custom-file pmd-custom-file-filled">
                                                            <input type="file" class="custom-file-input file_input" id="certificado_trabajo" name="certificado_trabajo" accept=".png, .jpg, .jpeg, .pdf">
                                                            <label class="custom-file-label file_label_certificado_trabajo" for="customfilledFile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button class="btn btn-success btn-sm" type="submit" name="enviar_experiencia_laboral">
                                                        <i class="fa fa-check"></i>
                                                        &nbsp;
                                                        Guardar
                                                    </button>
                                                </div>
                                                <div class="col-lg-12 alert-yellow text-center mt-4 p-2" style="border-radius: 15px;">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    &nbsp;
                                                    <strong>¡Atención!</strong>
                                                    &nbsp;
                                                    Si cuenta con más de 5 años en el Colegio Real Royal School no es requisito adjuntar experiencia anterior.
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-2">
                            <hr>
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Experiencia Laboral</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Nombre de la empresa</th>
                                        <th scope="col">Cargo</th>
                                        <th scope="col">Fecha de ingreso</th>
                                        <th scope="col">Fecha de retiro</th>
                                        <th scope="col">Fecha de expedición del certificado de trabajo</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center">
                                    <?php
                                    foreach($experiencias_laborales as $experiencia){
                                        $id_experiencia = $experiencia['id'];
                                        $nombre_empresa = $experiencia['nombre_empresa'];
                                        $cargo = $experiencia['cargo'];
                                        $fecha_ingreso = $experiencia['fecha_ingreso'];
                                        $fecha_retiro = ($experiencia['fecha_retiro'] != '') ? $experiencia['fecha_retiro'] : '';
                                        $nombre_doc = $experiencia['certificado_trabajo'];
                                        $fecha_certificado = ($experiencia['fecha_certificado'] != '') ? $experiencia['fecha_certificado'] : '';
                                        ?>
                                        <tr>
                                            <td><?=$nombre_empresa?></td>
                                            <td><?=$cargo?></td>
                                            <td><?=$fecha_ingreso?></td>
                                            <td><?=$fecha_retiro?></td>
                                            <td><?=($fecha_certificado != '')?$fecha_certificado:'No disponible'?></td>
                                            <td>
                                                <a href="<?=PUBLIC_PATH?>upload/<?=$nombre_doc?>" download class="btn btn-info btn-sm">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <form method="POST">
                                                    <input type="hidden" value="<?=$id_experiencia?>" name="id">
                                                    <input type="hidden" value="<?= $nombre_doc ?>" name="nombre_doc">
                                                    <input type="hidden" value="<?= $id_log ?>" name="redir">
                                                    <button class="btn btn-danger btn-sm mt-2" type="submit" name="eliminar_experiencia_laboral">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
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
</div>
<?php
if(isset($_POST['enviar_experiencia_laboral'])){
    $instancia->insertarNuevaExperienciaLaboralControl();
}

if(isset($_POST['eliminar_experiencia_laboral'])){
    $instancia->eliminarExperienciaLaboralControl();
}

?>
<script>
function mostrarOtroCargo(select) {
    const otro = document.getElementById("otroCargoContainer");
    if (select.value.includes("Otro")) {
        otro.style.display = "block";
    } else {
        otro.style.display = "none";
        document.getElementById("otro_cargo").value = "";
    }
}
</script>

