<?php

date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'recursos' . DS . 'ModeloRecursos.php';

require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';

require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';

require_once CONTROL_PATH . 'numeros.php';



class ControlRecursos

{



    private static $instancia;



    public static function singleton_recursos()

    {

        if (!isset(self::$instancia)) {

            $miclase         = __CLASS__;

            self::$instancia = new $miclase;

        }

        return self::$instancia;

    }

public function mostrarPermisosLicenciasUsuariosFiltroControl($datos)

{
   $usuario = ($datos['usuario'] == '') ? '' : "AND p.id_user = " . $datos['usuario'];
        
        $fechaMes = $datos['fecha']; // Ej: "2025-08"
        list($anio, $mes) = explode('-', $fechaMes);
        $fecha = ($datos['fecha'] == '') ? '' : "AND YEAR(p.fecha_permiso) = '" . $anio ."'" . " AND MONTH(p.fecha_permiso) = '" . $mes . "'";

        $datos = array(
            'id_nivel' => $datos['id_nivel'],
            'buscar' => $datos['buscar'],
            'usuario' => $usuario,
            'fecha' => $fecha,
        );

        $mostrar = ModeloRecursos::mostrarPermisosLicenciasUsuariosFiltroModel($datos);
        return $mostrar;
}
   

 public function mostrarPermisosLicenciasUsuarioNivelFiltroControl($datos){


        $buscar = ($datos['buscar'] == '') ? '' : "AND CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', u.correo) LIKE '%" . $datos['buscar'] . "%'";
        $usuario = ($datos['usuario'] == '') ? '' : "AND p.id_user = " . $datos['usuario'];
        
        $fechaMes = $datos['fecha']; // Ej: "2025-08"
        list($anio, $mes) = explode('-', $fechaMes);
        $fecha = ($datos['fecha'] == '') ? '' : "AND YEAR(p.fecha_permiso) = '" . $anio ."'" . " AND MONTH(p.fecha_permiso) = '" . $mes . "'";

        $datos = array(
            'id_nivel' => $datos['id_nivel'],
            'buscar' => $buscar,
            'usuario' => $usuario,
            'fecha' => $fecha,
        );

        $mostrar = ModeloRecursos::mostrarPermisosLicenciasUsuarioNivelFiltroModel($datos);
        return $mostrar;
    }
    

    public function mostrarTipoDocumentoControl($super_empresa)

    {

        $mostrar = ModeloRecursos::mostrarTipoDocumentoModel($super_empresa);

        return $mostrar;

    }



    public function mostrarSolicitudesIdControl($id)

    {

        $mostrar = ModeloRecursos::mostrarSolicitudesIdModel($id);

        return $mostrar;

    }



    public function mostrarSolicitudesControl($super_empresa)

    {

        $mostrar = ModeloRecursos::mostrarSolicitudesControl($super_empresa);

        return $mostrar;

    }



    public function certificadoMostrarControl($id)

    {

        $mostrar = ModeloRecursos::certificadoMostrarModel($id);

        return $mostrar;

    }



    public function mostrarDatosTipoDocumentoControl($id)

    {

        $mostrar = ModeloRecursos::mostrarDatosTipoDocumentoModel($id);

        return $mostrar;

    }



    public function mostrarTramitesControl()

    {

        $mostrar = ModeloRecursos::mostrarTramitesModel();

        return $mostrar;

    }



    public function mostrarListadoTramiteControl()

    {

        $mostrar = ModeloRecursos::mostrarListadoTramiteModel();

        return $mostrar;

    }



    public function mostrarListadoUsuarioTramiteControl($id)

    {

        $mostrar = ModeloRecursos::mostrarListadoUsuarioTramiteModel($id);

        return $mostrar;

    }



    public function mostrarDetallesTramiteControl($id)

    {

        $mostrar = ModeloRecursos::mostrarDetallesTramiteModel($id);

        return $mostrar;

    }



    public function mostrarDocumentosTramiteControl($id)

    {

        $mostrar = ModeloRecursos::mostrarDocumentosTramiteModel($id);

        return $mostrar;

    }



    public function mostrarTramiteFamiliarControl($id)

    {

        $mostrar = ModeloRecursos::mostrarTramiteFamiliarModel($id);

        return $mostrar;

    }



    public function mostrarDatosGrupoFamiliarControl()

    {

        $mostrar = ModeloRecursos::mostrarDatosGrupoFamiliarModel();

        return $mostrar;

    }



    public function mostrarMotivosPermisoControl()

    {

        $mostrar = ModeloRecursos::mostrarMotivosPermisoModel();

        return $mostrar;

    }



    public function mostrarTipoPermisoControl()

    {

        $mostrar = ModeloRecursos::mostrarTipoPermisoModel();

        return $mostrar;

    }



    public function mostrarListadoPermisosControl($id_nivel)

    {

        $mostrar = ModeloRecursos::mostrarListadoPermisosModel($id_nivel);

        return $mostrar;

    }


    public function mostrarListadoPermisosControlgestionhumana()

    {

        $mostrar = ModeloRecursos::mostrarListadoPermisosModelgestionhumana();

        return $mostrar;

    }




    public function mostrarPermisosUsuarioControl($id)

    {

        $mostrar = ModeloRecursos::mostrarPermisosUsuarioModel($id);

        return $mostrar;

    }



    public function mostrarPermisoIdControl($id)

    {

        $mostrar = ModeloRecursos::mostrarPermisoIdModel($id);

        return $mostrar;

    }



    public function solicitarCertificadoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {

            $datos = array(

                'id_user'          => $_POST['id_log'],

                'id_super_empresa' => $_POST['id_super_empresa'],

                'lugar'            => $_POST['lugar'],

                'cargo'            => $_POST['cargo'],

                'nombre_entidad'   => $_POST['nom_entidad'],

                'trabaja_act'      => $_POST['trabaja'],

                'tipo_cert'        => $_POST['tipo_cert'],

                'anio'             => $_POST['anio'],

            );



            $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);

            $tipo_doc      = ModeloRecursos::mostrarDatosTipoDocumentoModel($_POST['tipo_cert']);



            $guardar = ModeloRecursos::solicitarCertificadoModel($datos);



            if ($guardar == true) {

                echo '

                <script>

                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("certificados");

                }

                </script>

                ';



                $mensaje = '

                <p style="font-size: 1.3em;">

                El usuario <span style="font-weight: bold;">' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</span> ha realizado la solicitud para un certificado con la siguiente informacion diligenciada.

                <ul style="font-size: 1.2em;">

                <li><span style="font-weight: bold;">Documento: </span>' . $datos_usuario['documento'] . '</li>

                <li><span style="font-weight: bold;">Lugar de expedición: </span>' . $_POST['lugar'] . '</li>

                <li><span style="font-weight: bold;">Cargo: </span>' . $_POST['cargo'] . '</li>

                <li><span style="font-weight: bold;">Nombre entidad: </span>' . $_POST['nom_entidad'] . '</li>

                <li><span style="font-weight: bold;">Trabaja actualmente: </span>' . $_POST['trabaja'] . '</li>

                <li><span style="font-weight: bold;">Certificado solicitado: </span>' . $tipo_doc['nombre'] . '</li>

                <li><span style="font-weight: bold;">Año gravable: </span>' . $_POST['anio'] . '</li>

                </ul>

                </p>

                ';



                $datos_correo = array(

                    'asunto'  => 'Solicitud de certificado',

                    'correo'  => array('ghumana@colegiohebreounion.edu.co'),

                    'user'    => 'Gestion Humana',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $envio = Correo::enviarCorreoModel($datos_correo);



            } else {

                echo '

                <script>

                ohSnap("Error al crear usuario", {color: "red"});

                </script>

                ';

            }

        }

    }



    public function solicitarTramiteControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $motivo         = (isset($_POST['motivo'])) ? $_POST['motivo'] : '';

            $mencione       = (isset($_POST['mencione'])) ? $_POST['mencione'] : 0;

            $otra           = (isset($_POST['otra'])) ? $_POST['otra'] : '';

            $nombre_dirige  = (isset($_POST['nombre_dirige'])) ? $_POST['nombre_dirige'] : '';

            $correo         = (isset($_POST['correo'])) ? $_POST['correo'] : '';

            $modo_entrega   = (isset($_POST['modo_entrega'])) ? $_POST['modo_entrega'] : 0;

            $anio_grabable  = (isset($_POST['anio_grabable'])) ? $_POST['anio_grabable'] : 0;

            $eps_actual     = (isset($_POST['eps_actual'])) ? $_POST['eps_actual'] : 0;

            $eps_traslado   = (isset($_POST['eps_traslado'])) ? $_POST['eps_traslado'] : 0;

            $grupo_familiar = (isset($_POST['grupo_familiar'])) ? $_POST['grupo_familiar'] : 0;

            $beneficiario   = (isset($_POST['beneficiario'])) ? $_POST['beneficiario'] : 0;



            $datos = array(

                'id_log'         => $_POST['id_log'],

                'tramite'        => $_POST['tramite'],

                'motivo'         => $motivo,

                'mencione'       => $mencione,

                'otra'           => $otra,

                'nombre_dirige'  => $nombre_dirige,

                'correo'         => $correo,

                'modo_entrega'   => $modo_entrega,

                'anio_grabable'  => $anio_grabable,

                'eps_actual'     => $eps_actual,

                'eps_traslado'   => $eps_traslado,

                'grupo_familiar' => $grupo_familiar,

            );



            $guardar = ModeloRecursos::solicitarTramiteModel($datos);



            if ($guardar['guardar'] == true) {



                if (isset($_POST['grupo_familiar_familia']) && !empty($_POST['grupo_familiar_familia'])) {



                    $array_grupo_familia = array();

                    $array_grupo_familia = $_POST['grupo_familiar_familia'];



                    $it = new MultipleIterator();

                    $it->attachIterator(new ArrayIterator($array_grupo_familia));



                    foreach ($it as $dato) {



                        $datos_grupo_familia = array(

                            'id_tramite'     => $guardar['id'],

                            'grupo_familiar' => $dato[0],

                            'id_log'         => $_POST['id_log'],

                        );



                        $guardar_grupo = ModeloRecursos::guardarGrupoFamiliarTramiteModel($datos_grupo_familia);



                    }



                }



                if (isset($_POST['beneficiario']) && !empty($_POST['beneficiario'])) {



                    $array_beneficiario = array();

                    $array_beneficiario = $_POST['beneficiario'];



                    $it = new MultipleIterator();

                    $it->attachIterator(new ArrayIterator($array_beneficiario));



                    foreach ($it as $dato) {



                        $datos_grupo_familia = array(

                            'id_tramite'     => $guardar['id'],

                            'grupo_familiar' => $dato[0],

                            'id_log'         => $_POST['id_log'],

                        );



                        $guardar_grupo = ModeloRecursos::guardarGrupoFamiliarTramiteModel($datos_grupo_familia);



                    }



                }



                $nom_archivo = '';



                if (isset($_FILES['cedula_titular']) && !empty($_FILES['cedula_titular'])) {

                    $nom_archivo = guardarArchivo($_FILES['cedula_titular']);



                    $datos_cedula = array(

                        'id_log'     => $_POST['id_log'],

                        'id_tramite' => $guardar['id'],

                        'archivo'    => $nom_archivo,

                    );



                    $guardar_documento = ModeloRecursos::guardarDocumentosTramiteModel($datos_cedula);

                }



                if (isset($_FILES['documento_necesario']) && !empty($_FILES['documento_necesario'])) {



                    for ($i = 0; $i < count($_FILES['documento_necesario']); $i++) {



                        $nom_archivo = guardarVariosArchivos($_FILES['documento_necesario'], $i);



                        if (!empty($nom_archivo)) {



                            $datos_documento = array(

                                'id_log'     => $_POST['id_log'],

                                'id_tramite' => $guardar['id'],

                                'archivo'    => $nom_archivo,

                            );



                            $guardar_documento = ModeloRecursos::guardarDocumentosTramiteModel($datos_documento);

                        }

                    }

                }



                if (isset($_FILES['certificado_fondo']) && !empty($_FILES['certificado_fondo'])) {



                    for ($i = 0; $i < count($_FILES['certificado_fondo']); $i++) {



                        $nom_archivo = guardarVariosArchivos($_FILES['certificado_fondo'], $i);



                        if (!empty($nom_archivo)) {



                            $datos_documento = array(

                                'id_log'     => $_POST['id_log'],

                                'id_tramite' => $guardar['id'],

                                'archivo'    => $nom_archivo,

                            );



                            $guardar_documento = ModeloRecursos::guardarDocumentosTramiteModel($datos_documento);

                        }

                    }

                }



                echo '

                <script>

                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>

                ';



                $datos_tramite = ModeloRecursos::mostrarDetallesTramiteModel($guardar['id']);



                $fecha_entrega = fechaDiasHabiles($datos_tramite['fechareg'], 5);

                

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);



                $correo_solicitante=$datos_usuario['correo'];



                $mensaje = '

                <p style="font-size: 1.4em;">

                El usuario <span style="font-weight: bold;">' . $datos_tramite['nom_user'] . '</span> ha realizado el siguiente tramite:

                <ul style="font-size: 1.3em;">

                <li><span style="font-weight: bold;">Documento: </span>' . $datos_tramite['documento'] . '</li>

                <li><span style="font-weight: bold;">Nombre: </span>' . $datos_tramite['nom_user'] . '</li>

                <li><span style="font-weight: bold;">Telefono: </span>' . $datos_tramite['telefono'] . '</li>

                <li><span style="font-weight: bold;">Tramite: </span>' . $datos_tramite['nom_tipo'] . '</li>

                <li><span style="font-weight: bold;">Fecha del tramite: </span>' . date('Y-m-d', strtotime($datos_tramite['fechareg'])) . '</li>

                <li><span style="font-weight: bold;">Fecha de entrega: </span>' . $fecha_entrega . '</li>

                <li><span style="font-weight: bold;">Estado del tramite: </span> Pendiente</li>

                </ul>

                </p>

                ';

                $correos = array('ghumana@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com', $correo_solicitante);


                $datos_correo = array(
                    'correo'    => $correos,
                    'asunto'  => 'Tramite o servicio',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                if ($enviar_correo) {
                    echo '<script>console.log("Correo enviado correctamente.");</script>';
                } else {
                    echo '<script>console.log("Error al enviar el correo.");</script>';
                }



            } else {

                echo '

                <script>

                ohSnap("Error al guardar", {color: "red"});

                </script>

                ';

            }



        }

    }



    public function subirArchivoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log']) &&

            isset($_POST['id_sol']) &&

            !empty($_POST['id_sol'])

        ) {



            $datos = array(

                'archivo' => $_FILES['archivo']['name'],

                'id_sol'  => $_POST['id_sol'],

                'id_log'  => $_POST['id_log'],

            );



            $guardar = $this->guardarArchivoControl($datos);



            if ($guardar == true) {

                echo '

                <script>

                ohSnap("Subido correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("solicitados");

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



    public function estadoTramiteControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $nom_archivo = '';



            if (isset($_FILES['certificado_laboral']['name']) && !empty($_FILES['certificado_laboral']['name'])) {

                $nom_archivo = guardarArchivo($_FILES['certificado_laboral']);

            }



            $datos = array(

                'id_tramite' => $_POST['id_tramite'],

                'id_log'     => $_POST['id_log'],

                'estado'     => $_POST['estado'],

                'motivo'     => $_POST['motivo'],

            );



            $guardar = ModeloRecursos::estadoTramiteModel($datos);



            if ($guardar == true) {



                $datos_certificado = array(

                    'id_tramite' => $_POST['id_tramite'],

                    'archivo'    => $nom_archivo,

                    'id_log'     => $_POST['id_log'],

                );



                $guardar_certificado = ModeloRecursos::guardarDocumentosTramiteModel($datos_certificado);



                echo '

                <script>

                ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("listado");

                }

                </script>

                ';



                $datos_tramite = ModeloRecursos::mostrarDetallesTramiteModel($_POST['id_tramite']);

                

                $correo_solicitante = $datos_tramite['correo'];



                $nom_estado = ($datos_tramite['estado'] == 1) ? 'Finalizado' : 'Rechazado';



                $fecha_entrega = fechaDiasHabiles($datos_tramite['fechareg'], 5);



                $archivos = ($datos_tramite['tipo_tramite'] == 1 && $datos_tramite['estado'] == 1) ? array($nom_archivo) : array('');



                $mensaje = '

                <p style="font-size: 1.4em;">

                El tramite No. ' . $_POST['id_tramite'] . ' que realizaste ha sido <span style="font-weight: bold;">' . $nom_estado . '</span>.

                <ul style="font-size: 1.3em;">

                <li><span style="font-weight: bold;">Documento: </span>' . $datos_tramite['documento'] . '</li>

                <li><span style="font-weight: bold;">Nombre: </span>' . $datos_tramite['nom_user'] . '</li>

                <li><span style="font-weight: bold;">Telefono: </span>' . $datos_tramite['telefono'] . '</li>

                <li><span style="font-weight: bold;">Tramite: </span>' . $datos_tramite['nom_tipo'] . '</li>

                <li><span style="font-weight: bold;">Fecha del tramite: </span>' . date('Y-m-d', strtotime($datos_tramite['fechareg'])) . '</li>

                <li><span style="font-weight: bold;">Fecha de entrega: </span>' . $fecha_entrega . '</li>

                <li><span style="font-weight: bold;">Estado del tramite: </span> ' . $nom_estado . '</li>';



                if ($datos_tramite['estado'] == 2) {

                    $mensaje .= '<br><li><span style="font-weight: bold;">Motivo de rechazo: </span> ' . $datos_tramite['motivo_rechazo'] . '</li>';

                }



                $mensaje .= '

                </ul>

                </p>

                ';



                $correos = ($_POST['salon'] == 9) ? : array('ghumana@colegiohebreounion.edu.co','angel_vargas_contreras@hotmail.com', $correo_solicitante);


                $datos_correo = array(
                    'correo'    => $correos,
                    'asunto'  => 'Tramite o servicio',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                if ($enviar_correo) {
                    echo '<script>console.log("Correo enviado correctamente.");</script>';
                } else {
                    echo '<script>console.log("Error al enviar el correo.");</script>';
                }



            } else {

                echo '

                <script>

                ohSnap("Error al cambiar estado", {color: "red"});

                </script>

                ';

            }

        }

    }



    public function solicitarPermisoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $fecha_permiso     = (isset($_POST['fecha_permiso']) && !empty($_POST['fecha_permiso'])) ? $_POST['fecha_permiso'] : '0000-00-00';

            $fecha_retorno     = (isset($_POST['fecha_retorno']) && !empty($_POST['fecha_retorno'])) ? $_POST['fecha_retorno'] : '0000-00-00';

            $dias_permiso      = (isset($_POST['dias_permiso']) && !empty($_POST['dias_permiso'])) ? $_POST['dias_permiso'] : '';

            $hora_salida       = (isset($_POST['hora_salida']) && !empty($_POST['hora_salida'])) ? $_POST['hora_salida'] : '00:00:00';

            $tiempo_aproximado = (isset($_POST['tiempo_aproximado']) && !empty($_POST['tiempo_aproximado'])) ? $_POST['tiempo_aproximado'] : '';

            $descripcion       = (isset($_POST['descripcion']) && !empty($_POST['descripcion'])) ? $_POST['descripcion'] : '';



            $datos = array(

                'id_log'            => $_POST['id_log'],

                'tipo_permiso'      => $_POST['tipo_permiso'],

                'motivo_permiso'    => $_POST['motivo_permiso'],

                'fecha_permiso'     => $fecha_permiso,

                'fecha_retorno'     => $fecha_retorno,

                'dias_permiso'      => $dias_permiso,

                'hora_salida'       => $hora_salida,

                'tiempo_aproximado' => $tiempo_aproximado,

                'descripcion'       => $descripcion,

            );



            $guardar = ModeloRecursos::solicitarPermisoModel($datos);



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



                $datos_permiso = ModeloRecursos::mostrarPermisoIdModel($guardar['id']);

               $correo_solicitante = $datos_permiso['correo_solicitante'];

                $nom_estado = ($datos_permiso['estado'] == 0) ? 'Pendiente' : '';



                $mensaje = '

                <p style="font-size: 1.4em;">

                El usuario <strong>' . $datos_permiso['nom_user'] . '</strong> ha realizado la solcitud de un permiso con los siguientes detalles:

                <ul style="font-size: 1.3em;">

                <li><span style="font-weight: bold;">Documento: </span>' . $datos_permiso['documento'] . '</li>

                <li><span style="font-weight: bold;">Nombre: </span>' . $datos_permiso['nom_user'] . '</li>

                <li><span style="font-weight: bold;">Telefono: </span>' . $datos_permiso['telefono'] . '</li>

                <li><span style="font-weight: bold;">Motivo Permiso: </span>' . $datos_permiso['nom_motivo'] . '</li>

                <li><span style="font-weight: bold;">Tipo Permiso: </span>' . $datos_permiso['nom_tipo'] . '</li>

                <li><span style="font-weight: bold;">Fecha del permiso: </span>' . $datos_permiso['fecha_permiso'] . '</li>

                <li><span style="font-weight: bold;">Estado de la solicitud: </span> ' . $nom_estado . '</li>

                </ul>

                </p>';



            $correos = ($_POST['salon'] == 9) ? : array('ghumana@colegiohebreounion.edu.co','angel_vargas_contreras@hotmail.com', $correo_solicitante);


                $datos_correo = array(
                    'correo'    => $correos,
                    'asunto'  => 'Permiso/Licencia',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );



                $envio = Correo::enviarCorreoModel($datos_correo);



            } else {

                echo '

                <script>

                ohSnap("Error al guardar", {color: "red"});

                </script>

                ';

            }

        }

    }



    public function estadoPermisoControl()

    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $datos = array(

                'id_permiso' => $_POST['id_permiso'],

                'id_log'     => $_POST['id_log'],

                'estado'     => $_POST['estado'],

                'motivo'     => $_POST['motivo'],

            );



            $guardar = ModeloRecursos::estadoPermisoModel($datos);



            if ($guardar == true) {



                echo '

                <script>

                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("listado");

                }

                </script>

                ';



                $datos_permiso = ModeloRecursos::mostrarPermisoIdModel($_POST['id_permiso']);



                $nom_estado = ($datos_permiso['estado'] == 1) ? 'Aprobado' : 'Rechazado';
                $correo_solicitante = $datos_permiso['correo_solicitante'];


                $mensaje = '

                <p style="font-size: 1.4em;">

                La solicitud No. ' . $_POST['id_permiso'] . ' ha sido <strong>' . $nom_estado . '</strong>:

                <ul style="font-size: 1.3em;">

                <li><span style="font-weight: bold;">Documento: </span>' . $datos_permiso['documento'] . '</li>

                <li><span style="font-weight: bold;">Nombre: </span>' . $datos_permiso['nom_user'] . '</li>

                <li><span style="font-weight: bold;">Telefono: </span>' . $datos_permiso['telefono'] . '</li>

                <li><span style="font-weight: bold;">Motivo Permiso: </span>' . $datos_permiso['nom_motivo'] . '</li>

                <li><span style="font-weight: bold;">Tipo Permiso: </span>' . $datos_permiso['nom_tipo'] . '</li>

                <li><span style="font-weight: bold;">Fecha del permiso: </span>' . $datos_permiso['fecha_permiso'] . '</li>

                <li><span style="font-weight: bold;">Estado de la solicitud: </span> ' . $nom_estado . '</li>';



                if ($datos_permiso['estado'] == 2) {

                    $mensaje .= '<li><span style="font-weight: bold;">Motivo del rechazo: </span> ' . $datos_permiso['motivo_rechazo'] . '</li>';

                }



                $mensaje .= '

                </ul>

                </p>';



                $datos_correo = array(

                    'asunto'  => 'Permiso/Licencia - ' . $nom_estado,

                    //'correo'  => array('jesus.polo@royalschool.edu.co'), aqui 

                    'correo'  => array('ghumana@colegiohebreounion.edu.co','angel_vargas_contreras@hotmail.com', $correo_solicitante),

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $envio = Correo::enviarCorreoModel($datos_correo);



            } else {

                echo '

                <script>

                ohSnap("Error al guardar", {color: "red"});

                </script>

                ';

            }



        }

    }



    public function guardarArchivoControl($datos)

    {

        //obtener el nombre del archivo

        $nom_arch = $datos['archivo'];

        //extraer la extencion del archivo de el archivo

        $ext_arch   = explode(".", $nom_arch);

        $ext_arch   = end($ext_arch);

        $fecha_arch = date('YmdHis');



        $nombre_archivo = strtolower(md5($datos['id_sol'] . '_' . $fecha_arch)) . '.' . $ext_arch;



        $datos_temp = array(

            'nombre' => $nombre_archivo,

            'id_sol' => $_POST['id_sol'],

            'id_log' => $_POST['id_log'],

        );



        $guardar_cert = ModeloRecursos::subirArchivoModel($datos_temp);



        if ($guardar_cert == true) {

            //ruta donde de alojamiento el archivo

            $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;

            $ruta_img     = $carp_destino . $nombre_archivo;



            //verificar si subio el archivo y se mueve a su destino

            if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {

                move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);

            }



            return true;

        }

    }

}

