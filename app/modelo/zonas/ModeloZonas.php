<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloZonas extends conexion
{

    public static function guardarZonasModel($datos)
    {
        $tabla  = 'zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, id_log, id_area) VALUES (:n, :idl, :ida)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':ida', $datos['area']);
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

    public static function buscarZonaModel($datos)
    {
        $tabla  = 'zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT z.*,
        (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) AS nom_area
        FROM zonas z
        LEFT JOIN areas_zona az ON az.id = z.id_area
        WHERE CONCAT(z.nombre, ' ', az.nombre) LIKE '%" . $datos['buscar'] . "%'
        " . $datos['area'] . "
        AND z.activo = 1
        ORDER BY z.nombre ASC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function mostrarZonaModel()
    {
        $tabla  = 'zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        z.*,
        (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) AS nom_area
        FROM " . $tabla . " z WHERE z.activo = 1 ORDER BY z.nombre ASC LIMIT 30;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function agregarAreaModel($datos)
    {
        $tabla  = 'areas_zona';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, id_log) VALUES (:n, :idl)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':n', $datos['nom_area']);
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

    public static function mostrarAreasZonasModel()
    {
        $tabla  = 'areas_zona';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function editarZonaModel($datos)
    {
        $tabla  = 'zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET nombre = :n, id_area = :ida, id_log = :idl WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nom_zona']);
            $preparado->bindParam(':id', $datos['id_zona']);
            $preparado->bindParam(':ida', $datos['id_area']);
            $preparado->bindParam(':idl', $datos['id_log']);
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

    public static function reportarZonaModel($datos)
    {
        $tabla  = 'reportes_zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_zona, observacion, estado, id_log, fecha_mantenimiento, observacion_repro, fechareg) VALUES (:idz, :ob, :est, :idl, :fe, :obr, :fr);
        UPDATE zonas SET estado = :est WHERE id = :idz;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idz', $datos['id_zona']);
            $preparado->bindParam(':ob', $datos['observacion']);
            $preparado->bindParam(':est', $datos['estado']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':fe', $datos['fecha_mant']);
            $preparado->bindParam(':obr', $datos['observacion_repro']);
            $preparado->bindParam(':fr', $datos['fecha_reporte']);
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

    public static function mostraReportesZonaModel()
    {
        $tabla  = 'reportes_zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        rz.*,
        (SELECT z.nombre FROM zonas z WHERE z.id = rz.id_zona) AS zona_nom,
        (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = rz.id_zona) AS area_nom,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = rz.id_log) AS nom_usuario
        FROM " . $tabla . " rz WHERE rz.estado IN(2,6,10) AND rz.id NOT IN(SELECT r.id_reporte_resp FROM reportes_zonas r WHERE r.estado IN(3))
        AND rz.id NOT IN(SELECT r.id_reporte_resp FROM reportes_zonas r WHERE r.estado IN(10))
        ORDER BY rz.id DESC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function datosReporteZonaModel($id)
    {
        $tabla  = 'reportes_zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        rz.*,
        (SELECT z.nombre FROM zonas z WHERE z.id = rz.id_zona) AS zona_nom,
        (SELECT e.nombre FROM estado e WHERE e.id = rz.estado) AS estado_nom,
        (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = rz.id_zona) AS area_nom,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = rz.id_log) AS nom_usuario
        FROM " . $tabla . " rz WHERE rz.id = " . $id;
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function solucionarReporteZonaModel($datos)
    {
        $tabla  = 'reportes_zonas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO reportes_zonas (
            id_zona,
            observacion,
            estado,
            id_log,
            id_user_resp,
            id_reporte_resp,
            fecha_respuesta,
            fecha_mantenimiento,
            observacion_repro
            )
        VALUES
        (
            '" . $datos['id_zona'] . "',
            '" . $datos['observacion'] . "',
            '" . $datos['estado'] . "',
            '" . $datos['id_user'] . "',
            '" . $datos['id_log'] . "',
            '" . $datos['id_reporte'] . "',
            '" . $datos['fecha_respuesta'] . "',
            '" . $datos['fecha_mant'] . "',
            '" . $datos['observacion_repro'] . "'
            );

            UPDATE zonas SET estado = " . $datos['estado'] . " WHERE id = " . $datos['id_zona'] . ";

            ";
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

        public static function mostrarDatosIdZonaModel($id)
        {
            $tabla  = 'zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            z.*,
            (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) AS area_nom
            FROM zonas z WHERE z.id = :id;";
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

        public static function reportesZonaModel($id)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta
            FROM reportes_zonas r WHERE
            r.estado NOT IN(3)
            AND r.id_zona = :id
            ORDER BY r.fechareg DESC;";
            try {
                $preparado = $cnx->preparar($cmdsql);
                $preparado->bindParam(':id', $id);
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

        public static function mostrarFechaRespuestaZonaModel($id)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT r.* FROM reportes_zonas r WHERE r.id = :id;";
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

        public static function mostrarHistorialZonaModel()
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta,
            (SELECT fr.id FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_respuesta
            FROM reportes_zonas r 
            LEFT JOIN zonas z ON z.id = r.id_zona
            WHERE r.estado NOT IN(3) AND r.estado IN(2) AND z.activo = 1 ORDER BY r.fechareg DESC LIMIT 20;";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function mostrarInformacionSolucionReporteModel($id)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta,
            (SELECT fr.id FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_respuesta,
            (SELECT fr.id_user_resp FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_usuario_respuesta,
            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(SELECT fr.id_user_resp FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id)) AS nom_user_respuesta,
            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = r.id_log) AS usuario_reporta,
            (SELECT f.nombre FROM firmas f WHERE f.id_user = r.id_log AND f.activo = 1 ORDER BY f.id DESC LIMIT 1) AS firma_responsable,
            (SELECT f.nombre FROM firmas f WHERE f.id_user IN(SELECT fr.id_user_resp FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AND f.activo = 1 ORDER BY f.id DESC LIMIT 1) AS firma_solucion,
            (SELECT e.nombre FROM estado e WHERE e.id IN(SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id)) AS nom_estado,
            (SELECT fr.visto_bueno FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS visto_bueno_respuesta
            FROM reportes_zonas r
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

        public static function mostrarHistorialZonaMantModel()
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta,
            (SELECT fr.id FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_respuesta
            FROM reportes_zonas r 
            LEFT JOIN zonas z ON z.id = r.id_zona
            WHERE r.estado NOT IN(3) AND r.estado IN(6,10) AND z.activo = 1 ORDER BY r.fecha_mantenimiento DESC LIMIT 30;";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function buscarHistorialZonaModel($fecha_inicio, $fecha_fin, $zona)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta,
            (SELECT fr.id FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_respuesta
            FROM reportes_zonas r 
            LEFT JOIN zonas z ON z.id = r.id_zona
            WHERE r.estado NOT IN(3) AND z.activo = 1
            AND r.fechareg >= '" . $fecha_inicio . " 00:00:00'
            AND r.fechareg <= '" . $fecha_fin . " 23:59:59'
            AND r.id_zona IN(SELECT z.id FROM zonas z WHERE z.nombre LIKE '%" . $zona . "%')
            AND r.estado = 2
            ORDER BY r.fechareg DESC;";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function buscarHistorialZonaMantModel($fecha_inicio, $fecha_fin, $zona)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT
            r.*,
            (SELECT z.nombre FROM zonas z WHERE z.id = r.id_zona) AS nom_zona,
            (SELECT (SELECT a.nombre FROM areas_zona a WHERE a.id = z.id_area) FROM zonas z WHERE z.id = r.id_zona) AS nom_area,
            (SELECT fr.fecha_respuesta FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS fecha_respuesta_con,
            (SELECT fr.observacion FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS observacion_resp,
            (SELECT fr.estado FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS estado_respuesta,
            (SELECT fr.id FROM reportes_zonas fr WHERE fr.id_reporte_resp = r.id) AS id_respuesta
            FROM reportes_zonas r 
            LEFT JOIN zonas z ON z.id = r.id_zona
            WHERE r.estado NOT IN(3) AND z.activo = 1
            AND r.fecha_mantenimiento >= '" . $fecha_inicio . " 00:00:00'
            AND r.fecha_mantenimiento <= '" . $fecha_fin . " 23:59:59'
            AND r.id_zona IN(SELECT z.id FROM zonas z WHERE z.nombre LIKE '%" . $zona . "%')
            AND r.estado  IN(6,10)
            ORDER BY r.fecha_mantenimiento DESC;";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function cantidadesSolucionadasZonasModel($datos)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT COUNT(r.id) AS solucion FROM reportes_zonas r WHERE r.`estado`IN(3)
            AND r.id_reporte_resp IN(SELECT rz.id FROM reportes_zonas rz WHERE rz.`estado` IN(2))
            AND r.fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00'
            AND r.fechareg <= '" . $datos['fecha_fin'] . " 24:59:59'
            AND `id_zona` IN
            (SELECT
            iv.id
            FROM
            zonas iv
            WHERE iv.activo IN (1));";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function cantidadesPendienteZonasModel($datos)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT COUNT(rz.id) AS cantidad FROM reportes_zonas rz WHERE rz.estado IN(2)
            AND rz.id NOT IN(SELECT r.id_reporte_resp FROM reportes_zonas r WHERE r.estado IN(3))
            AND rz.fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00'
            AND rz.fechareg <= '" . $datos['fecha_fin'] . " 24:59:59';";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function cantidadesMantenimientosSolucionadasZonasModel($datos)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT COUNT(r.id) AS solucion FROM reportes_zonas r WHERE r.`estado`IN(3)
            AND r.id_reporte_resp IN(SELECT rz.id FROM reportes_zonas rz WHERE rz.`estado` IN(6,10))
            AND r.fecha_respuesta >= '" . $datos['fecha_inicio'] . " 00:00:00'
            AND r.fecha_respuesta <= '" . $datos['fecha_fin'] . " 24:59:59'
            AND `id_zona` IN
            (SELECT
            iv.id
            FROM
            zonas iv
            WHERE iv.activo IN (1));";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function cantidadesMantenimientosPendienteZonasModel($datos)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "SELECT COUNT(rz.id) AS cantidad FROM reportes_zonas rz WHERE rz.estado IN(6,10)
            AND rz.id NOT IN(SELECT r.id_reporte_resp FROM reportes_zonas r WHERE r.estado IN(3,10))
            AND rz.fecha_mantenimiento >= '" . $datos['fecha_inicio'] . " 00:00:00'
            AND rz.fecha_mantenimiento <= '" . $datos['fecha_fin'] . " 24:59:59';";
            try {
                $preparado = $cnx->preparar($cmdsql);
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

        public static function editarReporteModel($datos)
        {
            $tabla  = 'reportes_zonas';
            $cnx    = conexion::singleton_conexion();
            $cmdsql = "UPDATE " . $tabla . " SET id_edit = :idl, fecha_edit = NOW(), observacion = :obr WHERE id = :id;";
            try {
                $preparado = $cnx->preparar($cmdsql);
                $preparado->bindParam(':idl', $datos['id_log']);
                $preparado->bindParam(':obr', $datos['observacion']);
                $preparado->bindParam(':id', $datos['id_reporte']);
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
