<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'zonas' . DS . 'ModeloZonas.php';
require_once CONTROL_PATH . 'hash.php';

class ControlZonas
{

    private static $instancia;

    public static function singleton_zonas()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarDatosIdZonaControl($id)
    {
        $mostrar = ModeloZonas::mostrarDatosIdZonaModel($id);
        return $mostrar;
    }

    public function reportesZonaControl($id)
    {
        $mostrar = ModeloZonas::reportesZonaModel($id);
        return $mostrar;
    }

    public function mostrarFechaRespuestaZonaControl($id)
    {
        $mostrar = ModeloZonas::mostrarFechaRespuestaZonaModel($id);
        return $mostrar;
    }

    public function buscarZonaControl($datos)
    {
        $area    = (!empty($_POST['area'])) ? ' AND z.id_area = ' . $datos['area'] : '';
        $datos   = array('area' => $area, 'buscar' => $datos['buscar']);
        $mostrar = ModeloZonas::buscarZonaModel($datos);
        return $mostrar;
    }

    public function mostrarZonaControl()
    {
        $mostrar = ModeloZonas::mostrarZonaModel();
        return $mostrar;
    }

    public function mostrarAreasZonasControl()
    {
        $mostrar = ModeloZonas::mostrarAreasZonasModel();
        return $mostrar;
    }

    public function mostraReportesZonaControl()
    {
        $mostrar = ModeloZonas::mostraReportesZonaModel();
        return $mostrar;
    }

    public function datosReporteZonaControl($id)
    {
        $mostrar = ModeloZonas::datosReporteZonaModel($id);
        return $mostrar;
    }

    public function mostrarHistorialZonaControl()
    {
        $mostrar = ModeloZonas::mostrarHistorialZonaModel();
        return $mostrar;
    }

    public function mostrarInformacionSolucionReporteControl($id)
    {
        $mostrar = ModeloZonas::mostrarInformacionSolucionReporteModel($id);
        return $mostrar;
    }

    public function mostrarHistorialZonaMantControl()
    {
        $mostrar = ModeloZonas::mostrarHistorialZonaMantModel();
        return $mostrar;
    }

    public function buscarHistorialZonaControl($fecha_inicio, $fecha_fin, $zona)
    {
        $mostrar = ModeloZonas::buscarHistorialZonaModel($fecha_inicio, $fecha_fin, $zona);
        return $mostrar;
    }

    public function buscarHistorialZonaMantControl($fecha_inicio, $fecha_fin, $zona)
    {
        $mostrar = ModeloZonas::buscarHistorialZonaMantModel($fecha_inicio, $fecha_fin, $zona);
        return $mostrar;
    }

    public function guardarZonasControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log' => $_POST['id_log'],
                'nombre' => $_POST['nombre'],
                'area'   => $_POST['area'],
            );

            $guardar = ModeloZonas::guardarZonasModel($datos);

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
            ohSnap("Error", {color: "red"});
            </script>
            ';
            }
        }
    }

    public function agregarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'   => $_POST['id_log'],
                'nom_area' => $_POST['nom_area'],
            );

            $guardar = ModeloZonas::agregarAreaModel($datos);

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
        ohSnap("Error", {color: "red"});
        </script>
        ';
            }
        }
    }

    public function editarZonaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'   => $_POST['id_log'],
                'id_area'  => $_POST['area_edit'],
                'id_zona'  => $_POST['id_zona'],
                'nom_zona' => $_POST['nom_zona'],
            );

            $guardar = ModeloZonas::editarZonaModel($datos);

            if ($guardar == true) {
                echo '
            <script>
            ohSnap("Editado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
               window.location.replace("index");
           }
           </script>
           ';
            } else {
                echo '
        <script>
        ohSnap("Error", {color: "red"});
        </script>
        ';
            }
        }
    }

    public function reportarZonaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $url = ($_POST['inicio'] == 1) ? '../inicio' : 'index';

            $fecha_mant    = ($_POST['estado'] == 2) ? '0000-00-00 00:00:00' : $_POST['fecha_mant'] . ' ' . date('H:i:s');
            $fecha_reporte = (isset($_POST['fecha_reporte'])) ? $_POST['fecha_reporte'] . date(' H:i:s') : date('Y-m-d-H-i-s');

            $datos = array(
                'id_log'            => $_POST['id_log'],
                'id_zona'           => $_POST['id_zona'],
                'estado'            => $_POST['estado'],
                'observacion'       => $_POST['observacion'],
                'observacion_repro' => '',
                'fecha_reporte'     => $fecha_reporte,
                'fecha_mant'        => $fecha_mant,
            );

            $reportar = ModeloZonas::reportarZonaModel($datos);

            if ($reportar == true) {
                echo '
            <script>
            ohSnap("Reportado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
               window.location.replace("' . $url . '");
           }
           </script>
           ';
            } else {
                echo '
        <script>
        ohSnap("Error", {color: "red"});
        </script>
        ';
            }
        }
    }

    public function solucionarReporteZonaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $fecha_respuesta = (isset($_POST['fecha_respuesta'])) ? $_POST['fecha_respuesta'] . date(' H:i:s') : date('Y-m-d H:i:s');

            $datos = array(
                'id_reporte'        => $_POST['id_reporte'],
                'id_log'            => $_POST['id_log'],
                'id_user'           => $_POST['id_user'],
                'id_zona'           => $_POST['id_zona'],
                'estado'            => 3,
                'observacion'       => $_POST['observacion'],
                'observacion_repro' => '',
                'fecha_respuesta'   => $fecha_respuesta,
                'fecha_mant'        => '0000-00-00',
            );

            $guardar = ModeloZonas::solucionarReporteZonaModel($datos);

            if ($guardar == true) {
                echo '
            <script>
            ohSnap("Solucionado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
               window.location.replace("reportes");
           }
           </script>
           ';
            } else {
                echo '
        <script>
        ohSnap("Error", {color: "red"});
        </script>
        ';
            }

        }
    }

    public function reprogramarFechaReporteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log_re']) &&
            !empty($_POST['id_log_re'])
        ) {

            $fecha_respuesta = date('Y-m-d H:i:s');

            $datos = array(
                'id_reporte'        => $_POST['id_reporte'],
                'id_log'            => $_POST['id_log_re'],
                'id_user'           => $_POST['id_user'],
                'id_zona'           => $_POST['id_zona'],
                'estado'            => 10,
                'observacion'       => $_POST['observacion'],
                'observacion_repro' => $_POST['observacion_repro'],
                'fecha_respuesta'   => $fecha_respuesta,
                'fecha_mant'        => $_POST['fecha_mant'] . ' ' . date('H:i:s'),
            );

            $guardar = ModeloZonas::solucionarReporteZonaModel($datos);

            if ($guardar == true) {
                echo '
            <script>
            ohSnap("Re-programado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
               window.location.replace("reportes");
           }
           </script>
           ';
            } else {
                echo '
        <script>
        ohSnap("Error", {color: "red"});
        </script>
        ';
            }
        }
    }

    public function cantidadesSolucionadosZonasControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['tipo']) &&
            !empty($_POST['tipo'])) {

            $tipo = ($_POST['tipo'] == 6) ? '6,10' : $_POST['tipo'];

            $datos = array(
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin'    => $_POST['fecha_fin'],
                'tipo'         => $tipo,
            );

            $mostrar      = ModeloZonas::cantidadesSolucionadasZonasModel($datos);
            $mostrar_pend = ModeloZonas::cantidadesPendienteZonasModel($datos);

            $resultado = array('solucionados' => $mostrar['solucion'], 'pendientes' => $mostrar_pend['cantidad']);
            return $resultado;
        }
    }

    public function cantidadesMantenimientosSolucionadosZonasControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['tipo']) &&
            !empty($_POST['tipo'])) {

            $tipo = ($_POST['tipo'] == 6) ? '6,10' : $_POST['tipo'];

            $datos = array(
                'fecha_inicio' => $_POST['fecha_inicio'],
                'fecha_fin'    => $_POST['fecha_fin'],
                'tipo'         => $tipo,
            );

            $mostrar      = ModeloZonas::cantidadesMantenimientosSolucionadasZonasModel($datos);
            $mostrar_pend = ModeloZonas::cantidadesMantenimientosPendienteZonasModel($datos);

            $resultado = array('solucionados' => $mostrar['solucion'], 'pendientes' => $mostrar_pend['cantidad']);
            return $resultado;
        }
    }

    public function editarReporteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $url = ($_POST['url'] == 0) ? 'historialZona' : 'historialZonaMant';

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'id_reporte'  => $_POST['id_reporte'],
                'observacion' => $_POST['observacion_resp'],
            );

            $guardar = ModeloZonas::editarReporteModel($datos);

            if ($guardar == true) {
                echo '
            <script>
            ohSnap("Editado correctamente!", {color: "green", "duration": "1000"});
            setTimeout(recargarPagina,1050);

            function recargarPagina(){
                window.location.replace("' . $url . '");
            }
            </script>
            ';
            } else {
                echo '
            <script>
            ohSnap("Error al editar", {color: "red"});
            </script>
            ';
            }
        }
    }

}
