<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloRecursos extends conexion

{

    public static function mostrarTipoDocumentoModel($super_empresa)

    {

        $tabla  = 'tipo_documento';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_super_empresa = :id";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $super_empresa);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }

public static function mostrarPermisosLicenciasUsuarioNivelFiltroModel($datos){
        $tabla = 'permiso';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT
                    p.*,
                    u.documento,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                    u.telefono,
                    pt.nombre AS nom_tipo,
                    pm.nombre AS nom_motivo,
                    u.correo as correo_user
                    FROM permiso p
                    LEFT JOIN usuarios u ON u.id_user = p.id_user
                    LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso
                    LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso
                    WHERE u.id_nivel = :id 
                    " . $datos['buscar'] . "
                    " . $datos['usuario'] . "
                    " . $datos['fecha'] . "
                    ORDER BY p.fecha_permiso DESC;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_nivel']);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }


 public static function mostrarPermisosLicenciasUsuarioNivelModel($id){
        $tabla = 'permiso';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT
                        p.*,
                        u.documento,
                        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                        u.telefono,
                        pt.nombre AS nom_tipo,
                        pm.nombre AS nom_motivo,
                        u.correo AS correo_user
                    FROM permiso p
                    LEFT JOIN usuarios u ON u.id_user = p.id_user
                    LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso
                    LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso
                    WHERE u.id_nivel = :id
                    AND MONTH(p.fecha_permiso) = MONTH(CURDATE())
                    AND YEAR(p.fecha_permiso) = YEAR(CURDATE())
                    ORDER BY p.fecha_permiso;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }
    

    public static function solicitarCertificadoModel($datos)

    {

        $tabla  = 'sol_certificado';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO sol_certificados (id_user, lugar, cargo, nombre_entidad, trabaja_act, tipo_cert, id_super_empresa, anio)

        VALUES (:idu,:l,:c,:ne,:ta,:t,:ids,:an);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':l', $datos['lugar']);

            $preparado->bindParam(':c', $datos['cargo']);

            $preparado->bindParam(':ne', $datos['nombre_entidad']);

            $preparado->bindParam(':ta', $datos['trabaja_act']);

            $preparado->bindParam(':t', $datos['tipo_cert']);

            $preparado->bindParam(':ids', $datos['id_super_empresa']);

            $preparado->bindParam(':an', $datos['anio']);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarSolicitudesIdModel($id)

    {

        $tabla  = 'sol_certificados';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT s.*,

        (SELECT t.nombre FROM tipo_documento t WHERE t.id = s.tipo_cert) as documento

        FROM " . $tabla . " s WHERE s.id_user = :id AND s.tipo_cert IN(1,2);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarSolicitudesControl($id)

    {

        $tabla  = 'sol_certificados';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT s.*,

        (SELECT t.nombre FROM tipo_documento t WHERE t.id = s.tipo_cert) as documento,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS usuario

        FROM " . $tabla . " s WHERE s.id_super_empresa = :id AND s.tipo_cert IN(1,2);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function subirArchivoModel($datos)

    {

        $tabla  = 'sol_certificados';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET estado = 2 WHERE id = :id;

        INSERT INTO documentos (nombre, id_sol, id_log) VALUES (:n,:id,:idl);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $datos['id_sol']);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function certificadoMostrarModel($id)

    {

        $tabla  = 'documentos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_sol = :id ORDER BY id DESC LIMIT 1";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarDatosTipoDocumentoModel($id)

    {

        $tabla  = 'tipo_documento';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id = :id

        ";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarTramitesModel()

    {

        $tabla  = 'tramite_tipo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarListadoTramiteModel()

    {

        $tabla  = 'tramite';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        t.*,

        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

        u.documento,

        u.telefono,

        tt.nombre AS nom_tipo,

        ep.nombre AS nom_eps_actual,

        ept.nombre AS nom_eps_traslado,

        tg.nombre AS nom_eps_grupo_familiar,

        tb.nombre AS nom_beneficiario

        FROM tramite t

        LEFT JOIN usuarios u ON u.id_user = t.id_user

        LEFT JOIN tramite_tipo tt ON tt.id = t.tipo_tramite

        LEFT JOIN eps ep ON ep.id = t.eps_actual

        LEFT JOIN eps ept ON ept.id = t.eps_traslado

        LEFT JOIN tramite_grupo_familiar tg ON tg.id = t.eps_grupo_familiar

        LEFT JOIN tramite_grupo_familiar tb ON tb.id = t.beneficiario

        ORDER BY t.fechareg DESC LIMIT 20;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarListadoUsuarioTramiteModel($id)

    {

        $tabla  = 'tramite';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        t.*,

        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

        u.documento,

        u.telefono,

        tt.nombre AS nom_tipo,

        ep.nombre AS nom_eps_actual,

        ept.nombre AS nom_eps_traslado,

        tg.nombre AS nom_eps_grupo_familiar,

        tb.nombre AS nom_beneficiario

        FROM tramite t

        LEFT JOIN usuarios u ON u.id_user = t.id_user

        LEFT JOIN tramite_tipo tt ON tt.id = t.tipo_tramite

        LEFT JOIN eps ep ON ep.id = t.eps_actual

        LEFT JOIN eps ept ON ept.id = t.eps_traslado

        LEFT JOIN tramite_grupo_familiar tg ON tg.id = t.eps_grupo_familiar

        LEFT JOIN tramite_grupo_familiar tb ON tb.id = t.beneficiario

        WHERE t.id_user = :id

        ORDER BY t.fechareg DESC LIMIT 20;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarDetallesTramiteModel($id)

    {

        $tabla  = 'tramite';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        t.*,

        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

        u.documento,

        u.telefono,

        tt.nombre AS nom_tipo,

        ep.nombre AS nom_eps_actual,

        ept.nombre AS nom_eps_traslado,

        tg.nombre AS nom_eps_grupo_familiar,

        tb.nombre AS nom_beneficiario

        FROM tramite t

        LEFT JOIN usuarios u ON u.id_user = t.id_user

        LEFT JOIN tramite_tipo tt ON tt.id = t.tipo_tramite

        LEFT JOIN eps ep ON ep.id = t.eps_actual

        LEFT JOIN eps ept ON ept.id = t.eps_traslado

        LEFT JOIN tramite_grupo_familiar tg ON tg.id = t.eps_grupo_familiar

        LEFT JOIN tramite_grupo_familiar tb ON tb.id = t.beneficiario

        WHERE t.id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarDocumentosTramiteModel($id)

    {

        $tabla  = 'tramite_documentos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_tramite = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarDatosGrupoFamiliarModel()

    {

        $tabla  = 'tramite_grupo_familiar';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function solicitarTramiteModel($datos)

    {

        $tabla  = 'tramite';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO tramite (

          id_user,

          tipo_tramite,

          motivo,

          mencion_certificado,

          otra,

          entidad_certificado,

          correo,

          modo_entrega,

          anio_grabable,

          eps_actual,

          eps_traslado,

          eps_grupo,

          id_log

          )

        VALUES

        (

            '" . $datos['id_log'] . "',

            '" . $datos['tramite'] . "',

            '" . $datos['motivo'] . "',

            '" . $datos['mencione'] . "',

            '" . $datos['otra'] . "',

            '" . $datos['nombre_dirige'] . "',

            '" . $datos['correo'] . "',

            '" . $datos['modo_entrega'] . "',

            '" . $datos['anio_grabable'] . "',

            '" . $datos['eps_actual'] . "',

            '" . $datos['eps_traslado'] . "',

            '" . $datos['grupo_familiar'] . "',

            '" . $datos['id_log'] . "'

        );";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                $id = $cnx->ultimoIngreso($tabla);

                $rs = array('guardar' => true, 'id' => $id);

                return $rs;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function guardarDocumentosTramiteModel($datos)

    {

        $tabla  = 'tramite_documentos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_tramite, archivo, id_log) VALUES ('" . $datos['id_tramite'] . "', '" . $datos['archivo'] . "', '" . $datos['id_log'] . "');";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function estadoTramiteModel($datos)

    {

        $tabla  = 'tramite';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET estado = '" . $datos['estado'] . "', id_edit = '" . $datos['id_log'] . "', fecha_edit = NOW(), motivo_rechazo = '" . $datos['motivo'] . "' WHERE id = '" . $datos['id_tramite'] . "';";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function guardarGrupoFamiliarTramiteModel($datos)

    {

        $tabla  = 'tramite_familiar';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_tramite, grupo_familiar, id_log) VALUES ('" . $datos['id_tramite'] . "','" . $datos['grupo_familiar'] . "','" . $datos['id_log'] . "');";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarTramiteFamiliarModel($id)

    {

        $tabla  = 'tramite_familiar';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                tf.*,

                tg.nombre

                FROM tramite_familiar tf

                LEFT JOIN tramite_grupo_familiar tg ON tg.id = tf.grupo_familiar

                WHERE tf.id_tramite = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarTipoPermisoModel()

    {

        $tabla  = 'permiso_tipo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarMotivosPermisoModel()

    {

        $tabla  = 'permiso_motivo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function solicitarPermisoModel($datos)

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO sami_hebreo.permiso (

                  id_user,

                  tipo_permiso,

                  motivo_permiso,

                  fecha_permiso,

                  fecha_retorno,

                  dias_permiso,

                  hora_salida,

                  tiempo_permiso,

                  descripcion,

                  id_log

                  )

                VALUES

                (

                    '" . $datos['id_log'] . "',

                    '" . $datos['tipo_permiso'] . "',

                    '" . $datos['motivo_permiso'] . "',

                    '" . $datos['fecha_permiso'] . "',

                    '" . $datos['fecha_retorno'] . "',

                    '" . $datos['dias_permiso'] . "',

                    '" . $datos['hora_salida'] . "',

                    '" . $datos['tiempo_aproximado'] . "',

                    '" . $datos['descripcion'] . "',

                    '" . $datos['id_log'] . "'

                );";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                $id = $cnx->ultimoIngreso($tabla);

                $rs = array('guardar' => true, 'id' => $id);

                return $rs;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarPermisoIdModel($id)

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                p.*,

                u.documento,

                CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                u.telefono,

                u.correo AS correo_solicitante,

                pt.nombre AS nom_tipo,

                pm.nombre AS nom_motivo

                FROM permiso p

                LEFT JOIN usuarios u ON u.id_user = p.id_user

                LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso

                LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso

                WHERE p.id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetch();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function mostrarListadoPermisosModel($id_nivel)

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                p.*,

                u.documento,

                CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                u.telefono,

                pt.nombre AS nom_tipo,

                pm.nombre AS nom_motivo

                FROM permiso p

                LEFT JOIN usuarios u ON u.id_user = p.id_user

                LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso

                LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso

                where u.id_nivel= :id_nivel

                ORDER BY p.fechareg DESC LIMIT 20;";

        try {

            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_nivel',$id_nivel) ;

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }

public static function mostrarPermisosLicenciasUsuariosFiltroModel($datos){

        $tabla = 'permiso';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    p.*,

                    u.documento,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                    u.telefono,

                    pt.nombre AS nom_tipo,

                    pm.nombre AS nom_motivo,

                    u.correo as correo_user

                    FROM permiso p

                    LEFT JOIN usuarios u ON u.id_user = p.id_user

                    LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso

                    LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso

                    WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', u.correo) LIKE '%" . $_POST['buscar'] . "%'

                    " . $datos['usuario'] . "

                    " . $datos['fecha'] . "

                    ORDER BY p.fecha_permiso DESC;";

            try{

                $preparado = $cnx->preparar($cmdsql);

                if($preparado->execute()){

                    return $preparado->fetchAll();

                }else{

                    return false;

                }

            }catch(PDOException $e){

                print 'Error!: ' . $e->getMessage();

            }

            $cnx->closed();

            $cnx = null;

    }

      public static function mostrarListadoPermisosModelgestionhumana()

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                p.*,

                u.documento,

                CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                u.telefono,

                pt.nombre AS nom_tipo,

                pm.nombre AS nom_motivo

                FROM permiso p

                LEFT JOIN usuarios u ON u.id_user = p.id_user

                LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso

                LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso

                ORDER BY p.fechareg DESC LIMIT 800;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }




    public static function mostrarPermisosUsuarioModel($id)

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                p.*,

                u.documento,

                CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                u.telefono,

                pt.nombre AS nom_tipo,

                pm.nombre AS nom_motivo

                FROM permiso p

                LEFT JOIN usuarios u ON u.id_user = p.id_user

                LEFT JOIN permiso_tipo pt ON pt.id = p.tipo_permiso

                LEFT JOIN permiso_motivo pm ON pm.id = p.motivo_permiso

                WHERE p.id_user = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return $preparado->fetchAll();

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }



    public static function estadoPermisoModel($datos)

    {

        $tabla  = 'permiso';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET motivo_rechazo = '".$datos['motivo']."', estado = '".$datos['estado']."', id_edit = '".$datos['id_log']."', fecha_edit = NOW() WHERE id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $datos['id_permiso']);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                return true;

            } else {

                return false;

            }

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();

        }

        $cnx->closed();

        $cnx = null;

    }

}

