<?php
date_default_timezone_set('America/Bogota');
mb_internal_encoding("UTF-8");
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

    public function buscarDatosDetalleUsuarioReservaControl($datos)

    {



        $salon = ($datos['salon'] == '') ? '' : ' AND r.id_salon = ' . $datos['salon'];

        $fecha = ($datos['fecha'] == '') ? '' : ' AND r.fecha_reserva = "' . $datos['fecha'] . '"';



        $datos = array('salon' => $salon, 'fecha' => $fecha, 'user' => $datos['user'], 'id_user' => $datos['id_user']);



        $comando = ModeloSalon::comandoSQL();

        $mostrar = ModeloSalon::buscarDatosDetalleUsuarioReservaModel($datos);

        return $mostrar;

    }

    public function mostrarSalonesIdUsuarioReservaControl($datos)

    {

        $salon = ($datos['salon'] == '') ? '' : ' AND r.id_salon = ' . $datos['salon'];

        $fecha = ($datos['fecha'] == '') ? '' : ' AND r.fecha_reserva = "' . $datos['fecha'] . '"';



        $datos = array('salon' => $salon, 'fecha' => $fecha, 'user' => $datos['user'], 'id_user' => $datos['id_user']);



        $comando = ModeloSalon::comandoSQL();

        $mostrar = ModeloSalon::mostrarSalonesIdUsuarioReservaModel($datos);

        return $mostrar;

    }

    public function mostrarDatosDetalleUsuarioReservaControl($id_log)

    {

        $comando = ModeloSalon::comandoSQL();

        $mostrar = ModeloSalon::mostrarDatosDetalleUsuarioReservaModel($id_log);

        return $mostrar;

    }

    public function mostrarSalonesUsuarioReservaControl($id_log)

    {

        $comando = ModeloSalon::comandoSQL();

        $mostrar = ModeloSalon::mostrarSalonesUsuarioReservaModel($id_log);

        return $mostrar;

    }

    public function eliminarReservasControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $array_reserva = array();

            $array_reserva = $_POST['check_reserva'];



            $it = new MultipleIterator();



            $it->attachIterator(new ArrayIterator($array_reserva));



            $salon_texto = '';

            $horas_texto = '';



            foreach ($it as $reserva) {



                $id_reserva = $reserva[0];



                $datos = array(

                    'id_reserva'   => $id_reserva,

                    'id_cancelado' => $_POST['id_log'],

                );





                $cancelar = ModeloSalon::eliminarReservasModel($datos);



                $datos_reserva = ModeloSalon::mostrarDatosReservaModel($id_reserva);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);




                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha cancelado la reserva:

                </p>

                <ul  style="font-size: 1.3em;">

                <li><b>Salon:</b> ' . $datos_reserva['salon'] . '</li>

                <li><b>Sonido:</b> ' . $datos_reserva['sonido'] . '</li>

                <li><b>Fecha reserva:</b> ' . $datos_reserva['fecha_reserva'] . '</li>

                <li><b>Hora reserva:</b> ' . $datos_reserva['hora_numero'] . '</li>

                <li><b>Estado Reserva:</b> Cancelado</li>

                </ul>

                <p style="font-size: 1.2em;"> <span><b>Detalle de la reserva: </b></span>' . $datos_reserva['detalle_reserva'] . '</p>

                </div>

                ';



                $correos = ($_POST['salon'] == 9) ? array($datos_usuario['correo']) : array('dcamerow@gmail.com','angel.vargas@royalschool.edu.co','jrodriguez@colegiohebreounion.edu.co','jcervantes@colegiohebreounion.edu.co','idiaz@colegiohebreounion.edu.co','japaricio@colegiohebreounion.edu.co');


                $datos = array(
                    'id_log'           => $_POST['id_log'],
                    'salon'            => $_POST['salon'],
                    'hora'             => $hora[0],
                    'fecha'            => $_POST['fecha'],
                    'detalle'          => $_POST['detalles'],
                    'confirmado'       => $confirmado_salon,
                    'nombre_actividad' => $_POST['nombre_actividad'],
                    'aforo'            => $_POST['aforo']
                );

                $datos_correo = array(
                    'para'    => $correos,
                    'asunto'  => 'Nueva Reserva de Salón',
                    'mensaje' => $mensaje,
                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);



            }


            if ($cancelar == true) {

                echo $cancelar." - ";



                echo '

                <script>

                ohSnap("Cancelado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("reservas");

                }

                </script>

                ';



            } else {

                echo '

                <script>

                ohSnap("Error al cancelar!", {color: "red", "duration": "1000"});

                </script>

                ';

            }



        }

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

       // 🔹 VALIDACIÓN NUEVA
$diaHoy = date("N"); // 1=lunes ... 7=domingo
$fecha_reserva = $_POST['fecha'];

// calcular inicio y fin de la próxima semana
$lunesProximaSemana  = date("Y-m-d", strtotime("monday next week"));
$sabadoProximaSemana = date("Y-m-d", strtotime("saturday next week"));

// Si hoy es viernes, sábado o domingo y la fecha reservada está en la próxima semana (lun-sab)
if (in_array($diaHoy, [5, 6, 7]) &&
    $fecha_reserva >= $lunesProximaSemana &&
    $fecha_reserva <= $sabadoProximaSemana) {

    echo '
    <script>
    ohSnap("No puedes reservar la siguiente semana desde viernes a domingo", {color: "red", "duration": "2000"});
    </script>
    ';
    return; // 🚫 corta la ejecución antes de guardar
}

        $horas_texto      = '';
        $confirmado_salon = ($_POST['salon'] == 9) ? 1 : 2;

        $array_horas = array();
        $array_horas = $_POST['horas'];

        $it = new MultipleIterator();
        $it->attachIterator(new ArrayIterator($array_horas));

        foreach ($it as $hora) {
            $datos = array(
                'id_log'           => $_POST['id_log'],
                'salon'            => $_POST['salon'],
                'hora'             => $hora[0],
                'fecha'            => $_POST['fecha'],
                'detalle'          => $_POST['detalles'],
                'confirmado'       => $confirmado_salon,
                'nombre_actividad' => $_POST['nombre_actividad'],
                'aforo'            => $_POST['aforo']
            );

            $guardar     = ModeloSalon::reservarSalonModel($datos);
            $buscar_hora = ModeloSalon::mostrarHorasIdModel($hora[0]);
            $horas_texto .= $buscar_hora['horas'] . ', ';
        }

        if ($guardar['guardar'] == true) {
            $comando       = ModeloSalon::comandoSQL();
            $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);
            $datos_reserva = ModeloSalon::mostrarDatosReservaModel($guardar['id']);

            $confirmado = ($datos_reserva['confirmado'] == 1) ? 'Pendiente' : 'Confirmado';
            $confirmado = ($datos_reserva['confirmado'] == 0) ? 'Rechazada' : $confirmado;

            $mensaje = '
            <div>
            <p style="font-size: 1.2em;">
            El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado una reserva:
            </p>
            <ul  style="font-size: 1.3em;">
            <li><b>Salon:</b> ' . $datos_reserva['salon'] . '</li>
            <li><b>Fecha reserva:</b> ' . $datos_reserva['fecha_reserva'] . '</li>
            <li><b>Hora reserva:</b> ' . $horas_texto . '</li>
            <li><b>Estado de la reserva:</b> ' . $confirmado . '</li>
            </ul>
            <p style="font-size: 1.2em;"> <span><b>Detalle de la reserva: </b></span>' . $datos_reserva['detalle_reserva'] . '</p>
            </div>
            ';

            $correos = ($_POST['salon'] == 9)
                ? array($datos_usuario['correo'])
                : array(
                    'angel_vargas_contreras@hotmail.com',
                    'jrodriguez@colegiohebreounion.edu.co',
                    'jcervantes@colegiohebreounion.edu.co',
                    'idiaz@colegiohebreounion.edu.co',
                    'japaricio@colegiohebreounion.edu.co'
                );

            $datos_correo = array(
                'para'    => $correos,
                'asunto'  => 'Nueva Reserva de Salón',
                'mensaje' => $mensaje,
            );

            $enviar_correo = Correo::enviarCorreoModel($datos_correo);

            if ($enviar_correo) {
                echo '<script>console.log("Correo enviado correctamente.");</script>';
            } else {
                echo '<script>console.log("Error al enviar el correo.");</script>';
            }

            echo '
            <script>
            ohSnap("Reservado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
                window.location.replace("apartar");
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
