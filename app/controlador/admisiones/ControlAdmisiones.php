<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'admisiones' . DS . 'ModeloAdmisiones.php';
require_once CONTROL_PATH . 'hash.php';

class ControlAdmisiones
{

    private static $instancia;

    public static function singleton_admisiones()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }


    public function datosClinicosControl($id)
    {
        $mostrar = ModeloAdmisiones::datosClinicosModel($id);
        return $mostrar;
    }


    public function mostrarHermanosControl($id)
    {
        $mostrar = ModeloAdmisiones::mostrarHermanosModel($id);
        return $mostrar;
    }


    public function mostrarAcudientesControl($super_empresa)
    {
        $mostrar = ModeloAdmisiones::mostrarAcudientesModel($super_empresa);
        return $mostrar;
    }


    public function guardarAcudienteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['super_empresa']) &&
            !empty($_POST['super_empresa']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log']) &&
            isset($_POST['documento']) &&
            !empty($_POST['documento']) &&
            isset($_POST['nombre']) &&
            !empty($_POST['nombre'])
        ) {

            $pass_hash = Hash::hashpass($_POST['documento']);

            $datos = array(
                'super_empresa' => $_POST['super_empresa'],
                'id_log' => $_POST['id_log'],
                'documento' => $_POST['documento'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'telefono' => $_POST['telefono'],
                'correo' => $_POST['correo'],
                'usuario' => $_POST['documento'],
                'perfil' => 6,
                'asignatura' => '',
                'pass' => $pass_hash,
                'id_nivel' => $_POST['nivel']
            );

            $guardar = ModeloAdmisiones::guardarAcudienteModel($datos);

            if ($guardar['guardar'] == TRUE) {


                $datos_est = array(
                    'id_acudiente' => $guardar['id'],
                    'nombre' => $_POST['nom_est']
                );

                $guardar_est = ModeloAdmisiones::guardarEstudianteModel($datos_est);

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


    public function habilitarFormatoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $id = $_POST['id'];
            
            $actualizar = ModeloAdmisiones::habilitarFormatoModel($id);

            $rs = ($actualizar == TRUE) ? 'ok' : 'error';
            return $rs;
        }
    }
}
