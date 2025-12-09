<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'permisos' . DS . 'ModeloPermisos.php';
require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once CONTROL_PATH . 'hash.php';

class ControlPermisos
{

    private static $instancia;

    public static function singleton_permisos()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function permisosUsuarioControl($id_opcion, $perfil)
    {
        $mostrar = ModeloPermisos::permisosUsuarioModel($id_opcion, $perfil);
        return $mostrar;
    }

    public function mostrarOpcionesPermisosControl()
    {
        $mostrar = ModeloPermisos::mostrarOpcionesPermisosModel();
        return $mostrar;
    }

    public function opcionsIdActivosPerfilControl($id_perfil, $id_opcion)
    {
        $mostrar = ModeloPermisos::opcionsIdActivosPerfilModel($id_perfil, $id_opcion);
        return $mostrar;
    }

    public function mostrarAnioActivoControl()
    {
        $mostrar = ModeloPermisos::mostrarAnioActivoModel();
        return $mostrar;
    }

    public function mostrarAniosLectivoControl()
    {
        $mostrar = ModeloPermisos::mostrarAniosLectivoModel();
        return $mostrar;
    }

    public function activarPermisoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['opcion']) &&
            !empty($_POST['opcion']) &&
            isset($_POST['perfil']) &&
            !empty($_POST['perfil']) &&
            isset($_POST['user']) &&
            !empty($_POST['user'])
        ) {

            $datos = array(
                'opcion' => $_POST['opcion'],
                'perfil' => $_POST['perfil'],
                'user'   => $_POST['user'],
            );

            $inactivar = ModeloPermisos::inactivarPermisoModel($datos);

            if ($inactivar == true) {
                $activar = ModeloPermisos::activarPermisoModel($datos);
            }
            return $activar;
        }
    }

    public function inactivarPermisoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['opcion']) &&
            !empty($_POST['opcion']) &&
            isset($_POST['perfil']) &&
            !empty($_POST['perfil']) &&
            isset($_POST['user']) &&
            !empty($_POST['user'])
        ) {

            $datos = array(
                'opcion' => $_POST['opcion'],
                'perfil' => $_POST['perfil'],
                'user'   => $_POST['user'],
            );

            $inactivar = ModeloPermisos::inactivarPermisoModel($datos);
            return $inactivar;
        }
    }

    public function finalizarAnioEscolarControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_anio']) &&
            !empty($_POST['id_anio'])
        ) {
            $fin = ModeloPermisos::finalizarAnioEscolarModel($_POST['id_anio'], $_POST['id_log']);

            if ($fin == true) {
                $dato = ModeloPermisos::mostrarAnioIdActivoModel($_POST['id_anio']);

                $anio_inicio = date("Y", strtotime($dato['anio_inicio'] . "+ 6 month"));
                $anio_fin    = date("Y", strtotime($dato['anio_fin'] . "+ 1 year"));

                $datos = array(
                    'anio_inicio' => $anio_inicio,
                    'anio_fin'    => $anio_fin,
                    'anio_inicio' => $anio_inicio,
                    'id_log'      => $_POST['id_log'],
                );

                $guardar = ModeloPermisos::iniciarNuevoAnioModel($datos);

                if ($guardar == true) {

                    $mostrar = ModeloUsuarios::mostrarUsuariosModel(1);

                    $mensaje = '
                    <div>
                    <p style="font-size: 1.2em;">
                    Se ha iniciado el proceso de inicio de año escolar ' . $anio_inicio . '-' . $anio_fin . ', favor confirmar el invetario de las areas.
                    </p>
                    <a style="font-size: 1.2em;" href="' . BASE_URL . '/inventario/listado" target="_blank">' . BASE_URL . 'inventario/listado</a>
                    </div>
                    ';

                    /*$datos_correo = array(
                    'asunto'  => 'Inventario ' . $anio_inicio . '-' . $anio_fin,
                    'correo'  => 'jesus.polo@royalschool.edu.co',
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    );

                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);*/

                    foreach ($mostrar as $usuario) {

                        $correo = $usuario['correo'];

                        $datos_correo = array(
                            'asunto'  => 'Inventario ' . $anio_inicio . '-' . $anio_fin,
                            'correo'  => $correo,
                            'user'    => 'Administrador',
                            'mensaje' => $mensaje,
                            'archivo' => array(''),
                        );

                        $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                    }

                    echo '
                    <script>
                    ohSnap("Finalizado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1100);

                    function recargarPagina(){
                        window.location.replace("' . BASE_URL . 'inventario/listado");
                    }
                    </script>
                    ';
                }
            }
        }
    }
}
