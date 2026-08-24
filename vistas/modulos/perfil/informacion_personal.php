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

$datos       = $instancia->mostrarDatosPerfilControl($id_log);
$datos_nivel = $instancia->mostrarNivelesControl($id_super_empresa);
$ver_nivel   = ($nivel == 5) ? 'd-none' : '';

$tipos_documentos = $instancia->mostrarTiposDocumentosControl();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?= BASE_URL ?>perfil/index" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Información Personal
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" id="form_enviar" enctype="multipart/form-data">
                        <input type="hidden" value="<?= $datos['id_user'] ?>" name="id_user">
                        <input type="hidden" value="<?= $datos['pass'] ?>" name="pass_old">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="circular--portrait">
                                    <img src="<?= PUBLIC_PATH . $foto_perfil ?>">
                                </div>
                            </div>
                            <div class="col-lg-8 form-group">
                                <div class="row p-2">

                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Numero de Documento <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control numeros" name="documento" maxlength="50" minlength="1" value="<?= $datos['documento'] ?>" required>
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control letras" maxlength="50" minlength="1" value="<?= strtoupper($datos['nombre'])?>" name="nombre" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Apellido</label>
                                        <input type="text" class="form-control letras" maxlength="50" minlength="1" value="<?=strtoupper($datos['apellido'])?>" name="apellido">
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Correo institucional<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" maxlength="50" minlength="1" value="<?= $datos['correo'] ?>" name="correo" readonly>
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" maxlength="50" minlength="1" value="<?= $datos['user'] ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group <?= $ver_nivel ?>">
                                <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                                <select name="nivel" class="form-control" required>
                                    <option value="0" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_nivel as $nivel) {
                                        $id_nivel = $nivel['id'];
                                        $nombre   = $nivel['nombre'];
                                        $estado   = $nivel['activo'];

                                        $ver    = ($estado == 1) ? '' : 'd-none';
                                        $select = ($datos['id_nivel'] == $id_nivel) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id_nivel ?>" class="<?= $ver ?>" <?= $select ?>><?= $nombre ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="font-weight-bold">Foto de perfil <span class="text-danger">*</span></label>
                                <input id="file" type="file" class="file" name="archivo" accept=".png,.jpg,.jpeg">
                            </div>

                            <div class="col-lg-12 form-group mt-4">
                                <h4 class="text-hebreo font-weight-bold text-center">Cambiar Contrase&ntilde;a</h4>
                                <hr>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Nueva Contrase&ntilde;a</label>
                                <input type="password" class="form-control" maxlength="16" minlength="8" name="password" id="password">
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Confirmar Nueva Contrase&ntilde;a</label>
                                <input type="password" class="form-control" maxlength="16" minlength="8" name="conf_password" id="conf_password">
                            </div>

                        </div>
                </div>
                <div class="form-group col-lg-12 mt-2 text-right">
                    <button type="submit" class="btn btn-hebreo btn-sm" id="enviar_perfil">
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Guardar Cambios
                    </button>
                    <input type="hidden" name="perfil" value="<?= $datos['perfil'] ?>">
                </div>
                </form>
                <div class="col-lg-12 form-group mt-4">
                    <h4 class="text-hebreo font-weight-bold text-center">Información adicional personal</h4>
                    <hr>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <?php
                    $info_adicional_guardada_cedula = $instancia->mostrarInformacionAdicionalControl($datos['id_user']);
                    ?>
                    <input type="hidden" name="id_log" value="<?=$datos['id_user']?>">
                    <input type="hidden" name="id_datos" value="<?=$info_adicional_guardada_cedula['id']?>">
                    <div class="col-lg-12 form-group">
                        <div class="row p-2">
                            <?php
                            $nombre_cedula = $info_adicional_guardada_cedula['cedula_doc'] ?? '';
                            $ruta_archivo = PUBLIC_PATH_ARCH . 'upload/' . $nombre_cedula;
                            $archivo_existe = !empty($nombre_cedula) && file_exists($ruta_archivo);

                            if (!$archivo_existe) {
                            ?>
                                <!-- Formulario para subir la cédula -->
                                <div class="col-lg-12 form-group">
                                    <label class="font-weight-bold">Documento de Identidad (JPG, PNG, PDF, JPEG): <span class="text-danger">*</span></label>
                                    <div class="custom-file pmd-custom-file-filled">
                                        <input type="file" class="custom-file-input file_input" id="documento_identidad" name="documento_identidad" accept=".png, .jpg, .jpeg, .pdf">
                                        <label class="custom-file-label file_label_documento_identidad" for="customfilledFile"></label>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group text-right">
                                    <button type="submit" class="btn btn-success btn-sm" name="enviar_documento_identidad">
                                        <i class="fa fa-check"></i>
                                        &nbsp; Guardar
                                    </button>
                                </div>
                            <?php } else { ?>
                                <!-- Mostrar imagen y opción de descarga -->
                                <div class="col-lg-12 form-group">
                                    <div class="row p-2">
                                        <div class="col-lg-6 form-group">
                                            <label class="font-weight-bold">Cédula <span class="text-danger">*</span></label>
                                            <br>
                                            <img src="<?= PUBLIC_PATH ?>upload/<?= $nombre_cedula ?>" class="img-fluid rounded" alt="Cedula">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group text-right">
                                    <a href="<?= PUBLIC_PATH ?>upload/<?= $nombre_cedula ?>" download class="btn btn-info btn-sm">
                                        <i class="fa fa-download"></i>
                                        Descargar Cédula
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>

                <form method="POST">
                    <?php
                    $info_adicional_guardada = $instancia->mostrarInformacionAdicionalControl($datos['id_user']);
                    $id_usuario = $datos['id_user'];
                    ?>
                    <input type="hidden" name="id_usuario" value="<?=$id_usuario?>">
                    <div class="col-lg-12 form-group">
                        <div class="row p-2">
                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Tipo de documento <span class="text-danger">*</span></label>
                                <select name="tipo_doc" required>
                                    <option value="0" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($tipos_documentos as $tipo_documento) {
                                        $id_tipo_documento = $tipo_documento['id'];
                                        $nombre_tipo_documento = $tipo_documento['nombre'];
                                    ?>
                                        <option value="<?= $id_tipo_documento ?>" <?=($id_tipo_documento == $info_adicional_guardada['tipo_documento'])?'selected':''?>><?= $nombre_tipo_documento ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Fecha de expedicion</label>
                                <input type="date" class="form-control" name="fecha_expedicion" value="<?=$info_adicional_guardada['fecha_expedicion']?>">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Fecha de nacimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_nacimiento" value="<?=$info_adicional_guardada['fecha_nacimiento']?>" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Departamento de nacimiento<span class="text-danger">*</span></label>
                                <select name="departamento_nacimiento" required>
                                    <option value="">Seleccione una opción ...</option>
                                    <?php
                                    $departamentos = array(
                                        'amazonas' => 'Amazonas',
                                        'antioquia' => 'Antioquia',
                                        'arauca' => 'Arauca',
                                        'atlantico' => 'Atlántico',
                                        'bolivar' => 'Bolívar',
                                        'boyaca' => 'Boyacá',
                                        'caldas' => 'Caldas',
                                        'caqueta' => 'Caquetá',
                                        'casanare' => 'Casanare',
                                        'cauca' => 'Cauca',
                                        'cesar' => 'Cesar',
                                        'choco' => 'Chocó',
                                        'cordoba' => 'Córdoba',
                                        'cundinamarca' => 'Cundinamarca',
                                        'guainia' => 'Guainía',
                                        'guaviare' => 'Guaviare',
                                        'huila' => 'Huila',
                                        'la-guajira' => 'La Guajira',
                                        'magdalena' => 'Magdalena',
                                        'meta' => 'Meta',
                                        'narino' => 'Nariño',
                                        'norte-de-santander' => 'Norte de Santander',
                                        'putumayo' => 'Putumayo',
                                        'quindio' => 'Quindío',
                                        'risaralda' => 'Risaralda',
                                        'san-andres-y-providencia' => 'San Andrés y Providencia',
                                        'santander' => 'Santander',
                                        'sucre' => 'Sucre',
                                        'tolima' => 'Tolima',
                                        'valle-del-cauca' => 'Valle del Cauca',
                                        'vaupes' => 'Vaupés',
                                        'vichada' => 'Vichada'
                                    );
                                    foreach ($departamentos as $departamento => $nombre_departamento) {
                                        $id_departamento = $departamento;
                                        ?>
                                            <option value="<?= $id_departamento ?>" <?=($id_departamento == $info_adicional_guardada['departamento_nacimiento'])?'selected':''?>><?= $nombre_departamento ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label name="direccion" class="font-weight-bold">Direccion de vivienda<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" value="<?=$info_adicional_guardada['direccion_vivienda']?>" name="direccion" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Genero <span class="text-danger">*</span></label>
                                <select name="genero" required>
                                    <option value="">Seleccione una opcion...</option>
                                    <?php
                                    $generos = array(
                                        'masculino' => 'Masculino',
                                        'femenino' => 'Femenino'
                                    );
                                    foreach ($generos as $genero => $nombre_genero) {
                                        $id_genero = $genero;
                                        ?>
                                            <option value="<?= $id_genero ?>" <?=($id_genero == $info_adicional_guardada['genero'])?'selected':''?>><?= $nombre_genero ?></option>
                                        <?php
                                    }
                                    ?> 
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Telefono</label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" value="<?= $datos['telefono'] ?>" name="telefono" required>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label class="font-weight-bold">Estrato <span class="text-danger">*</span></label>
                                <select name="estrato" required>
                                    <option value="">Seleccione una opción ...</option>
                                    <?php
                                    $estratos = array(
                                        '1' => 'Estrato 1 (bajo-bajo)',
                                        '2' => 'Estrato 2 (bajo)',
                                        '3' => 'Estrato 3 (medio-bajo)',
                                        '4' => 'Estrato 4 (medio)',
                                        '5' => 'Estrato 5 (medio-alto)',
                                        '6' => 'Estrato 6 (alto)'
                                    );
                                    foreach ($estratos as $estrato => $nombre_estrato) {
                                        $id_estrato = $estrato;
                                        ?>
                                            <option value="<?= $id_estrato ?>" <?=($id_estrato == $info_adicional_guardada['estrato'])?'selected':''?>><?= $nombre_estrato ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12 mt-2 text-right">
                                <button type="submit" class="btn btn-hebreo btn-sm" id="enviar_perfil" name="informacion_adicional">
                                    <i class="fa fa-save"></i>
                                    &nbsp;
                                    Guardar información adicional
                                </button>
                            </div>
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

if (isset($_POST['nombre'])) {
    $instancia->editarPerfilControl();
}

if(isset($_POST['informacion_adicional'])){
    $instancia->agregarInformacionAdicionalControl();
}

if(isset($_POST['enviar_documento_identidad'])){
    $instancia->agregarDocumentoIdentidadControl();
}
?>

<script src="<?= PUBLIC_PATH ?>js/validaciones.js"></script>