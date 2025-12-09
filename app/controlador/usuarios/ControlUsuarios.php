<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';
require_once CONTROL_PATH . 'hash.php';

class ControlUsuarios
{

    private static $instancia;

    public static function singleton_usuarios()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarUsuariosControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarUsuariosModel();
        return $mostrar;
    }

    public function mostrarUsuariosEvaluarControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarUsuariosEvaluarModel();
        return $mostrar;
    }

    public function mostrarEstudiantesPROMControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarEstudiantesPROMModel();
        return $mostrar;
    }

    public function cuposEstudiantesControl($id)
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::cuposEstudiantesModel($id);
        return $mostrar;
    }

    public function buscarUsuariosNivelControl($datos)
    {

        $nivel = ($datos['nivel'] == '') ? '' : ' AND u.id_nivel = ' . $datos['nivel'];
        $curso = ($datos['curso'] == '') ? '' : ' AND u.id_curso = ' . $datos['curso'];

        $datos = array('nivel' => $nivel, 'curso' => $curso, 'buscar' => $datos['buscar']);

        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::buscarUsuariosNivelModel($datos);
        return $mostrar;
    }

    public function mostrarUsuariosDatosControl($id)
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarUsuariosDatosModel($id);
        return $mostrar;
    }

    public function mostrarTodosUsuariosControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarTodosUsuariosModel();
        return $mostrar;
    }

    public function mostrarTodosUsuariosInventarioControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarTodosUsuariosInventarioModel();
        return $mostrar;
    }

    public function buscarUsuarioControl($buscar)
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::buscarUsuarioModel($buscar);
        return $mostrar;
    }

    public function validarFirmaIdControl($id)
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::validarFirmaModel($id);
        return $mostrar;
    }

    public function mostrarNivelesUsuarioControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarNivelesUsuarioModel();
        return $mostrar;
    }

    public function mostrarCursosUsuarioControl()
    {
        $consulta = ModeloUsuarios::comandoSQL();
        $mostrar  = ModeloUsuarios::mostrarCursosUsuarioModel();
        return $mostrar;
    }

    public function guardarUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $pass      = $_POST['password'];
            $conf_pass = $_POST['conf_password'];

            if ($conf_pass == $pass) {

                $pass_hash = Hash::hashpass($conf_pass);

                $datos = array(
                    'super_empresa' => $_POST['super_empresa'],
                    'id_log'        => $_POST['id_log'],
                    'documento'     => $_POST['documento'],
                    'nombre'        => $_POST['nombre'],
                    'apellido'      => $_POST['apellido'],
                    'telefono'      => $_POST['telefono'],
                    'correo'        => $_POST['correo'],
                    'usuario'       => $_POST['usuario'],
                    'perfil'        => $_POST['perfil'],
                    'asignatura'    => $_POST['asignatura'],
                    'pass'          => $pass_hash,
                    'curso'         => ($_POST['curso'] == '') ? 0 : $_POST['curso'],
                    'nivel'         => $_POST['nivel'],
                );

                $guardar = ModeloUsuarios::guardarUsuarioModel($datos);

                if ($guardar['guardar'] == true) {
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
            } else {
                echo '
                <script>
                ohSnap("Contraseñas no coinciden", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function editarUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_user']) &&
            !empty($_POST['id_user'])
        ) {
            $pass      = $_POST['pass_editar'];
            $conf_pass = $_POST['conf_pass_editar'];
            $pass_old  = $_POST['pass_old'];

               //$pass_hash = ($conf_pass == $pass) ? Hash::hashpass($conf_pass) : $pass_old;

            // Si no se escribió nada, no se actualiza la contraseña
            $pass_hash = '';
            if (!empty($pass) && !empty($conf_pass) && $conf_pass == $pass) {
                $pass_hash = Hash::hashpass($conf_pass);
            }

            $datos = array(
                'id_user'    => $_POST['id_user'],
                'documento'  => $_POST['documento_edit'],
                'nombre'     => $_POST['nombre_edit'],
                'apellido'   => $_POST['apellido_edit'],
                'telefono'   => $_POST['telefono_edit'],
                'perfil'     => $_POST['perfil_edit'],
                'asignatura' => $_POST['asignatura_edit'],
                'pass'       => $pass_hash,
                'correo'     => $_POST['correo_edit'],
                'curso'      => $_POST['curso_edit'],
                'nivel'      => $_POST['nivel_edit'],
            );
           

            $guardar = ModeloUsuarios::editarUsuarioModel($datos);

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
                ohSnap("Error al Editar usuario", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function guardarFirmaUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            //obtener el nombre del archivo
            $nom_arch = $_FILES['firma']['name'];
            //extraer la extencion del archivo de el archivo
            $ext_arch   = explode(".", $nom_arch);
            $ext_arch   = end($ext_arch);
            $fecha_arch = date('YmdHis');

            $nombre_archivo = strtolower(md5($_POST['id_log'] . '_' . $fecha_arch)) . '.' . $ext_arch;

            $datos = array(
                'nombre'   => $nombre_archivo,
                'id_user'  => $_POST['id_log'],
                'user_log' => $_POST['id_log'],
            );

            $guardar = ModeloUsuarios::guardarFirmaUsuarioModel($datos);

            if ($guardar == true) {
                //ruta donde de alojamiento el archivo
                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $nombre_archivo;

                //verificar si subio el archivo y se mueve a su destino
                if (is_uploaded_file($_FILES['firma']['tmp_name'])) {
                    move_uploaded_file($_FILES['firma']['tmp_name'], $ruta_img);
                }
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("inicio");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al subir archivo", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function subirFotoCarnetControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $nombre_archivo = $_POST['foto_carnet'];

            if (isset($_FILES['foto']['name']) && !empty($_FILES['foto']['name'])) {

                $nom_arch   = $_FILES['foto']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');

                $nombre_archivo = strtolower(md5($_POST['documento'] . '_' . $_POST['nombre'] . $fecha_arch)) . '.' . $ext_arch;

                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $nombre_archivo;

                if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_img);
                }
            }

            $datos = array(
                'id_user'   => $_POST['id_user'],
                'id_log'    => $_POST['id_log'],
                'documento' => $_POST['documento'],
                'nombre'    => $_POST['nombre'],
                'apellido'  => $_POST['apellido'],
                'curso'     => $_POST['curso'],
                'foto'      => $nombre_archivo,
            );

            $guardar = ModeloUsuarios::subirFotoCarnetModel($datos);

            if ($guardar == true) {
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
                ohSnap("Error al subir archivo", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function verificarDocumentoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['documento']) &&
            !empty($_POST['documento'])
        ) {
            $documento = $_POST['documento'];
            $consulta  = ModeloUsuarios::comandoSQL();
            $buscar    = ModeloUsuarios::buscarDocumentoModel($documento);

            if ($buscar['id_user'] != "") {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function validarFirmaControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $id       = $_POST['id'];
            $consulta = ModeloUsuarios::comandoSQL();
            $buscar   = ModeloUsuarios::validarFirmaModel($id);
            return $buscar;
        }
    }

    public function verificarUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['usuario']) &&
            !empty($_POST['usuario'])
        ) {
            $usuario  = $_POST['usuario'];
            $consulta = ModeloUsuarios::comandoSQL();
            $buscar   = ModeloUsuarios::verificarUsuarioModel($usuario);

            if ($buscar['id_user'] != "") {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function inactivarUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_user']) &&
            !empty($_POST['id_user'])
        ) {
            $id_user = $_POST['id_user'];
            $fecha   = date('Y-m-d H:i:s');

            $datos    = array('id_user' => $id_user, 'fecha' => $fecha);
            $consulta = ModeloUsuarios::comandoSQL();
            $buscar   = ModeloUsuarios::inactivarUsuarioModel($datos);

            if ($buscar == true) {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function activarUsuarioControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_user']) &&
            !empty($_POST['id_user'])
        ) {
            $id_user = $_POST['id_user'];
            $fecha   = date('Y-m-d H:i:s');

            $datos    = array('id_user' => $id_user, 'fecha' => $fecha);
            $consulta = ModeloUsuarios::comandoSQL();
            $buscar   = ModeloUsuarios::activarUsuarioModel($datos);

            if ($buscar == true) {
                $rs = 'ok';
            } else {
                $rs = 'no';
            }

            return $rs;
        }
    }

    public function disminuirCupoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {

            $datos = ModeloUsuarios::datosCupoPromModel($_POST['id']);

            $cupo_disponible = $datos['cupo_disponible'];
            $cupo_ocupado    = $datos['cupo_ocupado'];

            if ($cupo_disponible == 0) {

                $rs = 'limite';

            } else {

                $cupo_disponible = $cupo_disponible - 1;
                $cupo_ocupado    = $cupo_ocupado + 1;

                $datos_cupo = array(
                    'cupo_disponible' => $cupo_disponible,
                    'cupo_ocupado'    => $cupo_ocupado,
                    'id_estudiante'   => $_POST['id'],
                );

                $guardar = ModeloUsuarios::disminuirCupoModel($datos_cupo);
                $rs      = ($guardar == true) ? 'ok' : 'no';
            }

            return $rs;
        }
    }

}
