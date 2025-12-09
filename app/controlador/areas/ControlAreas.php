<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'areas' . DS . 'ModeloAreas.php';

class ControlAreas
{

    private static $instancia;

    public static function singleton_areas()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarAreasControl($super_empresa)
    {
        $mostrar = ModeloAreas::mostrarAreasModel($super_empresa);
        return $mostrar;
    }

    public function mostrarAreasNoInventarioControl()
    {
        $mostrar = ModeloAreas::mostrarAreasNoInventarioModel();
        return $mostrar;
    }

    public function mostrarAreasInventarioControl()
    {
        $mostrar = ModeloAreas::mostrarAreasInventarioModel();
        return $mostrar;
    }

    public function mostrarPaginarAreasControl($inicio, $final)
    {
        $mostrar = ModeloAreas::mostrarPaginarAreasModel($inicio, $final);
        return $mostrar;
    }

    public function guradarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $datos = array(
                'id_log'     => $_POST['id_log'],
                'nombre'     => $_POST['nombre'],
                'inventario' => $_POST['inventario'],
            );

            $guardar = ModeloAreas::guardarAreaModel($datos);

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
                ohSnap("Error al crear usuario", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function editarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_area']) &&
            !empty($_POST['id_area'])
        ) {
            $datos = array(
                'id_area' => $_POST['id_area'],
                'nombre'  => $_POST['nom_edit'],
                'inventario' => $_POST['inventario'],
            );

            $guardar = ModeloAreas::editarAreaModel($datos);

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
                ohSnap("Error al crear usuario", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function usuarioResponsableControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_area']) &&
            !empty($_POST['id_area'])
        ) {
            $id = $_POST['id_area'];

            $guardar = ModeloAreas::usuarioResponsableModel($id);

            if ($guardar != false) {
                $rs = $guardar['usuario'];
            } else {
                $rs = 'error';
            }

            return $rs;
        }
    }

    public function asignarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_area']) &&
            !empty($_POST['id_area'])
        ) {
            $datos = array(
                'id_area' => $_POST['id_area'],
                'id_user' => $_POST['usuario'],
            );

            $guardar = ModeloAreas::asignarAreaModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Asignado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("reasignar");
                }
                </script>
                ';
            }
        }
    }

    public function activarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_area']) &&
            !empty($_POST['id_area'])
        ) {
            $id_area = $_POST['id_area'];
            $fecha   = date('Y-m-d H:i:s');

            $datos = array('id_area' => $id_area);

            $buscar = ModeloAreas::activarAreaModel($datos);

            if ($buscar == true) {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function inactivarAreaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_area']) &&
            !empty($_POST['id_area'])
        ) {
            $id_area = $_POST['id_area'];
            $fecha   = date('Y-m-d H:i:s');

            $datos = array('id_area' => $id_area);

            $buscar = ModeloAreas::inactivarAreaModel($datos);

            if ($buscar == true) {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }
}
