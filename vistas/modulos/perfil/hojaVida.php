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

$id_perfil_user = $id_log;
$link = "0";

if (isset($_GET['id'])) {
    $id_perfil_user = base64_decode($_GET['id']);
    $link = $_GET['link'];
}

//Información personal del usuario
$datos       = $instancia->mostrarDatosPerfilControl($id_perfil_user);
$url_imgen = ($datos['imagen'] != null) ? 'upload/' . $datos['imagen'] : 'img/user.svg';
$datos_adicional = $instancia->mostrarInformacionAdicionalControl($id_perfil_user);
$tipo_documento = "";

$tipo_documento = ($datos_adicional['tipo_documento'] != null) ? $datos_adicional['tipo_documento'] : '';

switch ($tipo_documento) {
    case 1:
        $tipo_documento = "Tarjeta de Identidad";
        break;
    case 2:
        $tipo_documento = "Registro Civil";
        break;
    case 3:
        $tipo_documento = "Cedula de Ciudadania";
        break;
    case 4:
        $tipo_documento = "Cedula de Extranjeria (Extranjero)";
        break;
    case 5:
        $tipo_documento = "Pasaporte";
        break;
    case 6:
        $tipo_documento = "NUIP";
        break;
    case 7:
        $tipo_documento = "Numero de Secretaria";
        break;
    case 8:
        $tipo_documento = "Permiso Especial de Permanencia";
        break;
    case 9:
        $tipo_documento = "Permiso Protección Temporal";
        break;
    case 10:
        $tipo_documento = "Certificado de Nacimiento";
        break;
    case 11:
        $tipo_documento = "NIT";
        break;
    case 12:
        $tipo_documento = "VISA";
    default:
        $tipo_documento = "Desconocido";
        break;
}

//Formacion del usuario
$formaciones_usuario = $instancia->mostrarFormacionesFormalesUsuarioControl($id_perfil_user);
$formaciones_informales = $instancia->mostrarFormacionesInformalesUsuarioControl($id_perfil_user);

//Experiencia Laboral
$experiencias_laborales = $instancia->mostrarTodasLasExperienciasLaboralesUserControl($id_perfil_user);

//Otros documentos
$otros_documentos = $instancia->mostrarDocumentosVariosUsuarioControl($id_perfil_user);

//Produccion Intelectual
$produccion_intelectual = $instancia->mostrarProduccionIntelectualUsuarioControl($id_perfil_user);

$datos_nivel = $instancia->mostrarNivelesControl($id_super_empresa);
$ver_nivel   = ($nivel == 5) ? 'd-none' : '';


$url = ($link == "1") ? "recursos/personal" : "perfil/index";


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?= BASE_URL . $url ?>" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Hoja de vida
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <div class="circular--portrait">
                                <img src="<?=PUBLIC_PATH . $url_imgen?>">
                            </div>
                        </div>
                        <!-- <div class="col-lg-12 align-items-center">
                            <div class="circular--portrait--hv">
                                <img src="<?= PUBLIC_PATH . $foto_perfil ?>">
                            </div>
                        </div> -->
                        <div class="col-lg-12 mt-2">
                            <h2 class="text-center"><strong><?= strtoupper($datos['nombre']) . ' ' . strtoupper($datos['apellido']) ?></strong></h2>
                        </div>
                        <!-- Linea separadora -->
                        <div class="col-lg-12 mt-2">
                            <hr>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <h4 class="font-weight-bold text-center text-blue-dark mt-4 text-uppercase">
                                informacion personal
                            </h4>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="tipo_documento" class="font-weight-bold">Tipo de documento</label>
                            <input type="text" class="form-control" id="tipo_documento" value="<?= $tipo_documento ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="numero_documento" class="font-weight-bold">Numero de documento</label>
                            <input type="text" class="form-control" id="numero_documento" value="<?= $datos['documento'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="fecha_expedicion" class="font-weight-bold">Fecha de expedicion del Documento</label>
                            <input type="text" class="form-control" id="fecha_expedicion" value="<?= $datos_adicional['fecha_expedicion'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="fecha_nacimiento" class="font-weight-bold">Fecha de nacimiento</label>
                            <input type="text" class="form-control" id="fecha_nacimiento" value="<?= $datos_adicional['fecha_nacimiento'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="departamento_nacimiento" class="font-weight-bold">Departamento de nacimiento</label>
                            <input type="text" class="form-control" id="departamento_nacimiento" value="<?= strtoupper($datos_adicional['departamento_nacimiento']) ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="direccion" class="font-weight-bold">Direccion de vivienda</label>
                            <input type="text" class="form-control" id="direccion" value="<?= $datos_adicional['direccion_vivienda'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="genero" class="font-weight-bold">Genero</label>
                            <input type="text" class="form-control" id="genero" value="<?= strtoupper($datos_adicional['genero']) ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="telefono" class="font-weight-bold">Telefono</label>
                            <input type="text" class="form-control" id="telefono" value="<?= $datos['telefono'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="estrato" class="font-weight-bold">Estrato</label>
                            <input type="text" class="form-control" id="estrato" value="<?= $datos_adicional['estrato'] ?>" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="correo_institucional" class="font-weight-bold">Correo Institucional</label>
                            <input type="text" class="form-control" id="fecha_certificado" value="<?= $datos['correo'] ?>" disabled>
                        </div>
                        <!-- Linea separadora -->
                        <div class="col-lg-12 mt-2">
                            <hr>
                        </div>
                        <div class="col-lg-12 mt-2 flex-row">
                            <h4 class="font-weight-bold text-center text-blue-dark mt-4 text-uppercase">
                                Formación
                            </h4>
                            <a class="btn btn-warning btn-sm text-white" href="<?=BASE_URL?>perfil/formacion?id=<?=base64_encode($id_perfil_user)?>" target="_blank">
                                Editar formación
                                &nbsp;    
                                <i class="fa fa-edit 2x"></i>
                            </a>
                        </div>
                        <div class="table-responsive mt-2">
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Formaciones Formales</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Programa</th>
                                        <th scope="col">Nombre Institución</th>
                                        <th scope="col">Fecha grado</th>
                                        <th scope="col">Certificado</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center">
                                    <?php
                                    foreach ($formaciones_usuario as $formacion) {
                                        $id_formacion = $formacion['id'];
                                        $programa = $formacion['programa'];
                                        $institucion = $formacion['institucion'];
                                        $fecha_exp = $formacion['fecha_grado'];

                                        $certificado = $instancia->mostrarInformacionCertificadoFormacionControl($id_formacion);
                                    ?>
                                        <tr>
                                            <td><?= $programa ?></td>
                                            <td><?= $institucion ?></td>
                                            <td><?= $fecha_exp ?></td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $certificado['nombre_archivo'] ?>" download class="btn btn-success btn-sm">
                                                    <i class="fa fa-download"></i>
                                                    Descargar certificado
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive mt-2">
                            <hr>
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Formaciones Informales</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Programa</th>
                                        <th scope="col">Nombre Institución</th>
                                        <th scope="col">Fecha de expedición del certificado</th>
                                        <th>Duración</th>
                                        <th scope="col">Certificado</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center">
                                    <?php
                                    foreach ($formaciones_informales as $formacion) {
                                        $id_formacion = $formacion['id'];
                                        $programa = $formacion['programa'];
                                        $institucion = $formacion['institucion'];
                                        $fecha_grado = $formacion['fecha_expedicion_certi'];
                                        $duracion = $formacion['duracion'];
                                        $certificado = $instancia->mostrarInformacionCertificadoFormacionControl($id_formacion);
                                    ?>
                                        <tr>
                                            <td><?= $programa ?></td>
                                            <td><?= $institucion ?></td>
                                            <td><?= $fecha_grado ?></td>
                                            <td><?= $duracion ?> Horas</td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $certificado['nombre_archivo'] ?>" download class="btn btn-success btn-sm">
                                                    <i class="fa fa-download"></i>
                                                    Descargar certificado
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <hr>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <h4 class="font-weight-bold text-center text-blue-dark text-uppercase">
                                Experiencia Laboral
                            </h4>
                            <a href="<?= BASE_URL ?>perfil/experienciaLaboral?id=<?= base64_encode($id_perfil_user) ?>&link=1" class="btn btn-sm btn-warning text-decoration-none">
                                Editar Experiencia Laboral
                                &nbsp;    
                                <i class="fa fa-edit 2x"></i>
                            </a>
                        </div>
                        <div class="table-responsive mt-2">
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Experiencia Laboral</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Nombre de la empresa</th>
                                        <th scope="col">Cargo</th>
                                        <th scope="col">Fecha de ingreso</th>
                                        <th scope="col">Fecha de retiro</th>
                                        <th scope="col">Fecha de expedición del certificado de trabajo</th>
                                        <th scope="col">Certificado de trabajo</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center">
                                    <?php
                                    foreach ($experiencias_laborales as $experiencia) {
                                        $id_experiencia = $experiencia['id'];
                                        $nombre_empresa = $experiencia['nombre_empresa'];
                                        $cargo = $experiencia['cargo'];
                                        $fecha_ingreso = $experiencia['fecha_ingreso'];
                                        $fecha_retiro = ($experiencia['fecha_retiro'] != '') ? $experiencia['fecha_retiro'] : '';
                                        $nombre_doc = $experiencia['certificado_trabajo'];
                                        $fecha_certificado = ($experiencia['fecha_certificado'] != '') ? $experiencia['fecha_certificado'] : '';
                                    ?>
                                        <tr>
                                            <td><?= $nombre_empresa ?></td>
                                            <td><?= $cargo ?></td>
                                            <td><?= $fecha_ingreso ?></td>
                                            <td><?= $fecha_retiro ?></td>
                                            <td><?= ($fecha_certificado != '') ? $fecha_certificado : 'No disponible' ?></td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $nombre_doc ?>" download class="btn btn-info btn-sm">
                                                    <i class="fa fa-download"></i>
                                                    Descargar certificado
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <hr>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <h4 class="font-weight-bold text-center text-blue-dark text-uppercase">
                                Otros Documentos de Interes
                            </h4>
                            <a href="<?= BASE_URL ?>perfil/otrosDocumentos?id=<?= base64_encode($id_perfil_user) ?>&link=1" class="text-decoration-none btn-warning btn-sm">
                                Editar otros documentos de interés
                                &nbsp;    
                                <i class="fa fa-edit 2x"></i>
                            </a>
                        </div>
                        <div class="table-responsive mt-2">
                            <h5 class="text-hebreo font-weight-bold text-uppercase text-center mt-4">Otros Documentos</h5>
                            <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                                <thead class="text-center font-weight-bold">
                                    <tr class="text-center font-weight-bold">
                                        <th scope="col">Tipo de documento</th>
                                        <th scope="col">Nombre del documento</th>
                                        <th scope="col">Fecha de registro</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center text-uppercase">
                                    <?php
                                    foreach ($otros_documentos as $documento) {
                                        $tipo_doc = $documento['tipo_doc'];
                                        $nombre_doc = $documento['nombre_doc'];
                                        $fecha_reg = $documento['fechareg'];
                                    ?>
                                        <tr class="text-center">
                                            <td><?= $tipo_doc ?></td>
                                            <td><?= $nombre_doc ?></td>
                                            <td><?= $fecha_reg ?></td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $nombre_doc ?>" download class="btn btn-info btn-sm">
                                                    <i class="fa fa-download"></i>
                                                    Descargar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <hr>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <h4 class="font-weight-bold text-center text-blue-dark text-uppercase">
                                Producción Intelectual
                            </h4>
                            <a href="<?= BASE_URL ?>perfil/produccionIntelectual?id=<?= base64_encode($id_perfil_user) ?>&link=1" class="text-decoration-none btn-warning btn-sm">
                                Editar Producción Intelectual
                                &nbsp;    
                                <i class="fa fa-edit 2x"></i>
                            </a>
                        </div>
                        <div class="table-responsive mt-2">
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
                                        <th scope="col">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody class="buscar text-center">
                                    <?php
                                    foreach ($produccion_intelectual as $produccion) {
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
                                            <td><?= $nombre_produccion ?></td>
                                            <td><?= $tipo_produccion ?></td>
                                            <td><?= $denominacion_produccion ?></td>
                                            <td><?= $objetivo_produccion ?></td>
                                            <td><?= $descipcion_produccion ?></td>
                                            <td><?= $duracion ?></td>
                                            <td><?= $lugar ?></td>
                                            <td>
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $evidencia_produccion ?>" download class="btn btn-info btn-sm">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </td>
                                            <td><?= $observaciones ?></td>
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