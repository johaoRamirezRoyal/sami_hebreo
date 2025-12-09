<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPerfil extends conexion
{

    public static function mostrarDatosPerfilModel($id)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*,
        (SELECT n.nombre FROM nivel n WHERE n.id = u.id_nivel) as nom_nivel,
        (SELECT nombre from perfiles where id_perfil = u.perfil) as nom_perfil,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) as nom_curso,
        (select nombre_foto from foto_perfil f where f.id_user = u.id_user and f.activo = 1) as imagen
        FROM " . $tabla . " u where u.id_user = :i";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':i', $id, PDO::PARAM_INT);
            if ($preparado->execute()) {
                if ($preparado->rowCount() == 1) {
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

    public static function mostrarInformacionPerfilModel($id)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*,
        (SELECT n.nombre FROM nivel n WHERE n.id = u.id_nivel) as nom_nivel,
        (SELECT nombre from perfiles where id_perfil = u.perfil) as nom_perfil,
        (select nombre_foto from foto_perfil f where f.id_user = u.id_user and f.activo = 1) as imagen
        FROM " . $tabla . " u where u.documento = '" . $id . "' AND u.estado = 'activo' AND u.perfil NOT IN(1,17,6);";
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

    public static function mostrarPerfilesModel()
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        p.*,
        (SELECT COUNT(m.id) FROM cron_permisos m WHERE m.id_perfil = p.`id_perfil` AND m.activo = 1) AS cant_modulos
        FROM perfiles p WHERE estado = 'activo' and id_perfil not in(1,17,6) ORDER BY id_perfil ASC;";
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


    public static function mostrarPerfilesTodosModel()
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        p.*,
        (SELECT COUNT(m.id) FROM cron_permisos m WHERE m.id_perfil = p.id_perfil AND m.activo = 1) AS cant_modulos
        FROM perfiles p WHERE estado = 'activo' ORDER BY id_perfil ASC;";
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

    public static function mostrarDatosSuperEmpresaModel($super_empresa, $tipo_img)
    {
        $tabla  = 'super_empresa';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE s.*,
        (SELECT i.nombre FROM img_super_empresa i WHERE i.id_super_empresa = s.id AND i.tipo_img IN('$tipo_img')) AS imagen
        FROM " . $tabla . " s WHERE s.id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $super_empresa);
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

    public static function guardarPerfilModelo($datos)
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre,user_log,id_super_empresa,fechareg) VALUES (:n,:ul,:is,:fr)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':ul', $datos['user_log']);
            $preparado->bindParam(':is', $datos['super_empresa']);
            $preparado->bindParam(':fr', $datos['fechareg']);
            //$preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function editarPerfilesModel($datos)
    {
        $tabla = 'perfiles';
        $cnx   = conexion::singleton_conexion();
        $sql   = "UPDATE " . $tabla . " SET nombre = :n WHERE id_perfil = :id";
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindValue(':id', $datos['id_perfil']);
            if ($preparado->execute()) {
                $id        = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function editarPerfilModel($datos)
    {
        $tabla = 'usuarios';
        $cnx   = conexion::singleton_conexion();
        $sql   = "UPDATE " . $tabla . " SET nombre= :n,apellido= :a,telefono= :t,documento= :d,pass= :p,perfil= :r, id_nivel = :nv WHERE id_user = :id";
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':a', $datos['apellido']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':p', $datos['pass']);
            $preparado->bindParam(':r', $datos['perfil']);
            $preparado->bindParam(':nv', $datos['nivel']);
            $preparado->bindValue(':id', $datos['id_user']);
            if ($preparado->execute()) {
                $id        = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
                return $resultado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarPerfilModelo($id)
    {
        $tabla  = 'perfiles';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM " . $tabla . " WHERE id_perfil = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', (int) trim($id), PDO::PARAM_INT);
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

    public static function guardarFotoModel($datos)
    {
        $tabla  = 'foto_perfil';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id_user = :id;
        INSERT INTO " . $tabla . " (nombre_foto, id_user) VALUES (:n,:id)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':id', $datos['id_user']);
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

    public static function mostrarNivelesModel($super_empresa)
    {
        $tabla  = 'nivel';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_super_empresa = :ids";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':ids', $super_empresa);
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

    public static function mostrarDatosCoordinadorModel($nivel)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_nivel = :n AND perfil = 22 AND estado = 'activo' ORDER BY id_user DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':n', $nivel);
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

    public static function mostrarDatosComprasModel($perfil)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE perfil = :p AND estado = 'activo' ORDER BY id_user DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':p', $perfil);
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

}
