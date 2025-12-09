<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'contabilidad' . DS . 'ModeloContabilidad.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';

class ControlContabilidad
{

    private static $instancia;

    public static function singleton_contabilidad()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }


    public function mostrarNominaMesActualControl($id,$anio_mes)
    {
        $mostrar = ModeloContabilidad::mostrarNominaMesActualModel($id,$anio_mes);
        return $mostrar;
    }

    public function mostrarNominasControl($id)
    {
        $mostrar = ModeloContabilidad::mostrarNominasModel($id);
        return $mostrar;
    }


    public function subirNominaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log']) &&
            isset($_POST['id_user']) &&
            !empty($_POST['id_user']) &&
            isset($_POST['anio_mes']) &&
            !empty($_POST['anio_mes'])
        ) {

            $datos = array(
                'archivo' => $_FILES['archivo']['name'],
                'id_log' => $_POST['id_log'],
                'anio_mes' => $_POST['anio_mes'],
                'id_user' => $_POST['id_user']
            );

            $guardar = $this->guardarNominaControl($datos);

            if ($guardar == TRUE) {
                echo '
                <script>
                ohSnap("Subido correctamente!", {color: "green", "duration": "1000"});
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


    public function guardarNominaControl($datos)
    {
        //obtener el nombre del archivo
        $nom_arch = $datos['archivo'];
        //extraer la extencion del archivo de el archivo
        $ext_arch = explode(".", $nom_arch);
        $ext_arch = end($ext_arch);
        $fecha_arch = date('YmdHis');

        $nombre_archivo = strtolower(md5($datos['anio_mes'] . '_' . $fecha_arch)) . '.' . $ext_arch;

        $datos_temp = array(
            'nombre' => $nombre_archivo,
            'id_log' => $datos['id_log'],
            'anio_mes' => $datos['anio_mes'],
            'id_user' => $datos['id_user']
        );

        $guardar_cert = ModeloContabilidad::guardarNominaModel($datos_temp);

        if ($guardar_cert == TRUE) {
            //ruta donde de alojamiento el archivo
            $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
            $ruta_img = $carp_destino . $nombre_archivo;

            //verificar si subio el archivo y se mueve a su destino
            if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
                move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
            }

            return TRUE;
        }
    }
}
