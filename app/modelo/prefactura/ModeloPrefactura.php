<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPrefactura extends conexion
{

    public function mostrarDatosPrefacturaModel($id)
    {
        $tabla = 'apartados_residuos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
        r.id_apartado,
        r.id_residuo,
        (SELECT CONCAT('CM ' , m.numero, ' ', DATE(m.fechareg)) FROM consecutivo m WHERE m.id_reserva = r.id_apartado) AS servicio,
        (SELECT CONCAT('CM ' , m.numero, ' ', DATE(m.fechareg)) FROM consecutivo_cert m WHERE m.id_reserva = r.id_apartado) AS servicio_recepcion,
        (SELECT re.nombre FROM residuos re WHERE re.id_residuo = r.id_residuo) AS residuo,
        r.id_tipo_residuo,
        (SELECT cr.cantidad FROM apartados_residuos_log cr WHERE cr.id_apartado = r.id_apartado AND cr.id_residuo = r.id_residuo
        AND cr.id_log IN(r.id_log) GROUP BY cr.id_residuo ORDER BY cr.id DESC) AS cantidad_conciliada,
        (SELECT re.valor_unt FROM residuos re WHERE re.id_residuo = r.id_residuo) AS valor_unt,
        r.id_log,
        (SELECT CONCAT(u.nombre, ' ' , u.apellido) FROM usuarios u WHERE u.id_user = r.id_log) AS usuario_concilia,
        (SELECT re.recepcion FROM reservas re WHERE re.id_reserva = r.id_apartado) AS recepcion,
        (SELECT td.nombre FROM td_listado td WHERE td.id IN(r.id_td1)) AS listado,
        r.kilogramos,
        r.galones,
        (SELECT r.recepcion FROM reservas r WHERE r.id_reserva = r.id_apartado) AS recepcion,
        r.id_td1
        FROM " . $tabla . " r WHERE r.id_apartado = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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



    public function mostrarDatosPrefacturacionEmpresaModel($id)
    {
        $tabla = 'prefacturacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
        (SELECT e.nombre FROM empresas e WHERE e.id_empresa IN(SELECT r.id_empresa FROM reservas r WHERE r.id_reserva IN(pr.id_reserva))) AS empresa,
        (SELECT e.nit FROM empresas e WHERE e.id_empresa IN(SELECT r.id_empresa FROM reservas r WHERE r.id_reserva IN(pr.id_reserva))) AS nit,
        (SELECT e.nom_contacto FROM empresas e WHERE e.id_empresa IN(SELECT r.id_empresa FROM reservas r WHERE r.id_reserva IN(pr.id_reserva))) AS contacto,
        (SELECT e.telefono FROM empresas e WHERE e.id_empresa IN(SELECT r.id_empresa FROM reservas r WHERE r.id_reserva IN(pr.id_reserva))) AS telefono,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(pr.id_log)) AS usuario,
        (SELECT (SELECT p.nombre FROM perfiles p WHERE p.id_perfil IN(u.perfil)) FROM usuarios u WHERE u.id_user IN(pr.id_log)) AS perfil,
        pr.subtotal,
        pr.descuento,
        pr.iva,
        pr.total_final,
        pr.observacion
        FROM " . $tabla . " pr WHERE pr.id_reserva = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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


    public function mostrarDatosPrefacturacionAlteradaEmpresaModel($id)
    {
        $tabla = 'prefacturacion_alt';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
        pr.id_reserva,
        pr.id_residuo,
        (SELECT CONCAT('CM ' , m.numero, ' ', DATE(m.fechareg)) FROM consecutivo m WHERE m.id_reserva = pr.id_reserva) AS servicio,
        (SELECT CONCAT('CM ' , m.numero, ' ', DATE(m.fechareg)) FROM consecutivo_cert m WHERE m.id_reserva = pr.id_reserva) AS servicio_recepcion,
        (SELECT re.nombre FROM residuos re WHERE re.id_residuo = pr.id_residuo) AS residuo,
        pr.id_tipo_residuo,
        pr.valor_unt,
        pr.id_log,
        (SELECT CONCAT(u.nombre, ' ' , u.apellido) FROM usuarios u WHERE u.id_user = pr.id_log) AS usuario_concilia,
        (SELECT re.recepcion FROM reservas re WHERE re.id_reserva = pr.id_reserva) AS recepcion,
        (SELECT td.nombre FROM td_listado td WHERE td.id IN(pr.id_td)) AS listado,
        pr.kilogramos,
        pr.galones,
        (SELECT r.recepcion FROM reservas r WHERE r.id_reserva = pr.id_reserva) AS recepcion,
        pr.id_td
        FROM " . $tabla . " pr WHERE pr.id_reserva = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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




    public function buscarReservasPrefacturadasModel($fecha_ini, $fecha_fin)
    {
        $tabla = 'prefacturacion_alt';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
        pr.id,
        pr.id_reserva,
        (SELECT IF(r.recepcion = 0, r.id_empresa, r.id_sucursal) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS id_empresa,
        (SELECT IF(r.recepcion = 0,(SELECT e.nombre FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.nombre FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS empresa,
        (SELECT IF(r.recepcion = 0,(SELECT e.direccion FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.direccion FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS direccion,
        (SELECT IF(r.recepcion = 0,(SELECT e.ciudad FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.ciudad FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS ciudad,
        (SELECT IF(r.recepcion = 0,(SELECT e.telefono FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.telefono FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS telefono,
        (SELECT IF(r.recepcion = 0,(SELECT e.nom_contacto FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.nom_contacto FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS contacto,
        (SELECT IF(r.recepcion = 0,(SELECT e.nit FROM empresas e WHERE e.id_empresa IN(r.id_empresa)), 
        (SELECT s.nit FROM sucursales s WHERE s.id_sucursal IN(r.id_sucursal))) FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS nit,
        pr.subtotal,
        pr.iva,
        pr.descuento,
        pr.total_subtotal,
        pr.total_descuento,
        pr.total_iva,
        pr.total_final,
        pr.fechareg,
        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(pr.id_log)) AS usuario_prefactura,
        (SELECT (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(r.id_user))FROM reservas r WHERE r.id_reserva IN(pr.id_reserva)) AS usuario_reserva,
        (SELECT (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(ap.id_log)) FROM apartados_residuos ap WHERE ap.id_apartado IN(pr.id_reserva) ORDER BY ap.id DESC LIMIT 1) AS usuario_concilia,
        (SELECT (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(apr.id_log)) FROM apartados_residuos_log apr WHERE apr.id_apartado IN(pr.id_reserva) 
        AND apr.id_log IN(SELECT u.id_user FROM usuarios u WHERE u.perfil IN(5)) ORDER BY apr.id DESC LIMIT 1) AS usuario_confirma
        FROM prefacturacion pr WHERE pr.fechareg BETWEEN '" . $fecha_ini . " 00:00:00' AND '" . $fecha_fin . " 23:59:59'";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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



    public function guardarPreciosPrefacturaModel($datos)
    {
        $tabla = 'prefacturacion';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_reserva, subtotal, iva, descuento, total_descuento, total_subtotal, total_iva, total_final, id_log, id_super_empresa, observacion) 
        VALUES (:ir, :s, :i, :d, :td, :ts, :ti, :tf, :idl, :ids, :ob);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ir', $datos['id_reserva']);
            $preparado->bindParam(':s', $datos['subtotal']);
            $preparado->bindParam(':i', $datos['iva']);
            $preparado->bindParam(':d', $datos['descuento']);
            $preparado->bindParam(':td', $datos['total_descuento']);
            $preparado->bindParam(':ts', $datos['total_subtotal']);
            $preparado->bindParam(':ti', $datos['total_iva']);
            $preparado->bindParam(':tf', $datos['total_final']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':ids', $datos['id_super_empresa']);
            $preparado->bindParam(':ob', $datos['observacion']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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


    public function guardarPrefacturaAlteradaModel($datos)
    {
        $tabla = 'prefacturacion_alt';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO prefacturacion_alt (id_reserva, id_residuo, id_tipo_residuo, id_td, kilogramos, galones, valor_unt, id_log, id_super_empresa) 
        VALUES (:ir, :idr, :itr, :idt, :k, :g, :vu, :il, :ids);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':ir', $datos['id_reserva']);
            $preparado->bindParam(':idr', $datos['id_residuo']);
            $preparado->bindParam(':itr', $datos['id_tipo_residuo']);
            $preparado->bindParam(':idt', $datos['id_td']);
            $preparado->bindParam(':k', $datos['kilogramos']);
            $preparado->bindParam(':g', $datos['galones']);
            $preparado->bindParam(':vu', $datos['valor_unt']);
            $preparado->bindParam(':il', $datos['id_log']);
            $preparado->bindParam(':ids', $datos['id_super_empresa']);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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
