<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPermisos extends conexion
{

    public static function permisosUsuarioModel($id_opcion, $perfil)
    {
        $tabla  = 'cron_permisos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM cron_permisos WHERE id_opcion = :io AND id_perfil = :p AND activo = 1 ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':io', $id_opcion);
            $preparado->bindParam(':p', $perfil);
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

    public static function mostrarOpcionesPermisosModel()
    {
        $tabla  = 'cron_opciones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE cr.*,
        IF(cm.id = 1 , cr.nombre, CONCAT(cm.nombre,  ' - ', cr.nombre)) AS opcion
        FROM " . $tabla . " cr
        LEFT JOIN cron_modulos cm ON cm.id = cr.id_modulo
        WHERE cr.activo = 1 ORDER BY opcion ASC;";
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

    public static function opcionsIdActivosPerfilModel($perfil, $id_opcion)
    {
        $tabla  = 'cron_permisos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_perfil = :id AND id_opcion = :io AND activo = 1 LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $perfil);
            $preparado->bindParam(':io', $id_opcion);
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

    public static function activarPermisoModel($datos)
    {
        $tabla  = 'cron_permisos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_opcion, id_perfil, user_log) VALUES (:o, :p, :ul);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':o', $datos['opcion']);
            $preparado->bindValue(':p', $datos['perfil']);
            $preparado->bindParam(':ul', $datos['user']);
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

    public static function inactivarPermisoModel($datos)
    {
        $tabla  = 'cron_permisos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM " . $tabla . " WHERE id_perfil = :p AND id_opcion = :o;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':o', $datos['opcion']);
            $preparado->bindValue(':p', $datos['perfil']);
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

    public static function mostrarAnioActivoModel()
    {
        $tabla  = 'anio_escolar';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1 ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function mostrarAniosLectivoModel()
    {
        $tabla  = 'anio_escolar';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla;
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

    public static function mostrarAnioIdActivoModel($id)
    {
        $tabla  = 'anio_escolar';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id = :id";
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

    public static function finalizarAnioEscolarModel($id, $log)
    {
        $tabla  = 'anio_escolar';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0, id_log = :idl WHERE id = :id;
        UPDATE inventario SET confirmado = 0;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->bindParam(':idl', $log);
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

    public static function iniciarNuevoAnioModel($datos)
    {
        $tabla  = 'anio_escolar';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (anio_inicio, anio_fin, id_log) VALUES (:ani, :anf, :idl)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ani', $datos['anio_inicio']);
            $preparado->bindParam(':anf', $datos['anio_fin']);
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
}
