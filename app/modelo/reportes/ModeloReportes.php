<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloReportes extends conexion

{

   public static function mostrarReportesModel() {
    $tabla = 'inventario';
    $cnx = conexion::singleton_conexion();

    $cmdsql = "
            SELECT DISTINCT SQL_NO_CACHE
                iv.*,
                (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) AS nom_estado,
                (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS AREA,
                (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = rp.id_user) AS usuario,
                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id ORDER BY r.id DESC LIMIT 1) AS fecha_reporte,
                (SELECT r.id FROM reportes r WHERE r.id_inventario = iv.id ORDER BY r.id DESC LIMIT 1) AS id_reporte,
                CONCAT(u.nombre, ' ', u.apellido) AS nom_usuario,
                ar.nombre AS nom_area,
                rp.id AS reporte_id
            FROM inventario iv
            INNER JOIN reportes rp ON rp.id_inventario = iv.id
            LEFT JOIN usuarios u ON u.id_user = iv.id_user
            LEFT JOIN areas ar ON ar.id = rp.id_area
            WHERE iv.estado = 2 AND iv.activo = 1 AND rp.estado = 2
            AND NOT EXISTS (
                SELECT 1
                FROM reportes rpe
                WHERE rpe.id_reporte = rp.id AND rpe.estado = 3
            )
            ORDER BY fecha_reporte DESC;";



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

public static function mostrarArticuloReportadoModel($id)
    {
        $tabla  = 'reportes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        r.*,
        iv.descripcion,
        iv.marca,
        ar.nombre AS nom_area,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        r.fecha_reporte,
        r.cantidad,
        r.observacion
        FROM reportes r
        LEFT JOIN inventario iv ON iv.id = r.id_inventario
        LEFT JOIN usuarios u ON u.id_user = r.id_user
        LEFT JOIN areas ar ON ar.id = r.id_area
        WHERE r.id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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



public static function buscarReportesModel($datos)
{
    $tabla  = 'inventario';
    $cnx    = conexion::singleton_conexion();

    $cmdsql = "SELECT SQL_NO_CACHE
        iv.*,
        (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS nom_estado,
        (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS AREA,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(rp.id_user)) AS usuario,
        (SELECT r.fechareg FROM reportes r WHERE r.id_inventario IN(iv.id) ORDER BY r.id DESC LIMIT 1) AS fecha_reporte,
        (SELECT r.id FROM reportes r WHERE r.id_inventario IN(iv.id) ORDER BY r.id DESC LIMIT 1) AS id_reporte,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_usuario,
        ar.nombre AS nom_area,
        rp.id as reporte_id
    FROM inventario iv
    LEFT JOIN reportes rp ON rp.id_inventario = iv.id
    LEFT JOIN usuarios u ON u.id_user = iv.id_user
    LEFT JOIN areas ar ON ar.id = rp.id_area
    WHERE iv.estado IN(2,6) AND iv.activo = 1
    AND rp.id NOT IN(SELECT rpe.id_reporte FROM reportes rpe WHERE rpe.estado = 3)
    AND CONCAT(u.nombre, ' ', u.apellido, ' ', ar.nombre, ' ', rp.fechareg, ' ', iv.descripcion) LIKE '%" . $datos['buscar'] . "%'
    " . $datos['usuario'] . "
    " . $datos['area'] . "
    ORDER BY fecha_reporte DESC;";

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




    public static function mostrarReportesSolucionadosModel()

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

        iv.*,

        CONCAT(u.nombre, ' ', u.apellido) AS usuario,

        (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS area,

        r.id AS id_reporte,

        r.fecha_respuesta

        FROM " . $tabla . " r

        LEFT JOIN inventario iv ON iv.id = r.id_inventario

        LEFT JOIN usuarios u ON u.id_user = iv.id_user

        WHERE r.estado = 3 AND r.visto_bueno = 0;";

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



    public static function consultarSolucionReporteDanoModel($id)

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE id FROM " . $tabla . " WHERE id_inventario = :id AND estado = 3 AND tipo_reporte = 1

        AND id IN(SELECT f.id_reporte FROM firmas f WHERE f.id_inventario = :id ORDER BY f.id DESC) ORDER BY id DESC LIMIT 1";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

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



    public static function consultarSolucionReporteMantModel($id)

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE id FROM " . $tabla . " WHERE id_inventario = :id AND estado = 3 AND tipo_reporte = 2

        AND id IN(SELECT f.id_reporte FROM firmas f WHERE f.id_inventario = :id ORDER BY f.id DESC) ORDER BY id DESC LIMIT 1";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id);

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



    public static function solucionarReporteModel($datos)

    {



        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_inventario, observacion, estado, id_log, id_resp, fecha_respuesta, tipo_reporte, id_reporte, id_user, id_area)

        VALUES(:idv, :ob, :e, :il, :ir, :fr, :tr, :idr, :idu, :ida);

        UPDATE inventario SET observacion = :ob, estado = :e WHERE id = :idv;

        INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado) VALUES (:idv,:idu,:ida,:il,:e);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':il', $datos['id_log']);

            $preparado->bindParam(':ir', $datos['id_resp']);

            $preparado->bindParam(':fr', $datos['fecha_respuesta']);

            $preparado->bindParam(':tr', $datos['tipo_reporte']);

            $preparado->bindParam(':idr', $datos['id_reporte']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

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



    public static function guardarFirmasModel($datos)

    {

        $tabla  = 'firmas';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_inventario, id_reporte, firma_responsable, firma_user, id_user, id_super_empresa, firma_solucionado, id_responsable)

        VALUES (:idv, :ir, :fr, :fu, :iu, :ids, :fs, :idr);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ir', $datos['id_reporte']);

            $preparado->bindParam(':fr', $datos['firma_responsable']);

            $preparado->bindParam(':fu', $datos['firma_user']);

            $preparado->bindParam(':iu', $datos['id_user']);

            $preparado->bindParam(':fs', $datos['firma_solucionado']);

            $preparado->bindParam(':idr', $datos['id_responsable']);

            $preparado->bindParam(':ids', $datos['super_empresa']);

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



    public static function mostrarInformacionSolucionReporteModel($id)

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

        rp.*,

        (SELECT iv.descripcion FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS descripcion,

        (SELECT iv.marca FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS marca,

        (SELECT (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS area,

        (SELECT f.nombre FROM firmas f WHERE f.id_user = rp.id_user AND f.activo = 1 ORDER BY f.id DESC LIMIT 1) AS firma_responsable,

        (SELECT f.nombre FROM firmas f WHERE f.id_user = rp.id_resp AND f.activo = 1 ORDER BY f.id DESC LIMIT 1) AS firma_solucionado,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(rp.id_user)) AS usuario,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(rp.id_resp)) AS usuario_solucion,

        (SELECT e.nombre FROM estado e WHERE e.id = rp.estado) AS nombre_estado,

        (SELECT rr.fechareg FROM reportes rr WHERE rr.id = rp.id_reporte) AS fecha_reportado,
        
        (select rr.observacion from reportes rr where rr.id=rp.id_reporte) AS solucion,

        rp.fecha_respuesta as fecha_solucionado

        FROM " . $tabla . " rp WHERE rp.id = :id";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $id);

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



    public static function vistoBuenoReporteModel($id)

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET visto_bueno = 1 WHERE id = :id";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $id);

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



    public static function vistoBuenoGeneralModel()

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET visto_bueno = 1 WHERE estado = 3 AND visto_bueno NOT IN(1);

        UPDATE reportes_zonas SET visto_bueno = 1 WHERE estado = 3 AND visto_bueno NOT IN(1);";

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



    public static function informacionReporteArticuloModel($id)

    {

        $tabla  = 'reportes';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_inventario = :id  ORDER BY id DESC LIMIT 1;";

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

