<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'hoja_vida' . DS . 'ModeloHojaVida.php';

class ControlHojaVida
{

    private static $instancia;

    public static function singleton_hoja_vida()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarDatosArticuloControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarDatosArticuloModel($id);
        return $mostrar;
    }

    public function mostrarCopiasSeguridadArticuloControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarCopiasSeguridadArticuloModel($id);
        return $mostrar;
    }

    public function mostrarComponentesHardwareControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarComponentesHardwareModel($id);
        return $mostrar;
    }

    public function mostrarComponentesAsignadoHardwareControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarComponentesAsignadoHardwareModel($id);
        return $mostrar;
    }

    public function mostrarComponentesAsignadoSoftwareControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarComponentesAsignadoSoftwareModel($id);
        return $mostrar;
    }

    public function mostrarReportesArticuloControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarReportesArticuloModel($id);
        return $mostrar;
    }

    public function mostrarFechaReportadoControl($id)
    {
        $mostrar = ModeloHojaVida::mostrarFechaReportadoModel($id);
        return $mostrar;
    }

    public function actualizarHojaVidaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['descripcion']) &&
            !empty($_POST['descripcion']) &&
            isset($_POST['id_super_empresa']) &&
            !empty($_POST['id_super_empresa']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $fecha_gant = ($_POST['fecha_gant'] == '') ? '0000-00-00' : $_POST['fecha_gant'];
            $fecha_ad   = ($_POST['fecha_ad'] == '') ? '0000-00-00' : $_POST['fecha_ad'];

            $datos = array(
                'id_log'           => $_POST['id_log'],
                'id_super_empresa' => $_POST['id_super_empresa'],
                'descripcion'      => $_POST['descripcion'],
                'marca'            => $_POST['marca'],
                'modelo'           => $_POST['modelo'],
                'ip'               => $_POST['ip'],
                'grupo'            => $_POST['grupo'],
                'tipo_con'         => $_POST['tipo_con'],
                'fecha_ad'         => $fecha_ad,
                'frec_mant'        => $_POST['frec_mant'],
                'frec_copia'       => $_POST['frec_copia'],
                'fecha_gant'       => $fecha_gant,
                'contacto'         => $_POST['contacto'],
                'id_hoja_vida'     => $_POST['id_hoja_vida'],
                'id_inventario'    => $_POST['id_inventario'],
                'numero_serie'     => $_POST['numero_serie'],
            );

            $actualizar = ModeloHojaVida::actualizarHojaVidaModel($datos);

            if ($actualizar == true) {
                echo '
                <script>
                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("' . BASE_URL . 'hoja_vida/index?inventario=' . base64_encode($_POST['id_inventario']) . '");
                }
                </script>';
            }
        }
    }

    public function asignarComponenteHardwareControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_hoja_vida']) &&
            !empty($_POST['id_hoja_vida']) &&
            isset($_POST['id_inventario']) &&
            !empty($_POST['id_inventario']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_inventario' => $_POST['id_inventario'],
                'id_hoja_vida'  => $_POST['id_hoja_vida'],
                'id_log'        => $_POST['id_log'],
            );

            $asignar_hardware = ModeloHojaVida::asignarComponenteHardwareModel($datos);

            if ($asignar_hardware == true) {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function guardarComponenteSoftwareControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_hoja_vida']) &&
            !empty($_POST['id_hoja_vida']) &&
            isset($_POST['descripcion_soft']) &&
            !empty($_POST['descripcion_soft']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_hoja_vida'     => $_POST['id_hoja_vida'],
                'id_log'           => $_POST['id_log'],
                'descripcion'      => $_POST['descripcion_soft'],
                'version'          => $_POST['version'],
                'fabricante'       => $_POST['fabricante'],
                'licencia'         => $_POST['licencia'],
                'observacion'      => $_POST['observacion'],
                'id_super_empresa' => $_POST['id_super_empresa'],
            );

            $guardar = ModeloHojaVida::guardarComponenteSoftwareControl($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Asignado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("' . BASE_URL . 'hoja_vida/index?inventario=' . base64_encode($_POST['id_inventario']) . '");
                }
                </script>';
            }
        }
    }
}
