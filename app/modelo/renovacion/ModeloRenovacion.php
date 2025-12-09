<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloRenovacion extends conexion
{
    public static function agregarRenovacionModel($datos)
    {
        $tabla  = 'renovacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, fecha_renovacion, fecha_proxima, id_log) VALUES (:n, :fr, :fp, :idl);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':fr', $datos['fecha_renovacion']);
            $preparado->bindParam(':fp', $datos['fecha_proxima']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id        = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function guardarEvidenciaRenovacionModel($datos)
    {
        $tabla  = 'renovacion_evidencia';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_renovacion, nombre, id_log) VALUES (:idr, :n, :idl);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idr', $datos['id_renovacion']);
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

    public static function agregarRenovacionDocumentoModel($datos)
    {
        $tabla  = 'renovacion_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        tipo_proceso,
        nom_documento,
        version_doc,
        fecha_vigencia,
        fecha_revision,
        categ_doc,
        gestion_cambio,
        url_archivo,
        evidencia,
        id_user,
        id_log
        )
        VALUES
        (
        '" . $datos['tipo_proceso'] . "',
        '" . $datos['nom_documento'] . "',
        '" . $datos['version_doc'] . "',
        '" . $datos['fecha_vigencia'] . "',
        '" . $datos['fecha_revision'] . "',
        '" . $datos['categ_doc'] . "',
        '" . $datos['gestion_cambio'] . "',
        '" . $datos['url_archivo'] . "',
        '" . $datos['evidencia'] . "',
        '" . $datos['id_log'] . "',
        '" . $datos['id_log'] . "'
    );";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id        = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarRenovacionesModel()
    {
        $tabla  = 'renovacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    r.* ,
    re.nombre AS archivo
    FROM renovacion r
    LEFT JOIN renovacion_evidencia re ON re.id_renovacion = r.id
    WHERE r.activo = 1 ORDER BY r.id DESC LIMIT 30;";
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

    public static function mostrarTodasRenovacionesModel()
    {
        $tabla  = 'renovacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    r.*,
    (SELECT MAX(rp.fecha_proxima) FROM renovacion rp WHERE rp.nombre = r.nombre AND rp.id < r.id ORDER BY rp.id DESC LIMIT 1) AS ultima_renovacion
    FROM renovacion r WHERE r.activo = 1;";
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

    public static function mostrarGestionModel()
    {
        $tabla  = 'procesos_documento';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    p.*,
    (SELECT COUNT(r.id) FROM renovacion_documentos r WHERE r.tipo_proceso = p.id AND r.activo = 1 GROUP BY r.tipo_proceso) AS cantidad
    FROM procesos_documento p WHERE p.activo = 1;";
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

    public static function mostrarRenovacionesDocumentosModel()
    {
        $tabla  = 'renovacion_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    r.*,
    t.nombre AS nom_tipo,
    c.nombre AS categ
    FROM renovacion_documentos r
    LEFT JOIN procesos_documento t ON t.id = r.tipo_proceso
    LEFT JOIN categoria_doc c ON c.id = r.categ_doc
    WHERE r.activo = 1
    ORDER BY r.id DESC LIMIT 20;";
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

    public static function buscarRenovacionesDocumentosModel($datos)
    {
        $tabla  = 'renovacion_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    r.*,
    t.nombre AS nom_tipo,
    c.nombre AS categ
    FROM renovacion_documentos r
    LEFT JOIN procesos_documento t ON t.id = r.tipo_proceso
    LEFT JOIN categoria_doc c ON c.id = r.categ_doc
    WHERE r.activo = 1 AND CONCAT(r.nom_documento, ' ', t.nombre, ' ', c.nombre, ' ', r.version_doc, ' ', r.fecha_vigencia, r.fecha_revision) LIKE '%" . $datos['buscar'] . "%' " . $datos['tipo'] . " " . $datos['fecha'] . ";";
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

    public static function buscarRenovacionesModel($buscar, $consulta, $condicion)
    {
        $tabla  = 'renovacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    r.* ,
    re.nombre AS archivo
    FROM renovacion r
    LEFT JOIN renovacion_evidencia re ON re.id_renovacion = r.id
    " . $consulta . "
    WHERE r.activo = 1 AND CONCAT(r.nombre, ' ', r.fecha_renovacion, ' ', r.fecha_proxima) LIKE '%" . $buscar . "%'
    " . $condicion . "
    ORDER BY r.id DESC;";
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

    public static function actualizarRenovacionDocumentoModel($datos)
    {
        $tabla  = 'renovacion_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . "
    SET
    tipo_proceso = '" . $datos['tipo_proceso'] . "',
    nom_documento = '" . $datos['nom_documento'] . "',
    version_doc = '" . $datos['version_doc'] . "',
    fecha_vigencia = '" . $datos['fecha_vigencia'] . "',
    fecha_revision = '" . $datos['fecha_revision'] . "',
    categ_doc = '" . $datos['categ_doc'] . "',
    gestion_cambio = '" . $datos['gestion_cambio'] . "',
    url_archivo = '" . $datos['url_archivo'] . "',
    evidencia = '" . $datos['evidencia'] . "',
    id_user = '" . $datos['id_user'] . "',
    id_log = '" . $datos['id_log'] . "',
    fecha_edit = NOW()
    WHERE id = '" . $datos['id_documento'] . "';
    ";
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

    public static function eliminarDocumentoModel($id, $log)
    {
        $tabla  = 'renovacion_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_inactiva = :idn, fecha_inactivo = NOW(), activo = 0 WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idn', $log);
            $preparado->bindParam(':id', $id);
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

    public static function comandoSQL()
    {
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SET SQL_BIG_SELECTS=1";
        try {
            $preparado = $cnx->preparar($cmdsql);
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
