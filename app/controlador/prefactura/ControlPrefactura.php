<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'prefactura' . DS . 'ModeloPrefactura.php';

class ControlPrefactura
{

    private static $instancia;

    public static function singleton_prefactura()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }


    public function mostrarDatosPrefacturaControl($id)
    {
        $mostrar = ModeloPrefactura::mostrarDatosPrefacturaModel($id);
        return $mostrar;
    }


    public function mostrarDatosPrefacturacionEmpresaControl($id)
    {
        $datos = ModeloPrefactura::mostrarDatosPrefacturacionEmpresaModel($id);
        return $datos;
    }

    public function mostrarDatosPrefacturacionAlteradaEmpresaControl($id)
    {
        $datos = ModeloPrefactura::mostrarDatosPrefacturacionAlteradaEmpresaModel($id);
        return $datos;
    }

    public function buscarReservasPrefacturadasControl($fecha_ini, $fecha_fin)
    {
        $datos = ModeloPrefactura::buscarReservasPrefacturadasModel($fecha_ini, $fecha_fin);
        return $datos;
    }


    public function guardarPreciosPrefacturaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_reserva']) &&
            !empty($_POST['id_reserva']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log']) &&
            isset($_POST['super_empresa']) &&
            !empty($_POST['super_empresa']) &&
            isset($_POST['subtotal']) &&
            !empty($_POST['subtotal']) &&
            isset($_POST['iva']) &&
            !empty($_POST['iva']) &&
            isset($_POST['descuento']) &&
            !empty($_POST['descuento'])
        ) {

            $iva = $_POST['iva'];
            $subtotal = $_POST['subtotal'];
            $descuento = $_POST['descuento'];

            $array_valor = array();
            $array_valor = $_POST['valor'];

            $array_cantidad = array();
            $array_cantidad = $_POST['cantidad'];

            $array_id_residuo = array();
            $array_id_residuo = $_POST['id_residuo'];

            $array_id_tipo_residuo = array();
            $array_id_tipo_residuo = $_POST['id_tipo_residuo'];

            $array_galones = array();
            $array_galones = $_POST['galones'];

            $array_listado = array();
            $array_listado = $_POST['listado'];

            /* print_r($_POST['listado']);
            die(); */

            $it = new MultipleIterator();
            $it->attachIterator(new ArrayIterator($array_valor));
            $it->attachIterator(new ArrayIterator($array_cantidad));
            $it->attachIterator(new ArrayIterator($array_id_residuo));
            $it->attachIterator(new ArrayIterator($array_id_tipo_residuo));
            $it->attachIterator(new ArrayIterator($array_galones));
            $it->attachIterator(new ArrayIterator($array_listado));

            foreach ($it as $datos) {

                /*                 $total_unidad = ($datos[0] * $datos[1]);
                $subtotal += $total_unidad; */

                $datos_pre = array(
                    'id_reserva' => $_POST['id_reserva'],
                    'id_residuo' => $datos[2],
                    'id_tipo_residuo' => $datos[3],
                    'id_td' => $datos[5],
                    'kilogramos' => $datos[1],
                    'galones' => $datos[4],
                    'valor_unt' => $datos[0],
                    'id_log' => $_POST['id_log'],
                    'id_super_empresa' => $_POST['super_empresa']
                );

                $guardar_pre_alt = ModeloPrefactura::guardarPrefacturaAlteradaModel($datos_pre);
            }

            if ($guardar_pre_alt == TRUE) {

                $subtotal = str_replace(',', '', $subtotal);

                $total_descuento = ($subtotal * $descuento) / 100;
                $total_subtotal = ($subtotal - $total_descuento);
                $total_iva = ($total_subtotal * $iva) / 100;
                $total = ($total_subtotal + $total_iva);

                $datos_pre_of = array(
                    'id_reserva' => $_POST['id_reserva'],
                    'subtotal' => $subtotal,
                    'iva' => $_POST['iva'],
                    'descuento' => $_POST['descuento'],
                    'id_log' => $_POST['id_log'],
                    'id_super_empresa' => $_POST['super_empresa'],
                    'total_descuento' => $total_descuento,
                    'total_subtotal' => $total_subtotal,
                    'total_iva' => $total_iva,
                    'total_final' => $total,
                    'observacion' => $_POST['nota']
                );


                $guardar_prefactura = ModeloPrefactura::guardarPreciosPrefacturaModel($datos_pre_of);

                if ($guardar_prefactura == TRUE) {
                    echo '
                    <script>
                    ohSnap("Facturado Correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.open("' . BASE_URL . 'imprimir/imprimirPrefactura?reserva=' . base64_encode($_POST['id_reserva']) . '", "_blank");
                        window.location.replace("' . BASE_URL . 'programacion/index");
                    }
                    </script>';
                } else {
                    echo '
                    <script>
                        ohSnap("Ha ocurrido un error", {color: "red"});
                    </script>';
                }
            }
        }
    }
}
