<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloAreas extends conexion
{

    public static function guardarAreaModel($datos)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, user_log, inventario) VALUES (:n,:ul, :iv)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':ul', $datos['id_log']);
            $preparado->bindParam(':iv', $datos['inventario']);
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

    public static function editarAreaModel($datos)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET nombre = :n, inventario = :iv WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindValue(':id', $datos['id_area']);
            $preparado->bindValue(':iv', $datos['inventario']);
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

    public static function mostrarAreasModel($super_empresa)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_super_empresa = :id AND activo = 1 ORDER BY nombre ASC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $super_empresa);
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

    public static function mostrarAreasNoInventarioModel()
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1 AND inventario = 0 ORDER BY nombre ASC;";
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

     public static function mostrarAreasInventarioModel()
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1 AND inventario = 1 ORDER BY nombre ASC;";
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

    public static function mostrarPaginarAreasModel($inicio, $final)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " LIMIT :inicio, :final";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $preparado->bindValue(':final', $final, PDO::PARAM_INT);
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

    public static function usuarioResponsableModel($id_area)
    {
        $tabla  = 'inventario';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = a.id_user) AS usuario
        FROM " . $tabla . " a WHERE a.id_area = :id GROUP BY a.id_user, a.id_area;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id_area);
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

    public static function asignarAreaModel($datos)
    {
        $tabla  = 'inventario';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_user = :idu WHERE id_area = :ida";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idu', $datos['id_user']);
            $preparado->bindValue(':ida', $datos['id_area']);
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

    public static function activarAreaModel($datos)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 1 WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $datos['id_area']);
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

    public static function inactivarAreaModel($datos)
    {
        $tabla  = 'areas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $datos['id_area']);
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
