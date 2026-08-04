<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'solicitud' . DS . 'ModeloSolicitud.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once CONTROL_PATH . 'hash.php';
require_once CONTROL_PATH . 'numeros.php';

class ControlSolicitud
{

    private static $instancia;

    public static function singleton_solicitud()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarSolicitudesControl($limite = 100)
    {
        $mostrar = ModeloSolicitud::mostrarSolicitudesModel($limite);
        return $mostrar;
    }

    public function mostrarSolicitudesAprobadasCoordinadorControl($limite = 100){
        $mostrar = ModeloSolicitud::mostrarSolicitudesAprobadasCoordinadorModel($limite);
        return $mostrar;
    }

    public function buscarSolicitudesAprobadasCoordinadorControl($datos)
    {
        $buscar       = (!empty($datos['buscar'])) ? trim($datos['buscar']) : '';
        $fecha_inicio = (!empty($datos['fecha_inicio'])) ? trim($datos['fecha_inicio']) : '';
        $fecha_fin    = (!empty($datos['fecha_fin'])) ? trim($datos['fecha_fin']) : '';
        $area         = (!empty($datos['area'])) ? trim($datos['area']) : '';
        $usuario      = (!empty($datos['usuario'])) ? trim($datos['usuario']) : '';
        $grado        = (!empty($datos['grado'])) ? trim($datos['grado']) : '';

        $datos = array(
            'buscar'       => $buscar,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
            'area'         => $area,
            'usuario'      => $usuario,
            'grado'        => $grado,
        );

        $mostrar = ModeloSolicitud::buscarSolicitudesAprobadasCoordinadorModel($datos);
        return $mostrar;
    }

    public function mostrarSolucitudesNivelControl($id_nivel, $limite = 100){
        $mostrar = ModeloSolicitud::mostrarSolucitudesNivelModel($id_nivel, $limite);
        return $mostrar;
    }

    public function buscarSolicitudesControl($datos)
    {
        $buscar  = (!empty($datos['buscar'])) ? trim($datos['buscar']) : '';
        $fecha   = (!empty($datos['fecha'])) ? trim($datos['fecha']) : '';
        $area    = (!empty($datos['area'])) ? trim($datos['area']) : '';
        $usuario = (!empty($datos['usuario'])) ? trim($datos['usuario']) : '';
        $nivel   = (!empty($datos['nivel'])) ? trim($datos['nivel']) : '';

        $datos = array(
            'buscar'  => $buscar,
            'fecha'   => $fecha,
            'area'    => $area,
            'usuario' => $usuario,
            'nivel'   => $nivel,
        );

        $mostrar = ModeloSolicitud::buscarSolicitudesModel($datos);
        return $mostrar;
    }

    public function buscarSolicitudesCotizacionControl($datos)
    {
        $buscar  = (!empty($datos['buscar'])) ? trim($datos['buscar']) : '';
        $fecha   = (!empty($datos['fecha'])) ? trim($datos['fecha']) : '';
        $area    = (!empty($datos['area'])) ? trim($datos['area']) : '';
        $usuario = (!empty($datos['usuario'])) ? trim($datos['usuario']) : '';

        $datos = array(
            'buscar'  => $buscar,
            'fecha'   => $fecha,
            'area'    => $area,
            'usuario' => $usuario,
        );

        $mostrar = ModeloSolicitud::buscarSolicitudesCotizacionModel($datos);
        return $mostrar;
    }

    public function mostrarSolicitudesUsuarioControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarSolicitudesUsuarioModel($id);
        return $mostrar;
    }

    public function mostrarDatosSolicitudIdControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarDatosSolicitudIdModel($id);
        return $mostrar;
    }

    public function mostrarProdcutosSolicitudControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarProdcutosSolicitudModel($id);
        return $mostrar;
    }

    public function mostrarDatosVerificacionControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarDatosVerificacionModel($id);
        return $mostrar;
    }

    public function cotizacionSolicitudControl($id)
    {
        $mostrar = ModeloSolicitud::cotizacionSolicitudModel($id);
        return $mostrar;
    }

    public function mostrarCotizacionControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarCotizacionModel($id);
        return $mostrar;
    }

    //------------------------INICIAL------------------------------//

    public function mostrarDatosSolicitudInicialIdControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarDatosSolicitudInicialIdModel($id);
        return $mostrar;
    }

    public function mostrarProdcutosSolicitudInicialControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarProdcutosSolicitudInicialModel($id);
        return $mostrar;
    }

    public function mostrarDatosVerificacionInicialControl($id)
    {
        $mostrar = ModeloSolicitud::mostrarDatosVerificacionInicialModel($id);
        return $mostrar;
    }

    public function mostrarCotizacionesControl()
    {
        $mostrar = ModeloSolicitud::mostrarCotizacionesModel();
        return $mostrar;
    }

    public function buscarCotizacionesControl($datos)
    {

        $fecha = (!empty($datos['fecha'])) ? ' AND c.fechareg LIKE "%' . $datos['fecha'] . '%"' : '';

        $datos = array('fecha' => $fecha, 'buscar' => $datos['buscar']);

        $mostrar = ModeloSolicitud::buscarCotizacionesModel($datos);
        return $mostrar;
    }

    public function registrarSolicitudControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $grado = (empty($_POST['grado'])) ? 0 : $_POST['grado'];

            $datos_solicitud = array(
                'id_log' => $_POST['id_log'],
                'id_user' => $_POST['id_user'],
                'fecha_solicitud' => $_POST['fecha_solicitud'],
                'area' => $_POST['area'],
                'justificacion' => $_POST['justificacion'],
                'grado' => $grado,
            );

            $guardar = ModeloSolicitud::registrarSolicitudModel($datos_solicitud);

            if ($guardar['guardar'] == true) {

                $array_producto = array();
                $array_producto = $_POST['producto'];

                $array_cantidad = array();
                $array_cantidad = $_POST['cantidad'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_producto));
                $it->attachIterator(new ArrayIterator($array_cantidad));

                foreach ($it as $a) {
                    $datos_producto = array(
                        'id_log' => $_POST['id_log'],
                        'id_solicitud' => $guardar['id'],
                        'producto' => $a[0],
                        'cantidad' => $a[1],
                    );

                    $guardar_producto = ModeloSolicitud::registrarProdcuctosModel($datos_producto);
                }

                if ($guardar_producto['guardar'] == true) {

                    $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                    $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($guardar['id']);
                    $datos_coordinador = ModeloPerfil::mostrarDatosCoordinadorModel($datos_solicitud['id_nivel']);

                    $curso = (empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom'];

                    $mensaje = '
                    <div>
                    <p style="font-size: 1.6em;">
                    El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado una solicitud de compra:
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                    $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    Se ha recibio su solitud de compra y esta en proceso de autorizacion:
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> Pendiente de autorizacion</li>
                    </ul>
                    </p>
                    </div>
                    ';

                    $datos_correo = array(
                        'asunto' => 'Solicitud de compra No. ' . $guardar['id'],
                        'correo' => array($datos_coordinador['correo']),
                        //'correo'  => array('jesuspolo00@gmail.com'),
                        'user' => 'Administrador',
                        'mensaje' => $mensaje,
                        'archivo' => array(''),
                    );

                    $datos_correo_usuario = array(
                        'asunto' => 'Solicitud de compra No. ' . $guardar['id'],
                        'correo' => array($datos_usuario['correo']),
                        //'correo'  => array('jesuspolo00@gmail.com'),
                        'user' => 'Administrador',
                        'mensaje' => $mensaje_usuario,
                        'archivo' => array(''),
                    );

                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                    $enviar_correo_usuario = Correo::enviarCorreoModel($datos_correo_usuario);

                    if($enviar_correo == true){
                        echo '
                        <script>
                        ohSnap("Guardado correctamente y enviado el correo al coordinador!", {color: "green", "duration": "1000"});
                        setTimeout(recargarPagina, 1050);

                        function recargarPagina()
                        {
                            window.location.replace("index");
                        }
                        </script>
                        ';
                    }else{
                        echo '
                        <script>
                        ohSnap("Error al enviar correo!", {color:"red", "duration":"1000"});
                        </script>
                        ';
                    }

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina, 1050);

                    function recargarPagina()
                    {
                        window.location.replace("index");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Error de solicitud!", {color:"red", "duration":"1000"});
                    </script>
                    ';
                }
            }
        }
    }

    public function confirmarSolicitudControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $fecha_aplazado = ($_POST['fecha_aplazado'] == '') ? '0000-00-00' : $_POST['fecha_aplazado'];

            $datos_solicitud = array(
                'id_solicitud' => $_POST['id_solicitud'],
                'estado' => $_POST['estado'],
                'fecha_solicitado' => date('Y-m-d H:i:s'),
                'fecha_aplazado' => $fecha_aplazado,
                'observacion' => $_POST['observacion'],
                'id_log' => $_POST['id_log'],
                'iva' => '',
                'id_proveedor' => 0,
            );

            $confirmar = ModeloSolicitud::actualizarEstadoModel($datos_solicitud);
            $eliminar_productos = ModeloSolicitud::removerProductosSolicitudModel($_POST['id_solicitud']);

            if ($eliminar_productos == true) {

                $array_producto = array();
                $array_producto = $_POST['producto'];

                $array_cantidad = array();
                $array_cantidad = $_POST['cantidad'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_producto));
                $it->attachIterator(new ArrayIterator($array_cantidad));

                foreach ($it as $a) {
                    $datos_producto = array(
                        'id_log' => $_POST['id_log'],
                        'id_solicitud' => $_POST['id_solicitud'],
                        'producto' => $a[0],
                        'cantidad' => $a[1],
                    );

                    $guardar_producto = ModeloSolicitud::registrarProdcuctosModel($datos_producto);
                }

                if ($guardar_producto == true) {

                    $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                    $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($_POST['id_solicitud']);
                    $datos_compras = ModeloPerfil::mostrarDatosComprasModel(26);

                    $curso = (empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom'];
                    $estado = ($_POST['estado'] == 1) ? 'Aprobada' : 'Rechazada';

                    $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    La solicitud de compra No. <b>' . $_POST['id_solicitud'] . '</b> ha sido ' . $estado . ' por el coordinador de nivel
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> ' . $estado . '</li>
                    <li><b>Observacion Coordinador:</b> ' . $_POST['observacion'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                    $datos_correo = array(
                        'asunto' => 'Solicitud de compra No. ' . $_POST['id_solicitud'],
                        'correo' => array($datos_compras['correo']),
                       // 'correo'  => array('angel.vargas@royalschool.edu.co'),
                        'user' => 'Administrador',
                        'mensaje' => $mensaje_usuario,
                        'archivo' => array(''),
                    );

                   $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                    /*-------------------------------------------------------------*/
                    $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    La solicitud de compra No. <b>' . $_POST['id_solicitud'] . '</b> ha sido Rechazada.
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> Rechazada</li>
                    <li><b>Motivo del rechazo:</b> ' . $_POST['motivo'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                    $datos_correo_usuario = array(
                        'asunto' => 'Solicitud de compra No. ' . $guardar['id'] . ' - Rechazada',
                        'correo' => array($datos_usuario['correo']),
                        //'correo'  => array('jesuspolo00@gmail.com'),
                        'user' => 'Administrador',
                        'mensaje' => $mensaje_usuario,
                        'archivo' => array(''),
                    );

              //      $enviar_correo = Correo::enviarCorreoModel($datos_correo_usuario);

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color:"green", "duration":"1000"});
                    setTimeout(recargarPagina, 1050);

                    function recargarPagina()
                    {
                        window.location.replace("listado");
                    }
                    </script>
                    ';
                }
            }
        }
    }

    public function verificarSolicitudControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_solicitud' => $_POST['id_solicitud'],
                'id_log' => $_POST['id_log'],
                'observacion_recibido' => $_POST['observacion_recibido'],
            );

            $guardar = ModeloSolicitud::recibidoSolicitudModel($datos);

            if ($guardar == true) {

                $array_producto = array();
                $array_producto = $_POST['producto'];

                $array_cantidad = array();
                $array_cantidad = $_POST['cantidad_recibida'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_producto));
                $it->attachIterator(new ArrayIterator($array_cantidad));

                foreach ($it as $dato) {

                    $datos_detalle = array(
                        'id_detalle' => $dato[0],
                        'cantidad_recibida' => $dato[1],
                        'id_log' => $_POST['id_log'],
                    );

                    $verificacion = ModeloSolicitud::verificarSolicitudModel($datos_detalle);
                }

                if ($verificacion == true) {
                    echo ' <script>
                    window . open("' . BASE_URL . 'imprimir/solicitud/cartaEntrega?solicitud=' . base64_encode($_POST['id_solicitud']) . '")
                    </script> ';

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color:"green", "duration":"1000"});
                    setTimeout(recargarPagina, 1050);

                    function recargarPagina()
                    {
                        window.location.replace("index");
                    }
                    </script>
                    ';
                }
            }
        }
    }

    public function removerProductoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $eliminar = ModeloSolicitud::removerProductoModel($_POST['id']);
            return $eliminar;
        }
    }

    public function agregarProductoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {

            $datos = array(
                'id_solicitud' => $_POST['id'],
                'id_log' => $_POST['id_log'],
                'producto' => '',
                'cantidad' => '',
            );

            $guardar = ModeloSolicitud::registrarProdcuctosModel($datos);
            return $guardar;
        }
    }

    public function anularSolicitudControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_log' => $_POST['id_log'],
                'id_solicitud' => $_POST['id_solicitud'],
                'estado' => 2,
                'motivo' => $_POST['motivo'],
            );

            $guardar = ModeloSolicitud::anularSolicitudModel($datos);

            if ($guardar == true) {

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($_POST['id_solicitud']);
                $solicitud = ModeloSolicitud::getSolicitud($_POST['id_solicitud']);

                $curso = (empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom'];

                $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    La solicitud de compra No. <b>' . $_POST['id_solicitud'] . '</b> ha sido Rechazada.
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> Rechazada</li>
                    <li><b>Motivo del rechazo:</b> ' . $_POST['motivo'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                $datos_correo = array(
                    'asunto' => 'Solicitud de compra No. ' . $solicitud[0]['id'] . ' - Rechazada',
                    //'correo'  => array($datos_usuario['correo']),
                    'correo' => array('ing.jpertuz@gmail.com'),
                    'user' => 'Administrador',
                    'mensaje' => $mensaje_usuario,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                echo '
                <script>
                ohSnap("Anulada correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina, 1050);

                function recargarPagina()
                {
                    window.location.replace("listado");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al anular!", {color:"red", "duration":"1000"});
                </script>
                ';
            }
        }
    }

    public function anularSolicitudComprasControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_log' => $_POST['id_log'],
                'id_solicitud' => $_POST['id_solicitud'],
                'estadocompra' => 2,
                'motivo' => $_POST['motivo'],
            );

            $guardar = ModeloSolicitud::anularSolicitudComprasModel($datos);

            if ($guardar == true) {

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($_POST['id_solicitud']);
                $solicitud = ModeloSolicitud::getSolicitud($_POST['id_solicitud']);

                $curso = (empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom'];

                $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    La solicitud de compra No. <b>' . $_POST['id_solicitud'] . '</b> ha sido Rechazada.
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> Rechazada</li>
                    <li><b>Motivo del rechazo:</b> ' . $_POST['motivo'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                $datos_correo = array(
                    'asunto' => 'Solicitud de compra No. ' . $solicitud[0]['id'] . ' - Rechazada',
                    //'correo'  => array($datos_usuario['correo']),
                    'correo' => array('ing.jpertuz@gmail.com'),
                    'user' => 'Administrador',
                    'mensaje' => $mensaje_usuario,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                echo '
                <script>
                ohSnap("Anulada correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina, 1050);

                function recargarPagina()
                {
                    window.location.replace("listado");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al anular!", {color:"red", "duration":"1000"});
                </script>
                ';
            }
        }
    }

    public function subirCotizacionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $cont = 0;
            for ($i = 0; $i < count($_FILES['archivo']['name']); $i++) {

                $nombre_archivo = '';

                if (isset($_FILES['archivo']['name'][$i]) && !empty($_FILES['archivo']['name'][$i])) {

                    $nombre_archivo = guardarVariosArchivos($_FILES['archivo'], $i);
                }

                $datos = array(
                    'id_log' => $_POST['id_log'],
                    'id_solicitud' => $_POST['id_solicitud'],
                    'archivo' => $nombre_archivo,
                );

                $guardar = ModeloSolicitud::subirCotizacionModel($datos);

                $cont++;
            }

            if ($guardar == true) {

                echo '
                <script>
                ohSnap("Subido correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina, 1050);

                function recargarPagina()
                {
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al subir!", {color:"red", "duration":"1000"});
                </script>
                ';
            }
        }
    }

    public function realizarPedidoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log' => $_POST['id_log'],
                'id_solicitud' => $_POST['id_solicitud'],
                'proveedor' => $_POST['proveedor'],
                'observacion' => $_POST['observacion'],
            );

            $guardar = ModeloSolicitud::realizarPedidoModel($datos);
            $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($_POST['id_solicitud']);

            $mensaje = '
            <div>
            <p style="font-size: 1.6em;">
            La solicitud de compra No. <b>' . $datos_solicitud['id'] . '</b> ha sido procesada por parte del departamento de compras.
            </p>
            <p>
            <ul style="font-size: 1.4em;">
            <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
            <li><b>Justificación:</b> ' . $datos_solicitud['justificacion'] . ' </li>
            <li><b>Grado:</b> ' . $datos_solicitud['curso_nom'] . '</li>
            li><b>Area:</b> ' . $datos_solicitud['area_nom'] . '</li>   
            <li><b>Solicitante:</b> ' . $datos_solicitud['nom_usuario'] . '</li>
            <li><b>Aprobado por:</b> ' . $datos_solicitud['nom_aprobado'] . '</li>
            <li><b>Fecha de revisión:</b> ' . $datos_solicitud['fecha_revision'] . '</li>
            <li><b>Fecha de revisión:</b> ' . $datos_solicitud['fecha_revision'] . '</li>
            </ul>
            </p>
            </div>
            ';  

            $datos_correo = array(
                'asunto' => 'Solicitud de compra No. ' . $datos_solicitud['id'],
                'correo' => array($datos_solicitud['correo']),
                //'correo'  => array('jesuspolo00@gmail.com'),
                'user' => 'Administrador',
                'mensaje' => $mensaje,
                'archivo' => array(''),
            );

            if ($guardar == true) {

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                echo '
                <script>
                ohSnap("Pedido realizado!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina, 1050);

                function recargarPagina()
                {
                    window.location.replace("listado");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error de solicitud!", {color:"red", "duration":"1000"});
                </script>
                ';
            }

        }
    }

    public function revisionSolicitudControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_solicitud' => $_POST['id_solicitud'],
                'id_log' => $_POST['id_log'],
                'observacion' => $_POST['observacion'],
            );

            $revision_solicitud = ModeloSolicitud::revisionSolicitudModel($datos);

            if ($revision_solicitud == true) {

                $array_producto = array();
                $array_producto = $_POST['id_producto'];

                $array_cantidad = array();
                $array_cantidad = $_POST['cantidad_existencia'];

                $array_existencia = array();

                $prod = ControlSolicitud::mostrarProdcutosSolicitudControl($datos['id_solicitud']);
                foreach ($prod as $key => $v) {
                    $array_existencia = $_POST['existencia_' . $v['id']];
                }

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_producto));
                $it->attachIterator(new ArrayIterator($array_cantidad));
                $it->attachIterator(new ArrayIterator($array_existencia));

                foreach ($it as $dato) {

                    $datos_producto = array(
                        'id_producto' => $dato[0],
                        'existencia' => $dato[2],
                        'cantidad_existencia' => $dato[1],
                    );

                    $actualizar_producto = ModeloSolicitud::revisionProdcutoSolicitudModel($datos_producto);

                }

                if ($actualizar_producto == true) {

                    $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                    $datos_solicitud = ModeloSolicitud::mostrarDatosSolicitudIdModel($_POST['id_solicitud']);

                    $curso = (empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom'];

                    $mensaje_usuario = '
                    <div>
                    <p style="font-size: 1.6em;">
                    La solicitud de compra No. <b>' . $_POST['id_solicitud'] . '</b> ha sido aprobada.
                    </p>
                    <p>
                    <ul style="font-size: 1.4em;">
                    <li><b>Fecha de solicitud:</b> ' . $datos_solicitud['fecha_solicitud'] . '</li>
                    <li><b>Area:</b> ' . $datos_solicitud['area_nom'] . ' </li>
                    <li><b>Grado:</b> ' . $curso . '</li>
                    <li><b>Solicitante:</b> ' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</li>
                    <li><b>Justificacion:</b> ' . $datos_solicitud['justificacion'] . '</li>
                    <li><b>Estado:</b> Aprobado</li>
                    <li><b>Observacion:</b> ' . $_POST['observacion'] . '</li>
                    </ul>
                    </p>
                    </div>
                    ';

                    $datos_correo = array(
                        'asunto' => 'Solicitud de compra No. ' . $_POST['id_solicitud'] . ' - Aprobada',
                        'correo' => array($datos_usuario['correo']),
                        //'correo'  => array('jesuspolo00@gmail.com'),
                        'user' => 'Administrador',
                        'mensaje' => $mensaje_usuario,
                        'archivo' => array(''),
                    );

                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                    echo '
                    <script>
                    ohSnap("Aprobado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina, 1050);

                    function recargarPagina()
                    {
                        window.location.replace("listado");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Error de solicitud!", {color:"red", "duration":"1000"});
                    </script>
                    ';
                }
            }

        }
    }

    public function aprobarCotizacionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log' => $_POST['id_log'],
                'id_solicitud' => $_POST['id_solicitud'],
                'observacion' => $_POST['observacion'],
            );

            $aprobar_cotizacion = ModeloSolicitud::aprobarCotizacionSolicitudModel($datos);

            if ($aprobar_cotizacion == true) {

                $array_cotizacion = array();
                $array_cotizacion = $_POST['cotizacion'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_cotizacion));

                foreach ($it as $dato) {

                    $datos_cotizacion = array(
                        'id_solicitud' => $_POST['id_solicitud'],
                        'cotizacion' => $dato[0],
                    );

                    $guardar = ModeloSolicitud::aprobarCotizacionModel($datos_cotizacion);

                }

                if ($guardar == true) {
                    echo '
                    <script>
                    ohSnap("Aprobado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina, 1050);

                    function recargarPagina()
                    {
                        window.location.replace("listado");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Error de aprobacion!", {color:"red", "duration":"1000"});
                    </script>
                    ';
                }
            }

        }
    }

}
