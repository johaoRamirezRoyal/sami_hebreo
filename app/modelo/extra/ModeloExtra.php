<?php
require_once MODELO_PATH . 'conexion_extra.php';

class ModeloExtra extends conexion_extra
{

    public static function mostrarDatosExtraModel()
    {
        $tabla  = 'registro';
        $cnx    = conexion_extra::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        r.*,
        (SELECT e.nombre FROM categoria e WHERE e.id = r.extra) AS categoria
        FROM " . $tabla . " r;";
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
}
