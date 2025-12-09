<?php 
date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'citaciones' . DS . 'ModeloCitaciones.php';
require_once CONTROL_PATH . 'hash.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';



class ControlCitaciones
{

    private static $instancia;

    public static function singleton_citaciones()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarTodosEstudiantesControl()
    {
        $mostrar = ModeloCitaciones::mostrarTodosEstudiantesModel();
        return $mostrar;
    }

    public function mostrarEstudiantesUsuariosControl()
    {
        $mostrar = ModeloCitaciones::mostrarEstudiantesUsuariosModel();
        return $mostrar;
    }

    public function filtrarCitacionesControl($datos)
    {
        $nivel = ($datos['nivel'] == '') ? '' : ' AND U.id_nivel = ' . $datos['nivel'];
        $curso = ($datos['curso'] == '') ? '' : ' AND U.id_curso = ' . $datos['curso'];

        $datos = array('nivel' => $nivel, 'curso' => $curso, 'buscar' => $datos['buscar']);
        $mostrar  = ModeloCitaciones::filtrarCitacionesModel($datos);

        return $mostrar;
    }

    public function buscarEstudiantesNivelControl($datos){
        $nivel = ($datos['nivel'] == '') ? '' : ' AND u.id_nivel = ' . $datos['nivel'];
        $curso = ($datos['curso'] == '') ? '' : ' AND u.id_curso = ' . $datos['curso'];

        $datos = array('nivel' => $nivel, 'curso' => $curso, 'buscar' => $datos['buscar']);
        $mostrar  = ModeloCitaciones::buscarEstudiantesNivelModel($datos);
        return $mostrar;
    }
    

    public function generarCitacionControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ){
            $datos = array(
                'id_usuario' => $_POST['id_estudiante'],
                'id_log' => $_POST['id_log'],
                'nivel_id' => $_POST['nivel_id'],
                'motivo' => $_POST['motivo'],
                'fecha_citacion' => $_POST['fecha_citacion'],
                'hora_citacion' => $_POST['hora_citacion']
            );

            $generar_citacion = ModeloCitaciones::generarCitacionModel($datos);

            if($generar_citacion){
                echo '<script>
                ohSnap("Citación enviada correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);
                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>';
            }else{
                echo '<script>
                ohSnap("Error al crear la citación. Intenté nuevamente en otro momento o consulte a los administradores", {color: "red"});
                </script>';
            }
        }
    }

    public function esRectoraControl($id_log){
        $datos = ModeloCitaciones::esRectoraModel($id_log);
        return $datos;
    }

    public function mostrarTodasLasCitacionesControl(){
        $mostrar = ModeloCitaciones::mostrarTodasLasCitacionesModel();
        return $mostrar;
    }

    public function mostrarEstadosCitacionControl(){
        $mostrar = ModeloCitaciones::mostrarEstadosCitacionModel();
        return $mostrar;
    }

    public function cambiarEstadoCitacionControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_citacion']) &&
            !empty($_POST['id_citacion'])
        ){
            if (!empty($_POST['correo_acudiente'])) {
                $correo = 'casaloboblanco@gmail.com'; //$_POST['correo_acudiente'];
            } else {
                $correo = 'casaloboblanco@gmail.com'; //$_POST['correo_profesor'];
            }

            $datos = array(
                'id' => $_POST['id_citacion'],
                'estado_id' => $_POST['estado_citacion'],
                'estudiante_id' => $_POST['id_estudiante'],
                'profesor_id' => $_POST['id_profesor'],
                'nombre_estudiante' => $_POST['nombre_estudiante'],
                'curso' => $_POST['curso'],
                'motivo' => $_POST['motivo'],
                'fecha_citacion' => $_POST['fecha_citacion'],
                'nombre_docente' => $_POST['nombre_docente'],
            );
            
            $cambiar_estado = ModeloCitaciones::cambiarEstadoCitacionModel($datos);

            
            if($cambiar_estado){
                if($datos['estado_id'] == 2){
                    $mensaje = '
                    <div style="font-size: 1.2em;">
                    <p><b>Buenos d&iacute;as, cordial saludo</b>
                    <br>
                    <br>
                    Se ha realizado un procedimiento de citaci&oacute;n al siguiente estudiante:
                    </p>
                    <ul>
                    <li><b>Fecha:</b> ' . $datos['fecha_citacion'] . '</li>
                    <li><b>Nombre:</b> ' . $datos['nombre_estudiante'] . '</li>
                    <li><b>Curso:</b> ' . $datos['curso'] . '</li>
                    <li><b>Motivo</b> ' . $datos['motivo'] . '</li>
                    </ul>
                    <p style="margin-top: 5%;"><b>' . $datos['nombre_docente'] . ' ' . '
                    <br>
                    <br>
                    COLEGIO HEBREO UNION
                    <br>
                    <br>
                    celular: 
                    <br>
                    <br>
                    <a href="https://www.colegiohebreounion.edu.co/wp/">Colegio Hebreo Union</a>
                    </b></p>
                    </div>
                    ';

                    $datos_correo = array(

                        'asunto' => 'Citación - COLEGIO HEBREO UNION',
    
                        'correo' => array(
                            'sami@colegiohebreounion.edu.co',
                        ),

                        'para' =>  array($correo, 'sami@colegiohebreounion.edu.co'),
    
                        'user' => 'Administrador S.A.M.I',
    
                        'mensaje' => $mensaje,
    
                        'archivo' => array(''),
    
                    );

                    // $datos_correo = array(
                    //     'asunto'  => 'Citación - COLEGIO HEBREO UNION',
                    //     'para' => array($correo),
                    //     'mensaje' => $mensaje,
                    //     'archivo' => array(''),
                    // );

                    
                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                    var_dump($enviar_correo);
                    die();

                    if($enviar_correo){
                        echo '<script>console.log("Correo enviado correctamente.");</script>';
                    }else{
                        echo '<script>console.log("Error al enviar el correo.");</script>';
                    }
                    
                }
                echo '<script>
                ohSnap("Citación actualizada correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);
                function recargarPagina(){
                    window.location.replace("ver");
                }
                </script>';

            }else{
                echo '<script>
                ohSnap("Error al crear", {color: "red"});
                </script>';
            }
        }
    }
}

?>