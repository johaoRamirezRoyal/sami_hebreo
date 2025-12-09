<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloCitaciones extends conexion{

    public static function mostrarTodosEstudiantesModel(){
        $tabla = 'usuarios'; 
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT u.*, n.NOMBRE AS nom_nivel, c.NOMBRE AS nom_curso
                    FROM USUARIOS U 
                    JOIN NIVEL N ON u.ID_NIVEL = n.ID
                    JOIN CURSO C ON c.ID = u.ID_CURSO
                    WHERE PERFIL = 3 
                    LIMIT 30";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarEstudiantesUsuariosModel()
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        //Tambien se debe cambiar el perfil a 16 en el where de la consulta
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

    public static function filtrarCitacionesModel($datos){
        $tabla  = 'citaciones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                    C.*, 
                    CONCAT(' ', U.NOMBRE, U.APELLIDO) AS nombre_estudiante, 
                    CONCAT(' ', P.NOMBRE, P.APELLIDO) AS nombre_docente,
                    N.NOMBRE AS nom_nivel, 
                    EC.CLAVE AS estado_citacion,
                    CU.NOMBRE AS nom_curso,
                    P.CORREO AS correo_profesor
                FROM $tabla C
                LEFT JOIN usuarios U ON C.ESTUDIANTES_ID = U.ID_USER
                LEFT JOIN usuarios P ON C.PROFESOR_ID = P.ID_USER
                LEFT JOIN nivel N ON N.ID = C.NIVEL_ID
                LEFT JOIN curso CU ON CU.ID = U.ID_CURSO
                LEFT JOIN estado_citacion EC ON EC.ID = C.ESTADO_ID
                WHERE CONCAT(' ', U.NOMBRE, U.APELLIDO, U.DOCUMENTO, U.CORREO, P.NOMBRE, P.APELLIDO) LIKE '%{$datos['buscar']}%'
                " . $datos['nivel'] . "
                " . $datos['curso'] . "
                ORDER BY C.fecha_citacion DESC;";

                try{
                    $preparado = $cnx->preparar($cmdsql);
                    if($preparado->execute()){
                        return $preparado->fetchAll();
                    }else{
                        return false;
                    }
                }catch(PDOException $e){   
                    print "Error!: " . $e->getMessage();
                }
        $cnx->closed();
        $cnx = null;
    }

    public static function buscarEstudiantesNivelModel($datos)
    {
        $tabla  = 'citaciones';
        $cnx    = conexion::singleton_conexion();
        //Se debe cambiar la consulta sql, cambiar el perfil al correspondiente por los estudiantes (16)
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

    public static function generarCitacionModel($datos){
        $tabla  = 'citaciones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO $tabla(estudiantes_id, profesor_id, nivel_id, estado_id, motivo, fecha_citacion, hora_citacion) 
        VALUES (:estudiantes_id, :profesor_id, :nivel_id, 1, :motivo, :fecha_citacion, :hora_citacion);";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':estudiantes_id', $datos['id_usuario']);
            $preparado->bindParam(':profesor_id', $datos['id_log']);
            $preparado->bindParam(':nivel_id', $datos['nivel_id']);
            $preparado->bindParam(':motivo', $datos['motivo']);
            $preparado->bindParam(':fecha_citacion', $datos['fecha_citacion']);
            $preparado->bindParam(':hora_citacion', $datos['hora_citacion']);
            if($preparado->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function esRectoraModel($id_log){
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT u.id_user, u.perfil FROM $tabla u
                    JOIN nivel n ON u.id_nivel = n.id
                    WHERE u.id_user = :id_log";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id_log', $id_log);
            if($preparado->execute()){
                return $preparado->fetch();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarTodasLasCitacionesModel(){
        $tabla  = 'citaciones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT 
                        C.*, 
                        CONCAT(U.NOMBRE, ' ', U.APELLIDO) AS nombre_estudiante, 
                        CONCAT(P.NOMBRE, ' ', P.APELLIDO) AS nombre_docente,
                        N.NOMBRE AS nom_nivel, 
                        EC.CLAVE AS estado_citacion,
                        CU.NOMBRE AS nom_curso,
                        P.correo AS correo_profesor
                    FROM $tabla C
                    LEFT JOIN usuarios U ON C.ESTUDIANTES_ID = U.ID_USER
                    LEFT JOIN usuarios P ON C.PROFESOR_ID = P.ID_USER
                    LEFT JOIN nivel N ON N.ID = C.NIVEL_ID
                    LEFT JOIN curso CU ON CU.ID = U.ID_CURSO
                    LEFT JOIN estado_citacion EC ON EC.ID = C.ESTADO_ID
                    ORDER BY C.fecha_citacion DESC, C.hora_citacion DESC;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed();
        $cnx = null;
    }

    public static function mostrarEstadosCitacionModel(){
        $tabla  = 'estado_citacion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT EC.id, EC.nombre FROM $tabla EC order by EC.id ASC;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            if($preparado->execute()){
                return $preparado->fetchAll();
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed(); 
        $cnx = null;
    }

    public static function cambiarEstadoCitacionModel($datos){
        $tabla  = 'citaciones';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE $tabla SET estado_id = :estado_id 
        WHERE id = :id AND estudiantes_id = :estudiante_id AND profesor_id = :profesor_id;";
        try{
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':estado_id', $datos['estado_id']);
            $preparado->bindParam(':id', $datos['id']);
            $preparado->bindParam(':estudiante_id', $datos['estudiante_id']);
            $preparado->bindParam(':profesor_id', $datos['profesor_id']);
            if($preparado->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            print "Error!: " . $e->getMessage();
        }
        $cnx->closed(); 
        $cnx = null;
    }

}


?>