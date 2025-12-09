<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloCategorias extends conexion
{


    public static function mostrarCategoriasModel($super_empresa)
    {
        $tabla = 'categoria';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_super_empresa = :ids;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':ids', $super_empresa);
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
