<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'enfermeria' . DS . 'ModeloEnfermeria.php';
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';

class ControlEnfermeria
{

    private static $instancia;

    public static function singleton_enfermeria()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarLimiteCategoriaControl()
    {
        $mostrar = ModeloEnfermeria::mostrarLimiteCategoriaModel();
        return $mostrar;
    }

    public function mostrarCategoriasEnfermeriaControl()
    {
        $mostrar = ModeloEnfermeria::mostrarCategoriasEnfermeriaModel();
        return $mostrar;
    }

    public function mostrarCategoriaPorIdControl($id)
    {
        $mostrar = ModeloEnfermeria::mostrarCategoriaPorIdModel($id);
        return $mostrar;
    }

    public function mostrarDatosUsuariosControl($documento)
    {
        $mostrar = ModeloEnfermeria::mostrarDatosUsuariosModel($documento);
        return $mostrar;
    }

    public function mostrarLimiteAtencionMedicaControl()
    {
        $mostrar = ModeloEnfermeria::mostrarLimiteAtencionMedicaModel();
        return $mostrar;
    }

    public function buscarAtencionMedicaControl($datos)
    {

        $fecha_hasta = (empty($datos['fecha_hasta'])) ? '' : ' AND en.fechareg <= "' . $datos['fecha_hasta'] . ' 00:00:00"';
        $fecha_desde = (empty($datos['fecha_desde'])) ? '' : ' AND en.fechareg >= "' . $datos['fecha_desde'] . ' 23:59:59"';

        $datos = array('fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'buscar' => $datos['buscar']);

        $mostrar = ModeloEnfermeria::buscarAtencionMedicaModel($datos);
        return $mostrar;
    }

    public function mostrarAtencionUsuarioControl($id)
    {
        $mostrar = ModeloEnfermeria::mostrarAtencionUsuarioModel($id);
        return $mostrar;
    }

    public function correosPadresControl($id)
    {
        $mostrar = ModeloEnfermeria::correosPadresModel($id);
        return $mostrar;
    }

    public function mostrarListadoAtencionMedicaProgramadasControl()
    {
        $mostrar = ModeloEnfermeria::mostrarListadoAtencionMedicaProgramadasModel();
        return $mostrar;
    }

    public function MostrarGraficaEnfermeriaControl($inicio, $fin)
    {
        $mostrar = ModeloEnfermeria::EstadisticasEnfermeriaModel($inicio, $fin);
        return $mostrar;
    }

    public function correosAutomaticosAtencionMedicaControl($id)
    {
        $datos_atencion       = ModeloEnfermeria::mostrarDetallesAtencionModel($id);
        $datos_usuario        = ModeloPerfil::mostrarDatosPerfilModel($datos_atencion['id_log']);
        $datos_correos_padres = ModeloEnfermeria::correosPadresModel($datos_atencion['id_user']);

        $mensaje = '
        <div style="font-size: 1.2em;">
        <p><b>Buenos d&iacute;as, cordial saludo</b>
        <br>
        <br>
        Se ha realizado un procedimiento de atenci&oacute;n medica al siguiente usuario:
        </p>
        <ul>
        <li><b>Fecha:</b> ' . date('Y-m-d', strtotime($datos_atencion['fechareg'])) . '</li>
        <li><b>Nombre:</b> ' . $datos_atencion['nom_user'] . '</li>
        <li><b>Motivo</b> ' . $datos_atencion['motivo_cons'] . '</li>
        <li><b>Observaciones:</b> ' . $datos_atencion['tratamiento'] . '</li>
        </ul>
        <p style="margin-top: 5%;"><b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '
        <br>
        <br>
        Enfermera
        <br>
        <br>
        Colegio Real - Royal School
        <br>
        <br>
        (+57-5) 359-9494 / 359-9516  EXT:107
        <br>
        <br>
        <a href="#">enfermeria@royalschool.edu.co</a>
        </b></p>
        </div>
        ';

        $correo = [];

        foreach ($datos_correos_padres as $envio_correo_padre) {
            $correo[] = $envio_correo_padre['correo'];
        }

        $correo[] = 'registro@royalschool.edu.co';
        $correo[] = 'enfermeria@royalschool.edu.co';

        $datos_correo = array(
            'asunto'  => 'Atencion Medica - Colegio Real Royal School',
            'correo'  => $correo,
            'mensaje' => $mensaje,
            'archivo' => array(''),
        );

        $enviar_correo = Correo::enviarCorreoModel($datos_correo);

        if ($enviar_correo == true) {
            $actualizar_envio = ModeloEnfermeria::envioCorreoAtencionModel($datos_atencion['id']);
        }
    }

    public function registrarCategoriaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'          => $_POST['id_log'],
                'nom_categoria'   => $_POST['nom_categoria'],
                'atencion_rapida' => $_POST['atencion_rapida'],
            );

            $guardar = ModeloEnfermeria::registrarCategoriaModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("procedimiento");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al crear", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function editarCategoriaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_categoria']) &&
            !empty($_POST['id_categoria'])
        ) {

            $datos = array(
                'id_categoria'    => $_POST['id_categoria'],
                'nom_categoria'   => $_POST['nom_categoria'],
                'atencion_rapida' => $_POST['atencion_rapida'],
            );

            $guardar = ModeloEnfermeria::editarCategoriaModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("procedimiento");
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

    public function registrarAtencionMedicaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'id_user'     => $_POST['id_user'],
                'motivo'      => $_POST['motivo'],
                'tratamiento' => $_POST['tratamiento'],
                'envio'       => $_POST['envio'],
            );

            $guardar = ModeloEnfermeria::registrarAtencionMedicaModel($datos);

            if ($guardar['guardar'] == true) {

                if ($_POST['envio'] == 1) {

                    $datos_atencion       = ModeloEnfermeria::mostrarDetallesAtencionModel($guardar['id']);
                    $datos_usuario        = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                    $datos_correos_padres = ModeloEnfermeria::correosPadresModel($datos_atencion['id_user']);

                    $mensaje = '
                    <div style="font-size: 1.2em;">
                    <p><b>Buenos d&iacute;as, cordial saludo</b>
                    <br>
                    <br>
                    Se ha realizado un procedimiento de atenci&oacute;n medica al siguiente usuario:
                    </p>
                    <ul>
                    <li><b>Fecha:</b> ' . date('Y-m-d', strtotime($datos_atencion['fechareg'])) . '</li>
                    <li><b>Nombre:</b> ' . $datos_atencion['nom_user'] . '</li>
                    <li><b>Motivo</b> ' . $datos_atencion['motivo_cons'] . '</li>
                    <li><b>Observaciones:</b> ' . $datos_atencion['tratamiento'] . '</li>
                    </ul>
                    <p style="margin-top: 5%;"><b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '
                    <br>
                    <br>
                    Enfermera
                    <br>
                    <br>
                    Colegio Real - Royal School
                    <br>
                    <br>
                    (+57-5) 359-9494 / 359-9516  EXT:107
                    <br>
                    <br>
                    <a href="#">enfermeria@royalschool.edu.co</a>
                    </b></p>
                    </div>
                    ';

                    $correo = [];

                    foreach ($datos_correos_padres as $envio_correo_padre) {
                        $correo[] = $envio_correo_padre['correo'];
                    }

                    $correo[] = 'registro@royalschool.edu.co';
                    $correo[] = 'enfermeria@royalschool.edu.co';

                    $datos_correo = array(
                        'asunto'  => 'Atencion Medica - Colegio Real Royal School',
                        'correo'  => $correo,
                        'mensaje' => $mensaje,
                        'archivo' => array(''),
                    );

                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                    if ($enviar_correo == true) {
                        $actualizar_envio = ModeloEnfermeria::envioCorreoAtencionModel($datos_atencion['id']);
                    }

                }

                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("atencion");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al crear", {color: "red"});
                </script>
                ';
            }

        }
    }

}
