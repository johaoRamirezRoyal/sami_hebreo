<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloInventario extends conexion
{

    public static function agregarInvProduct($id_compra, $id_producto, $cantidad)
    {
        $cnx = conexion::singleton_conexion();
        $sql = "INSERT INTO agregar_inv_product(id_compra,id_producto,consumo)
        VALUES($id_compra,$id_producto,$cantidad);";

        try {

            $preparado = $cnx->preparar($sql);

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

    public static function updateInvProduct($id_compra, $id_producto, $cantidad)
    {

        $cnx = conexion::singleton_conexion();
        $sql = "UPDATE agregar_inv_product
                SET consumo = $cantidad
                WHERE id_compra = $id_compra
                AND id_producto = $id_producto;";

        try {

            $preparado = $cnx->preparar($sql);

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

    public static function guardarInventarioDirectoModel($datos)
    {
        $tabla = 'inventario';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . "
        (descripcion,
            marca,
            modelo,
            precio,
            estado,
            activo,
            fecha_compra,
            observacion,
            id_user,
            id_area,
            id_categoria,
            user_log,
            id_super_empresa,
            codigo)
            VALUES (
            :descripcion,
            :marca,
            :modelo,
            :precio,
            :estado,
            :activo,
            :fecha_compra,
            :observacion,
            :id_user,
            :id_area,
            :id_categoria,
            :user_log,
            :id_super_empresa,
            :codigo
        );";

        try {
            $preparado = $cnx->preparar($cmdsql);
    
                // Asignar los valores a los parámetros correctos
                $preparado->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
                $preparado->bindParam(':marca', $datos['marca'], PDO::PARAM_STR);
                $preparado->bindParam(':modelo', $datos['modelo'], PDO::PARAM_STR);
                $preparado->bindParam(':precio', $datos['precio'], PDO::PARAM_STR);
                $preparado->bindParam(':estado', $datos['estado'], PDO::PARAM_STR);
                $preparado->bindParam(':activo', $datos['activo'], PDO::PARAM_INT);
                $preparado->bindParam(':fecha_compra', $datos['fecha_compra'], PDO::PARAM_STR);
                $preparado->bindParam(':observacion', $datos['observacion'], PDO::PARAM_STR);
                $preparado->bindParam(':id_user', $datos['id_user'], PDO::PARAM_INT);
                $preparado->bindParam(':id_area', $datos['id_area'], PDO::PARAM_INT);
                $preparado->bindParam(':id_categoria', $datos['id_categoria'], PDO::PARAM_INT);
                $preparado->bindParam(':user_log', $datos['user_log'], PDO::PARAM_STR);
                $preparado->bindParam(':id_super_empresa', $datos['id_super_empresa'], PDO::PARAM_INT);
                $preparado->bindParam(':codigo', $datos['codigo'], PDO::PARAM_STR);
    
            //var_dump($datos).'</br>';

            if ($preparado->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            return false;
        }
    
        $cnx->closed();
        $cnx = null;
    }

    public static function getInvProduct($id_compra, $id_producto)
    {
        $cnx = conexion::singleton_conexion();
        $sql = "SELECT * FROM agregar_inv_product
        WHERE id_compra = $id_compra
        AND id_producto = $id_producto;";

        try {

            $preparado = $cnx->preparar($sql);

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



    public static function guardarInventarioModel($datos)
    {
        $tabla = 'inventario';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . "
        (descripcion,
            marca,
            modelo,
            precio,
            estado,
            activo,
            fecha_compra,
            observacion,
            id_user,
            id_area,
            id_categoria,
            user_log,
            id_super_empresa,
            codigo,
            fechareg)
        SELECT descripcion,
        marca,
        modelo,
        precio,
        estado,
        activo,
        fecha_compra,
        observacion,
        id_user,
        id_area,
        id_categoria,
        user_log,
        id_super_empresa,
        codigo,
        fechareg
        FROM inventario_temp it
        WHERE it.estado = 1 AND it.activo = 1 AND it.user_log = :il AND it.id_super_empresa = :ids;
        INSERT INTO hoja_vida (id_inventario, id_super_empresa, fecha_update) SELECT it.id, it.id_super_empresa, it.fechareg FROM inventario it WHERE it.estado = 1
        AND it.activo = 1 AND it.user_log = :il AND it.id_super_empresa = :ids AND it.id NOT IN(SELECT h.id_inventario FROM hoja_vida h) AND it.id_categoria = 1;
        INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, id_super_empresa, estado)
        SELECT it.id, it.id_user, it.id_area, it.user_log, it.id_super_empresa, it.estado FROM inventario it
        WHERE it.estado = 1 AND it.activo = 1  AND it.id_super_empresa = :ids AND it.user_log = :il
        AND it.id NOT IN(SELECT h.id_inventario FROM inventario_log h);";

        echo $cmdsql;
        
        try {
            $preparado = $cnx->preparar($cmdsql);
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

    public static function guardarEvidenciaModel($datos)
    {
        $tabla = 'evidencias';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " SELECT * FROM evidencias_temp ev
        WHERE ev.id_log = :il AND ev.id_super_empresa = :ids;";
        try {
            $preparado = $cnx->preparar($cmdsql);
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



    public static function actualizarTemporalModel($datos)
    {

        $tabla = 'inventario_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SET SQL_SAFE_UPDATES = 0; UPDATE " . $tabla . " SET activo = 0 WHERE user_log = :il AND id_super_empresa = :ids;";

        try {

            $preparado = $cnx->preparar($cmdsql);

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



    public static function eliminarTemporalModel($datos)
    {

        $tabla = 'inventario_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "DELETE FROM " . $tabla . " WHERE user_log = :il AND id_super_empresa = :ids;

        DELETE FROM evidencias_temp WHERE id_log = :il AND id_super_empresa = :ids";

        try {

            $preparado = $cnx->preparar($cmdsql);

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



    public static function verificarCodigoModel($codigo)
    {

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE codigo FROM inventario WHERE codigo = :c;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':c', $codigo);

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



    public static function mostrarInventarioModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

        SQL_NO_CACHE iv.*,

        (SELECT

            e.nombre

            FROM

            estado e

            WHERE e.id IN (iv.estado)) AS estado_nombre,

        (SELECT

            CONCAT (u.nombre, ' ', u.apellido)

            FROM

            usuarios u

            WHERE u.id_user IN (iv.id_user)) AS usuario,

        COUNT(iv.id) AS cantidad,

        (SELECT

            a.nombre

            FROM

            areas a

            WHERE a.id IN (iv.id_area)) AS area_nom

        FROM

        inventario iv

        INNER JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE

        iv.activo = 1

        AND iv.estado NOT IN (4,5,7,8,9,10)

        AND iv.confirmado = 1

        GROUP BY iv.descripcion,

        iv.id_area ORDER BY iv.id DESC LIMIT 30;";

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



    public static function buscarInventarioModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

        SQL_NO_CACHE iv.*,

        (SELECT

            e.nombre

            FROM

            estado e

            WHERE e.id IN (iv.estado)) AS estado_nombre,

        (SELECT

            CONCAT (u.nombre, ' ', u.apellido)

            FROM

            usuarios u

            WHERE u.id_user IN (iv.id_user)) AS usuario,

        COUNT(iv.id) AS cantidad,

        (SELECT

            a.nombre

            FROM

            areas a

            WHERE a.id IN (iv.id_area)) AS area_nom

        FROM

        inventario iv

        INNER JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE

        CONCAT(u.nombre, ' ', u.apellido, ' ', a.nombre, ' ', iv.descripcion) LIKE '%" . $datos['articulo'] . "%'

        AND

        a.activo = 1

        AND

        iv.activo = 1

        AND iv.estado NOT IN (4,5,7,8,9,10)

        AND iv.confirmado = 1

        " . $datos['area'] . " " . $datos['usuario'] . "

        GROUP BY iv.descripcion,

        iv.id_area;

        ";

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



    public static function cantidadesInventarioModel($articulo)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

        iv.descripcion,

        COUNT(iv.id) AS cantidad

        FROM

        inventario iv

        INNER JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE

        iv.descripcion  like '" . $articulo . "%'

        AND

        a.activo = 1

        AND

        iv.activo = 1

        AND iv.estado NOT IN (2, 6, 5)

        AND iv.confirmado = 1;

        ";

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



    public static function mostrarCategoriasModel($super_empresa)
    {

        $tabla = 'categoria';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM categoria c WHERE c.activo = 1 AND c.id_super_empresa = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $super_empresa);

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



    public static function buscarInventarioDetalleModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

        iv.*,

        (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS estado_nombre,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS nom_user,

        (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS nom_area,

        COUNT(iv.descripcion) as cantidad

        FROM inventario iv

        LEFT JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE iv.activo = 1 AND iv.estado NOT IN (4, 5)

        AND CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', a.nombre, ' ', iv.descripcion, ' ', iv.id) LIKE '%" . $datos['articulo'] . "%' " . $datos['area'] . "" . $datos['usuario'] . " GROUP BY iv.descripcion , u.nombre, u.apellido, a.nombre order by iv.id DESC;";

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



    public static function buscarInventarioDescontinuadoDetalleModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

        iv.*,

        (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS estado_nombre,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS nom_user,

        (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS nom_area

        FROM inventario iv

        LEFT JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE a.activo = 1 and iv.estado IN(5)

       -- AND (iv.descripcion LIKE '%". $datos['articulo'] . "%')

        AND CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', a.nombre, ' ', iv.descripcion) LIKE '%" . $datos['articulo'] . "%' " . $datos['area'] . "" . $datos['usuario'] . " order by iv.id DESC;";

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



    public static function mostrarInventarioDetalleModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT 
                    iv.*,
                    e.nombre AS estado_nombre,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                    a.nombre AS nom_area,
                    COUNT(iv.id) AS cantidad
                FROM 
                    inventario iv
                    LEFT JOIN estado e ON e.id = iv.estado
                    LEFT JOIN usuarios u ON u.id_user = iv.id_user
                    LEFT JOIN areas a ON a.id = iv.id_area
                WHERE 
                    iv.activo = 1
                    AND iv.estado NOT IN (4, 5)
                GROUP BY 
                    iv.descripcion , u.nombre, u.apellido, a.nombre -- iv.marca, iv.modelo, e.nombre, iv.estado,
                ORDER BY 
                    iv.id DESC
                LIMIT 50;
                ";
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

   public static function obtenerInventarioDesagrupadoModel($desc_inventario, $id_area, $id_user){
        $tabla = "inventario"; 
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                    iv.*,
                    e.nombre AS estado_nombre,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                    a.nombre AS nom_area,
                    COUNT(iv.id) AS cantidad
                FROM $tabla iv
                    LEFT JOIN estado e ON e.id = iv.estado
                    LEFT JOIN usuarios u ON u.id_user = iv.id_user
                    LEFT JOIN areas a ON a.id = iv.id_area
                WHERE 
                    iv.activo = 1 AND 
                    iv.descripcion = :desc_inventario AND
                    iv.id_area= :id_area AND 
                    iv.id_user= :id_user AND 
                    iv.estado NOT IN (4, 5)
                GROUP BY 
                    iv.id -- , iv.id,  iv.estado, iv.id, iv.marca, iv.modelo, iv.precio, e.nombre, u.nombre, u.apellido, a.nombre
                ORDER BY 
                    iv.id DESC;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':desc_inventario', $desc_inventario);
            $preparado->bindParam(':id_area', $id_area);
            $preparado->bindParam(':id_user', $id_user);
            if($preparado->execute()){
                $resultado = $preparado->fetchAll();
                return $resultado;
            }
        }catch(PDOException $e){            
            return $e->getMessage();
}
}

    public static function getInventarioAires()
    {

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

        iv.*,

        (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS estado_nombre,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS nom_user,

        (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS nom_area,

        COUNT(iv.id) AS cantidad

        FROM inventario iv

        LEFT JOIN usuarios u ON u.id_user = iv.id_user

        LEFT JOIN areas a ON a.id = iv.id_area

        WHERE iv.activo = 1

        AND iv.descripcion LIKE 'aire%%'
        
        AND iv.estado IN (1,2,3,6,7,10) 
        

        GROUP BY iv.descripcion, iv.estado

        ORDER BY iv.id DESC;";

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

    public static function mostrarArticulosAgrupadosModalModel($descripcion, $idarea, $id_usuario) {
        $tabla = 'inventario';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                        iv.id,
                        iv.descripcion,
                        iv.observacion,
                        COUNT(iv.id) AS cantidad,
                        (SELECT CONCAT(u.nombre, ' ', u.apellido) 
                         FROM usuarios u 
                         WHERE u.id_user = iv.id_user) AS usuario,
                        (SELECT a.nombre 
                         FROM areas a 
                         WHERE a.id = iv.id_area) AS area,
                        (SELECT h.frecuencia_mantenimiento 
                         FROM hoja_vida h 
                         WHERE h.id_inventario = iv.id) AS frecuencia,
                        (SELECT e.nombre 
                         FROM estado e 
                         WHERE e.id = iv.estado) AS nombre_estado,
                        IFNULL((SELECT r.fechareg 
                            FROM reportes r 
                            WHERE r.id_inventario = iv.id AND r.estado = 6 
                            ORDER BY id DESC LIMIT 1), iv.fechareg) AS ultimo_mant
                    FROM 
                        inventario iv
                    WHERE 
                        iv.id_area = :idarea
                        AND iv.descripcion = :descripcion
                        AND iv.estado NOT IN (5) 
                        AND iv.confirmado NOT IN (2)
                        AND iv.id_user = :id_usuario
                    GROUP BY 
                        iv.id
                    ORDER BY 
                        iv.descripcion;";
    
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':descripcion', "$descripcion", PDO::PARAM_STR);
            $preparado->bindValue(':idarea', $idarea, PDO::PARAM_INT);
            $preparado->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            if ($preparado->execute()) {
                return $preparado->fetchAll();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
        }
        $cnx = null;
    }

    public static function actualizarEstadoInventarioModel($id_inv, $desc_report) {
        try {
            // Conexión a la base de datos
            $pdo = conexion::singleton_conexion();
            
            // Consulta SQL para actualizar el estado y la observación del artículo
            $sql = "UPDATE inventario SET estado = 2, observacion = :observacion WHERE id = :id";
            $stmt = $pdo->preparar($sql);
            
            // Bind de parámetros
            $stmt->bindParam(":observacion", $desc_report, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id_inv, PDO::PARAM_INT);
            
            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public static function obtenerDatosInventarioModel($id_inv) {
        try {
            $pdo = conexion::singleton_conexion();
            $sql = "SELECT 
                inv.id, 
                inv.id_area, 
                a.nombre, 
                inv.id_user AS id_user, 
                u.nombre AS responsable
            FROM 
                inventario inv
            LEFT JOIN 
                areas a ON inv.id_area = a.id
            LEFT JOIN 
                usuarios u ON inv.id_user = u.id_user
            WHERE 
                inv.id = :id";
            $stmt = $pdo->preparar($sql);
            $stmt->bindParam(":id", $id_inv, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public static function ingresarReporteModel($id_inv, $desc_report, $id_area, $id_user, $id_log) {
        try {
            $pdo = conexion::singleton_conexion();
            $sql = "INSERT INTO reportes 
                    (id_inventario, observacion, estado, id_area, id_user, tipo_reporte, id_log) 
                    VALUES 
                    (:id_inventario, :observacion, 2, :id_area, :id_user, 1, :id_log)";
            
            $stmt = $pdo->preparar($sql);
            $stmt->bindParam(":id_inventario", $id_inv, PDO::PARAM_INT);
            $stmt->bindParam(":observacion", $desc_report, PDO::PARAM_STR);
            $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
            $stmt->bindParam(":id_user", $id_user, PDO::PARAM_INT);
            $stmt->bindParam(":id_log", $id_log, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Error SQL: " . implode(", ", $stmt->errorInfo()));
                return false;
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("PDOException en ingresarReporteModel: " . $e->getMessage());
            return false;
        }
    }
    

    public static function mostrarInventarioDescontinuadoDetalleModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT
                        iv.*, 
                        e.nombre AS estado_nombre,
                        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                        a.nombre AS nom_area
                    FROM
                        inventario iv
                    LEFT JOIN estado e ON e.id = iv.estado
                    LEFT JOIN usuarios u ON u.id_user = iv.id_user
                    LEFT JOIN areas a ON a.id = iv.id_area
                    WHERE
                        iv.estado IN (5)
                    ORDER BY iv.id DESC
                    LIMIT 60;";

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



    public static function mostrarDatosTemporalesModel($id_log, $super_empresa)
    {

        $tabla = 'inventario_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE t.*,

        COUNT(t.id) AS cantidad,

        (SELECT a.nombre FROM areas a WHERE a.id = t.id_area) AS area_nom,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(t.id_user)) AS usuario,

        (SELECT e.nombre FROM estado e WHERE e.id IN(t.estado)) AS estado

        FROM " . $tabla . " t WHERE t.estado = 1 AND t.activo = 0 AND t.user_log = :il AND t.id_super_empresa = 1

        GROUP BY t.descripcion;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':il', $id_log);

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



    public static function mostrarDatosCartaEntregaModel($usuario, $area)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE t.*,

        COUNT(t.id) AS cantidad,

        (SELECT a.nombre FROM areas a WHERE a.id = t.id_area) AS area_nom,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(t.id_user)) AS usuario,

        (SELECT e.nombre FROM estado e WHERE e.id IN(t.estado)) AS estado

        FROM " . $tabla . " t WHERE t.estado IN(1,3,8,9) AND t.activo = 1 " . $usuario . " " . $area . "

        GROUP BY t.descripcion;";

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



    public static function guardarInventarioTempModel($datos)
    {

        $tabla = 'inventario_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (descripcion, marca, modelo, precio, estado, fecha_compra, id_user, id_area, user_log, id_super_empresa

            , codigo, id_categoria, fechareg) VALUES (:d,:mr,:md,:p,:e,:fc,:iu,:ia,:ul,:ids,:cd,:idc, '" . $datos['fecha_ingreso'] . "');";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':d', $datos['descripcion']);

            $preparado->bindParam(':mr', $datos['marca']);

            $preparado->bindParam(':md', $datos['modelo']);

            $preparado->bindParam(':p', $datos['precio']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':fc', $datos['fecha_compra']);

            $preparado->bindParam(':iu', $datos['id_user']);

            $preparado->bindParam(':ia', $datos['id_area']);

            $preparado->bindParam(':ul', $datos['id_log']);

            $preparado->bindParam(':ids', $datos['super_empresa']);

            $preparado->bindParam(':cd', $datos['codigo']);

            $preparado->bindParam(':idc', $datos['id_categoria']);

            if ($preparado->execute()) {

                $id = $cnx->ultimoIngreso($tabla);

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



    public static function guardarEvidenciaTempModel($datos)
    {

        $tabla = 'evidencias_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " (nombre, id_inventario_temp, id_log, id_super_empresa)

            VALUES (:n, :idt, :il, :ids)";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':n', $datos['nombre']);

            $preparado->bindParam(':idt', $datos['id_inventario_temp']);

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



    public static function liberarArticuloModel($datos)
    {

        $tabla = 'inventario_lib';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " (id_inventario, id_log, id_user, id_area)

            VALUES (:idv, :il, :idu, :ida);

            UPDATE inventario SET estado = :e, id_user = NULL WHERE id = :idv;

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado, id_super_empresa) VALUES (:idv,:idu,:ida,:il,:e,:ids);

            INSERT INTO reportes (id_inventario, estado, id_user, id_log, id_area) VALUES (:idv,:e,:idu,:il,:ida);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':il', $datos['id_log']);

            $preparado->bindParam(':ids', $datos['id_super_empresa']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindValue(':e', 4);

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



    public static function descontinuarArticuloModel($datos)
    {

        $tabla = 'inventario_desc';

        $cnx = conexion::singleton_conexion();

        //Poner el activo == 0 para que no se muestre en la lista
        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " (id_inventario, id_log)

            VALUES (:idv, :il);

            UPDATE inventario SET estado = :e, observacion = :ob WHERE id = :idv;

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado) VALUES (:idv,:idu,:ida,:il,:e);

            INSERT INTO reportes (id_inventario, observacion, estado, id_user, id_log, id_area, fechareg) VALUES (:idv,:ob,:e,:idu,:il,:ida, :fr);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':il', $datos['id_log']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':fr', $datos['fechareg']);

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




    public static function mantenimientoArticuloModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET estado = :e, observacion = :ob WHERE id = :idv;

            INSERT INTO reportes (id_inventario, observacion, estado, id_user, id_log, tipo_reporte, fechareg, id_area)

            VALUES  (:idv,:ob,:e,:idu,:idl,:tr,:fr,:ida);

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado, id_super_empresa) VALUES (:idv,:idu,:ida,:idl,:e,:ids);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':tr', $datos['tipo_reporte']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':fr', $datos['fechareg']);

            $preparado->bindParam(':ida', $datos['id_area']);

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



    public static function reportarArticuloModel($datos)
    {   


        $tabla = 'inventario';
    
        $cnx = conexion::singleton_conexion();
    
        // Construir la consulta SQL con marcadores de posición
        $cmdsql = "UPDATE inventario 
                   SET estado = :estado, observacion = :observacion 
                   WHERE descripcion = :descripcion 
                   AND id_area = :ida 
                   AND id_user = :idu 
                   AND estado NOT IN (2,4,5,6,10) 
                   ORDER BY id DESC 
                   LIMIT :cantidad";
    
        try {
            $preparado = $cnx->preparar($cmdsql);
    
            // Bind de los parámetros
            $preparado->bindParam(':estado', $datos['estado'], PDO::PARAM_INT);
            $preparado->bindParam(':observacion', $datos['observacion'], PDO::PARAM_STR);
            $preparado->bindParam(':descripcion', $datos['nom_inventario_rep'], PDO::PARAM_STR);
            $preparado->bindParam(':ida', $datos['id_area'], PDO::PARAM_INT);
            $preparado->bindParam(':idu', $datos['id_user'], PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $datos['cantidad'], PDO::PARAM_INT);
    
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

    public static function reportarArticuloIdModel($datos)
    {   


        $tabla = 'inventario';
    
        $cnx = conexion::singleton_conexion();
    
        // Construir la consulta SQL con marcadores de posición
        $cmdsql = "UPDATE inventario 
                   SET estado = :estado, observacion = :observacion 
                   WHERE id = :id_inventario
                   AND id_area = :ida 
                   AND id_user = :idu 
                   AND estado NOT IN (2,4,5,6,10) 
                   ORDER BY id DESC 
                   LIMIT :cantidad";
    
        try {
            $preparado = $cnx->preparar($cmdsql);
    
            // Bind de los parámetros
            $preparado->bindParam(':estado', $datos['estado'], PDO::PARAM_INT);
            $preparado->bindParam(':observacion', $datos['observacion'], PDO::PARAM_STR);
            $preparado->bindParam(':id_inventario', $datos['id_inventario'], PDO::PARAM_STR);
            $preparado->bindParam(':ida', $datos['id_area'], PDO::PARAM_INT);
            $preparado->bindParam(':idu', $datos['id_user'], PDO::PARAM_INT);
            $preparado->bindParam(':cantidad', $datos['cantidad'], PDO::PARAM_INT);
    
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
    



    public static function articulosReportadosModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

        *

            FROM inventario

            WHERE descripcion = '" . $datos['nom_inventario_rep'] . "' AND estado = '" . $datos['estado'] . "' AND id_area = '" . $datos['id_area'] . "' AND id_user = '" . $datos['id_user'] . "' ORDER BY id DESC LIMIT " . $datos['cantidad'] . ";";

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



    public static function insertarReporteModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO reportes (id_inventario, observacion, cantidad, estado, id_log, tipo_reporte, id_user, id_area, fechareg, fecha_reporte)

            VALUES(:idv,:ob, :c,:e,:idl,:tr,:idu,:ida,:fr, :fr);



            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado)

            VALUES (:idv,:idu,:ida,:idl,:e);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':c', $datos['cantidad']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':tr', $datos['tipo_reporte']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':fr', $datos['fecha']);

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



    public static function trabajoCasaArticuloModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET estado = :e, observacion = :ob WHERE id = :idv;

            INSERT INTO reportes (id_inventario, observacion, estado, id_log, tipo_reporte, id_user, id_area)

            VALUES(:idv,:ob,:e,:idl,:tr,:idu,:ida);

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado, id_super_empresa)

            VALUES (:idv,:idu,:ida,:idl,:e,:ids);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':tr', $datos['tipo_reporte']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':ids', $datos['super_empresa']);

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



    public static function removerTrabajoCasaArticuloModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET estado = :e, observacion = :ob WHERE id = :idv;

            INSERT INTO reportes (id_inventario, observacion, estado, id_log, tipo_reporte, id_user, id_area)

            VALUES(:idv,:ob,:e,:idl,:tr,:idu,:ida);

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, estado, id_super_empresa)

            VALUES (:idv,:idu,:ida,:idl,:e,:ids);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':ob', $datos['observacion']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':tr', $datos['tipo_reporte']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':ids', $datos['super_empresa']);

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



    public static function informacionReporteModel($id)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            rp.id,

            rp.id_log,

            rp.id_inventario,

            (SELECT iv.descripcion FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS descripcion,

            (SELECT iv.marca FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS marca,

            (SELECT iv.modelo FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS modelo,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(rp.id_user)) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id IN(rp.id_area)) AS area,

            (SELECT iv.id_area FROM inventario iv WHERE iv.id IN(rp.id_inventario)) AS id_area,

            (SELECT e.nombre FROM estado e WHERE e.id IN(rp.estado)) AS estado,

            rp.observacion,

            rp.fechareg

            FROM " . $tabla . " rp WHERE rp.id_inventario = :idv ORDER BY rp.id DESC LIMIT 1;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':idv', $id);

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



    public static function mostrarArticulosLiberadosModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT
                    iv.*,
                    a.nombre AS area,
                    e.nombre AS nombre_estado
                FROM
                    $tabla iv
                LEFT JOIN areas a ON a.id = iv.id_area
                LEFT JOIN estado e ON e.id = iv.estado
                WHERE
                    iv.estado = 4
                    AND iv.activo = 1;
";

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




    public static function reasignarArticuloModel($datos)
    {

        $tabla = 'inventario_log';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " (id_inventario, id_user, id_area, id_log, id_super_empresa, estado)

            VALUES (:idv,:idu,:ida,:idl,:ids,:e);

            UPDATE inventario SET id_user = :idu, id_area = :ida, estado = :e, observacion = 'RE-ASIGNADO' WHERE id = :idv;

            INSERT INTO reportes (id_inventario, estado, id_user, id_log, id_resp, id_reporte, fecha_respuesta)

            VALUES (:idv,:e,:idu,:idl,:idr,:idp,:fr);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idv', $datos['id_inventario']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':ids', $datos['id_super_empresa']);

            $preparado->bindValue(':e', 1);

            $preparado->bindParam(':idr', $datos['id_log']);

            $preparado->bindParam(':idp', $datos['id_reporte']);

            $preparado->bindParam(':fr', $datos['fecha_respuesta']);

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




    public static function mostrarEquipoComputoModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

        (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS nom_area,

        (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

        (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

        IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1) IS NULL, iv.fechareg,

            (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1)) AS ultimo_mant

        FROM inventario iv

        WHERE iv.activo = 1
    AND iv.id_categoria = 1 
        AND iv.estado IN (1,2,3,6,7,10)
        AND (iv.descripcion LIKE 'IMPRESORA%'
        || iv.descripcion LIKE 'COMPUTADOR%'
        || iv.descripcion LIKE 'PORTATIL%'
        || iv.descripcion LIKE 'VIDEO BEAM%') 
        ORDER BY iv.id DESC;";

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



    public static function mostrarEquipoTodosComputoModel($fecha, $id_categoria)
    {
        ##############################################################################################################################

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT
  SQL_NO_CACHE iv.*,
  CONCAT(u.nombre, ' ', u.apellido) AS usuario,
  a.nombre AS nom_area,
  h.frecuencia_mantenimiento AS frecuencia,
  h.frecuencia_copia AS frecuencia_copia,
  COALESCE(r_last.ultimo_mant, iv.fechareg) AS ultimo_mant,
  TIMESTAMPDIFF(MONTH, h.fecha_adquisicion, h.fecha_garantia) AS garantia,
  TIMESTAMPDIFF(MONTH, h.fecha_adquisicion, CURRENT_DATE) AS tiempo
FROM inventario iv
LEFT JOIN usuarios u ON u.id_user = iv.id_user
LEFT JOIN areas a ON a.id = iv.id_area
LEFT JOIN hoja_vida h ON h.id_inventario = iv.id
LEFT JOIN (
    SELECT id_inventario, MAX(fechareg) AS ultimo_mant
    FROM reportes
    WHERE estado = 6
    GROUP BY id_inventario
) r_last ON r_last.id_inventario = iv.id
WHERE
  iv.id_categoria = $id_categoria
  AND iv.estado IN (1,3)
ORDER BY iv.id DESC;";
            

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

    public static function getAiresMantenimientos($fecha,$idInventario)
    {
        ##############################################################################################################################

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

        (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

        (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS nom_area,

        (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

        (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

        IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1) IS NULL, iv.fechareg,

            (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1)) AS ultimo_mant,

        (SELECT TIMESTAMPDIFF(MONTH, h.fecha_adquisicion, h.fecha_garantia) FROM hoja_vida AS h WHERE h.id_inventario = iv.id) AS garantia,
        
        (SELECT TIMESTAMPDIFF(MONTH, h.fecha_adquisicion, CURRENT_DATE) FROM hoja_vida AS h WHERE h.id_inventario = iv.id) AS tiempo

        FROM inventario iv

        WHERE iv.activo = 1
        AND iv.id =$idInventario
        AND iv.estado IN(1,2,3,6,7,10)
        AND iv.fechareg <= '" . $fecha . "'
        GROUP BY iv.descripcion, iv.estado
        ORDER BY iv.id DESC;";
        var_dump($cmdsql);
        die();
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



    public static function mostrarFechasMantenimientosModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1) IS NULL, '',

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1)) AS ultimo_mant

            FROM " . $tabla . " iv

            LEFT JOIN reportes rp ON rp.id_inventario = iv.id

            WHERE iv.id_categoria = 1 AND iv.estado NOT IN(5, 7, 2, 4, 6) AND iv.id_categoria = 1 AND iv.id_user NOT IN(0, '')

            AND iv.descripcion NOT IN('Aire acondicionado', 'Televisor', 'Pagina Web', 'HUELLERO') AND iv.confirmado = 1 GROUP BY YEAR(ultimo_mant), MONTH(ultimo_mant) ORDER BY iv.id, rp.id DESC;";

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



    public static function mostrarFechasMantenimientosTodosModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT r.* FROM reportes r

            WHERE r.id_inventario IN(SELECT iv.id FROM inventario iv WHERE iv.id_categoria = 1 AND iv.estado NOT IN(5, 7, 2, 4, 6) AND iv.id_categoria = 1 AND iv.id_user NOT IN(0, '')

                AND iv.descripcion NOT IN('Aire acondicionado', 'Televisor', 'Pagina Web', 'HUELLERO') AND iv.confirmado = 1) AND r.estado = 6

            GROUP BY YEAR(r.fechareg), MONTH(r.fechareg);";

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



    public static function mostrarFechasCopiasSeguridadModel()
    {

        $tabla = 'copia_seguridad';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

            c.fecha

            FROM copia_seguridad c

            GROUP BY YEAR(c.fecha), MONTH(c.fecha);

            ORDER BY c.fecha ASC;";

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



    public static function mostrarDatosEquipoComputoModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            CONCAT(u.nombre, ' ', u.apellido) AS usuario,

            ar.nombre AS nom_area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1) IS NULL, iv.fechareg,

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY r.fechareg DESC LIMIT 1)) AS ultimo_mant

            FROM inventario iv

            LEFT JOIN usuarios u ON u.id_user = iv.id_user

            LEFT JOIN areas ar ON ar.id = iv.id_area

            WHERE CONCAT(iv.descripcion) LIKE '%" . $datos['buscar'] . "%'

            " . $datos['area'] . "

            " . $datos['usuario'] . "

            AND iv.id_categoria = 1 AND iv.estado NOT IN(5) ORDER BY iv.id DESC;

            ";

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



    public static function mostrarDatosArticulosModel($super_empresa)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area

            FROM " . $tabla . " iv WHERE iv.id_super_empresa = :ids ORDER BY id DESC LIMIT 100";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ids', $super_empresa);

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



    public static function mostrarDatosArticuloIdModel($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS area,

            (SELECT c.nombre FROM categoria c WHERE c.id IN(iv.id_categoria)) AS nom_categoria,

            (SELECT ev.nombre FROM evidencias ev WHERE ev.id_inventario = iv.id) AS evidencia,

            (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.id_reporte IS NULL ORDER BY r.id DESC LIMIT 1) AS fecha_reporte,

            (SELECT (SELECT re.fecha_respuesta FROM reportes re WHERE re.id_reporte = r.id AND re.tipo_reporte = 1 AND re.id_inventario = iv.id)

                FROM reportes r WHERE r.id_reporte IS NULL AND r.id_inventario = iv.id ORDER BY r.id DESC LIMIT 1) AS fecha_respuesta

            FROM " . $tabla . " iv

            WHERE iv.id = :id";

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



    public static function mostrarDatosAgrupadosArticulosModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT

            iv.*,

            COUNT(iv.id) AS cantidad,

            a.nombre AS area,

            CONCAT(u.nombre, ' ', u.apellido) AS usuario,

            (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) as nom_estado,

            (SELECT rp.fechareg FROM reportes rp WHERE rp.id_inventario = iv.id AND rp.estado = iv.estado ORDER BY rp.id DESC LIMIT 1) AS fecha_reporte

            FROM inventario iv

            LEFT JOIN areas a ON a.id = iv.id_area

            LEFT JOIN usuarios u ON u.id_user = iv.id_user

            WHERE iv.descripcion = '" . $datos['nom_inventario_rep'] . "'

            AND iv.id_area = " . $datos['id_area'] . "

            AND iv.id_user = " . $datos['id_user'] . "

            AND iv.estado = " . $datos['estado'] . "

            GROUP BY iv.descripcion ORDER BY iv.id DESC;";
            

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

    public static function mostrarDatosReporteImprimirModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = " SELECT
            iv.marca,
            iv.id,
            iv.descripcion,
            a.nombre AS AREA,
            CONCAT(u.nombre, ' ', u.apellido) AS usuario,
            (
                SELECT
                    e.nombre
                FROM
                    estado e
                WHERE
                    e.id = iv.estado) AS nom_estado,
            rp.id AS reporte_id,
            rp.fechareg AS fecha_reporte,
            rp.observacion
        FROM inventario iv
        INNER JOIN areas a ON a.id = iv.id_area
        INNER JOIN usuarios u ON u.id_user = iv.id_user
        INNER JOIN reportes rp ON iv.id = rp.id_inventario WHERE rp.id = " . $datos['id_reporte'] . ";";
            

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

    public static function mostrarDatosArticulosSinAgruparModel($datos)
{
    $tabla = 'inventario';

    $cnx = conexion::singleton_conexion();

    $cmdsql = "SELECT
        iv.*,
        a.nombre AS area,
        CONCAT(u.nombre, ' ', u.apellido) AS usuario,
        (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) as nom_estado,
        (SELECT rp.fechareg FROM reportes rp WHERE rp.id_inventario = iv.id AND rp.estado = iv.estado ORDER BY rp.id DESC LIMIT 1) AS fecha_reporte
        FROM inventario iv
        LEFT JOIN areas a ON a.id = iv.id_area
        LEFT JOIN usuarios u ON u.id_user = iv.id_user
        WHERE iv.descripcion = :nom_inventario_rep
        AND iv.id_area = :id_area
        AND iv.id_user = :id_user
        AND iv.estado = :estado
        ORDER BY iv.id DESC;";

    try {
        $preparado = $cnx->preparar($cmdsql);

        // Bind de parámetros
        $preparado->bindParam(':nom_inventario_rep', $datos['nom_inventario_rep']);
        $preparado->bindParam(':id_area', $datos['id_area']);
        $preparado->bindParam(':id_user', $datos['id_user']);
        $preparado->bindParam(':estado', $datos['estado']);

        if ($preparado->execute()) {
            return $preparado->fetchAll(); // Devolver todos los resultados
        } else {
            return false;
        }
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage();
    }

    $cnx->closed();
    $cnx = null;
}




    public static function historialArticuloModel($id)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE *,

            r.observacion AS observacion_reporte,

            r.fechareg AS fecha_reporte,

            r.estado AS estado_reporte,

            r.id AS id_reportado,

            (SELECT CONCAT(u.nombre, ' ' , u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario,

            (SELECT e.nombre FROM estado e WHERE e.id = r.estado) AS estado_nombre,

            (SELECT a.nombre FROM areas a WHERE a.id = r.id_area) AS area

            FROM " . $tabla . " r

            LEFT JOIN inventario iv ON r.id_inventario = iv.id

            WHERE r.id_inventario = :id ORDER by r.fechareg DESC;

            ";

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



    public static function buscarReporteLiberadoModel($id)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_inventario = :id AND estado = 4 ORDER BY id DESC LIMIT 1;";

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



    public static function historialReportesModel($super_empresa)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE *,

            r.id as id_reporte_inicio,

            r.estado as estado_reporte,

            r.observacion AS observacion_reporte,

            r.fechareg AS fecha_reporte,

            (SELECT CONCAT(u.nombre, ' ' , u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario,

            (SELECT e.nombre FROM estado e WHERE e.id = r.estado) AS estado_nombre,

            (SELECT a.nombre FROM areas a WHERE a.id = r.id_area) AS area

            FROM " . $tabla . " r

            LEFT JOIN inventario iv ON r.id_inventario = iv.id

            WHERE iv.id_super_empresa = :id ORDER BY r.fecha_respuesta DESC, r.fechareg DESC LIMIT 20";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $super_empresa);

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



    public static function buscarHistorialReportesControl($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE *,

            r.id as id_reporte_inicio,

            r.estado as estado_reporte,

            r.observacion AS observacion_reporte,

            r.fechareg AS fecha_reporte,

            (SELECT CONCAT(u.nombre, ' ' , u.apellido) FROM usuarios u WHERE u.id_user = r.id_user) AS usuario,

            (SELECT e.nombre FROM estado e WHERE e.id = r.estado) AS estado_nombre,

            (SELECT a.nombre FROM areas a WHERE a.id = r.id_area) AS area

            FROM " . $tabla . " r

            LEFT JOIN inventario iv ON r.id_inventario = iv.id

            LEFT JOIN usuarios u ON u.id_user = iv.id_user

            WHERE CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', iv.descripcion, ' ', iv.id,' ') LIKE '%" . $datos['buscar'] . "%' " . $datos['usuario'] . " " . $datos['area'] . "

            ORDER BY r.fecha_respuesta DESC, r.fechareg DESC;";

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



    public static function programarMantenimientoArticuloModel($datos)
    {

        $tabla = 'hoja_vida';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET frecuencia_mantenimiento = :fm, frecuencia_copia = :fc, fecha_update = NOW() WHERE id_inventario = :id";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $datos['id_inventario']);

            $preparado->bindValue(':fm', $datos['frec_mant']);

            $preparado->bindValue(':fc', $datos['frec_copia']);

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



    public static function articulosComputoAreaModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1) IS NULL, iv.fechareg,

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1)) AS ultimo_mant

            FROM " . $tabla . " iv

            LEFT JOIN usuarios u ON u.id_user = iv.id_user

            LEFT JOIN areas ar ON ar.id = iv.id_area

            WHERE CONCAT(iv.descripcion, ' ', u.nombre, ' ', u.apellido, ' ', ar.nombre) LIKE '%" . $datos['buscar'] . "%'

            " . $datos['area'] . "

            " . $datos['usuario'] . "

            AND iv.id_categoria = 1 ORDER BY iv.id DESC;";



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



    public static function mostrarArticulosComputoAreaModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT h.frecuencia_copia FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia_copia,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1) IS NULL, iv.fechareg,

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1)) AS ultimo_mant

            FROM " . $tabla . " iv WHERE iv.id_categoria = 1 ORDER BY iv.id DESC LIMIT 30;";

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



    public static function mostrarArticulosUsuarioModel($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            iv.*,

            COUNT(iv.id) AS cantidad,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) AS nombre_estado,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1) IS NULL, iv.fechareg,

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1)) AS ultimo_mant

            FROM inventario iv

            WHERE iv.id_user = :id AND iv.estado NOT IN(5) AND iv.confirmado NOT IN(2)

            GROUP BY iv.descripcion

            ORDER BY iv.descripcion LIMIT 400;";

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



    public static function mostrarArticulosBuscarUsuarioModel($id, $buscar)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            iv.*,

            COUNT(iv.id) AS cantidad,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) AS nombre_estado,

            IF((SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1) IS NULL, iv.fechareg,

                (SELECT r.fechareg FROM reportes r WHERE r.id_inventario = iv.id AND r.estado = 6 ORDER BY id DESC LIMIT 1)) AS ultimo_mant

            FROM inventario iv

            WHERE iv.id_user = :id AND iv.estado NOT IN(5) and concat(iv.descripcion , ' ', iv.marca, ' ', iv.modelo) like '%" . $buscar . "%'

            GROUP BY iv.descripcion, iv.estado

            ORDER BY iv.descripcion;";

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



    public static function editarInventarioModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET descripcion = :d, marca = :mr, modelo = :md, precio = :pr, id_user = :idu, id_area = :ida, id_categoria = :idc, codigo = :c

            WHERE id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':id', $datos['id_inventario']);

            $preparado->bindParam(':d', $datos['descripcion']);

            $preparado->bindParam(':mr', $datos['marca']);

            $preparado->bindParam(':md', $datos['modelo']);

            $preparado->bindParam(':pr', $datos['precio']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':idc', $datos['id_categoria']);

            $preparado->bindParam(':c', $datos['codigo']);

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



    public static function buscarAreaUsuarioControl($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE id_area FROM " . $tabla . " WHERE id_user = :id GROUP BY id_area;";

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



    public static function agregarMaterialTempModel($datos)
    {

        $tabla = 'inventario_temp';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO " . $tabla . " (descripcion, estado, id_user, id_area, id_categoria, user_log, id_super_empresa, codigo, observacion)

            VALUES (:d, :e, :idu, :ida, :idc, :ul, :ids, :c, :ob);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':d', $datos['descripcion']);

            $preparado->bindParam(':e', $datos['estado']);

            $preparado->bindParam(':idu', $datos['id_user']);

            $preparado->bindParam(':ida', $datos['id_area']);

            $preparado->bindParam(':idc', $datos['id_categoria']);

            $preparado->bindParam(':ul', $datos['id_log']);

            $preparado->bindParam(':ids', $datos['id_super_empresa']);

            $preparado->bindParam(':c', $datos['codigo']);

            $preparado->bindParam(':ob', $datos['observacion']);

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



    public static function mostrarMaterialDidacticoModel($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) AS nombre_estado,

            COUNT(id) AS cantidad

            FROM " . $tabla . " iv WHERE iv.id_super_empresa = :ids AND iv.id_categoria = 6 GROUP BY iv.descripcion, iv.id_user, iv.estado;;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ids', $id);

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



    public static function guardarMaterialControl($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO inventario SELECT * FROM inventario_temp WHERE id_super_empresa = :ids AND user_log = :idl AND id_categoria = 6;

            INSERT INTO inventario_log (id_inventario, id_user, id_area, id_log, id_super_empresa, estado)

            SELECT it.id, it.id_user, it.id_area, it.user_log, it.id_super_empresa, it.estado FROM inventario it WHERE it.id_super_empresa = :ids

            AND it.user_log = :idl AND it.id_categoria = 6;

            DELETE FROM inventario_temp WHERE id_super_empresa = :ids AND user_log = :idl AND id_categoria = 6;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':idl', $datos['id_log']);

            $preparado->bindValue(':ids', $datos['id_super_empresa']);

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



    public static function trabajoCasaModel($super_empresa)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area,

            (SELECT h.frecuencia_mantenimiento FROM hoja_vida h WHERE h.id_inventario = iv.id) AS frecuencia,

            (SELECT e.nombre FROM estado e WHERE e.id = iv.estado) AS nombre_estado

            FROM " . $tabla . " iv WHERE iv.estado = 8 AND iv.id_super_empresa = :ids";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ids', $super_empresa);

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



    public static function mostrarCantidadesInventarioModel($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            iv.*,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user = iv.id_user) AS usuario,

            (SELECT a.nombre FROM areas a WHERE a.id = iv.id_area) AS area_nom,

            COUNT(iv.id) AS cantidad

            FROM " . $tabla . " iv WHERE iv.id_user = :id AND iv.estado NOT IN (2,6,5) GROUP BY iv.descripcion;";

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



    public static function confirmarInventarioModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE inventario SET confirmado = 1, observacion = '' WHERE id_area = :ida AND confirmado NOT IN(2) AND descripcion LIKE '%" . $datos['descripcion'] . "%';";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ida', $datos['id_area']);

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



    public static function confirmarAgregarInventarioModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE inventario SET confirmado = 1, observacion = '' WHERE id_area = :ida AND descripcion LIKE '%" . $datos['descripcion'] . "%';";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ida', $datos['id_area']);

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



    public static function noConfirmarInventarioModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "UPDATE inventario SET confirmado = 2, observacion = :ob WHERE id_area = :ida AND id_user = :idu AND descripcion = :d;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindValue(':ida', $datos['id_area']);

            $preparado->bindValue(':idu', $datos['id_log']);

            $preparado->bindValue(':d', $datos['descripcion']);

            $preparado->bindValue(':ob', $datos['observacion']);

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



    public static function mostrarCantidadesModel($id)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE COUNT(id) AS cantidad,

            (SELECT COUNT(id) FROM inventario WHERE id_user = :id AND confirmado IN(1,2) AND estado NOT IN(5,6)) AS cantidad_confirmada

            FROM inventario WHERE id_user = :id AND estado NOT IN(5,6);";

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



    public static function mostrarFirmaUsuarioModel($id)
    {

        $tabla = 'firmas';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_user = :id;";

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



    public static function buscarInventarioNoConfirmadoModel($datos)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            iv.*,

            (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS estado_nombre,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS usuario,

            COUNT(iv.id) AS cantidad,

            (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS area

            FROM " . $tabla . " iv

            LEFT JOIN usuarios u ON u.id_user = iv.id_user

            WHERE iv.activo = 1 AND iv.confirmado = 2 AND iv.estado NOT IN(2,5,6) AND

            CONCAT(u.nombre, ' ', u.apellido, ' ', u.documento, ' ', iv.descripcion, ' ', iv.codigo, ' ', iv.id) LIKE '%" . $datos['buscar'] . "%'

            " . $datos['area'] . " " . $datos['usuario'] . "

            GROUP BY iv.descripcion, iv.id_area;";

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



    public static function mostrarInventarioNoConfirmadoModel()
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            iv.*,

            (SELECT e.nombre FROM estado e WHERE e.id IN(iv.estado)) AS estado_nombre,

            (SELECT CONCAT(u.nombre, ' ', u.apellido) FROM usuarios u WHERE u.id_user IN(iv.id_user)) AS usuario,

            COUNT(iv.id) AS cantidad,

            (SELECT a.nombre FROM areas a WHERE a.id IN(iv.id_area)) AS area

            FROM " . $tabla . " iv WHERE iv.activo = 1 AND iv.confirmado = 2 AND iv.estado NOT IN(2,5,6) GROUP BY iv.descripcion, iv.id_area LIMIT 30;";

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


//Consulta que me trae los reportes pendientes de mantenimiento preventivo para la grafica
    public static function cantidadesSolucionadasModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE COUNT(id) as solucion FROM " . $tabla . " 
        WHERE id_reporte IN(SELECT id 
        FROM " . $tabla . " 
        WHERE estado = " . $datos['tipo'] . ") 
        AND fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00' 
        AND fechareg <= '" . $datos['fecha_fin'] . " 23:59:59' 
        AND id_inventario 
        IN(SELECT iv.id FROM inventario iv WHERE iv.id_categoria IN(1,5));";

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


//Consulta que me trae los reportes de mantenimiento de daño  para la grafica de sistema
    public static function cantidadesPendienteModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE COUNT(r.id) AS cantidad 
        FROM reportes r 
        WHERE r.estado = " . $datos['tipo'] . " 
        AND r.id_inventario 
        IN(SELECT iv.id FROM inventario iv WHERE iv.id_categoria IN(1,5) AND iv.estado NOT IN(5)) 
        AND r.fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00' 
        AND r.fechareg <= '" . $datos['fecha_fin'] . " 23:59:59' 
        AND r.id NOT IN(SELECT p.id_reporte 
        FROM reportes p WHERE p.estado = 3);";

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



    public static function cantidadesGeneralSolucionadasModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

            COUNT(id) AS solucion

            FROM

            reportes

            WHERE id_reporte IN

            (

                SELECT id

                FROM

                reportes

                WHERE estado = " . $datos['tipo'] . ")

            AND fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00'

            AND fechareg <= '" . $datos['fecha_fin'] . " 23:59:59'

            AND id_inventario IN

            (SELECT

            iv.id

            FROM

            inventario iv

             WHERE iv.id_categoria NOT IN (1, 5) AND estado NOT IN(5,4,10)

        );";

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

//Graficas de los mantenimientos correctivos

    public static function cantidadesGeneralPendientesModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE COUNT(r.id) AS cantidad 
        FROM reportes r 
        WHERE r.estado = " . $datos['tipo'] . " 
        AND r.id_inventario 
        IN(SELECT iv.id FROM inventario iv WHERE iv.id_categoria NOT IN(1,5) AND iv.activo = 1 AND iv.estado NOT IN(5)) 
        AND r.fechareg >= '" . $datos['fecha_inicio'] . " 00:00:00' 
        AND r.fechareg <= '" . $datos['fecha_fin'] . " 23:59:59' 
        AND r.id 
        NOT IN(SELECT p.id_reporte FROM reportes p WHERE p.estado = 3);";

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



    public static function agregarInventarioPendienteModel($id, $codigo)
    {

        $tabla = 'inventario';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT HIGH_PRIORITY INTO inventario (descripcion, marca, modelo, precio, estado, activo, fecha_compra, observacion, id_user, id_area, id_categoria, user_log, confirmado, id_super_empresa, codigo)

        SELECT descripcion, marca, modelo, precio, estado, activo, fecha_compra, observacion, id_user, id_area, id_categoria, user_log, 2, id_super_empresa, " . $codigo . "

        FROM inventario

        WHERE id = " . $id;

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



    public static function copiaSeguridadArticuloModel($datos)
    {

        $tabla = 'copia_seguridad';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (

            id_area,

            id_user,

            id_inventario,

            fecha,

            id_log,

            observacion)

        VALUES

        (

            '" . $datos['id_area'] . "',

            '" . $datos['id_user'] . "',

            '" . $datos['id_inventario'] . "',

            '" . $datos['fecha'] . "',

            '" . $datos['id_log'] . "',

            '" . $datos['observacion'] . "');";

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


    public static function getHistorialMantenimiento()
    {
        $cnx = conexion::singleton_conexion();

        $sql = "   SELECT r.id,r.observacion,r.estado,r.fechareg, COALESCE(r.descripcion, 'SIN DESCRIPCION') AS descripcion, iv.descripcion as inventario  FROM reportes r
        INNER JOIN inventario iv
        ON iv.id = r.id_inventario
        
        WHERE iv.id_categoria=1
        AND r.estado=6
        ORDER BY r.id DESC;";

        try {

            $preparado = $cnx->preparar($sql);

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

    public static function getHistorialMantenimientoAires()
    {
        $cnx = conexion::singleton_conexion();

        $sql = "SELECT r.id,
        r.observacion,
        r.estado,
        r.fechareg, 
        COALESCE(r.descripcion, 'SIN DESCRIPCION') AS descripcion, 
        iv.descripcion as inventario  
        FROM reportes r

        INNER JOIN inventario iv
        ON iv.id = r.id_inventario
        
        WHERE r.estado=6
        AND iv.descripcion LIKE 'aire%%'
        ORDER BY r.id DESC;";

        try {

            $preparado = $cnx->preparar($sql);

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

    public static function registrarMantenimientosModel($datos)
    {

        $tabla = 'reportes';

        $descripcion = $datos['descripcion'];

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO reportes (id_inventario, observacion, estado, id_area, id_user, id_log, tipo_reporte, fechareg, descripcion) VALUES (

                '" . $datos['id_inventario'] . "',

                'Manteniento Preventivo',

                '" . $datos['estado'] . "',

                '" . $datos['id_area'] . "',

                '" . $datos['id_user'] . "',

                '" . $datos['id_log'] . "',

                '" . $datos['tipo_reporte'] . "',

                '" . $datos['fechareg'] . "',

                '" . $datos['descripcion'] . "');";

        try {

            $preparado = $cnx->preparar($cmdsql);

            if ($preparado->execute()) {

                $id = $cnx->ultimoIngreso($tabla);

                $rslt = array('guardar' => true, 'id' => $id);

                return $rslt;
            } else {

                return false;
            }
        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage();
        }

        $cnx->closed();

        $cnx = null;
    }



    public static function registrarSolucionMantenimientoControl($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO reportes (id_inventario, observacion, estado, id_area, id_user, id_log, id_resp, id_reporte, tipo_reporte, fecha_respuesta, fechareg) VALUES (

                    '" . $datos['id_inventario'] . "',

                    'Mantenimiento Preventivo (Solucion)',

                    '" . $datos['estado'] . "',

                    '" . $datos['id_area'] . "',

                    '" . $datos['id_user'] . "',

                    '" . $datos['id_log'] . "',

                    '" . $datos['id_resp'] . "',

                    '" . $datos['id_reporte'] . "',

                    '" . $datos['tipo_reporte'] . "',

                    '" . $datos['fecha_respuesta'] . "',

                    '" . $datos['fechareg'] . "');";

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



    public static function comandoSQL()
    {

        $cnx = conexion::singleton_conexion();

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

    public static function programarCopiaSeguridadOficina($data)
    {
        $id_area = $data['id_area'];
        $id_user = $data['id_user'];
        $observacion = $data['observacion'];
        $fecha = $data['fecha'];
        $estado = $data['estado'];
        $cnx = conexion::singleton_conexion();
        $sql = "INSERT INTO copia_seguridad_oficina (
            id_area,
            id_user,
            observacion,
            fecha_programacion,
            fecha_registro,
            estado
        )
        VALUE(
            $id_area,
            $id_user,
            '$observacion',
            '$fecha',
            CURRENT_TIMESTAMP,
            $estado

        )";

        try {

            $preparado = $cnx->preparar($sql);

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

    public static function actualizarDatosInventarioModel($datos){
        $tabla = 'inventario';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla 
                    SET descripcion = :descripcion, marca = :marca, modelo = :modelo, codigo = :codigo, id_categoria = :categoria, frecuencia_mantenimiento = :frecuencia_mantenimiento 
                    WHERE id = :id_inventario;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':descripcion', $datos['descripcion']);
            $preparado->bindParam(':marca', $datos['marca']);
            $preparado->bindParam(':modelo', $datos['modelo']);
            $preparado->bindParam(':codigo', $datos['codigo']);
            $preparado->bindParam(':categoria', $datos['categoria']);
            $preparado->bindParam(':frecuencia_mantenimiento', $datos['frecuencia_mantenimiento']);
            $preparado->bindParam(':id_inventario', $datos['id_inventario']);
            if($preparado->execute()){
                return true;
            }else{
                return false;
            }
        }
        catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerReporteDeInventarioModel($id_inventario){
        $cnx = conexion::singleton_conexion();
        $sql = "SELECT * FROM reportes r WHERE r.id_inventario = $id_inventario AND r.fecha_respuesta IS NULL
                ORDER BY fechareg DESC
                LIMIT 1;
                ";
        try{
            $preparado = $cnx->preparar($sql);
            if($preparado->execute()){
                $resultado = $preparado->fetch();
                return $resultado;
            }else{
                return false;
            }
        }
        catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function obtenerInventarioPorIdCategoriaModel($id_categoria){
        $tabla = "inventario";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "select * from $tabla where id_categoria = $id_categoria";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
    }

    public static function mostrarEquiposEnMantenimientoPreventivo()
    {
        $tabla = "reportes";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT r.*,
                        i.descripcion AS nom_inventario,
                        a.nombre AS nom_area,
                        CONCAT(u.nombre) AS nom_user,
                        i.modelo, 
                        i.codigo,
                        i.marca,
                        i.id_categoria,
                        e.nombre AS nom_estado
                    FROM $tabla r
                    LEFT JOIN reportes r2 ON r2.id_reporte = r.id 
                    LEFT JOIN inventario i ON r.id_inventario = i.id 
                    LEFT JOIN areas a ON a.id = i.id_area 
                    LEFT JOIN usuarios u ON u.id_user = i.id_user 
                    LEFT JOIN estado e ON r.estado = e.id 
                    LEFT JOIN categoria c ON i.id_categoria = c.id
                    WHERE r.estado = 6 AND r2.id IS NULL
                    ORDER BY r.fechareg ASC;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            if ($preparado->execute()) {
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            print "Error al mostrar los equipos en mantenimiento preventivo: " . $e->getMessage();
        }
    }

    public static function registrarSolucionMantenimientoHebreoModel($datos)
    {

        $tabla = 'reportes';

        $cnx = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO reportes (id_inventario, observacion, estado, id_area, id_user, id_log, id_resp, id_reporte, tipo_reporte, fecha_respuesta, fechareg) VALUES (

                    '" . $datos['id_inventario'] . "',

                    'Mantenimiento Preventivo (Solucion)',

                    '" . $datos['estado'] . "',

                    '" . $datos['id_area'] . "',

                    '" . $datos['id_user'] . "',

                    '" . $datos['id_log'] . "',

                    '" . $datos['id_resp'] . "',

                    '" . $datos['id_reporte'] . "',

                    '" . $datos['tipo_reporte'] . "',

                    '" . $datos['fecha_respuesta'] . "',

                    '" . $datos['fechareg'] . "');";

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

    public static function getHistorialMantenimientoModel(){
        $tabla = "reportes";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT r.id,r.observacion,r.estado,r.fechareg,
                    COALESCE(r.descripcion, 'SIN DESCRIPCION') AS descripcion, 
                    iv.descripcion as inventario,
                    iv.id AS id_inventario
                    FROM $tabla r
                    INNER JOIN inventario iv ON iv.id = r.id_inventario
                    AND r.estado = 6
                    ORDER BY r.id DESC
                    LIMIT 50;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error al recuperar el historial: " . $e->getMessage();
        }
    }
    public static function getHistorialMantenimientoFiltradoModel($anio, $categoria){
        
        $tabla = "reportes";
        $cnx = conexion::singleton_conexion();



        $cmdsql = "SELECT r.id,r.observacion,r.estado,r.fechareg,
                    COALESCE(r.descripcion, 'SIN DESCRIPCION') AS descripcion, 
                    iv.descripcion as inventario,
                    iv.id AS id_inventario
                    FROM $tabla r
                    INNER JOIN inventario iv ON iv.id = r.id_inventario
                    WHERE r.estado = 6 
                    $anio 
                    $categoria
                    ORDER BY r.id DESC";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error al recuperar el historial: " . $e->getMessage();
        }
    }
}
