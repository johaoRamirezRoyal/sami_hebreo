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

    public static function insertarNuevaExperienciaLaboralModel($datos)
    {
        $cnx = conexion::singleton_conexion();
        $sql = 'insert into experiencia_laboral(nombre_empresa, cargo, fecha_ingreso, fecha_retiro, fecha_certificado, id_user)
                values(:nombre_empresa, :cargo, :fecha_ingreso, :fecha_retiro, :fecha_certificado, :id_user);';
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':nombre_empresa', $datos['nombre_empresa']);
            $preparado->bindParam(':cargo', $datos['cargo']);
            $preparado->bindParam(':fecha_ingreso', $datos['fecha_ingreso']);
            $preparado->bindParam(':fecha_retiro', $datos['fecha_retiro']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':fecha_certificado', $datos['fecha_certificado']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso('experiencia_laboral');
                $respuesta = array('guardar' => true, 'id' => $id);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarExperienciaLaboralModel($id)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function agregarDocumentoExperienciaModel($nombre_doc, $id_experiencia)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla
                    SET certificado_trabajo = :nombre_doc
                    WHERE id = :id_experiencia;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':nombre_doc', $nombre_doc);
            $preparado->bindParam(':id_experiencia', $id_experiencia);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarTodasLasExperienciasLaboralesUserModel($id_user)
    {
        $tabla = 'experiencia_laboral';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla where id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function agregarNuevoDocumentoVariadoModel($datos)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla (tipo_doc, id_user, nombre_doc) VALUES (:tipo_doc, :id_user, :nombre_doc);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':tipo_doc', $datos['tipo_doc']);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':nombre_doc', $datos['nombre_doc']);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $respuesta = array('guardar' => true, 'id' => $id);
                return $respuesta;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarDocumentoVariosModel($id)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarDocumentosVariosUsuarioModel($id_user)
    {
        $tabla = 'documentos_varios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function agregarProduccionIntelectualModel($datos)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla 
        (id_user, nombre, tipo_produccion, denominacion, 
        objetivo, descripcion_actividades, duracion, 
        lugar, evidencia_pdf, observacion) VALUES (:id_user, :nombre_produccion, :tipo_produccion, :denominacion_produccion,
        :objetivo_produccion, :descipcion_produccion, :duracion, :lugar, :evidencia_produccion, :observaciones);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $datos['id_user']);
            $preparado->bindParam(':nombre_produccion', $datos['nombre_produccion']);
            $preparado->bindParam(':tipo_produccion', $datos['tipo_produccion']);
            $preparado->bindParam(':denominacion_produccion', $datos['denominacion_produccion']);
            $preparado->bindParam(':objetivo_produccion', $datos['objetivo_produccion']);
            $preparado->bindParam(':descipcion_produccion', $datos['descipcion_produccion']);
            $preparado->bindParam(':duracion', $datos['duracion']);
            $preparado->bindParam(':lugar', $datos['lugar']);
            $preparado->bindParam(':evidencia_produccion', $datos['evidencia_produccion']);
            $preparado->bindParam(':observaciones', $datos['observaciones']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $rs = array('guardar' => true, 'id' => $id);
                return $rs;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function eliminarProduccionIntelectualModel($id)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla 
                    WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarProduccionIntelectualUsuarioModel($id_user)
    {
        $tabla = 'produccion_intelectual';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM $tabla WHERE id_user = :id_user;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_user', $id_user);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerCorreosPerfilNivel($id_nivel, $id_perfil)
    {
        $tabla = 'usuarios';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT u.correo, 
                    u.id_user, u.documento, u.perfil, u.id_nivel,
                    CONCAT(u.nombre, ' ', u.apellido) as nom_psicologa
                     FROM $tabla u WHERE u.id_nivel = $id_nivel AND u.perfil = $id_perfil AND u.estado ='activo';";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarDatosCoordinadorModel($id_nivel)
    {
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT u.correo,
                    u.id_user, u.nombre, u.apellido, u.documento, u.perfil, u.id_nivel,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_completo
                    FROM usuarios u
                    WHERE u.id_nivel = :id_nivel AND u.perfil = 22 AND u.estado = 'activo' LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_nivel', $id_nivel, PDO::PARAM_INT);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetch();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarDatosComprasModel($id_perfil)
    {
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT u.correo,
                    u.id_user, u.nombre, u.apellido, u.documento, u.perfil, u.id_nivel,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_completo
                    FROM usuarios u
                    WHERE u.perfil = :perfil AND u.estado = 'activo' LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':perfil', $id_perfil, PDO::PARAM_INT);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetch();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }
}
