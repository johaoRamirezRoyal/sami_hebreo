<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'matricula' . DS . 'ModeloMatricula.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once CONTROL_PATH . 'numeros.php';

class ControlMatricula
{

    private static $instancia;

    public static function singleton_matricula()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarContratosConfiguradosControl()
    {
        $mostrar = ModeloMatricula::mostrarContratosConfiguradosModel();
        return $mostrar;
    }

    public function mostrarDatosEstudianteControl($id)
    {
        $mostrar = ModeloMatricula::mostrarDatosEstudianteModel($id);
        return $mostrar;
    }

    public function datosContratoUltimoControl()
    {
        $mostrar = ModeloMatricula::datosContratoUltimoModel();
        return $mostrar;
    }

    public function datosUltimoContratoNivelControl($nivel)
    {
        $mostrar = ModeloMatricula::datosUltimoContratoNivelModel($nivel);
        return $mostrar;
    }

    public function mostrarTodosContratosControl($id)
    {
        $mostrar = ModeloMatricula::mostrarTodosContratosModel($id);
        return $mostrar;
    }

    public function datosContratoIdControl($id)
    {
        $mostrar = ModeloMatricula::datosContratoIdModel($id);
        return $mostrar;
    }

    public function mostrarDatosAcudienteControl($id)
    {
        $mostrar = ModeloMatricula::mostrarDatosAcudienteModel($id);
        return $mostrar;
    }

    public function mostrarDatosAcudienteResponsableControl($id)
    {
        $mostrar = ModeloMatricula::mostrarDatosAcudienteResponsableModel($id);
        return $mostrar;
    }

    public function aprobarFirmaAcudienteControl($id, $estado)
    {
        $mostrar = ModeloMatricula::aprobarFirmaAcudienteModel($id, $estado);
        return $mostrar;
    }

    public function denegarFirmaAcudienteControl($id, $estado)
    {
        $mostrar = ModeloMatricula::denegarFirmaAcudienteModel($id, $estado);
        return $mostrar;
    }

    public function mostrarContratoGeneradoControl($id, $year)
    {
        $mostrar = ModeloMatricula::mostrarContratoGeneradoModel($id, $year);
        return $mostrar;
    }

    public function mostrarDatosContratoEstudianteControl($id)
    {
        $mostrar = ModeloMatricula::mostrarDatosContratoEstudianteModel($id);
        return $mostrar;
    }

    public function mostrarTiposDocumentoControl()
    {
        $mostrar = ModeloMatricula::mostrarTiposDocumentoModel();
        return $mostrar;
    }

    public function mostrarEstratosControl()
    {
        $mostrar = ModeloMatricula::mostrarEstratosModel();
        return $mostrar;
    }

    public function mostrarDatosTipoViviendaControl()
    {
        $mostrar = ModeloMatricula::mostrarDatosTipoViviendaModel();
        return $mostrar;
    }

    public function mostrarDatosTipoSangreControl()
    {
        $mostrar = ModeloMatricula::mostrarDatosTipoSangreModel();
        return $mostrar;
    }

    public function mostrarDatosReligionControl()
    {
        $mostrar = ModeloMatricula::mostrarDatosReligionModel();
        return $mostrar;
    }

    public function mostrarDatosEstadoPadresControl()
    {
        $mostrar = ModeloMatricula::mostrarDatosEstadoPadresModel();
        return $mostrar;
    }

    public function mostrarContactoEmergenciaControl()
    {
        $mostrar = ModeloMatricula::mostrarContactoEmergenciaModel();
        return $mostrar;
    }

    public function mostrarDatosEpsControl()
    {
        $mostrar = ModeloMatricula::mostrarDatosEpsModel();
        return $mostrar;
    }

    public function mostrarInformacionEstudianteControl($id)
    {
        $mostrar = ModeloMatricula::mostrarInformacionEstudianteModel($id);
        return $mostrar;
    }

    public function mostrarHistoriaClinicaControl($id)
    {
        $mostrar = ModeloMatricula::mostrarHistoriaClinicaModel($id);
        return $mostrar;
    }

    public function mostrarDocumentosEstudianteControl($anio, $id)
    {
        $mostrar = ModeloMatricula::mostrarDocumentosEstudianteModel($anio, $id);
        return $mostrar;
    }

    public function promoverCursosTodosControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos_estudiante = ModeloUsuarios::mostrarTodosEstudiantesModel();

            foreach ($datos_estudiante as $estudiante) {

                $id_estudiante = $estudiante['id_user'];
                $curso         = $estudiante['id_curso'];
                $curso_proximo = $estudiante['id_curso_proximo'];

                $datos = array(
                    'id_log'        => $_POST['id_log'],
                    'id_estudiante' => $id_estudiante,
                    'curso'         => $curso,
                    'curso_proximo' => $curso_proximo,
                );

                $guardar = ModeloMatricula::promoverCursosTodosModel($datos);

            }

            if ($guardar == true) {

                echo '
                <script>
                ohSnap("Promovido correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al promover", {color: "red"});
                </script>
                ';
            }
        }

    }

    public function informacionAcudienteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nom_archivo   = $_POST['firma_ant'];
            $firma_digital = $_POST['firma_digital'];

            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {
                $eliminar = eliminarArchivo($nom_archivo);
                if ($eliminar == true) {
                    $nom_archivo   = guardarArchivo($_FILES['foto']);
                    $firma_digital = 0;
                }
            }

            $datos = array(
                'id_log'           => $_POST['id_log'],
                'id_user'          => $_POST['id_user'],
                'correo'           => $_POST['correo'],
                'telefono'         => $_POST['telefono'],
                'lugar_expedicion' => $_POST['lugar_expedicion'],
                'direccion'        => $_POST['direccion'],
                'ciudad'           => $_POST['ciudad'],
                'celular'          => $_POST['celular'],
                'responsable'      => $_POST['responsable'],
                'firma'            => $nom_archivo,
                'terminos'         => $_POST['terminos'],
                'firma_digital'    => $firma_digital,
            );

            $guardar = ModeloMatricula::informacionAcudienteModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index?id=' . base64_encode($_POST['id_log']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al guardar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function contratoMatriculaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $pension   = str_replace('.', '', $_POST['pension']);
            $matricula = str_replace('.', '', $_POST['matricula']);

            $datos = array(
                'id_log'    => $_POST['id_log'],
                'anio'      => $_POST['anio'],
                'nivel'     => $_POST['nivel'],
                'pension'   => $pension,
                'matricula' => $matricula,
            );

            $guardar = ModeloMatricula::contratoMatriculaModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("contrato");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al guardar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function editarContratoControlMatriculaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $pension   = str_replace('.', '', $_POST['pension_edit']);
            $matricula = str_replace('.', '', $_POST['matricula_edit']);

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'id_contrato' => $_POST['id_contrato'],
                'pension'     => $pension,
                'matricula'   => $matricula,
            );

            $guardar = ModeloMatricula::editarContratoControlMatriculaModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("contrato");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al actualizar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function guardarDescuentoContratoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'              => $_POST['id_log'],
                'id_contrato'         => $_POST['id_contrato'],
                'id_user'             => $_POST['id_user'],
                'descuento_pension'   => $_POST['descuento_pension'],
                'descuento_matricula' => $_POST['descuento_matricula'],
            );

            $guardar = ModeloMatricula::guardarDescuentoContratoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al guardar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function generarContratoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'        => $_POST['id_log'],
                'id_estudiante' => $_POST['id_estudiante'],
                'id_contrato'   => $_POST['id_contrato'],
                'curso_proximo' => $_POST['curso_proximo'],
                'anio'          => $_POST['anio'],
            );

            $guardar = ModeloMatricula::generarContratoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Generado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al generar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function actualizarCursoProximoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_estudiante']) &&
            !empty($_POST['id_estudiante'])
        ) {

            $datos = array(
                'id_estudiante' => $_POST['id_estudiante'],
                'curso'         => $_POST['curso'],
            );

            $guardar = ModeloMatricula::actualizarCursoProximoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al actualizar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function guardarInformacionEstudianteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_estudiante']) &&
            !empty($_POST['id_estudiante'])
        ) {

            $datos_usuario = array(
                'id_estudiante' => $_POST['id_estudiante'],
                'documento'     => $_POST['documento'],
                'nombre'        => $_POST['nombre'],
                'apellido'      => $_POST['apellido'],
                'telefono'      => $_POST['telefono'],
                'correo'        => $_POST['correo'],
            );

            $actualizar_usuario = ModeloMatricula::actualizarEstudianteUsuarioModel($datos_usuario);

            if ($actualizar_usuario == true) {

                $validar_estudiante = ModeloMatricula::validarRegistroInfoEstudianteModel($_POST['id_estudiante']);

                $datos_estudiante = array(
                    'id_estudiante'        => $_POST['id_estudiante'],
                    'tipo_documento'       => $_POST['tipo_documento'],
                    'lugar_expedicion'     => $_POST['lugar_expedicion'],
                    'genero'               => $_POST['genero'],
                    'fecha_nacimiento'     => $_POST['fecha_nacimiento'],
                    'pais_nac'             => $_POST['pais_nac'],
                    'departamento_nac'     => $_POST['departamento_nac'],
                    'ciudad_nac'           => $_POST['ciudad_nac'],
                    'pais_resi'            => $_POST['pais_resi'],
                    'departamento_resi'    => $_POST['departamento_resi'],
                    'ciudad_resi'          => $_POST['ciudad_resi'],
                    'direccion'            => $_POST['direccion'],
                    'barrio'               => $_POST['barrio'],
                    'estrato'              => $_POST['estrato'],
                    'tipo_vivienda'        => $_POST['tipo_vivienda'],
                    'telefono_resi'        => $_POST['telefono_resi'],
                    'celular_resi'         => $_POST['celular_resi'],
                    'hermanos'             => $_POST['hermanos'],
                    'hijo_mayor'           => $_POST['hijo_mayor'],
                    'correo_institucional' => $_POST['correo_institucional'],
                    'estatura'             => $_POST['estatura'],
                    'peso'                 => $_POST['peso'],
                    'tipo_sangre'          => $_POST['tipo_sangre'],
                    'religion'             => $_POST['religion'],
                    'estado_civil_padres'  => $_POST['estado_civil_padres'],
                    'contactar'            => $_POST['contactar'],
                    'establecimiento'      => $_POST['establecimiento'],
                    'autorizo_imagen'      => $_POST['autorizo_imagen'],
                    'id_log'               => $_POST['id_log'],
                );

                if (empty($validar_estudiante['id'])) {
                    $guardar_informacion = ModeloMatricula::guardarInformacionEstudianteModel($datos_estudiante);
                } else {
                    $guardar_informacion = ModeloMatricula::actualizarInformacionEstudianteModel($datos_estudiante);
                }

                if ($guardar_informacion == true) {

                    $validar_clinico = ModeloMatricula::validarHistoriaClinicaEstudianteModel($_POST['id_estudiante']);

                    $datos_clinicos = array(
                        'id_estudiante'             => $_POST['id_estudiante'],
                        'medicamentos_pree'         => $_POST['medicamentos_pree'],
                        'cual_medicamento'          => $_POST['cual_medicamento'],
                        'dosis_medicamento'         => $_POST['dosis_medicamento'],
                        'alergias'                  => $_POST['alergias'],
                        'alergia_cual'              => $_POST['alergia_cual'],
                        'alergia_tratamiento'       => $_POST['alergia_tratamiento'],
                        'alergia_alimento'          => $_POST['alergia_alimento'],
                        'alergia_alimento_cual'     => $_POST['alergia_alimento_cual'],
                        'dieta'                     => $_POST['dieta'],
                        'dieta_cual'                => $_POST['dieta_cual'],
                        'seguro'                    => $_POST['seguro'],
                        'loratadina'                => $_POST['loratadina'],
                        'dolex'                     => $_POST['dolex'],
                        'milanta'                   => $_POST['milanta'],
                        'buscapina'                 => $_POST['buscapina'],
                        'eps'                       => $_POST['eps'],
                        'nombre_medico'             => $_POST['nombre_medico'],
                        'telefono_medico'           => $_POST['telefono_medico'],
                        'clinica'                   => $_POST['clinica'],
                        'recomendacion_medica'      => $_POST['recomendacion_medica'],
                        'enfermedad_ojos'           => $_POST['enfermedad_ojos'],
                        'lentes'                    => $_POST['lentes'],
                        'asma'                      => $_POST['asma'],
                        'enfermedad_respiratoria'   => $_POST['enfermedad_respiratoria'],
                        'enfermedad_cardiaca'       => $_POST['enfermedad_cardiaca'],
                        'enfermedad_gastricas'      => $_POST['enfermedad_gastricas'],
                        'enfermedad_diabetes'       => $_POST['enfermedad_diabetes'],
                        'convulsiones'              => $_POST['convulsiones'],
                        'celiaquismo'               => $_POST['celiaquismo'],
                        'psiquiatra'                => $_POST['psiquiatra'],
                        'hepatitis'                 => $_POST['hepatitis'],
                        'anemia'                    => $_POST['anemia'],
                        'hipertension_arterial'     => $_POST['hipertension_arterial'],
                        'hipotension_arterial'      => $_POST['hipotension_arterial'],
                        'epilepsia'                 => $_POST['epilepsia'],
                        'hernias'                   => $_POST['hernias'],
                        'migrana'                   => $_POST['migrana'],
                        'fractura_trauma'           => $_POST['fractura_trauma'],
                        'accidente_cardio'          => $_POST['accidente_cardio'],
                        'artritis'                  => $_POST['artritis'],
                        'desnutricion'              => $_POST['desnutricion'],
                        'obesidad'                  => $_POST['obesidad'],
                        'cancer'                    => $_POST['cancer'],
                        'vih'                       => $_POST['vih'],
                        'enfermedad_renal'          => $_POST['enfermedad_renal'],
                        'otros'                     => $_POST['otros'],
                        'protesis'                  => $_POST['protesis'],
                        'cirugias'                  => $_POST['cirugias'],
                        'perdido_conocimiento'      => $_POST['perdido_conocimiento'],
                        'enfermedad_actual'         => $_POST['enfermedad_actual'],
                        'medicamentos_no'           => $_POST['medicamentos_no'],
                        'condicion_salud'           => $_POST['condicion_salud'],
                        'diabetes_familiar'         => $_POST['diabetes_familiar'],
                        'cancer_familiar'           => $_POST['cancer_familiar'],
                        'hipertension'              => $_POST['hipertension'],
                        'enfermedad_cardiovascular' => $_POST['enfermedad_cardiovascular'],
                        'covid'                     => $_POST['covid'],
                        'etnia'                     => $_POST['etnia'],
                        'inclusion'                 => $_POST['inclusion'],
                        'otros_familiar'            => $_POST['otros_familiar'],
                        'id_log'                    => $_POST['id_log'],
                    );

                    if (empty($validar_clinico['id'])) {
                        $guardar_clinica = ModeloMatricula::guardarHistoriaClinicaModel($datos_clinicos);
                    } else {
                        $guardar_clinica = ModeloMatricula::actualizarHistoriaClinicaModel($datos_clinicos);
                    }

                    if ($guardar_clinica == true) {

                        $datos_validar = ModeloMatricula::mostrarContratoGeneradoModel($_POST['id_estudiante'], date('Y'));

                        if (empty($datos_validar['id']) || $datos_validar['estado'] == 2) {

                            $datos_contrato = array(
                                'id_log'        => $_POST['id_log'],
                                'id_estudiante' => $_POST['id_estudiante'],
                                'id_contrato'   => $_POST['id_contrato'],
                                'curso_proximo' => $_POST['curso_proximo'],
                                'anio'          => $_POST['anio'],
                            );

                            $guardar = ModeloMatricula::generarContratoModel($datos_contrato);
                        }

                        $datos_acudientes = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                        $datos_estudiante = ModeloMatricula::mostrarDatosEstudianteModel($_POST['id_estudiante']);

                        $mensaje = '
                            <div>
                            <p style="font-size: 1.4em;">
                            El acudiente <b>' . $datos_acudientes['nombre'] . ' ' . $datos_acudientes['apellido'] . '</b> ha realizado ha empezado el proceso de matricula del estudiante:
                            </p>
                            <p>
                            <ul style="font-size: 1.4em;">
                            <li><b>Identificacion:</b> ' . $datos_estudiante['documento'] . '</li>
                            <li><b>Estudiante:</b> ' . $datos_estudiante['nombre'] . ' ' . $datos_estudiante['apellido'] . '</li>
                            <li><b>Curso:</b> ' . $datos_estudiante['nom_curso'] . ' </li>
                            <li><b>Curso Proximo:</b> ' . $datos_estudiante['curso_proximo_est'] . ' </li>
                            <li><b>Estado Proceso:</b> Pendiente</li>
                            </ul>
                            </p>
                            </div>
                            ';

                        $datos_correo = array(
                            'asunto'  => 'Proceso de matricula',
                            'correo'  => array('peggy.robles@royalschool.edu.co', $datos_acudientes['correo']),
                            'user'    => 'Administrador',
                            'mensaje' => $mensaje,
                            'archivo' => array(''),
                        );

                        $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                        echo '
                        <script>
                        ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                        setTimeout(recargarPagina,1050);

                        function recargarPagina(){
                            window.location.replace("index?id=' . base64_encode($_POST['id_log']) . '");
                        }
                        </script>
                        ';
                    } else {
                        echo '
                        <script>
                        ohSnap("Error al guardar", {color: "red"});
                        </script>
                        ';
                    }

                }

            }

        }
    }

    public function subirDocumentosEstudianteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_estudiante_documentos']) &&
            !empty($_POST['id_estudiante_documentos'])
        ) {

            $url = ($_POST['url'] == 1) ? 'index?id=' . base64_encode($_POST['id_log']) : 'informacion?estudiante=' . base64_encode($_POST['id_estudiante_documentos']);

            $documento_est       = '';
            $certificado         = '';
            $documento_acudiente = '';

            if (isset($_FILES['documento_est']['name']) && !empty($_FILES['documento_est']['name'])) {
                $documento_est = guardarArchivo($_FILES['documento_est']);
            }

            if (isset($_FILES['certificado']['name']) && !empty($_FILES['certificado']['name'])) {
                $certificado = guardarArchivo($_FILES['certificado']);
            }

            if (isset($_FILES['documento_acudiente']['name']) && !empty($_FILES['documento_acudiente']['name'])) {
                $documento_acudiente = guardarArchivo($_FILES['documento_acudiente']);
            }

            $datos = array(
                'id_log'               => $_POST['id_log'],
                'id_estudiante'        => $_POST['id_estudiante_documentos'],
                'documento_estudiante' => $documento_est,
                'certificado'          => $certificado,
                'documento_acudiente'  => $documento_acudiente,
            );

            $guardar = ModeloMatricula::subirDocumentosEstudianteModel($datos);

            if ($guardar == true) {
                echo '
            <script>
            ohSnap("Subido correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
                window.location.replace("' . $url . '");
            }
            </script>
            ';
            } else {
                echo '
            <script>
            ohSnap("Error al subir", {color: "red"});
            </script>
            ';
            }

        }
    }

    public function aprobarContratoEstudianteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'        => $_POST['id_log'],
                'id_estudiante' => $_POST['id_estudiante'],
                'id_contrato'   => $_POST['id_contrato'],
            );

            $guardar = ModeloMatricula::aprobarContratoEstudianteModel($datos);

            if ($guardar == true) {

                $datos_usuario    = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                $datos_acudientes = ModeloMatricula::mostrarDatosAcudienteModel($_POST['id_estudiante']);

                $correos_acudientes = array();

                foreach ($datos_acudientes as $acudiente) {
                    $correos_acudientes[] = $acudiente['correo'];
                }

                $correos_acudientes[] = 'peggy.robles@royalschool.edu.co';

                $datos_estudiante = ModeloMatricula::mostrarDatosEstudianteModel($_POST['id_estudiante']);

                $mensaje = '
            <div>
            <p style="font-size: 1.4em;">
            El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha aprobado el proceso de matricula del estudiante:
            </p>
            <p>
            <ul style="font-size: 1.4em;">
            <li><b>Identificacion:</b> ' . $datos_estudiante['documento'] . '</li>
            <li><b>Estudiante:</b> ' . $datos_estudiante['nombre'] . ' ' . $datos_estudiante['apellido'] . '</li>
            <li><b>Curso:</b> ' . $datos_estudiante['nom_curso'] . ' </li>
            <li><b>Curso Proximo:</b> ' . $datos_estudiante['curso_proximo_est'] . ' </li>
            <li><b>Estado Proceso:</b> Aprobado</li>
            </ul>
            </p>
            </div>
            ';

                $datos_correo = array(
                    'asunto'  => 'Proceso de matricula',
                    'correo'  => $correos_acudientes,
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                echo '
            <script>
            ohSnap("Aprobado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
                window.location.replace("index");
            }
            </script>
            ';
            } else {
                echo '
            <script>
            ohSnap("Error al aprobar", {color: "red"});
            </script>
            ';
            }
        }
    }

    public function denegarContratoEstudianteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'        => $_POST['id_log'],
                'id_estudiante' => $_POST['id_estudiante'],
                'id_contrato'   => $_POST['id_contrato_denegar'],
                'motivo'        => $_POST['motivo'],
            );

            $guardar = ModeloMatricula::denegarContratoEstudianteModel($datos);

            if ($guardar == true) {

                $datos_usuario    = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                $datos_acudientes = ModeloMatricula::mostrarDatosAcudienteModel($_POST['id_estudiante']);

                $correos_acudientes = array();

                foreach ($datos_acudientes as $acudiente) {
                    $correos_acudientes[] = $acudiente['correo'];
                }

                $correos_acudientes[] = 'peggy.robles@royalschool.edu.co';

                $datos_estudiante = ModeloMatricula::mostrarDatosEstudianteModel($_POST['id_estudiante']);

                $mensaje = '
            <div>
            <p style="font-size: 1.4em;">
            El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha rechazado el proceso de matricula del estudiante:
            </p>
            <p>
            <ul style="font-size: 1.4em;">
            <li><b>Identificacion:</b> ' . $datos_estudiante['documento'] . '</li>
            <li><b>Estudiante:</b> ' . $datos_estudiante['nombre'] . ' ' . $datos_estudiante['apellido'] . '</li>
            <li><b>Curso:</b> ' . $datos_estudiante['nom_curso'] . ' </li>
            <li><b>Curso Proximo:</b> ' . $datos_estudiante['curso_proximo_est'] . ' </li>
            <li><b>Estado Proceso:</b> Rechazado</li>
            <li><b>Motivo del rechazo:</b> ' . $_POST['motivo'] . '</li>
            </ul>
            </p>
            </div>
            ';

                $datos_correo = array(
                    'asunto'  => 'Proceso de matricula',
                    'correo'  => $correos_acudientes,
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                echo '
            <script>
            ohSnap("Denegado correctamente!", {color: "yellow", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
                window.location.replace("index");
            }
            </script>
            ';
            } else {
                echo '
            <script>
            ohSnap("Error al aprobar", {color: "red"});
            </script>
            ';
            }
        }
    }

}
