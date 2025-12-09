<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';
require_once MODELO_PATH . 'proveedor' . DS . 'ModeloProveedor.php';
require_once CONTROL_PATH . 'hash.php';

class ControlProveedor
{

    private static $instancia;

    public static function singleton_proveedor()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarDatosProveedorIdControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarDatosProveedorIdModel($id);
        return $mostrar;
    }

    public function getEvaluacionProveedor($id_proveedor,$id_calificacion)
    {
        $result =  ModeloProovedor::getEvaluacionProveedor($id_proveedor,$id_calificacion);
        return $result;
    }

    public function mostrarContactosProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarContactosProveedorModel($id);
        return $mostrar;
    }

    public function mostrarBancoProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarBancoProveedorModel($id);
        return $mostrar;
    }

    public function mostrarDocumentosProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarDocumentosProveedorModel($id);
        return $mostrar;
    }

    public function mostrarProveedoresControl()
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarProveedoresModel();
        return $mostrar;
    }

    public function mostrarTodosProveedoresControl()
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarTodosProveedoresModel();
        return $mostrar;
    }

    public function buscarProveedorControl($buscar)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::buscarProveedorModel($buscar);
        return $mostrar;
    }

    public function validarDocumentosProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::validarDocumentosProveedorModel($id);
        return $mostrar;
    }

    public function validarEvaluacionProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::validarEvaluacionProveedorModel($id);
        return $mostrar;
    }

    public function mostrarCalificacionProveedorControl($id)
    {
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarCalificacionProveedorModel($id);
        return $mostrar;
    }

    public function registrarProveedorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $pass = Hash::hashpass('proovedor132456@');

            $datos = array(
                'documento'        => $_POST['num_identificacion'],
                'nombre'           => $_POST['nombre'],
                'apellido'         => '',
                'telefono'         => $_POST['telefono'],
                'correo'           => $_POST['correo'],
                'usuario'          => $_POST['num_identificacion'],
                'perfil'           => 17,
                'pass'             => $pass,
                'id_log'           => $_POST['id_log'],
                'id_super_empresa' => 0,
                'asignatura'       => '',
            );

            $guardar = ModeloUsuarios::guardarUsuarioModel($datos);

            if ($guardar['guardar'] == true) {

                $datos_proveedor = array(
                    'id_proveedor'                 => $guardar['id'],
                    'nombre'                       => $_POST['nombre'],
                    'identificacion'               => $_POST['identificacion'],
                    'num_identificacion'           => $_POST['num_identificacion'],
                    'direccion'                    => $_POST['direccion'],
                    'ciudad'                       => $_POST['ciudad'],
                    'departamento'                 => $_POST['departamento'],
                    'pais'                         => $_POST['pais'],
                    'telefono'                     => $_POST['telefono'],
                    'correo'                       => $_POST['correo'],
                    'fecha_ingreso'                => $_POST['fecha_ingreso'],
                    'tipo'                         => $_POST['tipo'],
                    'tiempo_entrega'               => $_POST['tiempo_entrega'],
                    'garantia'                     => $_POST['garantia'],
                    'plazo_pago'                   => $_POST['plazo_pago'],
                    'detalle_producto'             => $_POST['detalle_producto'],
                    'nom_representante'            => $_POST['nom_representante'],
                    'identificacion_representante' => $_POST['identificacion_representante'],
                    'correo_representante'         => $_POST['correo_representante'],
                    'telefono_representante'       => $_POST['telefono_representante'],
                    'regimen_proveedor'            => $_POST['regimen_proveedor'],
                    'contribuyente_proveedor'      => $_POST['contribuyente_proveedor'],
                    'autoretenedor_proveedor'      => $_POST['autoretenedor_proveedor'],
                    'comercio_proveedor'           => $_POST['comercio_proveedor'],
                    'actividad_proveedor'          => $_POST['actividad_proveedor'],
                    'tarifa_proveedor'             => $_POST['tarifa_proveedor'],
                    'comercial_nombre'             => $_POST['comercial_nombre'],
                    'identificacion_comercial'     => $_POST['identificacion_comercial'],
                    'correo_comercial'             => $_POST['correo_comercial'],
                    'telefono_comercial'           => $_POST['telefono_comercial'],
                    'direccion_comercial'          => $_POST['direccion_comercial'],
                    'ciudad_comercial'             => $_POST['ciudad_comercial'],
                    'departamento_comercial'       => $_POST['departamento_comercial'],
                    'id_log'                       => $_POST['id_log'],
                );

                $guardar_prooveedor = ModeloProovedor::registrarProveedorModel($datos_proveedor);

                if ($guardar_prooveedor == true) {

                    if ($_POST['nombre_contacto'] != "") {
                        $datos_contacto = array(
                            'id_proveedor'      => $guardar['id'],
                            'nombre_contacto'   => $_POST['nombre_contacto'],
                            'telefono_contacto' => $_POST['telefono_contacto'],
                            'correo_contacto'   => $_POST['correo_contacto'],
                            'cargo_contacto'    => $_POST['cargo_contacto'],
                            'id_log'            => $_POST['id_log'],
                        );
                        $guardar_contacto = ModeloProovedor::guardarContactoProveedorModel($datos_contacto);
                    }

                    if ($guardar_prooveedor == true) {

                        if ($_POST['nom_banco'] != "") {
                            $datos_banco = array(
                                'id_proveedor' => $guardar['id'],
                                'nom_banco'    => $_POST['nom_banco'],
                                'num_banco'    => $_POST['num_banco'],
                                'tipo_cuenta'  => $_POST['tipo_cuenta'],
                                'id_log'       => $_POST['id_log'],
                            );

                            $guardar_banco = ModeloProovedor::guardarBancoProveedorModel($datos_banco);
                        }

                        if ($guardar_prooveedor == true) {

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
                            ohSnap("Ha ocurrido un error", {color: "red"});
                            </script>
                            ';
                        }
                    }
                }
            }
        }
    }

    public function agregarContactoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos_contacto = array(
                'id_proveedor'      => $_POST['id_proveedor'],
                'nombre_contacto'   => $_POST['nombre_contacto'],
                'telefono_contacto' => $_POST['telefono_contacto'],
                'correo_contacto'   => $_POST['correo_contacto'],
                'cargo_contacto'    => $_POST['cargo_contacto'],
                'id_log'            => $_POST['id_log'],
            );

            $guardar_contacto = ModeloProovedor::guardarContactoProveedorModel($datos_contacto);

            if ($guardar_contacto == true) {

                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("hojaRegistro?proveedor=' . base64_encode($_POST['id_proveedor']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function agregarBancoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos_banco = array(
                'id_proveedor' => $_POST['id_proveedor'],
                'nom_banco'    => $_POST['nom_banco'],
                'num_banco'    => $_POST['num_banco'],
                'tipo_cuenta'  => $_POST['tipo_cuenta'],
                'id_log'       => $_POST['id_log'],
            );

            $guardar_banco = ModeloProovedor::guardarBancoProveedorModel($datos_banco);

            if ($guardar_banco == true) {

                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("hojaRegistro?proveedor=' . base64_encode($_POST['id_proveedor']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function actualizarProveedorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_user'   => $_POST['id_proveedor'],
                'documento' => $_POST['num_identificacion'],
                'nombre'    => $_POST['nombre'],
                'apellido'  => '',
                'telefono'  => $_POST['telefono'],
                'correo'    => $_POST['correo'],
                'perfil'    => 17,
            );

            $actualizar = ModeloUsuarios::editarUsuarioProveedorModel($datos);

            if ($actualizar == true) {

                $datos_proveedor = array(
                    'id_proveedor'                 => $_POST['id_proveedor'],
                    'nombre'                       => $_POST['nombre'],
                    'identificacion'               => $_POST['identificacion'],
                    'num_identificacion'           => $_POST['num_identificacion'],
                    'direccion'                    => $_POST['direccion'],
                    'ciudad'                       => $_POST['ciudad'],
                    'departamento'                 => $_POST['departamento'],
                    'pais'                         => $_POST['pais'],
                    'telefono'                     => $_POST['telefono'],
                    'correo'                       => $_POST['correo'],
                    'fecha_ingreso'                => $_POST['fecha_ingreso'],
                    'tipo'                         => $_POST['tipo'],
                    'tiempo_entrega'               => $_POST['tiempo_entrega'],
                    'garantia'                     => $_POST['garantia'],
                    'plazo_pago'                   => $_POST['plazo_pago'],
                    'detalle_producto'             => $_POST['detalle_producto'],
                    'nom_representante'            => $_POST['nom_representante'],
                    'identificacion_representante' => $_POST['identificacion_representante'],
                    'correo_representante'         => $_POST['correo_representante'],
                    'telefono_representante'       => $_POST['telefono_representante'],
                    'regimen_proveedor'            => $_POST['regimen_proveedor'],
                    'contribuyente_proveedor'      => $_POST['contribuyente_proveedor'],
                    'autoretenedor_proveedor'      => $_POST['autoretenedor_proveedor'],
                    'comercio_proveedor'           => $_POST['comercio_proveedor'],
                    'actividad_proveedor'          => $_POST['actividad_proveedor'],
                    'tarifa_proveedor'             => $_POST['tarifa_proveedor'],
                    'comercial_nombre'             => $_POST['comercial_nombre'],
                    'identificacion_comercial'     => $_POST['identificacion_comercial'],
                    'correo_comercial'             => $_POST['correo_comercial'],
                    'telefono_comercial'           => $_POST['telefono_comercial'],
                    'direccion_comercial'          => $_POST['direccion_comercial'],
                    'ciudad_comercial'             => $_POST['ciudad_comercial'],
                    'departamento_comercial'       => $_POST['departamento_comercial'],
                    'id_log'                       => $_POST['id_log'],
                );

                $actualizar_proveedor = ModeloProovedor::actualizarProveedorModel($datos_proveedor);

                if ($actualizar_proveedor == true) {

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("hojaRegistro?proveedor=' . base64_encode($_POST['id_proveedor']) . '");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Ha ocurrido un error", {color: "red"});
                    </script>
                    ';
                }
            }
        }
    }

    public function documentosProveedorControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos_rut = array(
                'archivo'        => $_FILES['rut']['name'],
                'id_log'         => $_POST['id_log'],
                'tipo_documento' => 4,
                'tipo'           => 'rut',
                'id_proveedor'   => $_POST['id_proveedor'],
            );

            $datos_cedula_representante = array(
                'archivo'        => $_FILES['cedula_representante']['name'],
                'id_log'         => $_POST['id_log'],
                'tipo_documento' => 6,
                'tipo'           => 'cedula_representante',
                'id_proveedor'   => $_POST['id_proveedor'],
            );

            $datos_cert_bancaria = array(
                'archivo'        => $_FILES['cert_bancaria']['name'],
                'id_log'         => $_POST['id_log'],
                'tipo_documento' => 7,
                'tipo'           => 'cert_bancaria',
                'id_proveedor'   => $_POST['id_proveedor'],
            );

            $guardar = $this->guardarDocumentoControl($datos_rut);
            $guardar = $this->guardarDocumentoControl($datos_cedula_representante);
            $guardar = $this->guardarDocumentoControl($datos_cert_bancaria);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardadocorrectamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Haocurridounerror", {color: "red"});
                </script>
                ';
            }
        }
    }


    public function actualizarDocumentoHojaRegistroControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_FILES['archivo']) &&
            $_FILES['archivo']['error'] === UPLOAD_ERR_OK &&
            is_uploaded_file($_FILES['archivo']['tmp_name'])
        ){

            // var_dump('Entro al if: ');
            // die();

            $id_documento = $_POST['id_documento'];

            $datos_archivo = array(
                'archivo' => $_FILES['archivo']['name'],
                'id_log' => $_POST['id_log'],
                'tipo_documento' => $_POST['tipo_documento'],
                'id_proveedor' => $_POST['id_proveedor'],
                'archivo_temp' => $_FILES['archivo']['tmp_name'],
                'tipo' => 'archivo',
            );
            
            $guardar = $this->guardarDocumentoControl($datos_archivo);

            if ($guardar == true) {

                $eliminar = ModeloProovedor::eliminarDocumentoModel($id_documento);

                echo '
                <script>
                ohSnap("Actualizado correctamente!!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("' . BASE_URL . 'proveedor/hojaRegistro?proveedor=' . base64_encode($datos_archivo['id_proveedor']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }
        }else{
            echo '
            <script>
            ohSnap("Ha ocurrido un error subiendo el archivo", {color: "red"});
            </script>
            ';
        }
    }

    public function mostrarInformacionDocumentoControl($id_proveedor, $id_documento){
        $comando = ModeloProovedor::comandoSQL();
        $mostrar = ModeloProovedor::mostrarInformacionDocumentoModel($id_proveedor, $id_documento);
        return $mostrar;
    }


    public function registrarEvaluacionAnualControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $pregunta_1 = $_POST['pregunta_1'];
            $pregunta_2 = $_POST['pregunta_2'];
            $pregunta_3 = $_POST['pregunta_3'];
            $pregunta_4 = $_POST['pregunta_4'];
            $pregunta_5 = $_POST['pregunta_5'];

            $suma  = $pregunta_1 + $pregunta_2 + $pregunta_3 + $pregunta_4 + $pregunta_5;
            $total = $suma / 5;

            $datos = array(
                'id_proveedor'       => $_POST['id_proveedor'],
                'fecha_inicio'       => $_POST['fecha_inicio'],
                'fecha_finalizacion' => $_POST['fecha_finalizacion'],
                'fecha_evaluacion'   => $_POST['fecha_evaluacion'],
                'evaluador'          => $_POST['evaluador'],
                'pregunta_1'         => $_POST['pregunta_1'],
                'pregunta_2'         => $_POST['pregunta_2'],
                'pregunta_3'         => $_POST['pregunta_3'],
                'pregunta_4'         => $_POST['pregunta_4'],
                'pregunta_5'         => $_POST['pregunta_5'],
                'observacion_1'      => $_POST['observacion_1'],
                'observacion_2'      => $_POST['observacion_2'],
                'observacion_3'      => $_POST['observacion_3'],
                'observacion_4'      => $_POST['observacion_4'],
                'observacion_5'      => $_POST['observacion_5'],
                'id_log'             => $_POST['id_log'],
                'total'              => $total,
            );

            $guardar = ModeloProovedor::registrarEvaluacionAnualModel($datos);

            if ($guardar == true) {

                $mensaje = '
                <div>
                <p style="font-size: 1.6em;">
                El usuario <b>' . $datos['evaluador'] . '</b> ha solucionado el reporte del siguiente articulo:
                </p>
                <p>
                <ul style="font-size: 1.4em;">
                <li><b>Fecha evaluación:</b> ' . $datos['fecha_evaluacion'] . '</li>
                <li><b>Pregunta 1:</b> ' . $datos['pregunta_1'] . '</li>
                <li><b>Pregunta 2:</b> ' . $datos['pregunta_2'] . '</li>
                <li><b>Pregunta 3:</b> ' . $datos['pregunta_3'] . '</li>
                <li><b>Pregunta 4:</b> ' . $datos['pregunta_4'] . '</li>
                <li><b>Pregunta 5:</b> ' . $datos['pregunta_5'] . '</li>
                <li><b>Total:</b> ' . $datos['total'] . '</li>
                </ul>
                </p>
                </div>
                ';

                //enviar correo
                $datos_correo = array(
                    'asunto'  => 'Solucion de articulo',
                    'para' => array('jrodriguez@colegiohebreounion.edu.co'),
                    'correo'  => array('jrodriguez@colegiohebreounion.edu.co', 'sami@colegiohebreounion.edu.co'),
                    'user'    => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                
                if($enviar_correo){
                    echo '<script>console.log("Correo enviado correctamente.");</script>';
                }else{
                    echo '<script>console.log("Error al enviar el correo.");</script>';
                }

                echo '
                <script>
                ohSnap("Guardadocorrectamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Haocurridounerror", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function eliminarContactoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $eliminar = ModeloProovedor::eliminarContactoModel($_POST['id']);
            return $eliminar;
        }
    }

    public function eliminarBancoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ) {
            $eliminar = ModeloProovedor::eliminarBancoModel($_POST['id']);
            return $eliminar;
        }
    }

    public function guardarDocumentoControl($datos)
    {

        $nom_arch   = $datos['archivo'];
        $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
        $fecha_arch = date('YmdHis');

        $nombre_archivo = strtolower(md5($datos['id_proveedor'] . '_' . $datos['tipo_documento'] . $fecha_arch)) . '.' . $ext_arch;

        $datos_archivo = array(
            'nombre'         => $nombre_archivo,
            'id_log'         => $datos['id_log'],
            'tipo_documento' => $datos['tipo_documento'],
            'id_proveedor'   => $datos['id_proveedor'],
        );

        $guardar_cert = ModeloProovedor::documentosProveedorModel($datos_archivo);

        if ($guardar_cert == true) {

            $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
            $ruta_img     = $carp_destino . $nombre_archivo;

            if (is_uploaded_file($_FILES[$datos['tipo']]['tmp_name'])) {
                move_uploaded_file($_FILES[$datos['tipo']]['tmp_name'], $ruta_img);
            }

            return true;
        }
    }

}
