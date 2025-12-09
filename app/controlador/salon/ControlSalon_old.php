<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'salon' . DS . 'ModeloSalon.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';

class ControlSalon
{

    private static $instancia;

    public static function singleton_salon()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarSalonesControl()
    {
        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::mostrarSalonesModel();
        return $mostrar;
    }

    public function mostrarSalonesReservaControl()
    {
        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::mostrarSalonesReservaModel();
        return $mostrar;
    }

    public function mostrarSalonesIdReservaControl($datos)
    {
        $salon = ($datos['salon'] == '') ? '' : ' AND r.id_salon = ' . $datos['salon'];
        $fecha = ($datos['fecha'] == '') ? '' : ' AND r.fecha_reserva = "' . $datos['fecha'] . '"';

        $datos = array('salon' => $salon, 'fecha' => $fecha, 'user' => $datos['user']);

        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::mostrarSalonesIdReservaModel($datos);
        return $mostrar;
    }

    public function mostrarHorasControl()
    {
        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::mostrarHorasModel();
        return $mostrar;
    }

    public function mostrarDatosDetalleReservaControl()
    {
        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::mostrarDatosDetalleReservaModel();
        return $mostrar;
    }

    public function buscarDatosDetalleReservaControl($datos)
    {

        $salon = ($datos['salon'] == '') ? '' : ' AND r.id_salon = ' . $datos['salon'];
        $fecha = ($datos['fecha'] == '') ? '' : ' AND r.fecha_reserva = "' . $datos['fecha'] . '"';

        $datos = array('salon' => $salon, 'fecha' => $fecha, 'user' => $datos['user']);

        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::buscarDatosDetalleReservaModel($datos);
        return $mostrar;
    }

    public function contarPortatilDisponibleControl($fecha)
    {
        $comando = ModeloSalon::comandoSQL();
        $mostrar = ModeloSalon::contarPortatilDisponibleModel($fecha);
        return $mostrar;
    }

    public function guardarSalonControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $portatil = (isset($_POST['portatil'])) ? $_POST['portatil'] : 'no';
            $sonido   = (isset($_POST['sonido'])) ? $_POST['sonido'] : 'no';

            $datos = array(
                'id_log'   => $_POST['id_log'],
                'nombre'   => $_POST['nombre'],
                'portatil' => $portatil,
                'sonido'   => $sonido,
            );

            $guardar = ModeloSalon::guardarSalonModel($datos);

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
                ohSnap("Error al crear salon", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function reservarSalonControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $portatil = (isset($_POST['portatil'])) ? $_POST['portatil'] : 'no';
            $sonido   = (isset($_POST['sonido'])) ? $_POST['sonido'] : 'no';

            if ($portatil == 'si') {
                $reservar_portatil = ModeloSalon::reservarPortatilModel($_POST['salon'], $_POST['fecha']);
                $options_portatil  = $reservar_portatil['id'];
            } else {
                $options_portatil = 'no';
            }

            $horas_texto      = '';
            $confirmado_salon = ($_POST['salon'] == 9) ? 1 : 2;

            $array_horas = array();
            $array_horas = $_POST['horas'];

            $it = new MultipleIterator();

            $it->attachIterator(new ArrayIterator($array_horas));

            foreach ($it as $hora) {
                $datos = array(
                    'id_log'     => $_POST['id_log'],
                    'salon'      => $_POST['salon'],
                    'portatil'   => $options_portatil,
                    'sonido'     => $sonido,
                    'hora'       => $hora[0],
                    'fecha'      => $_POST['fecha'],
                    'detalle'    => $_POST['detalles'],
                    'confirmado' => $confirmado_salon,
                );

                $guardar     = ModeloSalon::reservarSalonModel($datos);
                $buscar_hora = ModeloSalon::mostrarHorasIdModel($hora[0]);
                $horas_texto .= $buscar_hora['horas'] . ', ';
            }

            if ($guardar['guardar'] == true) {
                $comando       = ModeloSalon::comandoSQL();
                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
                $datos_reserva = ModeloSalon::mostrarDatosReservaModel($guardar['id']);

                $option_portatil = ($datos_reserva['portatil'] != 'no') ? 'si' : 'no';

                echo '
                <script>
                ohSnap("Reservado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("apartar");
                }
                </script>
                ';

                $confirmado = ($datos_reserva['confirmado'] == 1) ? 'Pendiente' : 'Confirmado';
                $confirmado = ($datos_reserva['confirmado'] == 0) ? 'Rechazada' : $confirmado;

                $mensaje = '
                <div>
                <p style="font-size: 1.2em;">
                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado una reserva:
                </p>
                <ul  style="font-size: 1.3em;">
                <li><b>Salon:</b> ' . $datos_reserva['salon'] . '</li>
                <li><b>Portatil:</b> ' . $option_portatil . ' </li>
                <li><b>Sonido:</b> ' . $datos_reserva['sonido'] . '</li>
                <li><b>Fecha reserva:</b> ' . $datos_reserva['fecha_reserva'] . '</li>
                <li><b>Hora reserva:</b> ' . $horas_texto . '</li>
                <li><b>Estado de la reserva:</b> ' . $confirmado . '</li>
                </ul>
                <p style="font-size: 1.2em;"> <span><b>Detalle de la reserva: </b></span>' . $datos_reserva['detalle_reserva'] . '</p>
                </div>
                ';
//mario.esmeral@royalschool.edu.co

                $correos = ($_POST['salon'] == 9) ? array('reservas.sistemas@royalschool.edu.co', 'mario.esmeral@royalschool.edu.co', $datos_usuario['correo']) : array('reservas.sistemas@royalschool.edu.co');

                $datos_correo = array(
                    'asunto'  => 'Reserva de salon (' . $datos_reserva['salon'] . ')',
                    'correo'  => $correos,
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                //var_dump($datos_correo);

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

            } else {
                echo '
                <script>
                ohSnap("Error al crear salon", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function mostrarDatosSalonIdControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $id_salon = $_POST['id'];
            $comando  = ModeloSalon::comandoSQL();
            $mostrar  = ModeloSalon::mostrarDatosSalonIdModel($id_salon);
            return $mostrar;
        }
    }

    public function mostrarHorasDisponiblesControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['salon']) &&
            !empty($_POST['salon'])
        ) {

            $datos = array(
                'salon' => $_POST['salon'],
                'fecha' => $_POST['fecha'],
            );

            $comando = ModeloSalon::comandoSQL();
            $mostrar = ModeloSalon::mostrarHorasDisponiblesModel($datos);
            return $mostrar;
        }
    }

    public function aprobarReservaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $aprobar = ModeloSalon::aprobarReservaModel($_POST['id']);

            if ($aprobar == true) {

                $comando       = ModeloSalon::comandoSQL();
                $datos_reserva = ModeloSalon::mostrarDatosReservaModel($_POST['id']);
                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($datos_reserva['id_user']);

                $confirmado = ($datos_reserva['confirmado'] == 1) ? 'Pendiente' : 'Confirmado';
                $confirmado = ($datos_reserva['confirmado'] == 0) ? 'Rechazada' : $confirmado;

                $mensaje = '
                <div>
                <p style="font-size: 1.2em;">
                Se ha APROBADO la reserva del usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b>:
                </p>
                <ul  style="font-size: 1.3em;">
                <li><b>Salon:</b> ' . $datos_reserva['salon'] . '</li>
                <li><b>Portatil:</b> ' . $datos_reserva['portatil'] . ' </li>
                <li><b>Sonido:</b> ' . $datos_reserva['sonido'] . '</li>
                <li><b>Fecha reserva:</b> ' . $datos_reserva['fecha_reserva'] . '</li>
                <li><b>Hora reserva:</b> ' . $datos_reserva['hora_numero'] . '</li>
                <li><b>Estado de la reserva:</b> ' . $confirmado . '</li>
                </ul>
                <p style="font-size: 1.2em;"> <span><b>Detalle de la reserva: </b></span>' . $datos_reserva['detalle_reserva'] . '</p>
                </div>
                ';

                $correos = array('reservas.sistemas@royalschool.edu.co', $datos_usuario['correo']);

                $datos_correo = array(
                    'asunto'  => 'Reserva APROBADA de salon (' . $datos_reserva['salon'] . ')',
                    'correo'  => $correos,
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            }

            $rs = ($aprobar == true) ? 'ok' : 'no';
            return $rs;
        }
    }

    public function rechazarReservaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $aprobar = ModeloSalon::rechazarReservaModel($_POST['id']);

            if ($aprobar == true) {

                $comando       = ModeloSalon::comandoSQL();
                $datos_reserva = ModeloSalon::mostrarDatosReservaModel($_POST['id']);
                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($datos_reserva['id_user']);

                $confirmado = ($datos_reserva['confirmado'] == 1) ? 'Pendiente' : 'Confirmado';
                $confirmado = ($datos_reserva['confirmado'] == 0) ? 'Rechazada' : $confirmado;

                $mensaje = '
                <div>
                <p style="font-size: 1.2em;">
                Se ha RECHAZADO la reserva del usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b>:
                </p>
                <ul  style="font-size: 1.3em;">
                <li><b>Salon:</b> ' . $datos_reserva['salon'] . '</li>
                <li><b>Portatil:</b> ' . $datos_reserva['portatil'] . ' </li>
                <li><b>Sonido:</b> ' . $datos_reserva['sonido'] . '</li>
                <li><b>Fecha reserva:</b> ' . $datos_reserva['fecha_reserva'] . '</li>
                <li><b>Hora reserva:</b> ' . $datos_reserva['hora_numero'] . '</li>
                <li><b>Estado de la reserva:</b> ' . $confirmado . '</li>
                </ul>
                <p style="font-size: 1.2em;"> <span><b>Detalle de la reserva: </b></span>' . $datos_reserva['detalle_reserva'] . '</p>
                </div>
                ';

                $correos = array('reservas.sistemas@royalschool.edu.co', $datos_usuario['correo']);

                $datos_correo = array(
                    'asunto'  => 'Reserva RECHAZADA de salon (' . $datos_reserva['salon'] . ')',
                    'correo'  => $correos,
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            }

            $rs = ($aprobar == true) ? 'ok' : 'no';
            return $rs;
        }
    }
}
