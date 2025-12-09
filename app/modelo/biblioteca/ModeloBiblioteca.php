<?php

require_once MODELO_PATH . 'conexion.php';



class ModeloBiblioteca extends conexion

{

    public static function mostrarCategoriasBibliotecaModel()

    {

        $tabla  = 'biblioteca_categorias';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1;";

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



    public static function cantidadEjemplaresDisponiblesModel()

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        COUNT(id) AS cantidad_disponible

        FROM biblioteca_ejemplares

        WHERE estado = 1;";

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



    public static function cantidadEjemplaresDisponiblesLibroModel($id)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        COUNT(id) AS cantidad_disponible

        FROM biblioteca_ejemplares

        WHERE estado = 1 AND id_libro = :id;";

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



    public static function cantidadEjemplaresPrestadosLibroModel($id)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        COUNT(id) AS cantidad_prestado

        FROM biblioteca_ejemplares

        WHERE estado = 2 AND id_libro = :id;";

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



    public static function cantidadEjemplaresPrestadosModel()

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

        COUNT(id) AS cantidad_prestado

        FROM biblioteca_ejemplares

        WHERE estado = 2;";

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



    public static function cargarSubcategoriasModel($id)

    {

        $tabla  = 'biblioteca_subcategoria';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 AND id_categoria = :id;";

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



    public static function mostrarEjemplaresModel($id)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_libro = :id AND estado NOT IN(4);";

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



    public static function registrarLibroModel($datos)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (

            titulo,

            autor,

            editorial,

            edicion,

            id_categoria,

            id_subcategoria,

            observacion,

            foto,

            id_log

            )

        VALUES

        (

            '" . $datos['titulo'] . "',

            '" . $datos['autor'] . "',

            '" . $datos['editorial'] . "',

            '" . $datos['edicion'] . "',

            '" . $datos['id_categoria'] . "',

            '" . $datos['id_subcategoria'] . "',

            '" . $datos['observacion'] . "',

            '" . $datos['foto'] . "',

            '" . $datos['id_log'] . "'

            );

            ";

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



    public static function actualizarLibroModel($datos)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE

            " . $tabla . "

            SET

            titulo = '" . $datos['titulo'] . "',

            autor = '" . $datos['autor'] . "',

            editorial = '" . $datos['editorial'] . "',

            edicion = '" . $datos['edicion'] . "',

            id_categoria = '" . $datos['id_categoria'] . "',

            id_subcategoria = '" . $datos['id_subcategoria'] . "',

            observacion = '" . $datos['observacion'] . "',

            foto = '" . $datos['foto'] . "',

            id_log_act = '" . $datos['id_log'] . "',

            fecha_actualizacion = NOW()

            WHERE id = :id;

            ";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $datos['id_libro']);

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



    public static function registrarEjemplarModel($datos)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (

                id_libro,

                estado,

                id_log

                )

            VALUES

            (

                '" . $datos['id_libro'] . "',

                '" . $datos['estado'] . "',

                '" . $datos['id_log'] . "'

                );

                ";

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



    public static function codigoEjemplarModel($id, $codigo)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET codigo = '" . $codigo . "' WHERE id = :id;";

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



    public static function buscarLibrosModel($datos)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                bl.*,

                bc.nombre AS nom_categoria,

                bs.nombre AS nom_subcategoria,

                (SELECT COUNT(bj.id) FROM biblioteca_ejemplares bj WHERE bj.id_libro = bl.id and bj.estado NOT in(5)) AS cantidad_ejemplares

                FROM " . $tabla . " bl

                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                WHERE bl.activo = 1 AND CONCAT(bl.titulo, ' ', bl.autor, ' ', bl.editorial, ' ', bc.nombre, ' ') LIKE '%" . $datos['buscar'] . "%'" . " " . $datos['categoria'] . " ". $datos['subcategoria'] . ";";

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



    public static function mostrarLibrosModel()

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                bl.*,

                bc.nombre AS nom_categoria,

                bs.nombre AS nom_subcategoria,

                (SELECT COUNT(bj.id) FROM biblioteca_ejemplares bj WHERE bj.id_libro = bl.id and bj.estado NOT in(5)) AS cantidad_ejemplares

                FROM " . $tabla . " bl

                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                WHERE bl.activo = 1
                
                LIMIT 50;";

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



    public static function mostrarInformacionLibroModel($id)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                bl.*,

                bc.nombre AS nom_categoria,

                bs.nombre AS nom_subcategoria,

                (SELECT COUNT(bj.id) FROM biblioteca_ejemplares bj WHERE bj.id_libro = bl.id) AS cantidad_ejemplares

                FROM " . $tabla . " bl

                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                WHERE bl.id = :id;";

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



    public static function cargarInformacionEjemplarModel($id)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                bj.id as id_ejemplar,

                bj.*,

                bl.*,

                bc.nombre AS nom_categoria,

                bs.nombre AS nom_subcategoria

                FROM biblioteca_ejemplares bj

                LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                WHERE bj.codigo = :id;";

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



    public static function informacionCodigoEjemplarModel($id)

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                bj.id as id_ejemplar,

                bj.*,

                bl.*,

                bc.nombre AS nom_categoria,

                bs.nombre AS nom_subcategoria

                FROM biblioteca_ejemplares bj

                LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                WHERE bj.id = :id AND bj.estado = 1;";

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



    public static function prestarEjemplarModel($datos)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (

                    id_ejemplar,

                    id_usuario,

                    fecha_prestamo,

                    fecha_devolucion,

                    observacion,

                    id_log

                    )

                VALUES

                (

                    '" . $datos['id_ejemplar'] . "',

                    '" . $datos['id_usuario'] . "',

                    '" . $datos['fecha_prestamo'] . "',

                    '" . $datos['fecha_devolucion'] . "',

                    '" . $datos['observacion'] . "',

                    '" . $datos['id_log'] . "'

                    );



                    UPDATE biblioteca_ejemplares SET estado = 2 WHERE id = '" . $datos['id_ejemplar'] . "';

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



    public static function mostrarTodosLibrosModel()

    {

        $tabla  = 'biblioteca_libros';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT SQL_NO_CACHE

                    bl.*,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    (SELECT COUNT(bj.id) FROM biblioteca_ejemplares bj WHERE bj.id_libro = bl.id) AS cantidad_ejemplares

                    FROM " . $tabla . " bl

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    WHERE bl.activo = 1

                    ORDER BY bl.titulo ASC;";

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



    public static function utlimosPrestamosGeneralModel()

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    bpr.id_devuelto,

                    bpr.fecha_devuelto,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                    c.nombre AS nom_curso,

                    n.nombre AS nom_nivel

                    FROM biblioteca_prestamos bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    LEFT JOIN curso c ON c.id = u.id_curso

                    LEFT JOIN nivel n ON n.id = u.id_nivel

                    ORDER BY bpr.id DESC LIMIT 5;";

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

    public static function mostrarUltimosPrestamosEjemplarModel($id_ejemplar){
        $tabla = "biblioteca_prestamos";
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT b.fecha_prestamo, b.fecha_devolucion, b.fecha_devuelto, b.id_ejemplar, b.id 
                    FROM $tabla b  
                    WHERE b.id_ejemplar = $id_ejemplar 
                    ORDER BY b.fecha_prestamo DESC LIMIT 5;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print 'Error!: ' . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function reporteLibrosPrestadosModel($datos)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    bpr.id_devuelto,

                    bpr.fecha_devuelto,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                    c.nombre AS nom_curso,

                    n.nombre AS nom_nivel

                    FROM biblioteca_prestamos bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    LEFT JOIN curso c ON c.id = u.id_curso

                    LEFT JOIN nivel n ON n.id = u.id_nivel

                    WHERE bpr.id NOT IN('')

                    " . $datos['nivel'] . "

                    " . $datos['curso'] . "

                    " . $datos['fecha_inicio'] . "

                    " . $datos['fecha_fin'] . "

                    " . $datos['codigo'] . "

                    ORDER BY bpr.fecha_prestamo DESC;";

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



    public static function prestamosUsuarioModel($id)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    bpr.id_devuelto,

                    bpr.fecha_devuelto,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user

                    FROM " . $tabla . " bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    WHERE u.id_user = :id

                    ORDER BY bpr.id;";

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



    public static function utlimasDevolucionesGeneralModel()

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    bpr.fecha_devuelto,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user

                    FROM " . $tabla . " bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    WHERE bpr.id_devuelto IS NOT NULL

                    ORDER BY bpr.id DESC LIMIT 30;";

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



    public static function ultimmosPrestamosUsuarioModel($id)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user

                    FROM " . $tabla . " bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    WHERE u.documento = '" . $id . "'

                    ORDER BY bpr.id DESC LIMIT 30;";

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



    public static function ultimmosPrestamosEjemplarModel($id)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user

                    FROM " . $tabla . " bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    WHERE bj.codigo = :id

                    ORDER BY bpr.id DESC LIMIT 30;";

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



    public static function ultimmosDevolucionesEjemplarModel($id)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                    bpr.id AS id_prestamo,

                    bl.titulo,

                    bj.codigo,

                    bc.nombre AS nom_categoria,

                    bs.nombre AS nom_subcategoria,

                    bpr.fecha_prestamo,

                    bpr.fecha_devolucion,

                    bpr.observacion,

                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                    bpr.fecha_devuelto

                    FROM " . $tabla . " bpr

                    LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar

                    LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro

                    LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria

                    LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria

                    LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario

                    WHERE bj.codigo = :id AND bpr.id_devuelto IS NOT NULL

                    ORDER BY bpr.id DESC LIMIT 30;";

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



    public static function devolucionLibroModel($codigo, $id_log)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET id_devuelto = :id, fecha_devuelto = NOW() WHERE id_ejemplar IN(SELECT be.id FROM biblioteca_ejemplares be WHERE be.codigo = :c AND be.estado = 2) ORDER BY id DESC LIMIT 1;

                    UPDATE biblioteca_ejemplares SET estado = 1 WHERE codigo = :c;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id_log);

            $preparado->bindParam(':c', $codigo);

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



    public static function devolucionPaqueteModel($codigo, $id_log)

    {

        $tabla  = 'biblioteca_paquete_prestamo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET id_devuelto = :id, fecha_devuelto = NOW() WHERE id_paquete IN(SELECT be.id FROM biblioteca_paquete be WHERE be.codigo = :c AND be.estado = 2) ORDER BY id DESC LIMIT 1;

                    UPDATE biblioteca_paquete SET estado = 1 WHERE codigo = :c;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $id_log);

            $preparado->bindParam(':c', $codigo);

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



    public static function inactivarEjemplaresModel($datos)

    {

        $tabla  = 'biblioteca_ejemplares';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET id_log = :idl, estado = 4, fecha_inactivo = NOW() WHERE id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':id', $datos['id_ejemplar']);

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



    public static function buscarUsuariosBibliotecaNivelModel($datos)

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

                    WHERE u.perfil NOT IN(1,17,6) AND u.estado = 'activo' AND

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



    public static function mostrarUsuariosBibliotecaModel()

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

                    WHERE u.perfil NOT IN(1,17,6) AND u.estado = 'activo' ORDER BY u.nombre ASC LIMIT 30;";

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



    public static function guardarPaqueteModel($datos)

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (nombre, codigo, id_log) VALUES (:n, :cd, :idl);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':n', $datos['nom_paquete']);

            $preparado->bindParam(':cd', $datos['codigo']);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->setFetchMode(PDO::FETCH_ASSOC);

            if ($preparado->execute()) {

                $id   = $cnx->ultimoIngreso($tabla);

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



    public static function guardarDetallePaqueteModel($datos)

    {

        $tabla  = 'biblioteca_paquete_contenido';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (titulo, id_paquete, codigo, id_log) VALUES (:t, :idp, :cd, :idl);";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':t', $datos['nombre']);

            $preparado->bindParam(':idp', $datos['id_paquete']);

            $preparado->bindParam(':cd', $datos['codigo']);

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



    public static function mostrarLimitePaquetesModel()

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY id DESC LIMIT 30;";

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



    public static function moatrarDatosPaqueteModel($id)

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id = :id;";

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



    public static function moatrarDatosCodigoPaqueteModel($codigo)

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE codigo = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':id', $codigo);

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



    public static function mostrarDetallePaqueteModel($id)

    {

        $tabla  = 'biblioteca_paquete_contenido';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_paquete = :id AND activo = 1;";

        

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



    public static function mostrarDatosDetallePaqueteModel($id)

    {

        $tabla  = 'biblioteca_paquete_contenido';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id = :id;";

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



    public static function codigoPaqueteModel($codigo)

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT id FROM biblioteca_paquete WHERE codigo = :cd

                    UNION SELECT id FROM biblioteca_paquete_contenido WHERE codigo = :cd;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':cd', $codigo);

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



    public static function inactivarContenidoModel($datos)

    {

        $tabla  = 'biblioteca_paquete_contenido';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET activo = 0, id_inactivo = :idl, fecha_inactivo = NOW() WHERE id = :id;";

        try {

            $preparado = $cnx->preparar($cmdsql);

            $preparado->bindParam(':idl', $datos['id_log']);

            $preparado->bindParam(':id', $datos['id_detalle']);

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



    public static function ultimoCodigoContenidoPaqueteModel($id)

    {

        $tabla  = 'biblioteca_paquete_contenido';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT codigo FROM " . $tabla . " WHERE id_paquete = :id ORDER BY codigo DESC LIMIT 1;";

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



    public static function mostrarTodosPaquetesModel()

    {

        $tabla  = 'biblioteca_paquete';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1;";

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



    public static function prestarPaqueteModel($datos)

    {

        $tabla  = 'biblioteca_paquete_prestamo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "INSERT INTO " . $tabla . " (id_paquete, id_usuario, fecha_prestamo, fecha_devolucion, observacion, id_log) VALUES (

                        '" . $datos['id_paquete'] . "',

                        '" . $datos['id_usuario'] . "',

                        '" . $datos['fecha_prestamo'] . "',

                        '" . $datos['fecha_devolucion'] . "',

                        '" . $datos['observacion'] . "',

                        '" . $datos['id_log'] . "');



                        UPDATE biblioteca_paquete SET estado = 2 WHERE id = '" . $datos['id_paquete'] . "';";

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



    public static function mostrarUltimosPrestamosModel()

    {

        $tabla  = 'biblioteca_paquete_prestamo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                        b.*,

                        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                        bp.nombre AS nom_paquete,

                        bp.codigo

                        FROM biblioteca_paquete_prestamo b

                        LEFT JOIN biblioteca_paquete bp ON bp.id = b.id_paquete

                        LEFT JOIN usuarios u ON u.id_user = b.id_usuario

                        ORDER BY id DESC LIMIT 30;";

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



    public static function historialPaquetesUsuariosModel($id)

    {

        $tabla  = 'biblioteca_paquete_prestamo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT bp.*, bpr.nombre, bpr.codigo, bpr.estado, bpr.id_devuelto, bpr.fecha_devuelto
         FROM biblioteca_paquete_prestamo bp 
         LEFT JOIN biblioteca_paquete bpr ON bpr.id = bp.id_paquete 
         WHERE bp.id_usuario = :id;";

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

    public static function mostrarListadoDePaquetesPendientesModel($id_user){
        $tabla = 'biblioteca_paquete_prestamo';
        $cnx = conexion::singleton_conexion();
        $sql = "SELECT bp.nombre, bp.codigo, bpp.fecha_prestamo, bpp.fecha_devolucion, bpp.fecha_devuelto 
                FROM $tabla bpp  
                LEFT JOIN biblioteca_paquete bp ON bpp.id_paquete = bp.id 
                WHERE bpp.id_usuario = :id AND bpp.id_devuelto IS NULL;";
        try {
            $preparado = $cnx->preparar($sql);
            $preparado->bindParam(':id', $id_user);
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



    public static function mostrarUltimosDevolucionesModel()

    {

        $tabla  = 'biblioteca_paquete_prestamo';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "SELECT

                        b.*,

                        CONCAT(u.nombre, ' ', u.apellido) AS nom_user,

                        bp.nombre AS nom_paquete,

                        bp.codigo

                        FROM biblioteca_paquete_prestamo b

                        LEFT JOIN biblioteca_paquete bp ON bp.id = b.id_paquete

                        LEFT JOIN usuarios u ON u.id_user = b.id_usuario

                        WHERE b.id_devuelto IS NOT NULL

                        ORDER BY id DESC LIMIT 30;";

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



    public static function extenderFechaPrestamoModel($datos)

    {

        $tabla  = 'biblioteca_prestamos';

        $cnx    = conexion::singleton_conexion();

        $cmdsql = "UPDATE " . $tabla . " SET fecha_devolucion = '" . $datos['fecha_nueva'] . "' WHERE id = '" . $datos['id_prestamo'] . "';";

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

    

    public static function eliminarLibro($data){

        

        $id_libro=$data['id_libro'];

        $activo=$data['activo'];



        $cnx    = conexion::singleton_conexion();



        $sql="UPDATE biblioteca_libros

              SET activo=$activo

              WHERE id=$id_libro;

        ";



        try {



            $preparado = $cnx->preparar($sql);



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

    public static function mostrarUsuariosPazYSalvoModel($id_curso){
        $tabla = 'biblioteca_prestamos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT
                    bpr.id AS id_prestamo,
                    bpr.fecha_prestamo,
                    bpr.fecha_devolucion,
                    bpr.id_devuelto,
                    bpr.fecha_devuelto,
                    CONCAT(u.nombre, ' ', u.apellido) AS nom_user,
                    u.id_user,
                    u.correo AS correo_estudiante,
                    ua.correo AS correo_acudiente
                FROM biblioteca_prestamos bpr
                LEFT JOIN biblioteca_ejemplares bj ON bj.id = bpr.id_ejemplar
                LEFT JOIN biblioteca_libros bl ON bl.id = bj.id_libro
                LEFT JOIN biblioteca_categorias bc ON bc.id = bl.id_categoria
                LEFT JOIN biblioteca_subcategoria bs ON bs.id = bl.id_subcategoria
                LEFT JOIN usuarios u ON u.id_user = bpr.id_usuario
                LEFT JOIN estudiantes_padres ep ON ep.id_estudiante = u.id_user
                LEFT JOIN usuarios ua ON ua.id_user = ep.id_acudiente
                WHERE u.id_curso = $id_curso 
                AND bpr.fecha_devuelto IS NOT NULL
                GROUP BY u.id_user
                ORDER BY bpr.id;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->setFetchMode(PDO::FETCH_ASSOC);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error al recuperar a los docentes sin Coordinación: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }   

    

    

}

