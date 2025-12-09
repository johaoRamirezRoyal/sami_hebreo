<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloHojaVida extends conexion
{

    public static function mostrarDatosArticuloModel($id)
    {
        $tabla  = 'hoja_vida';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE *, h.id as id_hoja_vida, iv.fechareg as fecha_registro,
        COALESCE((SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user), 'Sin usuario') AS usuario,
        COALESCE((SELECT a.nombre FROM areas a WHERE a.id = iv.id_area), 'Sin área') AS area,
        h.id_inventario AS codigo
        FROM " . $tabla . " h
        LEFT JOIN inventario iv ON h.id_inventario = iv.id
        WHERE h.id_inventario = :id";
        
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
            
            if ($preparado->execute()) {
                $resultado = $preparado->fetch();
                if ($resultado) {
                    return $resultado;
                } else {
                    // Si no hay resultados, realizar el INSERT en hoja_vida
                    $insertSql = "INSERT INTO hoja_vida (id_inventario, fecha_adquisicion, frecuencia_mantenimiento, fecha_garantia, fechareg)
                                  VALUES (:id_inventario, :fecha_adquisicion, :frecuencia_mantenimiento, :fecha_garantia, :fechareg)";
                    
                    $insertPreparado = $cnx->preparar($insertSql);
                    $insertPreparado->bindValue(':id_inventario', $id);
                    $insertPreparado->bindValue(':fecha_adquisicion', date('Y-m-d')); // Fecha de hoy
                    $insertPreparado->bindValue(':frecuencia_mantenimiento', 6); // Frecuencia de mantenimiento por defecto
                    $insertPreparado->bindValue(':fecha_garantia', date('Y-m-d')); // Fecha de garantía (hoy)
                    $insertPreparado->bindValue(':fechareg', date('Y-m-d H:i:s')); // Fecha y hora de hoy
                    
                    if ($insertPreparado->execute()) {
                        return false; // O podrías devolver un mensaje de éxito si lo prefieres
                    } else {
                        return false; // Falló el INSERT
                    }
                }
            } else {
                return false; // Falló la ejecución de la consulta SELECT
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarCopiasSeguridadArticuloModel($id)
    {
        $tabla  = 'copia_seguridad';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        c.*,
        CONCAT(u.nombre, ' ', u.`apellido`) AS nom_user,
        a.nombre AS nom_area,
        iv.`descripcion` AS nom_inventario
        FROM
        copia_seguridad c
        LEFT JOIN areas a ON a.id = c.id_area
        LEFT JOIN usuarios u ON u.id_user = c.id_user
        LEFT JOIN inventario iv ON iv.id = c.`id_inventario`
        WHERE c.id_inventario = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function actualizarHojaVidaModel($datos)
    {
        var_dump($datos);
        $tabla  = 'hoja_vida';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE hoja_vida SET direccion_ip = :di, grupo_trabajo = :gt, tipo_conexion = :tc, fecha_adquisicion = :fa,
        frecuencia_mantenimiento = :fm, fecha_garantia = :fg, contacto_garantia = :cg, fecha_update = NOW(), id_log = :il, frecuencia_copia = :fc, numero_serie = :ns WHERE id = :id;
        UPDATE inventario SET descripcion = :d, modelo = :md, marca = :mc WHERE id = :idv;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':di', $datos['ip']);
            $preparado->bindParam(':gt', $datos['grupo']);
            $preparado->bindParam(':tc', $datos['tipo_con']);
            $preparado->bindParam(':fa', $datos['fecha_ad']);
            $preparado->bindParam(':fm', $datos['frec_mant']);
            $preparado->bindParam(':fc', $datos['frec_copia']);
            $preparado->bindParam(':fg', $datos['fecha_gant']);
            $preparado->bindParam(':cg', $datos['contacto']);
            $preparado->bindValue(':id', $datos['id_hoja_vida']);
            $preparado->bindParam(':il', $datos['id_log']);
            $preparado->bindParam(':ns', $datos['numero_serie']);
            $preparado->bindParam(':d', $datos['descripcion']);
            $preparado->bindParam(':md', $datos['modelo']);
            $preparado->bindParam(':mc', $datos['marca']);
            $preparado->bindParam(':idv', $datos['id_inventario']);
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

    public static function mostrarComponentesHardwareModel($id)
    {
        $tabla  = 'inventario';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE iv.*,
        (SELECT IF(h.id = '', 'no', 'si') FROM hardware h WHERE h.id_inventario IN(iv.id) AND h.activo = 1) AS asignado
        FROM " . $tabla . " iv WHERE iv.id_user = :id AND iv.activo = 1 AND iv.estado NOT IN(2,4,5,6) AND iv.id_categoria = 5;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function asignarComponenteHardwareModel($datos)
    {
        $tabla  = 'hardware';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_hoja_vida, id_inventario, id_log) VALUES (:idh, :idv, :idl)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idh', $datos['id_hoja_vida']);
            $preparado->bindParam(':idv', $datos['id_inventario']);
            $preparado->bindParam(':idl', $datos['id_log']);
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

    public static function mostrarComponentesAsignadoHardwareModel($id)
    {
        $tabla  = 'hardware';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        h.id,
        h.id_hoja_vida,
        h.id_inventario,
        h.fechareg,
        (SELECT iv.descripcion FROM inventario iv WHERE iv.id IN(h.id_inventario)) AS descripcion,
        (SELECT iv.marca FROM inventario iv WHERE iv.id IN(h.id_inventario)) AS marca,
        (SELECT iv.modelo FROM inventario iv WHERE iv.id IN(h.id_inventario)) AS modelo,
        (SELECT iv.codigo FROM inventario iv WHERE iv.id IN(h.id_inventario)) AS codigo,
        (SELECT iv.observacion FROM inventario iv WHERE iv.id IN(h.id_inventario)) AS observacion
        FROM hardware h WHERE h.id_hoja_vida = :id AND h.activo = 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function guardarComponenteSoftwareControl($datos)
    {
        $tabla  = 'software';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_hoja_vida, descripcion, version, fabricante, licencia, observacion, id_log, id_super_empresa)
        VALUES (:idh,:d,:v,:f,:l,:ob,:il,:ids);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idh', $datos['id_hoja_vida']);
            $preparado->bindParam(':d', $datos['descripcion']);
            $preparado->bindParam(':v', $datos['version']);
            $preparado->bindParam(':f', $datos['fabricante']);
            $preparado->bindParam(':l', $datos['licencia']);
            $preparado->bindParam(':ob', $datos['observacion']);
            $preparado->bindParam(':il', $datos['id_log']);
            $preparado->bindParam(':ids', $datos['id_super_empresa']);
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

    public static function mostrarComponentesAsignadoSoftwareModel($id)
    {
        $tabla  = 'software';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_hoja_vida = :id AND activo = 1";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function mostrarReportesArticuloModel($id)
    {
        $tabla  = 'reportes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE *,
        (SELECT e.nombre FROM estado e WHERE e.id = estado) as nombre_estado
        FROM " . $tabla . " WHERE id_inventario = :id AND estado = 3 ORDER BY fechareg DESC;;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function mostrarFechaReportadoModel($id)
    {
        $tabla  = 'reportes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE rp.fechareg FROM " . $tabla . " rp WHERE rp.id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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
