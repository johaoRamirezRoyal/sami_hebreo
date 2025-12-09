<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloAdmisiones extends conexion
{


    public static function guardarAcudienteModel($datos)
    {
        $tabla = 'usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO usuarios (documento, nombre, apellido, correo, telefono, asignatura, user, pass, perfil, id_super_empresa, user_log, id_nivel) 
        VALUES (:d, :n, :a, :c, :t, :ag, :u, :p, :ip, :ids, :ul, :idn);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':a', $datos['apellido']);
            $preparado->bindParam(':c', $datos['correo']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':ag', $datos['asignatura']);
            $preparado->bindParam(':u', $datos['usuario']);
            $preparado->bindParam(':p', $datos['pass']);
            $preparado->bindParam(':ip', $datos['perfil']);
            $preparado->bindParam(':ids', $datos['super_empresa']);
            $preparado->bindParam(':ul', $datos['id_log']);
            $preparado->bindParam(':idn', $datos['id_nivel']);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => TRUE, 'id' => $id);
                return $resultado;
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }


    public static function mostrarAcudientesModel($super_empresa)
    {
        $tabla = 'usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*,
        (SELECT s.nombre_est FROM estudiante s WHERE s.id_acudiente = u.id_user) AS estudiante
        FROM " . $tabla . "  u WHERE perfil = 6 AND u.id_super_empresa = :ids;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ids', $super_empresa);
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


    public static function datosClinicosModel($id)
    {
        $tabla = 'historia_clinica';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_acudiente = :id ORDER BY id DESC LIMIT 1";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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


    public static function mostrarHermanosModel($id)
    {
        $tabla = 'hermanos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_log = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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


    public static function habilitarFormatoModel($id)
    {
        $tabla = 'formato';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id_acudiente = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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


    public static function guardarEstudianteModel($datos)
    {
        $tabla = 'estudiante';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla .  " (id_acudiente, nombre_est) VALUES (:id,:n)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_acudiente']);
            $preparado->bindParam(':n', $datos['nombre']);
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
}
