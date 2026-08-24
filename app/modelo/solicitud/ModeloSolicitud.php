<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloSolicitud extends conexion
{

    public static function registrarSolicitudModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_user,
        id_area,
        fecha_solicitud,
        id_log,
        justificacion,
        grado
        )
        VALUES (:idu, :ida, :fs, :idl, :j, :g);

        INSERT INTO solicitudes_inicial (
        id_user,
        id_area,
        fecha_solicitud,
        id_log,
        justificacion,
        grado
        )
        VALUES (:idu, :ida, :fs, :idl, :j, :g);
        ";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':idu', $datos['id_user']);
            $preparado->bindParam(':ida', $datos['area']);
            $preparado->bindParam(':fs', $datos['fecha_solicitud']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':j', $datos['justificacion']);
            $preparado->bindParam(':g', $datos['grado']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function registrarProdcuctosModel($datos)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_solicitud,
        producto,
        cantidad,
        id_log,
        iva
        )
        VALUES (:ids,:p,:c,:idl,0);

        INSERT INTO solicitud_productos_inicial (
        id_solicitud,
        producto,
        cantidad,
        id_log,
        iva
        )
        VALUES (:ids,:p,:c,:idl,0);
        ";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ids', $datos['id_solicitud']);
            $preparado->bindParam(':p', $datos['producto']);
            $preparado->bindParam(':c', $datos['cantidad']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function mostrarSolucitudesNivelModel($id_nivel, $limite = 100){
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $limite = (int) $limite;
        $cmdsql = "SELECT
                    s.*,
                    (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
                    (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
                    (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
                    (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
                    (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
                FROM solicitudes s
                JOIN usuarios u ON u.id_user = s.id_user
                WHERE u.id_nivel = $id_nivel -- nivel a filtrar
                ORDER BY s.id DESC 
                LIMIT $limite;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarSolicitudesModel($limite = 100)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $limite = (int) $limite;
        $cmdsql =  "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s ORDER BY s.id DESC LIMIT $limite;";
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

    public static function mostrarSolicitudesAprobadasCoordinadorModel($limite = 100){
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $limite = (int) $limite;
        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM $tabla s 
        WHERE s.estado = 1
        ORDER BY s.id DESC LIMIT $limite;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function buscarSolicitudesAprobadasCoordinadorModel($datos)
    {
        $tabla = 'solicitudes';
        $cnx   = conexion::singleton_conexion();

        $where  = array('s.estado = 1');
        $params = array();

        if (!empty($datos['buscar'])) {
            $where[] = "s.justificacion LIKE :buscar";
            $params[':buscar'] = '%' . $datos['buscar'] . '%';
        }

        if (!empty($datos['fecha_inicio'])) {
            $where[] = "s.fecha_solicitud >= :fecha_inicio";
            $params[':fecha_inicio'] = $datos['fecha_inicio'];
        }

        if (!empty($datos['fecha_fin'])) {
            $where[] = "s.fecha_solicitud <= :fecha_fin";
            $params[':fecha_fin'] = $datos['fecha_fin'];
        }

        if (!empty($datos['area'])) {
            $where[] = "s.id_area = :area";
            $params[':area'] = $datos['area'];
        }

        if (!empty($datos['usuario'])) {
            $where[] = "s.id_user = :usuario";
            $params[':usuario'] = $datos['usuario'];
        }

        if (!empty($datos['grado'])) {
            $where[] = "s.grado = :grado";
            $params[':grado'] = $datos['grado'];
        }

        $whereSql = 'WHERE ' . implode(' AND ', $where);

        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s
        JOIN usuarios u ON u.id_user = s.id_user
        $whereSql
        ORDER BY s.id DESC;";

        try {
            $preparado = $cnx->preparar($cmdsql);
            foreach ($params as $key => $value) {
                $preparado->bindValue($key, $value);
            }
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

    public static function mostrarSolicitudesUsuarioModel($id)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s WHERE s.id_user = :id ORDER BY id DESC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarDatosSolicitudIdModel($id)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT a.nombre FROM curso a WHERE a.id = s.grado) AS curso_nom,
        (SELECT a.id_nivel FROM curso a WHERE a.id = s.grado) AS nivel_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_pedido) AS nom_pedido,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_log) AS nom_aprobado,
        (SELECT u.documento FROM usuarios u WHERE u.id_user = s.id_user) AS documento,
        (SELECT u.telefono FROM usuarios u WHERE u.id_user = s.id_user) AS telefono,
        (SELECT p.nombre FROM proveedor_detalle p WHERE p.id = s.id_proveedor) AS nom_proveedor,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_area_compras) AS nom_area_compra,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s WHERE s.id = :id";
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

    public static function mostrarDatosSolicitudInicialIdModel($id)
    {
        $tabla  = 'solicitudes_inicial';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_log) AS nom_aprobado,
        (SELECT u.documento FROM usuarios u WHERE u.id_user = s.id_user) AS documento,
        (SELECT u.telefono FROM usuarios u WHERE u.id_user = s.id_user) AS telefono
        FROM solicitudes_inicial s WHERE s.id = :id";
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

    public static function mostrarProdcutosSolicitudModel($id)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " sp WHERE sp.id_solicitud = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarProdcutosSolicitudInicialModel($id)
    {
        $tabla  = 'solicitud_productos_inicial';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " sp WHERE sp.id_solicitud = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function actualizarEstadoModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET estado = :e, fecha_aplazado = :f, observacion = :o, id_log = :idl, iva = :iva, id_proveedor = :idp, fecha_solicitud = :fs WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_solicitud']);
            $preparado->bindParam(':e', $datos['estado']);
            $preparado->bindParam(':f', $datos['fecha_aplazado']);
            $preparado->bindParam(':o', $datos['observacion']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':iva', $datos['iva']);
            $preparado->bindParam(':idp', $datos['id_proveedor']);
            $preparado->bindParam(':fs', $datos['fecha_solicitado']);
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

    public static function preciosProductoControl($datos)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET precio = :p, producto = :n, cantidad = :c, iva = :iv WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_producto']);
            $preparado->bindParam(':p', $datos['precio']);
            $preparado->bindParam(':n', $datos['nom_producto']);
            $preparado->bindParam(':c', $datos['cantidad']);
            $preparado->bindParam(':iv', $datos['iva']);
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

    public static function verificarSolicitudModel($datos)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_recibido = " . $datos['id_log'] . ", cantidad_recibida = '" . $datos['cantidad_recibida'] . "' WHERE id = '" . $datos['id_detalle'] . "';";
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

    public static function recibidoSolicitudModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_recibido = '" . $datos['id_log'] . "', fecha_recibido = NOW(), observacion_recibido = '" . $datos['observacion_recibido'] . "' WHERE id = '" . $datos['id_solicitud'] . "';";
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

    public static function mostrarDatosVerificacionModel($id)
    {
        $tabla  = 'solicitud_verificacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT *, (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = id_log) AS nom_usuario FROM " . $tabla . " WHERE id_solicitud = :id";
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

    public static function cotizacionSolicitudModel($id)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE count(id) as cantidad FROM " . $tabla . " WHERE id_solicitud = :id";
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

    public static function mostrarCotizacionModel($id)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_solicitud = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function mostrarDatosVerificacionInicialModel($id)
    {
        $tabla  = 'solicitud_verificacion_inicial';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT *, (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = id_log) AS nom_usuario FROM " . $tabla . " WHERE id_solicitud = :id";
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

    public static function removerProductoModel($id)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM " . $tabla . " WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function removerProductosSolicitudModel($id)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM " . $tabla . " WHERE id_solicitud = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
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

    public static function anularSolicitudModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET motivo = :m, estado =:e, id_log = :idl WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_solicitud']);
            $preparado->bindParam(':m', $datos['motivo']);
            $preparado->bindParam(':e', $datos['estado']);
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

    public static function anularSolicitudComprasModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET motivo = :m, estadocompra =:e, id_log = :idl WHERE id = :id";
        //echo $cmdsql;
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_solicitud']);
            $preparado->bindParam(':m', $datos['motivo']);
            $preparado->bindParam(':e', $datos['estadocompra']);
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
        //exit();
    }

    public static function subirCotizacionModel($datos)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_solicitud,
        archivo,
        id_log)
        VALUES
        (
        '" . $datos['id_solicitud'] . "',
        '" . $datos['archivo'] . "',
        '" . $datos['id_log'] . "');";
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

    public static function mostrarCotizacionesModel()
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        c.*,
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user
        FROM cotizacion c
        LEFT JOIN usuarios u ON u.id_user = c.id_log
        WHERE activo = 1 ORDER BY c.fecha DESC LIMIT 20;";
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

    public static function mostrarDatosCotizacionIdModel($id)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        c.*,
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user
        FROM cotizacion c
        LEFT JOIN usuarios u ON u.id_user = c.id_log
        WHERE c.id = :id;";
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

    public static function subirFacturaModel($datos)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET factura = :fc, id_factura = :idf, fecha_factura = NOW(), observacion = :ob WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':fc', $datos['factura']);
            $preparado->bindParam(':idf', $datos['id_log']);
            $preparado->bindParam(':id', $datos['id_cotizacion']);
            $preparado->bindParam(':ob', $datos['observacion']);
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

    public static function subirOrdenModel($datos)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET orden_compra = :fc, id_orden = :idf, fecha_orden = NOW(), observacion = :ob WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':fc', $datos['orden']);
            $preparado->bindParam(':idf', $datos['id_log']);
            $preparado->bindParam(':id', $datos['id_cotizacion']);
            $preparado->bindParam(':ob', $datos['observacion']);
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

    public static function buscarCotizacionesModel($datos)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        c.*,
        u.documento,
        CONCAT(u.nombre, ' ', u.apellido) AS nom_user
        FROM cotizacion c
        LEFT JOIN usuarios u ON u.id_user = c.id_log
        WHERE activo = 1 AND CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', c.concepto) LIKE '%" . $datos['buscar'] . "%'
        " . $datos['fecha'] . "
        ORDER BY c.fecha DESC;";
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

    public static function buscarSolicitudesCotizacionModel($datos)
    {
        $cnx = conexion::singleton_conexion();

        $where  = array();
        $params = array();

        if (!empty($datos['buscar'])) {
            $where[] = "s.justificacion LIKE :buscar";
            $params[':buscar'] = '%' . $datos['buscar'] . '%';
        }

        if (!empty($datos['fecha'])) {
            $where[] = "s.fecha_solicitud = :fecha";
            $params[':fecha'] = $datos['fecha'];
        }

        if (!empty($datos['area'])) {
            $where[] = "s.id_area = :area";
            $params[':area'] = $datos['area'];
        }

        if (!empty($datos['usuario'])) {
            $where[] = "s.id_user = :usuario";
            $params[':usuario'] = $datos['usuario'];
        }

        $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s
        $whereSql
        ORDER BY s.id DESC;";

        try {
            $preparado = $cnx->preparar($cmdsql);
            foreach ($params as $key => $value) {
                $preparado->bindValue($key, $value);
            }
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

    public static function buscarSolicitudesModel($datos)
    {
        $cnx = conexion::singleton_conexion();

        $where  = array();
        $params = array();

        if (!empty($datos['buscar'])) {
            $where[] = "s.justificacion LIKE :buscar";
            $params[':buscar'] = '%' . $datos['buscar'] . '%';
        }

        if (!empty($datos['fecha'])) {
            $where[] = "s.fecha_solicitud = :fecha";
            $params[':fecha'] = $datos['fecha'];
        }

        if (!empty($datos['area'])) {
            $where[] = "s.id_area = :area";
            $params[':area'] = $datos['area'];
        }

        if (!empty($datos['usuario'])) {
            $where[] = "s.id_user = :usuario";
            $params[':usuario'] = $datos['usuario'];
        }

        if (!empty($datos['nivel'])) {
            $where[] = "u.id_nivel = :nivel";
            $params[':nivel'] = $datos['nivel'];
        }

        $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        $cmdsql = "SELECT
        s.*,
        (SELECT a.nombre FROM areas a WHERE a.id = s.id_area) AS area_nom,
        (SELECT c.nombre FROM curso c WHERE c.id = s.grado) AS nom_curso,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = s.id_user) AS nom_usuario,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id ORDER BY c.id DESC LIMIT 1) AS cotizacion,
        (SELECT COUNT(c.id) FROM cotizacion c WHERE c.id_solicitud = s.id AND c.aprobado = 1 ORDER BY c.id DESC LIMIT 1) AS cotizacion_aprobada
        FROM solicitudes s
        JOIN usuarios u ON u.id_user = s.id_user
        $whereSql
        ORDER BY s.id DESC;";

        try {
            $preparado = $cnx->preparar($cmdsql);
            foreach ($params as $key => $value) {
                $preparado->bindValue($key, $value);
            }
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

    public static function revisionSolicitudModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE solicitudes SET id_area_compras = " . $datos['id_log'] . ", fecha_revision = NOW(), observacion_revision = '" . $datos['observacion'] . "' WHERE id = " . $datos['id_solicitud'] . ";";
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

    public static function revisionProdcutoSolicitudModel($datos)
    {
        $tabla  = 'solicitud_productos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE solicitud_productos SET existencia = '" . $datos['existencia'] . "', cantidad_existencia = '" . $datos['cantidad_existencia'] . "' WHERE id = '" . $datos['id_producto'] . "';";
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

    public static function aprobarCotizacionSolicitudModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET observacion_cotizacion = '" . $datos['observacion'] . "', id_cotizacion = '" . $datos['id_log'] . "', fecha_cotizacion = NOW() WHERE id = '" . $datos['id_solicitud'] . "';";
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

    public static function aprobarCotizacionModel($datos)
    {
        $tabla  = 'cotizacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET aprobado = 1 WHERE id = " . $datos['cotizacion'] . " AND id_solicitud = " . $datos['id_solicitud'];
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

    public static function realizarPedidoModel($datos)
    {
        $tabla  = 'solicitudes';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET id_pedido = '" . $datos['id_log'] . "', observacion_pedido = '" . $datos['observacion'] . "', id_proveedor = '" . $datos['proveedor'] . "', estadocompra=3 WHERE id = '" . $datos['id_solicitud'] . "';";
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
    
    public static function getSolicitud($id){

        $cnx    = conexion::singleton_conexion();
        $sql = "SELECT * FROM solicitudes 
        WHERE id=$id
        ORDER BY id DESC;";
        
        try {
            $preparado = $cnx->preparar($sql);
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
}
