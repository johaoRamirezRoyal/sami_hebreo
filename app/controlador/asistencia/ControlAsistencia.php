<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'asistencia' . DS . 'ModeloAsistencia.php';
require_once MODELO_PATH . 'asistencia' . DS . 'ModeloAsistenciaCron.php';

class ControlAsistencia
{

    private static $instancia;

    public static function singleton_asistencia()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarAsistenciaControl()
    {
        $consulta = ModeloAsistencia::comandoSQL();
        $mostrar  = ModeloAsistencia::mostrarAsistenciaModel();
        return $mostrar;
    }

    public function mostrarAsistenciaListadoControl()
    {
        $consulta = ModeloAsistenciaCron::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::mostrarAsistenciaListadoModel();
        return $mostrar;
    }

    public function buscarUsuarioAsistenciaControl($buscar)
    {
        $consulta = ModeloAsistencia::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::buscarUsuarioAsistenciaModel($buscar);
        return $mostrar;
    }

    public function buscarUsuarioAsistenciaGestionControl($datos)
    {

        $perfil           = ($datos['perfil'] == '') ? '' : ' AND u.perfil = ' . $datos['perfil'];
        $fecha_asistencia = ($datos['fecha'] == '') ? '' : ' AND a.fecha_asistencia = ' . $datos['fecha'];

        $datos = array('buscar' => $datos['buscar'], 'perfil' => $perfil, 'fecha' => $fecha_asistencia);

        $consulta = ModeloAsistencia::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::buscarUsuarioAsistenciaGestionModel($datos);
        return $mostrar;
    }

    public function validarTokenControl($token)
    {
        $consulta = ModeloAsistencia::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::validarTokenModel($token);

        $dia_hoy = date("w");

        if ($mostrar['dia'] == $dia_hoy) {
            $rs = 'ok';
        } else {
            $rs = 'No';
        }

        return $rs;
    }

    public function validarDocumentoControl($documento)
    {
        $consulta = ModeloAsistencia::comandoSQL();
        $mostrar  = ModeloAsistenciaCron::validarDocumentoModel($documento);

        if ($mostrar['id_user'] != '') {
            $datos = array(
                'id_user'   => $mostrar['id_user'],
                'fecha_hoy' => date("Y-m-d"),
                'hora_hoy'  => date("H:i:s"),
            );

            $validar_asistencia_hoy = ModeloAsistenciaCron::validarAsistenciaHoyModel($datos);
            if ($validar_asistencia_hoy['id'] == '') {
                $guardar = ModeloAsistenciaCron::TomarAsistenciaModel($datos);

                if ($guardar == true) {
                    $rs = 'ok';
                } else {
                    $rs = 'No';
                }
            } else {
                $rs = 'tomada';
            }

        } else {
            $rs = 'No';
        }

        return $rs;

    }

    public function buscarUsuarioAsistenciaPersonalControl($id_log)
    {
        // Obtenemos el mes y el año actual
        $mes_actual = date('m');
        $anio_actual = date('Y');

        // Armamos los datos para enviar al modelo
        $datos = array(
            'id_log' => $id_log,
            'mes' => $mes_actual,
            'anio' => $anio_actual
        );

        // Ejecutamos el modelo
        $mostrar  = ModeloAsistenciaCron::buscarAsistenciaMesActual($datos);
        return $mostrar;
    }

    public function buscarUsuarioAsistenciaPersonalFiltroControl($datos)
    {
        if(isset($_POST['buscar'])){
            // Obtenemos el mes y el año filtrado
            list($anio_actual, $mes_actual) = explode('-', $_POST['fecha']);
            $id_log = $_POST['id_log'];

            // Armamos los datos para enviar al modelo
            $datos = array(
                'id_log' => $id_log,
                'mes' => $mes_actual,
                'anio' => $anio_actual
            );

            // Ejecutamos el modelo
            $mostrar  = ModeloAsistenciaCron::buscarAsistenciaMesActual($datos);
            return $mostrar;
        }
    }
}
