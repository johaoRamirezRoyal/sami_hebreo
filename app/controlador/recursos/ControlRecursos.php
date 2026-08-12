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
 public function cancelar() {
    if (isset($_GET['id'])) {
        $id_permiso = base64_decode($_GET['id']);

        // Llamar al modelo
        $resultado = $this->modelo->cancelarPermiso($id_permiso);

        if ($resultado) {
            // Redirigir a la lista con mensaje de éxito
            header("Location: " . BASE_URL . "recursos/permisos?msg=cancelado");
            exit();
        } else {
            echo "Error al cancelar el permiso.";
        }
    } else {
        echo "ID de permiso inválido.";
    }
}

public function cancelarPermiso() {
    if (isset($_POST['idcancelarpermiso'])) {
        $id_permiso = $_POST['idcancelarpermiso'];
     
        $resultado = ModeloRecursos::cancelarPermiso($id_permiso);

        if($resultado == true){
          echo '
                <script>
                ohSnap("Solicitud Cancelada!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("misSolicitudes");
                }
                </script>
 ';

        }else{
            echo '
                <h1>Error!</h1>
            ';
        }
   
        exit;
    } else {
        echo "ID inválido";
    }
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

    public function mostrarListadoTramitePermisosControl(){
        $mostrar = ModeloRecursos::mostrarListadoTramitePermisosModel();
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

    public function mostrarListadoPermisosControl()
    {
        $mostrar = ModeloRecursos::mostrarListadoPermisosModel();
        return $mostrar;
    }

    public function mostrarPermisosLicenciasUsuarioNivelControl($id){
        $mostrar = ModeloRecursos::mostrarPermisosLicenciasUsuarioNivelModel($id);
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

    public function mostrarPermisosLicenciasUsuariosFiltroControl($datos){

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
                    'correo'  => array('ghumana@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),
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
            $fecha_inicio   = (isset($_POST['fecha_inicio'])) ? $_POST['fecha_inicio'] : '';
            $fecha_fin      = (isset($_POST['fecha_fin'])) ? $_POST['fecha_fin'] : '';
            $hora_salida    = (isset($_POST['hora_salida'])) ? $_POST['hora_salida'] : '';
            $descripcion    = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '';

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
                'fecha_inicio'   => $fecha_inicio,
                'fecha_fin'      => $fecha_fin,
                'hora_salida'    => $hora_salida,
                'descripcion'    => $descripcion,
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

                $datos_correo = array(
                    'asunto'  => 'Tramite o servicio',
                    //'correo'  => array('jesus.polo@royalschool.edu.co'),
                    'correo'  => array('ghumana@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com', $correo_solicitante),
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

    //Aquí importante función
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
                $correo_solicitante_sami = $datos_tramite['correo_user'];

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

                $datos_correo = array(
                    'asunto'  => 'Tramite o servicio - ' . $nom_estado,
                    //'correo'  => array('jesus.polo@royalschool.edu.co'),
                    'correo'  => array('ghumana@colegiohebreounion.edu.co', 'angel_vargas_contreras', ($correo_solicitante == "") ? $correo_solicitante_sami : $correo_solicitante),
                    'mensaje' => $mensaje,
                    'archivo' => $archivos,
                );

                $envio = Correo::enviarCorreoModel($datos_correo);

            } else {
                echo '
                <script>
                ohSnap("Error al cambiar estado", {color: "red"});
                </script>
                ';
            }
        }
    }

    //Aquí importante función
    public function solicitarPermisoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $evidencia_permiso = $_FILES['evidencia_permiso']['name'];
            $extension_permiso = pathinfo($evidencia_permiso, PATHINFO_EXTENSION);
            $fecha_archivo = date('YmdHis');

            $nombre_archivo = ($evidencia_permiso == '') ? '' : strtolower(md5($fecha_archivo)) . '_' . $evidencia_permiso;


            $fecha_permiso     = (isset($_POST['fecha_permiso']) && !empty($_POST['fecha_permiso'])) ? $_POST['fecha_permiso'] : '0000-00-00';
            $fecha_retorno     = (isset($_POST['fecha_retorno']) && !empty($_POST['fecha_retorno'])) ? $_POST['fecha_retorno'] : '0000-00-00';
            $dias_permiso      = (isset($_POST['dias_permiso']) && !empty($_POST['dias_permiso'])) ? $_POST['dias_permiso'] : '';
            $hora_salida       = (isset($_POST['hora_salida']) && !empty($_POST['hora_salida'])) ? $_POST['hora_salida'] : '00:00:00';
            $tiempo_aproximado = (isset($_POST['tiempo_aproximado']) && !empty($_POST['tiempo_aproximado'])) ? $_POST['tiempo_aproximado'] : '';
            $descripcion       = (isset($_POST['descripcion']) && !empty($_POST['descripcion'])) ? $_POST['descripcion'] : '';
            $permiso_detalle   = (isset($_POST['permiso_ley']) && !empty($_POST['permiso_ley'])) ? $_POST['permiso_ley'] : $_POST['permiso_personal'];


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
                'nombre_archivo'    => $nombre_archivo,
                'tipo_permiso_detalle' => $permiso_detalle,
                'nivel' => $_POST['nivel'],
                'perfil' => $_POST['perfil']    
            );

            $guardar = ModeloRecursos::solicitarPermisoModel($datos);

            if ($guardar['guardar'] == true) {

                //ruta donde de alojamiento el archivo
				$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
				$ruta_img = $carp_destino . $datos['nombre_archivo'];
	
                if(isset($_FILES['evidencia_permiso']['name']) && !empty($_FILES['evidencia_permiso']['name'])){
                    if (is_uploaded_file($_FILES['evidencia_permiso']['tmp_name'])) {
                        if (move_uploaded_file($_FILES['evidencia_permiso']['tmp_name'], $ruta_img)) {
                            if (file_exists($ruta_img)) {
                                echo "✅ Archivo guardado correctamente ";
                            } else {
                                echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino.";
                            }
                        } else {
                            echo "❌ Error al mover el archivo.";
                        }
                    } else {
                        echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
                        die();
                    }
                }

                $datos_permiso = ModeloRecursos::mostrarPermisoIdModel($guardar['id']);

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
                <li><span style="font-weight: bold;">Descripción del permiso: </span>' . $datos_permiso['descripcion'] . '</li>
                <li><span style="font-weight: bold;">Fecha del permiso: </span>' . $datos_permiso['fecha_permiso'] . '</li>
                <li><span style="font-weight: bold;">Estado de la solicitud: </span> ' . $nom_estado . '</li>
                </ul>
                </p>';

                $correo_coordinador = $datos_permiso['correo_coordinador'];
                $informacion_asistente = ModeloRecursos::correoAsistenteDeNivel($datos_permiso['nivel_user']);
                $correo_asistente = $informacion_asistente['correo'];

                if(($correo_coordinador === null || $correo_coordinador === '') && 
                    ($datos_permiso['nivel_user'] == 1 || $datos_permiso['nivel_user'] == 6 || $datos['nivel_user'] == 7)){
                    $correo_coordinador = 'ghumana@colegiohebreounion.edu.co' ;
                }

                $datos_correo = array(
                    'asunto'  => 'Permiso/Licencia',
                    //'correo'  => array('hernando.ramirez@royalschool.edu.co'),
                    'correo'  => array('ghumana@colegiohebreounion.edu.co', $correo_asistente, $datos_permiso['correo_user'], $correo_coordinador),
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );
          

                $envio = Correo::enviarCorreoModel($datos_correo);

                echo '
                <script>
                ohSnap("Solicitado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';

            } else {
                echo '
                <script>
                ohSnap("Error al guardar", {color: "red"});
                </script>
                ';
            }
        }
    }

    //Aquí importante función
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
                'remunerado' => $_POST['remunerado']
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
           
                
                $informacion_asistente = ModeloRecursos::correoAsistenteDeNivel($datos_permiso['nivel_user']);
                    
                $nom_estado = ($datos_permiso['estado'] == 1) ? 'Aprobado' : 'Rechazado';
                $dias = ($datos_permiso['dias_permiso'] != '') ? $datos_permiso['dias_permiso'] : '';
                $fecha_retorno = ($datos_permiso['fecha_retorno'] != '') ? $datos_permiso['fecha_retorno'] : '';
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
                <li><span style="font-weight: bold;">Dias del permiso: </span>' . $dias . '</li>
                <li><span style="font-weight: bold;">Hora de salida: </span>' . $datos_permiso['hora_salida'] . '</li>
                <li><span style="font-weight: bold;">Fecha de retorno: </span>' . $fecha_retorno . '</li>
                <li><span style="font-weight: bold;">Solicitud remunerada: </span> ' . $datos_permiso['remunerado'] . '</li>
                <li><span style="font-weight: bold;">Estado de la solicitud: </span> ' . $nom_estado . '</li>';

                if ($datos_permiso['estado'] == 2) {
                    $mensaje .= '<li><span style="font-weight: bold;">Motivo del rechazo: </span> ' . $datos_permiso['motivo_rechazo'] . '</li>';
                }

                $mensaje .= '
                </ul>
                </p>';

                $correo_coordinador = $datos_permiso['correo_coordinador'];
                $correo_asistente = $informacion_asistente['correo'];

                if(($correo_coordinador === null || $correo_coordinador === '') && 
                    ($datos_permiso['nivel_user'] == 1 || $datos_permiso['nivel_user'] == 6) || $datos['perfil'] == 11){
                    $correo_coordinador = 'ghumana@colegiohebreounion.edu.co';
                   // $correo_asistente = 'carolina.otalora@royalschool.edu.co';
                }

                $datos_correo = array(
                    'asunto'  => 'Permiso/Licencia - ' . $nom_estado,
                    //'correo'  => array('aprendiz.sistemas@royalschool.edu.co'),
                    'correo'  => array('ghumana@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com', $datos_permiso['correo_user'], $correo_asistente, $correo_coordinador),
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

    public function mostrarPermisosUsuarioNivelControl($id_nivel){
        $mostrar = ModeloRecursos::mostrarPermisosUsuarioNivelModel($id_nivel);
        return $mostrar;
    }

    public function mostrarPermisosLegalesControl(){
        $permisos = ModeloRecursos::mostrarPermisosLegalesModel();
        return $permisos;
    }

    public function mostrarPermisosPersonalesControl(){
        $permisos = ModeloRecursos::mostrarPermisosPersonalesModel();
        return $permisos;
    }   

    public function enviarRecordatorioControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id']) &&
            !empty($_POST['id'])
        ){
            $datos = array(
                'id' => $_POST['id'],
                'correo' => $_POST['correo'],
                'id_log' => $_POST['id_log'],
            );

            $datos_permiso = $this->mostrarPermisoIdControl($datos['id']);
            
            $mensaje = '<h1>Recordatorio Solicitud de permiso No. '.$datos['id'].' de '.$datos_permiso['nom_user'].' con la fecha de '.$datos_permiso['fecha_permiso'].'. </h1>';
            $mensaje .= '<p>Este recordatorio es para notificarle que su solicitud se encuentra pendiente de aprobación debido a la falta de entrega de la evidencia de permiso. <b>Por favor, adjunte un documento que evidencie de manera pertinente su solicitud </p> </p>';

            $datos_correo = array(
                'asunto'  => 'Permiso/Licencia - Solicitud No. '. $datos['id'],
                //'correo'  => array('aprendiz.sistemas@royalschool.edu.co'), //array($datos['correo']),
                'correo'  => array($datos['correo']),
                'mensaje' => $mensaje,
                'archivo' => array(''),
            );
            $envio = Correo::enviarCorreoModel($datos_correo);
        }
    }

    public function actualizarDatosDelPermiso($datos){
        if($_SERVER['REQUEST_METHOD'] == 'POST' 
            && isset($_POST['id_permiso']) 
            && !empty($_POST['id_permiso'])){

                if(!empty($_FILES['evidencia_permiso']['name'])){
                    //obtener el nombre de la evidencia
                    $nombre_evidencia = $_FILES['evidencia_permiso']['name'];
                    $extension_evidencia = pathinfo($nombre_evidencia, PATHINFO_EXTENSION);
                    $fecha_evidencia = date('YmdHis');

                    $nombre_evidencia = ($nombre_evidencia == '') ? '' : strtolower(md5($fecha_evidencia)) . '_' . $nombre_evidencia;
                }else{
                    $nombre_evidencia = $_POST['evidencia_permiso_guardada'];
                }

                $datos = array(
                    'id_permiso' => $_POST['id_permiso'],
                    'id_log' => $_POST['id_log'],
                    'descripcion' => $_POST['descripcion'],
                    'dias_permiso' => (!empty($_POST['dias_modal'])) ? $_POST['dias_modal'] : '',
                    'hora_salida' => (!empty($_POST['hora_salida'])) ? $_POST['hora_salida'] : '',
                    'tiempo_aproximado' => (!empty($_POST['tiempo_aproximado'])) ? $_POST['tiempo_aproximado'] : '',
                    'fecha_retorno' => (!empty($_POST['fecha_retorno_modal'])) ? $_POST['fecha_retorno_modal'] : '',
                    'fecha_permiso' => (!empty($_POST['fecha_permiso_modal'])) ? $_POST['fecha_permiso_modal'] : '',
                    'evidencia_permiso' => $nombre_evidencia,
                );

                $actualizar = ModeloRecursos::actualizarDatosDelPermisoModel($datos);

                if($actualizar){
                    $id = $datos['id_permiso'];

                    $datos_permiso = ModeloRecursos::mostrarPermisoIdModel($id);
                    $informacion_asistente = ModeloRecursos::correoAsistenteDeNivel($datos_permiso['nivel_user']);
                    
                    //Enviar a correo
                    $mensaje = '<h1>Permiso/Licencia actualizado</h1>';
                    $mensaje .= '<p>El permiso/licencia No. '.$datos_permiso['id'].' ha sido actualizado con el siguiente detalle:</p>';
                    $mensaje .= '<ul style="font-size: 1.3em;">';
                    $mensaje .= '<li><span style="font-weight: bold;">Documento: </span>'.$datos_permiso['documento'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Nombre: </span>'.$datos_permiso['nom_user'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Telefono: </span>'.$datos_permiso['telefono'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Motivo del permiso: </span>'.$datos_permiso['nom_motivo'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Tipo de permiso: </span>'.$datos_permiso['nom_tipo'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Fecha del permiso: </span>'.$datos_permiso['fecha_permiso'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Fecha de retorno: </span>'.$datos_permiso['fecha_retorno'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Cantidad de días de permiso: </span>'.$datos_permiso['dias_permiso'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Hora de salida: </span>'.$datos_permiso['hora_salida'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Tiempo aproximado del permiso(Minutos): </span>'.$datos_permiso['tiempo_permiso'].'</li>';
                    $mensaje .= '<li><span style="font-weight: bold;">Detalle del permiso: </span>'.$datos_permiso['nom_motivo'].'</li>';
                    $mensaje .= '</ul>';

                    $datos_correo = array(
                        'asunto'  => 'Permiso/Licencia - Solicitud No. '. $datos['id'],
                        'correo'  => array($datos_permiso['correo_user'], $datos_permiso['correo_coordinador'], $informacion_asistente['correo']),
                        'mensaje' => $mensaje,
                        'archivo' => array(''),
                    );

                    $envio = Correo::enviarCorreoModel($datos_correo);

                    $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                    $ruta_img = $carp_destino . $nombre_evidencia;

                    if(isset($_FILES['evidencia_permiso']['name']) && !empty($_FILES['evidencia_permiso']['name'])){
                    if (is_uploaded_file($_FILES['evidencia_permiso']['tmp_name'])) {
                        if (move_uploaded_file($_FILES['evidencia_permiso']['tmp_name'], $ruta_img)) {
                            if (file_exists($ruta_img)) {
                                echo "✅ Archivo guardado correctamente ";
                            } else {
                                echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino. Informe al administrador del sistema. (Recargue la página)";
                                die();
                            }
                            } else {
                                echo "❌ Error al mover el archivo. Informe al administrador del sistema. (Recargue la página)";
                                die();
                            }
                        } else {
                            echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
                            die();
                        }   
                    }
                    echo '
                        <script>
                        ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});
                        setTimeout(recargarPagina,1050);

                        function recargarPagina(){
                            window.location.replace("detalles?id='.base64_encode($datos['id_permiso']).'&enlace='.base64_encode(0).'");
                        }
                        </script>
                        ';
                }else{
                    echo '
                        <script>
                        ohSnap("Error al Actualizar", {color: "red"});
                        </script>
                    ';
                }
        }
    }

    public function mostrarReportesYLicenciasDiaCompletoParaExcelControl(){
        $datos = ModeloRecursos::mostrarReportesYLicenciasDiaCompletoParaExcelModel();
        return $datos;
    }

    public function mostrarReportesYLicenciasDiaParcialParaExcelControl(){
        $datos = ModeloRecursos::mostrarReportesYLicenciasDiaParcialParaExcelModel();
        return $datos;
    }

    public function mostrarReportesParcialesFiltroControl($datos){
        $datos = ModeloRecursos::mostrarReportesParcialesFiltroModel($datos);
        return $datos;
    }

    public function mostrarReportesDiaCompletoFiltroControl($datos){

        $cond_nivel = ($datos['nivel'] == '') ? '' : "AND u.id_nivel = ".$datos['nivel'];

        $datos = array(
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'cond_nivel' => $cond_nivel,
        );

        $datos_reportes = ModeloRecursos::mostrarReportesDiaCompletoFiltroModel($datos);
        return $datos_reportes;
    } 

    public function mostrarPermisosInstitucionalesControl(){
        $permisos = ModeloRecursos::mostrarPermisosInstitucionalesModel();
        return $permisos;
    }

}