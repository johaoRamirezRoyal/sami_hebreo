<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloContabilidad extends conexion
{
    public static function guardarNominaModel($datos)
    {
        $tabla = 'documentos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO documentos (nombre, id_log, id_user, anio_mes) VALUES (:n,:id,:idu,:am);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_log']);
            $preparado->bindParam(':idu', $datos['id_user']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':am', $datos['anio_mes']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }


    public static function mostrarNominaMesActualModel($id, $anio)
    {
        $tabla = 'documentos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE anio_mes = :am AND id_user = :id ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
            $preparado->bindValue(':am', $anio);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetch();
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }


    public static function mostrarNominasModel($id)
    {
        $tabla = 'documentos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE df.*,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = df.id_log) AS usuario
        FROM documentos df WHERE df.id_user = :id
        AND df.id IN(SELECT MAX(d.id) FROM documentos d 
        WHERE d.id_user = df.id_user GROUP BY d.anio_mes ORDER BY d.id) GROUP BY df.anio_mes;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }
}
