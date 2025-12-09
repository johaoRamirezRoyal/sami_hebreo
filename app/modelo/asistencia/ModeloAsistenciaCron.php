<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloAsistenciaCron extends conexion
{
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

    public static function mostrarAsistenciaListadoModel()
    {
        $tabla  = 'asistencia_gestion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        (SELECT nombre FROM perfiles WHERE id_perfil = u.perfil) AS perfil,
        a.hora_asistencia, a.fecha_asistencia
        FROM asistencia_gestion a
        INNER JOIN usuarios u ON u.id_user = a.id_user ORDER BY a.id DESC LIMIT 25;";
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

    public static function buscarUsuarioAsistenciaGestionModel($datos)
    {
        $tabla  = 'asistencia_gestion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = " SELECT
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        (SELECT nombre FROM perfiles WHERE id_perfil = u.perfil) AS perfil,
        a.hora_asistencia, a.fecha_asistencia
        FROM asistencia_gestion a
        INNER JOIN usuarios u ON a.id_user = u.id_user
        WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento) like '%" . $datos['buscar'] . "%'
        " . $datos['perfil'] . "
        " . $datos['fecha'] . "
        ORDER BY a.id DESC;";
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

    public static function validarTokenModel($token)
    {
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM dias_qr WHERE token = '" . $token . "';";
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

    public static function validarDocumentoModel($documento)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT * FROM usuarios WHERE documento = '" . $documento . "' AND perfil NOT IN(1,17)";
        try {
            $preparado = $cnx->preparar($cmd);
            if ($preparado->execute()) {
                if ($preparado->rowCount() >= 1) {
                    return $preparado->fetch();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function TomarAsistenciaModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "INSERT INTO cronograma.asistencia_gestion (

            id_user,
            fecha_asistencia,
            hora_asistencia

            )
        VALUES
        (

            :idu,
            :fA,
            :hA

            );
        ";
        try {
            $preparado = $cnx->preparar($cmd);
            $preparado->bindParam(':idu', $datos['id_user']);
            $preparado->bindParam(':fA', $datos['fecha_hoy']);
            $preparado->bindParam(':hA', $datos['hora_hoy']);
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

    public static function validarAsistenciaHoyModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT * FROM asistencia_gestion WHERE id_user = :idu AND fecha_asistencia = '" . $datos['fecha_hoy'] . "' ORDER BY id DESC LIMIT 1";
        try {
            $preparado = $cnx->preparar($cmd);
            $preparado->bindParam(':idu', $datos['id_user']);
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
}
