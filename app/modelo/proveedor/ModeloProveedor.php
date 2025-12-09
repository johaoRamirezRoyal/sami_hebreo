<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloProovedor extends conexion
{

    public static function registrarProveedorModel($datos)
    {
        $tabla  = 'proveedor_detalle';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "
        INSERT INTO " . $tabla . " (
        id_proveedor,
        nombre,
        identificacion,
        num_identificacion,
        direccion,
        ciudad,
        departamento,
        pais,
        telefono,
        correo,
        fecha_ingreso,
        tipo,
        tiempo_entrega,
        garantia,
        plazo_pago,
        detalle_producto,
        nom_representante,
        identificacion_representante,
        correo_representante,
        telefono_representante,
        regimen_proveedor,
        contribuyente_proveedor,
        autoretenedor_proveedor,
        comercio_proveedor,
        actividad_proveedor,
        tarifa_proveedor,
        comercial_nombre,
        identificacion_comercial,
        correo_comercial,
        telefono_comercial,
        direccion_comercial,
        ciudad_comercial,
        departamento_comercial,
        id_log
        )
        VALUES
        (
        '" . $datos['id_proveedor'] . "',
        '" . $datos['nombre'] . "',
        '" . $datos['identificacion'] . "',
        '" . $datos['num_identificacion'] . "',
        '" . $datos['direccion'] . "',
        '" . $datos['ciudad'] . "',
        '" . $datos['departamento'] . "',
        '" . $datos['pais'] . "',
        '" . $datos['telefono'] . "',
        '" . $datos['correo'] . "',
        '" . $datos['fecha_ingreso'] . "',
        '" . $datos['tipo'] . "',
        '" . $datos['tiempo_entrega'] . "',
        '" . $datos['garantia'] . "',
        '" . $datos['plazo_pago'] . "',
        '" . $datos['detalle_producto'] . "',
        '" . $datos['nom_representante'] . "',
        '" . $datos['identificacion_representante'] . "',
        '" . $datos['correo_representante'] . "',
        '" . $datos['telefono_representante'] . "',
        '" . $datos['regimen_proveedor'] . "',
        '" . $datos['contribuyente_proveedor'] . "',
        '" . $datos['autoretenedor_proveedor'] . "',
        '" . $datos['comercio_proveedor'] . "',
        '" . $datos['actividad_proveedor'] . "',
        '" . $datos['tarifa_proveedor'] . "',
        '" . $datos['comercial_nombre'] . "',
        '" . $datos['identificacion_comercial'] . "',
        '" . $datos['correo_comercial'] . "',
        '" . $datos['telefono_comercial'] . "',
        '" . $datos['direccion_comercial'] . "',
        '" . $datos['ciudad_comercial'] . "',
        '" . $datos['departamento_comercial'] . "',
        '" . $datos['id_log'] . "'
        );

        ";
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

    public static function getEvaluacionProveedor($id_proveedor,$id_calificacion)
    {
        $cnx    = conexion::singleton_conexion();

        $sql = "SELECT * 
        FROM proveedor_evaluacion 
        WHERE id_proveedor=$id_proveedor
        AND id= $id_calificacion;";

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

    public static function guardarContactoProveedorModel($datos)
    {
        $tabla  = 'proveedor_contactos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_proveedor,
        nombre_contacto,
        telefono_contacto,
        correo_contacto,
        cargo_contacto,
        id_log
        )
        VALUES
        (
        '" . $datos['id_proveedor'] . "',
        '" . $datos['nombre_contacto'] . "',
        '" . $datos['telefono_contacto'] . "',
        '" . $datos['correo_contacto'] . "',
        '" . $datos['cargo_contacto'] . "',
        '" . $datos['id_log'] . "'
        );

        ";
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

    public static function guardarBancoProveedorModel($datos)
    {
        $tabla  = 'proveedor_banco';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_proveedor,
        nom_banco,
        num_banco,
        tipo_cuenta,
        id_log
        )
        VALUES
        (
        '" . $datos['id_proveedor'] . "',
        '" . $datos['nom_banco'] . "',
        '" . $datos['num_banco'] . "',
        '" . $datos['tipo_cuenta'] . "',
        '" . $datos['id_log'] . "'
        );

        ";
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

    public static function actualizarProveedorModel($datos)
    {
        $tabla  = 'proveedor_detalle';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . "
        SET
        nombre = '" . $datos['nombre'] . "',
        identificacion = '" . $datos['identificacion'] . "',
        num_identificacion = '" . $datos['num_identificacion'] . "',
        direccion = '" . $datos['direccion'] . "',
        ciudad = '" . $datos['ciudad'] . "',
        departamento = '" . $datos['departamento'] . "',
        pais = '" . $datos['pais'] . "',
        telefono = '" . $datos['telefono'] . "',
        correo = '" . $datos['correo'] . "',
        fecha_ingreso = '" . $datos['fecha_ingreso'] . "',
        tipo = '" . $datos['tipo'] . "',
        tiempo_entrega = '" . $datos['tiempo_entrega'] . "',
        garantia = '" . $datos['garantia'] . "',
        plazo_pago = '" . $datos['plazo_pago'] . "',
        detalle_producto = '" . $datos['detalle_producto'] . "',
        nom_representante = '" . $datos['nom_representante'] . "',
        identificacion_representante = '" . $datos['identificacion_representante'] . "',
        correo_representante = '" . $datos['correo_representante'] . "',
        telefono_representante = '" . $datos['telefono_representante'] . "',
        regimen_proveedor = '" . $datos['regimen_proveedor'] . "',
        contribuyente_proveedor = '" . $datos['contribuyente_proveedor'] . "',
        autoretenedor_proveedor = '" . $datos['autoretenedor_proveedor'] . "',
        comercio_proveedor = '" . $datos['comercio_proveedor'] . "',
        actividad_proveedor = '" . $datos['actividad_proveedor'] . "',
        tarifa_proveedor = '" . $datos['tarifa_proveedor'] . "',
        comercial_nombre = '" . $datos['comercial_nombre'] . "',
        identificacion_comercial = '" . $datos['identificacion_comercial'] . "',
        correo_comercial = '" . $datos['correo_comercial'] . "',
        telefono_comercial = '" . $datos['telefono_comercial'] . "',
        direccion_comercial = '" . $datos['direccion_comercial'] . "',
        ciudad_comercial = '" . $datos['ciudad_comercial'] . "',
        departamento_comercial = '" . $datos['departamento_comercial'] . "',
        id_log = '" . $datos['id_log'] . "',
        fechareg = NOW()
        WHERE id_proveedor = '" . $datos['id_proveedor'] . "';
        ";
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

    public static function mostrarDatosProveedorIdModel($id)
    {
        $tabla  = 'proveedor_detalle';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_proveedor = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function mostrarContactosProveedorModel($id)
    {
        $tabla  = 'proveedor_contactos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_proveedor = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function mostrarBancoProveedorModel($id)
    {
        $tabla  = 'proveedor_banco';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_proveedor = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function mostrarDocumentosProveedorModel($id)
    {
        $tabla  = 'proveedor_documento';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE *,
        (SELECT t.nombre FROM tipo_documento t WHERE t.id  = tipo_documento) AS nom_documento
        FROM " . $tabla . " WHERE id_proveedor = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function documentosProveedorModel($datos)
    {
        $tabla  = 'proveedor_documento';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_proveedor, nombre, tipo_documento, id_log) VALUES (:id, :n, :td, :idl)";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            $preparado->bindParam(':id', $datos['id_proveedor']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':td', $datos['tipo_documento']);
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

    public static function mostrarProveedoresModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*, p.*,
        p.nombre AS razon_social,
        (SELECT c.nombre_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS contacto,
        (SELECT c.telefono_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS telefono_contacto
        FROM " . $tabla . " u
        INNER JOIN proveedor_detalle p ON p.id_proveedor = u.id_user
        WHERE perfil  = 17 ORDER BY u.id_user DESC;";
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

    public static function mostrarTodosProveedoresModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*, p.*,
        p.nombre AS razon_social,
        (SELECT c.nombre_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS contacto,
        (SELECT c.telefono_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS telefono_contacto
        FROM " . $tabla . " u
        INNER JOIN proveedor_detalle p ON p.id_proveedor = u.id_user
        WHERE perfil  = 17 ORDER BY u.id_user DESC;";
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

    public static function buscarProveedorModel($buscar)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE u.*, p.*,
        p.nombre AS razon_social,
        (SELECT c.nombre_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS contacto,
        (SELECT c.telefono_contacto FROM proveedor_contactos c WHERE c.id_proveedor = u.id_user AND c.activo = 1) AS telefono_contacto
        FROM " . $tabla . " u
        INNER JOIN proveedor_detalle p ON p.id_proveedor = u.id_user
        WHERE perfil  = 17 AND CONCAT(p.num_identificacion, ' ', p.nombre, ' ', p.correo, ' ', p.direccion, ' ', p.ciudad) LIKE '%" . $buscar . "%';";
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

    public static function validarDocumentosProveedorModel($id)
    {
        $tabla  = 'proveedor_documento';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE COUNT(id) as contar FROM " . $tabla . " WHERE id_proveedor = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function validarEvaluacionProveedorModel($id)
    {
        $tabla  = 'proveedor_evaluacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_proveedor = :id AND DATE_FORMAT(fechareg,'%Y') = " . date('Y') . ";";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function mostrarCalificacionProveedorModel($id)
    {
        $tabla  = 'proveedor_evaluacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_proveedor = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function registrarEvaluacionAnualModel($datos)
    {
        $tabla  = 'proveedor_evaluacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
        id_proveedor,
        fecha_inicio,
        fecha_finalizacion,
        fecha_evaluacion,
        evaluador,
        pregunta_1,
        pregunta_2,
        pregunta_3,
        pregunta_4,
        pregunta_5,
        observacion_1,
        observacion_2,
        observacion_3,
        observacion_4,
        observacion_5,
        total,
        id_log
        )
        VALUES
        (
        '" . $datos['id_proveedor'] . "',
        '" . $datos['fecha_inicio'] . "',
        '" . $datos['fecha_finalizacion'] . "',
        '" . $datos['fecha_evaluacion'] . "',
        '" . $datos['evaluador'] . "',
        '" . $datos['pregunta_1'] . "',
        '" . $datos['pregunta_2'] . "',
        '" . $datos['pregunta_3'] . "',
        '" . $datos['pregunta_4'] . "',
        '" . $datos['pregunta_5'] . "',
        '" . $datos['observacion_1'] . "',
        '" . $datos['observacion_2'] . "',
        '" . $datos['observacion_3'] . "',
        '" . $datos['observacion_4'] . "',
        '" . $datos['observacion_5'] . "',
        '" . $datos['total'] . "',
        '" . $datos['id_log'] . "'
        );
        ";

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

    public static function eliminarContactoModel($id)
    {
        $tabla  = 'proveedor_contactos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    public static function eliminarBancoModel($id)
    {
        $tabla  = 'proveedor_banco';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id = :id";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
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

    //Eliminar documento unico
    public static function eliminarDocumentoModel($id){
        $tabla = 'proveedor_documento'; 
        $cnx = conexion::singleton_conexion();
        $cmdsql = "DELETE FROM $tabla WHERE id = :id;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            $preparado->bindParam(':id', $id);
            if($preparado->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
    }

    public static function mostrarInformacionDocumentoModel($id_proveedor, $id_documento){
        $tabla = 'proveedor_documento';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                        pd.id_proveedor, 
                        pd.nombre, 
                        td.nombre AS tipo_documento_nombre,
                        td.id AS id_tipo_documento
                    FROM 
                        $tabla pd
                    LEFT JOIN 
                        tipo_documento td ON td.id = pd.tipo_documento
                    WHERE 
                        pd.id = :id_documento 
                        AND pd.id_proveedor = :id_proveedor;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_proveedor', $id_proveedor);
            $preparado->bindParam(':id_documento', $id_documento);
            if($preparado->execute()){
                return $preparado->fetch();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
    }
}
