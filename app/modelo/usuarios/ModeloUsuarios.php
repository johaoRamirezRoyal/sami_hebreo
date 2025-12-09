<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloUsuarios extends conexion
{

    public static function guardarUsuarioModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO usuarios (documento, nombre, apellido, correo, telefono, asignatura, user, pass, perfil, id_super_empresa, user_log, id_nivel, id_curso)
        VALUES (:d, :n, :a, :c, :t, :ag, :u, :p, :ip, :ids, :ul, :idn, :idc);";
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
            $preparado->bindParam(':idn', $datos['nivel']);
            $preparado->bindParam(':idc', $datos['curso']);
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

    public static function editarUsuarioModel($datos)
    {
       
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE usuarios SET nombre = :n, apellido = :ap, telefono = :t, asignatura = :ag, pass = :pw, perfil = :ip, documento = :d, correo = :c, id_nivel = :idn, id_curso = :idc, fecha_editado = NOW()
        WHERE id_user = :id;";

       if($datos['pass'] != ''){
            $cmdsql = "UPDATE usuarios SET nombre = :n, apellido = :ap, telefono = :t, asignatura = :ag, pass = :pw, perfil = :ip, documento = :d, correo = :c, id_nivel = :idn, id_curso = :idc, fecha_editado = NOW()
            WHERE id_user = :id;";
        }else{
            $cmdsql = "UPDATE usuarios SET nombre = :n, apellido = :ap, telefono = :t, asignatura = :ag, perfil = :ip, documento = :d, correo = :c, id_nivel = :idn, id_curso = :idc, fecha_editado = NOW()
            WHERE id_user = :id;";
        }

        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':ap', $datos['apellido']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':ag', $datos['asignatura']);
                if($datos['pass'] != ''){
                $preparado->bindParam(':pw', $datos['pass']);
            }
           // $preparado->bindParam(':pw', $datos['pass']);
            $preparado->bindParam(':c', $datos['correo']);
            $preparado->bindParam(':ip', $datos['perfil']);
            $preparado->bindParam(':id', $datos['id_user']);
            $preparado->bindParam(':idc', $datos['curso']);
            $preparado->bindParam(':idn', $datos['nivel']);
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

    public static function subirFotoCarnetModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla .
            " SET
        documento = '" . $datos['documento'] . "',
        nombre = '" . $datos['nombre'] . "',
        apellido = '" . $datos['apellido'] . "',
        id_curso = '" . $datos['curso'] . "',
        foto_carnet = '" . $datos['foto'] . "',
        user_log = '" . $datos['id_log'] . "',
        fecha_editado = NOW()
        WHERE id_user = '" . $datos['id_user'] . "';
        ";
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

    public static function editarUsuarioProveedorModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE usuarios SET nombre = :n, apellido = :ap, telefono = :t, perfil = :ip, documento = :d, correo = :c
        WHERE id_user = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':ap', $datos['apellido']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':c', $datos['correo']);
            $preparado->bindParam(':ip', $datos['perfil']);
            $preparado->bindValue(':id', $datos['id_user']);
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

    public static function guardarFirmaUsuarioModel($datos)
    {
        $tabla  = 'firmas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_user, nombre, user_log) VALUES (:id, :n, :idl)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_user']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':idl', $datos['user_log']);
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

    public static function mostrarUsuariosModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil NOT IN(1,17) ORDER BY u.nombre ASC LIMIT 30;";
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

    public static function mostrarUsuariosEvaluarModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            u.*,
            (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
            n.nombre AS nom_nivel,
            c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.id_user = 88
          AND u.perfil NOT IN(1,17)
        ORDER BY u.nombre ASC
        LIMIT 30;";
        
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
    

    public static function mostrarEstudiantesPROMModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil IN(16) AND u.id_curso IN(29,30,3,4) ORDER BY u.nombre ASC;";
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

    public static function buscarUsuariosNivelModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil NOT IN(1,17) AND
        CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', u.correo) LIKE '%" . $datos['buscar'] . "%'
        " . $datos['nivel'] . "
        " . $datos['curso'] . "
        ORDER BY u.nombre ASC;";
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

    public static function mostrarUsuariosDatosModel($id)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil NOT IN(1) AND u.id_user = :id;";
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

    public static function mostrarTodosUsuariosModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        CONCAT(UPPER(u.nombre), ' ', UPPER(u.apellido)) AS nom_user,
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM usuarios u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil NOT IN(1,6,17) AND u.estado = 'activo' ORDER BY u.nombre ASC;";
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

    public static function mostrarTodosUsuariosInventarioModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        CONCAT(UPPER(u.nombre), ' ', UPPER(u.apellido)) AS nom_user,
        u.*,
        (SELECT p.nombre FROM perfiles p WHERE p.id_perfil = u.perfil) AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM usuarios u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        WHERE u.perfil NOT IN(1,6,17,16) AND u.estado = 'activo' ORDER BY u.nombre ASC;";
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

    public static function buscarUsuarioModel($buscar)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.*,
        p.nombre AS nom_perfil,
        n.nombre AS nom_nivel,
        c.nombre AS nom_curso
        FROM " . $tabla . " u
        LEFT JOIN nivel n ON n.id = u.id_nivel
        LEFT JOIN curso c ON c.id = u.id_curso
        LEFT JOIN perfiles p ON p.id_perfil = u.perfil
        WHERE u.perfil NOT IN(1,17) AND CONCAT(u.nombre, ' ', u.apellido, ' ', u.correo, ' ' , u.documento, ' ', u.user, ' ', c.nombre, ' ', n.nombre) LIKE '%" . $buscar . "%' ORDER BY u.nombre;";
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

    public static function validarFirmaModel($id)
    {
        $tabla  = 'firmas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . "
         WHERE id_user = :id AND activo = 1 ORDER BY id DESC LIMIT 1;";
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

    public static function verificarUsuarioModel($usuario)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE id_user FROM " . $tabla . " WHERE user LIKE '" . $usuario . "%' ORDER BY id_user DESC LIMIT 1";
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

    public static function buscarDocumentoModel($documento)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE id_user FROM " . $tabla . " WHERE documento = :doc ORDER BY id_user DESC LIMIT 1";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':doc', $documento);
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

    public static function inactivarUsuarioModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET fecha_inactivo = :fi, estado = 'inactivo' WHERE id_user = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $datos['id_user']);
            $preparado->bindValue(':fi', $datos['fecha']);
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

    public static function activarUsuarioModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET fecha_activo = :fa, estado = 'activo' WHERE id_user = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $datos['id_user']);
            $preparado->bindValue(':fa', $datos['fecha']);
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

    public static function mostrarNivelesUsuarioModel()
    {
        $tabla  = 'nivel';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT n.* FROM nivel n WHERE activo = 1;";
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

    public static function mostrarCursosUsuarioModel()
    {
        $tabla  = 'curso';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT n.* FROM curso n WHERE activo = 1;";
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

    public static function disminuirCupoModel($datos)
    {
        $tabla  = 'prom_codigos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET cupo_disponible = '" . $datos['cupo_disponible'] . "', cupo_ocupado = '" . $datos['cupo_ocupado'] . "' WHERE id_estudiante = '" . $datos['id_estudiante'] . "';";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function datosCupoPromModel($id)
    {
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROm prom_codigos WHERE id_estudiante = :id AND activo = 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function cuposEstudiantesModel($id)
    {
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROm prom_codigos WHERE id_estudiante = :id AND activo = 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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
