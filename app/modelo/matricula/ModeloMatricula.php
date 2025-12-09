<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloMatricula extends conexion
{

    public static function contratoMatriculaModel($datos)
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (anio, id_nivel, pension, matricula, id_log)
        VALUES (
            '" . $datos['anio'] . "',
            '" . $datos['nivel'] . "',
            '" . $datos['pension'] . "',
            '" . $datos['matricula'] . "',
            '" . $datos['id_log'] . "'
        );";
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

    public static function mostrarContratosConfiguradosModel()
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        mc.*,
        n.nombre AS nom_nivel
        FROM matricula_contrato mc
        LEFT JOIN nivel n ON n.id = mc.id_nivel ORDER BY mc.id DESC;";
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

    public static function datosUltimoContratoNivelModel($nivel)
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        mc.*,
        n.nombre AS nom_nivel
        FROM matricula_contrato mc
        LEFT JOIN nivel n ON n.id = mc.id_nivel
        WHERE mc.id_nivel = :n ORDER BY mc.id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':n', $nivel);
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

    public static function datosContratoUltimoModel()
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " ORDER BY id DESC LIMIT 1;";
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

    public static function datosContratoIdModel($id)
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id = :id;";
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

    public static function mostrarDatosAcudienteModel($id)
    {
        $tabla  = 'estudiantes_padres';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.id_user,
        u.documento,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        est.*,
        u.firma_digital,
        (SELECT f.nombre FROM firmas f WHERE f.id_user = est.id_acudiente AND activo = 1) AS firma
        FROM estudiantes_padres est
        LEFT JOIN usuarios u ON u.id_user = est.id_acudiente
        WHERE est.id_estudiante = :id;";
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

    public static function mostrarDatosAcudienteResponsableModel($id)
    {
        $tabla  = 'estudiantes_padres';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
        u.id_user,
        u.documento,
        u.nombre,
        u.apellido,
        u.correo,
        u.telefono,
        est.*
        FROM estudiantes_padres est
        LEFT JOIN usuarios u ON u.id_user = est.id_acudiente
        WHERE est.id_estudiante = :id AND est.responsable = 1;";
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

    public static function guardarDescuentoContratoModel($datos)
    {
        $tabla  = 'matricula_descuento';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET activo = 0 WHERE id_user = '" . $datos['id_user'] . "' AND id_contrato = '" . $datos['id_contrato'] . "';
        INSERT INTO " . $tabla . " (id_user, id_contrato, descuento_pension, descuento_matricula, id_log) VALUES ('" . $datos['id_user'] . "','" . $datos['id_contrato'] . "','" . $datos['descuento_pension'] . "','" . $datos['descuento_matricula'] . "','" . $datos['id_log'] . "');";
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

    public static function mostrarDatosEstudianteModel($id)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
            u.*,
            (SELECT md.descuento_pension FROM matricula_descuento md WHERE md.id_user = u.id_user AND md.activo = 1) AS descuento_pension,
            (SELECT md.descuento_matricula FROM matricula_descuento md WHERE md.id_user = u.id_user AND md.activo = 1) AS descuento_matricula,
            (SELECT md.id_contrato FROM matricula_descuento md WHERE md.id_user = u.id_user AND md.activo = 1) AS id_contrato,
            c.nombre AS nom_curso,
            (SELECT cc.nombre FROM curso cc WHERE cc.id = c.curso_proximo) as curso_proximo,
            (SELECT cc.nombre FROM curso cc WHERE cc.id = u.curso_proximo_user) as curso_proximo_est,
            c.curso_proximo as id_curso_proximo
            FROM usuarios u
            LEFT JOIN curso c ON c.id = u.id_curso
            WHERE u.id_user = :id;";
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

    public static function informacionAcudienteModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE estudiantes_padres SET lugar_expedicion = '" . $datos['lugar_expedicion'] . "', direccion = '" . $datos['direccion'] . "', ciudad = '" . $datos['ciudad'] . "', celular = '" . $datos['celular'] . "', responsable = '" . $datos['responsable'] . "' WHERE id_acudiente = :id;

            UPDATE usuarios SET telefono = '" . $datos['telefono'] . "', correo = '" . $datos['correo'] . "', terminos = '" . $datos['terminos'] . "', firma_digital = '" . $datos['firma_digital'] . "' WHERE id_user = :id;

            DELETE FROM firmas WHERE id_user = :id;
            INSERT INTO firmas (id_user, nombre, user_log) VALUES (:id, '" . $datos['firma'] . "', :id);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_user']);
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

    public static function aprobarFirmaAcudienteModel($id, $estado)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE usuarios SET firma_digital = :est WHERE id_user = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->bindParam(':est', $estado);
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

    public static function denegarFirmaAcudienteModel($id, $estado)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE usuarios SET firma_digital = :est WHERE id_user = :id;
            UPDATE firmas SET activo = 0 WHERE id_user = :id ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $id);
            $preparado->bindParam(':est', $estado);
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

    public static function generarContratoModel($datos)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO matricula_contrato_estudiante (id_contrato, id_estudiante, curso, id_log, anio) VALUES ('" . $datos['id_contrato'] . "', '" . $datos['id_estudiante'] . "', '" . $datos['curso_proximo'] . "', '" . $datos['id_log'] . "', '" . $datos['anio'] . "');";
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

    public static function mostrarContratoGeneradoModel($id, $year)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_estudiante = :id AND fechareg LIKE '%" . $year . "%' ORDER BY id DESC LIMIT 1;";
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

    public static function mostrarDatosContratoEstudianteModel($id)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id = :id;";
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

    public static function promoverCursosTodosModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE  " . $tabla . " SET curso_proximo_user = '" . $datos['curso_proximo'] . "' WHERE id_user = '" . $datos['id_estudiante'] . "' AND id_curso = '" . $datos['curso'] . "';";
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

    public static function actualizarCursoProximoModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = " UPDATE " . $tabla . " SET curso_proximo_user = :c WHERE id_user = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':id', $datos['id_estudiante']);
            $preparado->bindParam(':c', $datos['curso']);
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

    public static function mostrarTodosContratosModel($id)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                mce.*,
                c.nombre AS nom_curso,
                (SELECT n.nombre FROM nivel n WHERE n.id = mc.id_nivel) AS nom_nivel
                FROM matricula_contrato_estudiante mce
                LEFT JOIN curso c ON c.id = mce.curso
                LEFT JOIN matricula_contrato mc ON mc.id = mce.id_contrato
                WHERE mce.id_estudiante = :id;";
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

    public static function editarContratoControlMatriculaModel($datos)
    {
        $tabla  = 'matricula_contrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET pension = :p, matricula = :m, id_log_edit = :idl, fecha_edit = NOW() WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':p', $datos['pension']);
            $preparado->bindParam(':m', $datos['matricula']);
            $preparado->bindParam(':idl', $datos['id_log']);
            $preparado->bindParam(':id', $datos['id_contrato']);
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

    public static function actualizarEstudianteUsuarioModel($datos)
    {
        $tabla  = 'usuarios';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET documento = :d, nombre = :n, apellido = :ap, correo = :c, telefono = :t WHERE id_user = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindParam(':d', $datos['documento']);
            $preparado->bindParam(':n', $datos['nombre']);
            $preparado->bindParam(':ap', $datos['apellido']);
            $preparado->bindParam(':c', $datos['correo']);
            $preparado->bindParam(':t', $datos['telefono']);
            $preparado->bindParam(':id', $datos['id_estudiante']);
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

    public static function guardarInformacionEstudianteModel($datos)
    {
        $tabla  = 'estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO estudiante (
                  id_estudiante,
                  tipo_documento,
                  lugar_expedicion,
                  genero,
                  fecha_nacimiento,
                  pais_nac,
                  departamento_nac,
                  ciudad_nac,
                  pais_resi,
                  departamento_resi,
                  ciudad_resi,
                  direccion,
                  barrio,
                  estrato,
                  tipo_vivienda,
                  telefono_resi,
                  celular_resi,
                  hermanos,
                  hijo_mayor,
                  correo_institucional,
                  estatura,
                  peso,
                  tipo_sangre,
                  religion,
                  estado_civil_padres,
                  contactar,
                  establecimiento,
                  autorizo_imagen,
                  id_log
                  )
                VALUES
                (
                    '" . $datos['id_estudiante'] . "',
                    '" . $datos['tipo_documento'] . "',
                    '" . $datos['lugar_expedicion'] . "',
                    '" . $datos['genero'] . "',
                    '" . $datos['fecha_nacimiento'] . "',
                    '" . $datos['pais_nac'] . "',
                    '" . $datos['departamento_nac'] . "',
                    '" . $datos['ciudad_nac'] . "',
                    '" . $datos['pais_resi'] . "',
                    '" . $datos['departamento_resi'] . "',
                    '" . $datos['ciudad_resi'] . "',
                    '" . $datos['direccion'] . "',
                    '" . $datos['barrio'] . "',
                    '" . $datos['estrato'] . "',
                    '" . $datos['tipo_vivienda'] . "',
                    '" . $datos['telefono_resi'] . "',
                    '" . $datos['celular_resi'] . "',
                    '" . $datos['hermanos'] . "',
                    '" . $datos['hijo_mayor'] . "',
                    '" . $datos['correo_institucional'] . "',
                    '" . $datos['estatura'] . "',
                    '" . $datos['peso'] . "',
                    '" . $datos['tipo_sangre'] . "',
                    '" . $datos['religion'] . "',
                    '" . $datos['estado_civil_padres'] . "',
                    '" . $datos['contactar'] . "',
                    '" . $datos['establecimiento'] . "',
                    '" . $datos['autorizo_imagen'] . "',
                    '" . $datos['id_log'] . "'
                );";
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

    public static function validarRegistroInfoEstudianteModel($id)
    {
        $tabla  = 'estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_estudiante = :id;";
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

    public static function validarHistoriaClinicaEstudianteModel($id)
    {
        $tabla  = 'historia_clinica';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_estudiante = :id;";
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

    public static function actualizarInformacionEstudianteModel($datos)
    {
        $tabla  = 'estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE
                estudiante
                SET
                tipo_documento = '" . $datos['tipo_documento'] . "',
                lugar_expedicion = '" . $datos['lugar_expedicion'] . "',
                genero = '" . $datos['genero'] . "',
                fecha_nacimiento = '" . $datos['fecha_nacimiento'] . "',
                pais_nac = '" . $datos['pais_nac'] . "',
                departamento_nac = '" . $datos['departamento_nac'] . "',
                ciudad_nac = '" . $datos['ciudad_nac'] . "',
                pais_resi = '" . $datos['pais_resi'] . "',
                departamento_resi = '" . $datos['departamento_resi'] . "',
                ciudad_resi = '" . $datos['ciudad_resi'] . "',
                direccion = '" . $datos['direccion'] . "',
                barrio = '" . $datos['barrio'] . "',
                estrato = '" . $datos['estrato'] . "',
                tipo_vivienda = '" . $datos['tipo_vivienda'] . "',
                telefono_resi = '" . $datos['telefono_resi'] . "',
                celular_resi = '" . $datos['celular_resi'] . "',
                hermanos = '" . $datos['hermanos'] . "',
                hijo_mayor = '" . $datos['hijo_mayor'] . "',
                correo_institucional = '" . $datos['correo_institucional'] . "',
                estatura = '" . $datos['estatura'] . "',
                peso = '" . $datos['peso'] . "',
                tipo_sangre = '" . $datos['tipo_sangre'] . "',
                religion = '" . $datos['religion'] . "',
                estado_civil_padres = '" . $datos['estado_civil_padres'] . "',
                contactar = '" . $datos['contactar'] . "',
                establecimiento = '" . $datos['establecimiento'] . "',
                autorizo_imagen = '" . $datos['autorizo_imagen'] . "',
                id_edit = '" . $datos['id_log'] . "',
                fecha_edit = NOW()
                WHERE id_estudiante = '" . $datos['id_estudiante'] . "';";
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

    public static function guardarHistoriaClinicaModel($datos)
    {
        $tabla  = 'historia_clinica';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO historia_clinica (
                  id_estudiante,
                  medicamentos_pree,
                  cual_medicamento,
                  dosis_medicamento,
                  alergias,
                  alergia_cual,
                  alergia_tratamiento,
                  alergia_alimento,
                  alergia_alimento_cual,
                  dieta,
                  dieta_cual,
                  seguro,
                  loratadina,
                  dolex,
                  milanta,
                  buscapina,
                  eps,
                  nombre_medico,
                  telefono_medico,
                  clinica,
                  recomendacion_medica,
                  enfermedad_ojos,
                  lentes,
                  asma,
                  enfermedad_respiratoria,
                  enfermedad_cardiaca,
                  enfermedad_gastricas,
                  enfermedad_diabetes,
                  convulsiones,
                  celiaquismo,
                  psiquiatra,
                  hepatitis,
                  anemia,
                  hipertension_arterial,
                  hipotension_arterial,
                  epilepsia,
                  hernias,
                  migrana,
                  fractura_trauma,
                  accidente_cardio,
                  artritis,
                  desnutricion,
                  obesidad,
                  cancer,
                  vih,
                  enfermedad_renal,
                  otros,
                  protesis,
                  cirugias,
                  perdido_conocimiento,
                  enfermedad_actual,
                  medicamentos_no,
                  condicion_salud,
                  diabetes_familiar,
                  cancer_familiar,
                  hipertension,
                  enfermedad_cardiovascular,
                  covid,
                  etnia,
                  inclusion,
                  otros_familiar,
                  id_log
                  )
                VALUES
                (
                    '" . $datos['id_estudiante'] . "',
                    '" . $datos['medicamentos_pree'] . "',
                    '" . $datos['cual_medicamento'] . "',
                    '" . $datos['dosis_medicamento'] . "',
                    '" . $datos['alergias'] . "',
                    '" . $datos['alergia_cual'] . "',
                    '" . $datos['alergia_tratamiento'] . "',
                    '" . $datos['alergia_alimento'] . "',
                    '" . $datos['alergia_alimento_cual'] . "',
                    '" . $datos['dieta'] . "',
                    '" . $datos['dieta_cual'] . "',
                    '" . $datos['seguro'] . "',
                    '" . $datos['loratadina'] . "',
                    '" . $datos['dolex'] . "',
                    '" . $datos['milanta'] . "',
                    '" . $datos['buscapina'] . "',
                    '" . $datos['eps'] . "',
                    '" . $datos['nombre_medico'] . "',
                    '" . $datos['telefono_medico'] . "',
                    '" . $datos['clinica'] . "',
                    '" . $datos['recomendacion_medica'] . "',
                    '" . $datos['enfermedad_ojos'] . "',
                    '" . $datos['lentes'] . "',
                    '" . $datos['asma'] . "',
                    '" . $datos['enfermedad_respiratoria'] . "',
                    '" . $datos['enfermedad_cardiaca'] . "',
                    '" . $datos['enfermedad_gastricas'] . "',
                    '" . $datos['enfermedad_diabetes'] . "',
                    '" . $datos['convulsiones'] . "',
                    '" . $datos['celiaquismo'] . "',
                    '" . $datos['psiquiatra'] . "',
                    '" . $datos['hepatitis'] . "',
                    '" . $datos['anemia'] . "',
                    '" . $datos['hipertension_arterial'] . "',
                    '" . $datos['hipotension_arterial'] . "',
                    '" . $datos['epilepsia'] . "',
                    '" . $datos['hernias'] . "',
                    '" . $datos['migrana'] . "',
                    '" . $datos['fractura_trauma'] . "',
                    '" . $datos['accidente_cardio'] . "',
                    '" . $datos['artritis'] . "',
                    '" . $datos['desnutricion'] . "',
                    '" . $datos['obesidad'] . "',
                    '" . $datos['cancer'] . "',
                    '" . $datos['vih'] . "',
                    '" . $datos['enfermedad_renal'] . "',
                    '" . $datos['otros'] . "',
                    '" . $datos['protesis'] . "',
                    '" . $datos['cirugias'] . "',
                    '" . $datos['perdido_conocimiento'] . "',
                    '" . $datos['enfermedad_actual'] . "',
                    '" . $datos['medicamentos_no'] . "',
                    '" . $datos['condicion_salud'] . "',
                    '" . $datos['diabetes_familiar'] . "',
                    '" . $datos['cancer_familiar'] . "',
                    '" . $datos['hipertension'] . "',
                    '" . $datos['enfermedad_cardiovascular'] . "',
                    '" . $datos['covid'] . "',
                    '" . $datos['etnia'] . "',
                    '" . $datos['inclusion'] . "',
                    '" . $datos['otros_familiar'] . "',
                    '" . $datos['id_log'] . "'
                );";
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

    public static function actualizarHistoriaClinicaModel($datos)
    {
        $tabla  = 'historia_clinica';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE
                sami_royal.historia_clinica
                SET
                medicamentos_pree = '" . $datos['medicamentos_pree'] . "',
                cual_medicamento = '" . $datos['cual_medicamento'] . "',
                dosis_medicamento = '" . $datos['dosis_medicamento'] . "',
                alergias = '" . $datos['alergias'] . "',
                alergia_cual = '" . $datos['alergia_cual'] . "',
                alergia_tratamiento = '" . $datos['alergia_tratamiento'] . "',
                alergia_alimento = '" . $datos['alergia_alimento'] . "',
                alergia_alimento_cual = '" . $datos['alergia_alimento_cual'] . "',
                dieta = '" . $datos['dieta'] . "',
                dieta_cual = '" . $datos['dieta_cual'] . "',
                seguro = '" . $datos['seguro'] . "',
                loratadina = '" . $datos['loratadina'] . "',
                dolex = '" . $datos['dolex'] . "',
                milanta = '" . $datos['milanta'] . "',
                buscapina = '" . $datos['buscapina'] . "',
                eps = '" . $datos['eps'] . "',
                nombre_medico = '" . $datos['nombre_medico'] . "',
                telefono_medico = '" . $datos['telefono_medico'] . "',
                clinica = '" . $datos['clinica'] . "',
                recomendacion_medica = '" . $datos['recomendacion_medica'] . "',
                enfermedad_ojos = '" . $datos['enfermedad_ojos'] . "',
                lentes = '" . $datos['lentes'] . "',
                asma = '" . $datos['asma'] . "',
                enfermedad_respiratoria = '" . $datos['enfermedad_respiratoria'] . "',
                enfermedad_cardiaca = '" . $datos['enfermedad_cardiaca'] . "',
                enfermedad_gastricas = '" . $datos['enfermedad_gastricas'] . "',
                enfermedad_diabetes = '" . $datos['enfermedad_diabetes'] . "',
                convulsiones = '" . $datos['convulsiones'] . "',
                celiaquismo = '" . $datos['celiaquismo'] . "',
                psiquiatra = '" . $datos['psiquiatra'] . "',
                hepatitis = '" . $datos['hepatitis'] . "',
                anemia = '" . $datos['anemia'] . "',
                hipertension_arterial = '" . $datos['hipertension_arterial'] . "',
                hipotension_arterial = '" . $datos['hipotension_arterial'] . "',
                epilepsia = '" . $datos['epilepsia'] . "',
                hernias = '" . $datos['hernias'] . "',
                migrana = '" . $datos['migrana'] . "',
                fractura_trauma = '" . $datos['fractura_trauma'] . "',
                accidente_cardio = '" . $datos['accidente_cardio'] . "',
                artritis = '" . $datos['artritis'] . "',
                desnutricion = '" . $datos['desnutricion'] . "',
                obesidad = '" . $datos['obesidad'] . "',
                cancer = '" . $datos['cancer'] . "',
                vih = '" . $datos['vih'] . "',
                enfermedad_renal = '" . $datos['enfermedad_renal'] . "',
                otros = '" . $datos['otros'] . "',
                protesis = '" . $datos['protesis'] . "',
                cirugias = '" . $datos['cirugias'] . "',
                perdido_conocimiento = '" . $datos['perdido_conocimiento'] . "',
                enfermedad_actual = '" . $datos['enfermedad_actual'] . "',
                medicamentos_no = '" . $datos['medicamentos_no'] . "',
                condicion_salud = '" . $datos['condicion_salud'] . "',
                diabetes_familiar = '" . $datos['diabetes_familiar'] . "',
                cancer_familiar = '" . $datos['cancer_familiar'] . "',
                hipertension = '" . $datos['hipertension'] . "',
                enfermedad_cardiovascular = '" . $datos['enfermedad_cardiovascular'] . "',
                covid = '" . $datos['covid'] . "',
                etnia = '" . $datos['etnia'] . "',
                inclusion = '" . $datos['inclusion'] . "',
                otros_familiar = '" . $datos['otros_familiar'] . "',
                id_edit = '" . $datos['id_log'] . "',
                fecha_edit = NOW()
                WHERE id_estudiante = '" . $datos['id_estudiante'] . "';";
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

    public static function mostrarTiposDocumentoModel()
    {
        $tabla  = 'tipo_doc';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarEstratosModel()
    {
        $tabla  = 'estrato';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY id ASC;";
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

    public static function mostrarDatosTipoViviendaModel()
    {
        $tabla  = 'tipo_vivienda';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarDatosTipoSangreModel()
    {
        $tabla  = 'tipo_sangre';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarDatosReligionModel()
    {
        $tabla  = 'religion';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarDatosEstadoPadresModel()
    {
        $tabla  = 'estado_civil';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarContactoEmergenciaModel()
    {
        $tabla  = 'contacto_emergencia';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarDatosEpsModel()
    {
        $tabla  = 'eps';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE activo = 1 ORDER BY nombre ASC;";
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

    public static function mostrarInformacionEstudianteModel($id)
    {
        $tabla  = 'estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                est.*
                FROM estudiante est
                WHERE est.id_estudiante = :id;";
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

    public static function mostrarHistoriaClinicaModel($id)
    {
        $tabla  = 'estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT
                est.*
                FROM historia_clinica est
                WHERE est.id_estudiante = :id;";
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

    public static function subirDocumentosEstudianteModel($datos)
    {
        $tabla  = 'estudiante_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_estudiante, documento_estudiante, certificado, documento_acudiente, id_log) VALUES ('" . $datos['id_estudiante'] . "', '" . $datos['documento_estudiante'] . "', '" . $datos['certificado'] . "','" . $datos['documento_acudiente'] . "', '" . $datos['id_log'] . "');";
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

    public static function mostrarDocumentosEstudianteModel($anio, $id)
    {
        $tabla  = 'estudiante_documentos';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "SELECT * FROM " . $tabla . " WHERE id_estudiante = '" . $id . "' AND activo = 1 AND fechareg LIKE '%" . $anio . "%' ORDER BY id DESC LIMIT 1;";
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

    public static function aprobarContratoEstudianteModel($datos)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET estado = 1, id_edit = '" . $datos['id_log'] . "', fecha_edit = NOW() WHERE id_estudiante = '" . $datos['id_estudiante'] . "' AND id = '" . $datos['id_contrato'] . "';
        UPDATE usuarios SET firma_digital = 1 WHERE id_user IN(SELECT est.id_acudiente FROM estudiantes_padres est WHERE est.id_estudiante = '" . $datos['id_estudiante'] . "');";
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

    public static function denegarContratoEstudianteModel($datos)
    {
        $tabla  = 'matricula_contrato_estudiante';
        $cnx    = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET estado = 2, id_edit = '" . $datos['id_log'] . "', fecha_edit = NOW(), motivo = '" . $datos['motivo'] . "' WHERE id_estudiante = '" . $datos['id_estudiante'] . "' AND id = '" . $datos['id_contrato'] . "';
        UPDATE usuarios SET firma_digital = 0 WHERE id_user IN(SELECT est.id_acudiente FROM estudiantes_padres est WHERE est.id_estudiante = '" . $datos['id_estudiante'] . "');
        UPDATE firmas SET activo = 0 WHERE id_user IN(SELECT est.id_acudiente FROM estudiantes_padres est WHERE est.id_estudiante = '" . $datos['id_estudiante'] . "');
        UPDATE estudiante_documentos SET activo = 0 WHERE id_estudiante = '" . $datos['id_estudiante'] . "';";
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
