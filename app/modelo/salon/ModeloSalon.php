<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloSalon extends conexion
{

    public static function guardarSalonModel($datos)
    {
        $tabla  = 'salones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, portatil, sonido, id_user) VALUES (:n, :p, :s, :idu)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':p', $datos['portatil']);
            $preparado->bindParam(':s', $datos['sonido']);
            $preparado->bindParam(':idu', $datos['id_log']);
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

    public static function mostrarSalonesModel()
    {
        $tabla  = 'salones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE activo = 1;";
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

    public static function buscarDatosDetalleUsuarioReservaModel($datos)

    {

        $tabla  = 'reservas';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                r.*,

                r.id AS id_reserva,

                (SELECT s.nombre FROM salones s WHERE s.id = r.id_salon) AS salon,

                r.fecha_reserva,

                IF(r.portatil = 'no', r.portatil, 'si') AS portatil,

                r.sonido,

                (SELECT h.horas FROM horas h WHERE h.id = r.hora_reserva) AS hora,

                (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario

                FROM reservas r

                INNER JOIN salones s ON s.id = r.id_salon

                LEFT JOIN usuarios u ON u.id_user = r.id_user

                WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', s.nombre, ' ', r.fecha_reserva) LIKE '%" . $datos['user'] . "%' " . $datos['salon'] . $datos['fecha'] . " AND r.id_user = " . $datos['id_user'] . " ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC;";

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

    public static function mostrarSalonesIdUsuarioReservaModel($datos)

    {

        $tabla  = 'salones';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                s.*

                FROM salones s

                LEFT JOIN reservas r ON r.id_salon = s.id

                LEFT JOIN usuarios u ON u.id_user = r.id_user

                WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', s.nombre, ' ', r.fecha_reserva) LIKE '%" . $datos['user'] . "%' " . $datos['salon'] . " " . $datos['fecha'] . " AND r.activo = 1 AND r.id_user = " . $datos['id_user'] . " AND r.confirmado = 2 GROUP BY s.id ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC;";

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

    public static function eliminarReservasModel($datos)


    {

        $tabla  = 'reservas';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET id_cancelado = :idl, fecha_cancelado = NOW(), activo = 0 WHERE id = :id;";


        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idl', $datos['id_cancelado']);

            $preparado->bindParam(':id', $datos['id_reserva']);

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


    public static function mostrarSalonesUsuarioReservaModel($id_log)

    {

        $tabla  = 'salones';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE s.* FROM salones s WHERE s.id IN(SELECT r.id_salon FROM reservas r WHERE r.activo = 1 AND r.confirmado = 2

                AND r.fecha_reserva = '" . date('Y-m-d') . "' AND r.id_user = " . $id_log . ");";

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


    public static function mostrarDatosDetalleUsuarioReservaModel($id_log)

    {

        $tabla  = 'reservas';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                r.*,

                r.id AS id_reserva,

                (SELECT s.nombre FROM salones s WHERE s.id = r.id_salon) AS salon,

                r.fecha_reserva,

                IF(r.portatil = 'no', r.portatil, 'si') AS portatil,

                r.sonido,

                (SELECT h.horas FROM horas h WHERE h.id = r.hora_reserva) AS hora,

                (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario

                FROM " . $tabla . " r WHERE r.fecha_reserva = '" . date('Y-m-d') . "' AND r.id_user = " . $id_log . " ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC;";

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

    public static function mostrarSalonesReservaModel()
    {
        $tabla  = 'salones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE s.* FROM salones s WHERE s.id IN(SELECT r.id_salon FROM reservas r WHERE r.activo = 1 AND r.confirmado = 2);";
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

    public static function mostrarSalonesIdReservaModel($datos)
    {
        $tabla  = 'salones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        s.*
        FROM salones s
        LEFT JOIN reservas r ON r.id_salon = s.id
        LEFT JOIN usuarios u ON u.id_user = r.id_user
        WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', s.nombre, ' ', r.fecha_reserva) LIKE '%" . $datos['user'] . "%' " . $datos['salon'] . " " . $datos['fecha'] . " AND r.activo = 1 AND r.confirmado = 2 GROUP BY s.id ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC;";
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

    public static function mostrarHorasModel()
    {
        $tabla  = 'horas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla;
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

    public static function mostrarDatosDetalleReservaModel()
    {
        $tabla  = 'reservas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        r.*,
        r.id AS id_reserva,
        (SELECT s.nombre FROM salones s WHERE s.id = r.id_salon) AS salon,
        r.fecha_reserva,
        IF(r.portatil = 'no', r.portatil, 'si') AS portatil,
        r.sonido,
        (SELECT h.horas FROM horas h WHERE h.id = r.hora_reserva) AS hora,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario
        FROM " . $tabla . " r WHERE r.fecha_reserva = '" . date('Y-m-d') . "' ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC LIMIT 50;";
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

    public static function buscarDatosDetalleReservaModel($datos)
    {
        $tabla  = 'reservas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE
        r.*,
        r.id AS id_reserva,
        (SELECT s.nombre FROM salones s WHERE s.id = r.id_salon) AS salon,
        r.fecha_reserva,
        IF(r.portatil = 'no', r.portatil, 'si') AS portatil,
        r.sonido,
        (SELECT h.horas FROM horas h WHERE h.id = r.hora_reserva) AS hora,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario
        FROM reservas r
        INNER JOIN salones s ON s.id = r.id_salon
        LEFT JOIN usuarios u ON u.id_user = r.id_user
        WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', s.nombre, ' ', r.fecha_reserva) LIKE '%" . $datos['user'] . "%' " . $datos['salon'] . $datos['fecha'] . " ORDER BY r.fecha_reserva DESC, r.hora_reserva ASC;";
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

    public static function mostrarDatosSalonIdModel($id)
    {
        $tabla  = 'salones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id = :id";
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

    public static function contarPortatilDisponibleModel($fecha)
    {
        $tabla  = 'portatil';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT COUNT(p.id) AS cantidad FROM portatil p WHERE p.id NOT IN(SELECT r.portatil FROM reservas r WHERE r.fecha_reserva = '" . $fecha . "' AND r.activo = 1 AND r.confirmado NOT IN(0));";
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
    
    public static function reservarSalonModel($datos)
    {
        $tabla = 'reservas';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_salon, fecha_reserva, hora_reserva, detalle_reserva, id_user, confirmado, nombre_actividad, aforo, complementos) VALUES (:ids, :fr, :hr, :dr, :idl, :cf, :na, :a, :c);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ids', $datos['salon']);
            $preparado->bindParam(':fr', $datos['fecha']);
            $preparado->bindParam(':hr', $datos['hora']);
            $preparado->bindParam(':dr', $datos['detalle']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':cf', $datos['confirmado']);
            $preparado->bindParam(':na', $datos['nombre_actividad']);
            $preparado->bindParam(':a', $datos['aforo']);
            $preparado->bindParam(':c', $datos['complementos']);
    
            if ($preparado->execute()) {
                $id = $cnx->ultimoIngreso($tabla);
                $resultado = array('guardar' => true, 'id' => $id);
                return $resultado;
            } else {
                // Mostrar el error si la ejecución falla
                print "Error en la ejecución: " . $preparado->errorInfo()[2];
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }
    
    
    
    

    public static function reservarPortatilModel($salon, $fecha)
    {
        $tabla  = 'portatil';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM portatil p WHERE p.id NOT IN(SELECT r.portatil FROM reservas r WHERE r.id_salon = " . $salon . " AND r.fecha_reserva = '" . $fecha . "') ORDER BY p.id DESC LIMIT 1;";
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

    public static function mostrarDatosReservaModel($id)
    {
        $tabla  = 'reservas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        r.*,
        (SELECT h.horas FROM horas h WHERE h.id = r.hora_reserva) as hora_numero,
        (SELECT s.nombre FROM salones s WHERE s.id = r.id_salon) AS salon
        FROM reservas r WHERE r.id = :id;";
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

    public static function mostrarHorasIdModel($id)
    {
        $tabla  = 'horas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id = :id";
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

    public static function mostrarHorasDisponiblesModel($datos)
    {
        $tabla  = 'horas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT h.* FROM " . $tabla . " h WHERE h.id NOT IN (SELECT r.hora_reserva FROM reservas r WHERE r.id_salon = " . $datos['salon'] . " AND r.fecha_reserva = '" . $datos['fecha'] . "' AND r.activo = 1 AND r.confirmado NOT IN(0));";
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

    public static function aprobarReservaModel($id)
    {
        $tabla  = 'reservas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET confirmado = 2 WHERE id = :id;";
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

    public static function rechazarReservaModel($id)
    {
        $tabla  = 'reservas';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET confirmado = 0 WHERE id = :id;";
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
