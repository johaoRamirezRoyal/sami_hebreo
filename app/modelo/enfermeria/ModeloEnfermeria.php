<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloEnfermeria extends conexion
{

    public static function registrarCategoriaModel($datos)
    {
        $tabla  = 'enfermeria_categoria';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, id_log) VALUES (:n, :idl);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nom_categoria']);
            $preparado->bindParam(':idl', $datos['id_log']);
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

    public static function mostrarLimiteCategoriaModel()
    {
        $tabla  = 'enfermeria_categoria';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " ORDER BY nombre ASC LIMIT 20;";
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

    public static function mostrarCategoriasEnfermeriaModel()
    {
        $tabla  = 'enfermeria_categoria';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarDatosUsuariosModel($documento)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*,
        (SELECT n.nombre FROM nivel n WHERE n.id = u.id_nivel) as nom_nivel,
        (SELECT nombre from perfiles where id_perfil = u.perfil) as nom_perfil,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) as nom_curso,
        (select nombre_foto from foto_perfil f where f.id_user = u.id_user and f.activo = 1) as imagen
        FROM " . $tabla . " u where u.documento = '" . $documento . "' ORDER BY u.id_user DESC LIMIT 1;";
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

    public static function mostrarLimiteAtencionMedicaModel()
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        en.*,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        u.documento,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) AS nom_curso,
        (SELECT c.nombre FROM nivel c WHERE c.id = u.id_nivel) AS nom_nivel,
        enc.nombre AS motivo_cons
        FROM enfermeria_atencion en
        LEFT JOIN usuarios u ON u.id_user = en.id_user
        LEFT JOIN enfermeria_categoria enc ON enc.id = en.motivo ORDER BY en.id DESC LIMIT 20;";
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

    public static function buscarAtencionMedicaModel($datos)
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        en.*,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        u.documento,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) AS nom_curso,
        (SELECT c.nombre FROM nivel c WHERE c.id = u.id_nivel) AS nom_nivel,
        enc.nombre AS motivo_cons
        FROM enfermeria_atencion en
        LEFT JOIN usuarios u ON u.id_user = en.id_user
        LEFT JOIN enfermeria_categoria enc ON enc.id = en.motivo
        WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', enc.nombre, ' ', en.tratamiento) LIKE '%" . $datos['buscar'] . "%'
        " . $datos['fecha_desde'] . "
        " . $datos['fecha_hasta'] . "
        ORDER BY en.id DESC;";
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

    public static function registrarAtencionMedicaModel($datos)
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_user,
        motivo,
        tratamiento,
        envio,
        id_log
        )
        VALUES
        (
        '" . $datos['id_user'] . "',
        '" . $datos['motivo'] . "',
        '" . $datos['tratamiento'] . "',
        '" . $datos['envio'] . "',
        '" . $datos['id_log'] . "');";
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function mostrarDetallesAtencionModel($id)
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        en.*,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        enc.nombre AS motivo_cons
        FROM enfermeria_atencion en
        LEFT JOIN usuarios u ON u.id_user = en.id_user
        LEFT JOIN enfermeria_categoria enc ON enc.id = en.motivo
        WHERE en.id = :id;";
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

    public static function correosPadresModel($id)
    {
        $tabla  = 'estudiantes_padres';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
    *
        FROM estudiantes_padres
        WHERE correo NOT IN('', '.') AND id_estudiante = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarListadoAtencionMedicaProgramadasModel()
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        en.*,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        u.documento,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) AS nom_curso,
        (SELECT c.nombre FROM nivel c WHERE c.id = u.id_nivel) AS nom_nivel,
        enc.nombre AS motivo_cons
        FROM enfermeria_atencion en
        LEFT JOIN usuarios u ON u.id_user = en.id_user
        LEFT JOIN enfermeria_categoria enc ON enc.id = en.motivo
        WHERE en.envio = 2 AND en.enviado = 0
        ORDER BY en.id DESC;";
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

    public static function envioCorreoAtencionModel($id)
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET enviado = 1 WHERE id = :id;";
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

    public static function mostrarAtencionUsuarioModel($id)
    {
        $tabla  = 'enfermeria_atencion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        en.*,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
        u.documento,
        (SELECT c.nombre FROM curso c WHERE c.id = u.id_curso) AS nom_curso,
        (SELECT c.nombre FROM nivel c WHERE c.id = u.id_nivel) AS nom_nivel,
        enc.nombre AS motivo_cons
        FROM enfermeria_atencion en
        LEFT JOIN usuarios u ON u.id_user = en.id_user
        LEFT JOIN enfermeria_categoria enc ON enc.id = en.motivo
        WHERE en.id_user = :id
        ORDER BY en.id DESC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function EstadisticasEnfermeriaModel($inicio, $fin)
    {
        $tabla  = "enfermeria_atencion";
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT COUNT(ea.id) as cantidad, ec.nombre,ea.fechareg FROM enfermeria_atencion ea
        LEFT JOIN enfermeria_categoria ec ON ec.id = ea.motivo
        WHERE ea.fechareg >= '" . $inicio . " 00:00:00'
        AND ea.fechareg <= '" . $fin . " 23:59:59'
        GROUP BY ea.motivo ;";
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
