<?php
require_once MODELO_PATH . 'conexion_bio.php';

class ModeloAsistencia extends conexion_bio
{

    public static function mostrarAsistenciaModel()
    {
        $tabla  = 'llegada_tarde';
        $cnx    = conexion_bio::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        t.*,
        u.*,
        (SELECT f.id FROM form_auto f WHERE DATE_FORMAT(f.fechareg,'%Y-%m-%d') = t.fecha AND f.id_user = u.id_user ORDER BY f.id DESC LIMIT 1) AS encuesta,
        (SELECT p.nombre FROM perfiles p WHERE p.id = u.perfil) AS perfil_nom
        FROM llegada_tarde t
        LEFT JOIN usuarios u ON u.id_user = t.id_user ORDER BY t.id DESC LIMIT 50;";
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

    public static function buscarUsuarioAsistenciaModel($usuario)
    {
        $tabla  = 'llegada_tarde';
        $cnx    = conexion_bio::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        t.*,
        u.*,
        (SELECT f.id FROM form_auto f WHERE DATE_FORMAT(f.fechareg,'%Y-%m-%d') = t.fecha AND f.id_user = u.id_user ORDER BY f.id DESC LIMIT 1) AS encuesta,
        (SELECT p.nombre FROM perfiles p WHERE p.id = u.perfil) AS perfil_nom
        FROM llegada_tarde t
        LEFT JOIN usuarios u ON u.id_user = t.id_user WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', u.user, ' ', t.fecha) LIKE '%" . $usuario . "%';";
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
