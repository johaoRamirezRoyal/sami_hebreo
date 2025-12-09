<?php
require_once MODELO_PATH . 'conexion.php';

class ModeloPadres extends conexion
{
    public static function guardarFormatoModel($datos)
    {
        $tabla = 'historia_clinica';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (
            id_acudiente,
            nombre_form,
            fecha_lug,
            edad_form,
            nombre_padre,
            edad_padre,
            prof_padre,
            ocup_padre,
            nombre_madre,
            edad_madre,
            prof_madre,
            ocup_madre,
            tipo_union,
            cant_hermanos,
            per_sign,
            psicol,
            nivel_familiar,
            embarazo,
            tiempo_gest,
            parto,
            forcep,
            dificultades,
            req_ox,
            lloro,
            ictericia,
            anoxia,
            convulsiono,
            erupciones,
            tiempo_hos,
            estado_madre,
            estado_emocion_madre,
            estado_nino,
            aliment_nino,
            juega_nino,
            tetero,
            cuchara,
            alimentacion_hijo,
            rabietas,
            llora,
            sostenimiento,
            sentarse,
            equilibrio,
            gateo,
            caminar,
            seguimiento,
            agarre,
            abotonarse,
            recorte,
            trazo,
            lateralidad,
            comprension,
            lectoescritura,
            lengua,
            lenguaje_actual,
            ludica,
            afecto,
            normas,
            reaccion,
            llora_fac,
            pataleta,
            agresividad,
            tics,
            fobias,
            mentiras,
            insomnio,
            alimentacion,
            estomacal,
            alergias,
            esfinteres,
            edad_escolar,
            nombre_coleg,
            adaptacion,
            rel_comp,
            rel_prof,
            fortaleza,
            dif_academico,
            ref_academico,
            anio_perd,
            neurodesarrollo,
            fono,
            psico_clinica,
            psico_aprend,
            terapia,
            otra,
            observacion,
            con_duerme
          )
          VALUES
            (
              '" . $datos['id_acudiente'] . "',
              '" . $datos['nombre_form'] . "',
              '" . $datos['fecha_lug'] . "',
              '" . $datos['edad_form'] . "',
              '" . $datos['nombre_padre'] . "',
              '" . $datos['edad_padre'] . "',
              '" . $datos['prof_padre'] . "',
              '" . $datos['ocup_padre'] . "',
              '" . $datos['nombre_madre'] . "',
              '" . $datos['edad_madre'] . "',
              '" . $datos['prof_madre'] . "',
              '" . $datos['ocup_madre'] . "',
              '" . $datos['tipo_union'] . "',
              '" . $datos['cant_hermanos'] . "',
              '" . $datos['per_sign'] . "',
              '" . $datos['psicol'] . "',
              '" . $datos['nivel_familiar'] . "',
              '" . $datos['embarazo'] . "',
              '" . $datos['tiempo_gest'] . "',
              '" . $datos['parto'] . "',
              '" . $datos['forcep'] . "',
              '" . $datos['dificultades'] . "',
              '" . $datos['req_ox'] . "',
              '" . $datos['lloro'] . "',
              '" . $datos['ictericia'] . "',
              '" . $datos['anoxia'] . "',
              '" . $datos['convulsiono'] . "',
              '" . $datos['erupciones'] . "',
              '" . $datos['tiempo_hos'] . "',
              '" . $datos['estado_madre'] . "',
              '" . $datos['estado_emocion_madre'] . "',
              '" . $datos['estado_nino'] . "',
              '" . $datos['aliment_nino'] . "',
              '" . $datos['juega_nino'] . "',
              '" . $datos['tetero'] . "',
              '" . $datos['cuchara'] . "',
              '" . $datos['alimentacion_hijo'] . "',
              '" . $datos['rabietas'] . "',
              '" . $datos['llora'] . "',
              '" . $datos['sostenimiento'] . "',
              '" . $datos['sentarse'] . "',
              '" . $datos['equilibrio'] . "',
              '" . $datos['gateo'] . "',
              '" . $datos['caminar'] . "',
              '" . $datos['seguimiento'] . "',
              '" . $datos['agarre'] . "',
              '" . $datos['abotonarse'] . "',
              '" . $datos['recorte'] . "',
              '" . $datos['trazo'] . "',
              '" . $datos['lateralidad'] . "',
              '" . $datos['comprension'] . "',
              '" . $datos['lectoescritura'] . "',
              '" . $datos['lengua'] . "',
              '" . $datos['lenguaje_actual'] . "',
              '" . $datos['ludica'] . "',
              '" . $datos['afecto'] . "',
              '" . $datos['normas'] . "',
              '" . $datos['reaccion'] . "',
              '" . $datos['llora_fac'] . "',
              '" . $datos['pataleta'] . "',
              '" . $datos['agresividad'] . "',
              '" . $datos['tics'] . "',
              '" . $datos['fobias'] . "',
              '" . $datos['mentiras'] . "',
              '" . $datos['insomnio'] . "',
              '" . $datos['alimentacion'] . "',
              '" . $datos['estomacal'] . "',
              '" . $datos['alergias'] . "',
              '" . $datos['esfinteres'] . "',
              '" . $datos['edad_escolar'] . "',
              '" . $datos['nombre_coleg'] . "',
              '" . $datos['adaptacion'] . "',
              '" . $datos['rel_comp'] . "',
              '" . $datos['rel_prof'] . "',
              '" . $datos['fortaleza'] . "',
              '" . $datos['dif_academico'] . "',
              '" . $datos['ref_academico'] . "',
              '" . $datos['anio_perd'] . "',
              '" . $datos['neurodesarrollo'] . "',
              '" . $datos['fono'] . "',
              '" . $datos['psico_clinica'] . "',
              '" . $datos['psico_aprend'] . "',
              '" . $datos['terapia'] . "',
              '" . $datos['otra'] . "',
              '" . $datos['observacion'] . "',
              '" . $datos['con_duerme'] . "'
            );";
        try {
            $preparado = $cnx->preparar($cmdsql);
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


    public static function guardarHermanoModel($datos)
    {
        $tabla = 'hermanos';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (nombre, edad, tipo_rel, id_log) VALUES (:n,:e,:t,:id);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':n', $datos['nombre']);
            $preparado->bindValue(':e', $datos['edad']);
            $preparado->bindValue(':t', $datos['tipo_rel']);
            $preparado->bindValue(':id', $datos['id_log']);
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


    public static function formatoLlenoModel($id)
    {
        $tabla = 'formato';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "INSERT INTO " . $tabla . " (id_acudiente) VALUES (:id);";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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

    public static function consultarFormatoLlenoModel($id)
    {
        $tabla = 'historia_clinica';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id_acudiente = :id ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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


    public static function consultarFormatoActivoModel($id)
    {
        $tabla = 'formato';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE id FROM " . $tabla . " WHERE id_acudiente = :id AND activo = 1 ORDER BY id DESC LIMIT 1;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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


    public static function mostrarDatosFormatoIdModel($id)
    {
        $tabla = 'historia_clinica';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "SELECT SQL_NO_CACHE * FROM " . $tabla . " WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $id);
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


    public static function confirmarFormatoModel($datos)
    {
        var_dump($datos);
        $tabla = 'historia_clinica';
        $cnx = conexion::singleton_conexion();
        $cmdsql = "UPDATE " . $tabla . " SET
          nombre_form = '" . $datos['nombre_form'] . "',
          fecha_lug = '" . $datos['fecha_lug'] . "',
          edad_form = '" . $datos['edad_form'] . "',
          nombre_padre = '" . $datos['nombre_padre'] . "',
          edad_padre = '" . $datos['edad_padre'] . "',
          prof_padre = '" . $datos['prof_padre'] . "',
          ocup_padre = '" . $datos['ocup_padre'] . "',
          nombre_madre = '" . $datos['nombre_madre'] . "',
          edad_madre = '" . $datos['edad_madre'] . "',
          prof_madre = '" . $datos['prof_madre'] . "',
          ocup_madre = '" . $datos['ocup_madre'] . "',
          tipo_union = '" . $datos['tipo_union'] . "',
          cant_hermanos = '" . $datos['cant_hermanos'] . "',
          per_sign = '" . $datos['per_sign'] . "',
          psicol = '" . $datos['psicol'] . "',
          nivel_familiar = '" . $datos['nivel_familiar'] . "',
          embarazo = '" . $datos['embarazo'] . "',
          tiempo_gest = '" . $datos['tiempo_gest'] . "',
          parto = '" . $datos['parto'] . "',
          forcep = '" . $datos['forcep'] . "',
          dificultades = '" . $datos['dificultades'] . "',
          req_ox = '" . $datos['req_ox'] . "',
          lloro = '" . $datos['lloro'] . "',
          ictericia = '" . $datos['ictericia'] . "',
          anoxia = '" . $datos['anoxia'] . "',
          convulsiono = '" . $datos['convulsiono'] . "',
          erupciones = '" . $datos['erupciones'] . "',
          tiempo_hos = '" . $datos['tiempo_hos'] . "',
          estado_madre = '" . $datos['estado_madre'] . "',
          estado_emocion_madre = '" . $datos['estado_emocion_madre'] . "',
          estado_nino = '" . $datos['estado_nino'] . "',
          aliment_nino = '" . $datos['aliment_nino'] . "',
          juega_nino = '" . $datos['juega_nino'] . "',
          tetero = '" . $datos['tetero'] . "',
          cuchara = '" . $datos['cuchara'] . "',
          alimentacion = '" . $datos['alimentacion'] . "',
          rabietas = '" . $datos['rabietas'] . "',
          llora = '" . $datos['llora'] . "',
          sostenimiento = '" . $datos['sostenimiento'] . "',
          sentarse = '" . $datos['sentarse'] . "',
          equilibrio = '" . $datos['equilibrio'] . "',
          gateo = '" . $datos['gateo'] . "',
          caminar = '" . $datos['caminar'] . "',
          seguimiento = '" . $datos['seguimiento'] . "',
          agarre = '" . $datos['agarre'] . "',
          abotonarse = '" . $datos['abotonarse'] . "',
          recorte = '" . $datos['recorte'] . "',
          trazo = '" . $datos['trazo'] . "',
          lateralidad = '" . $datos['lateralidad'] . "',
          comprension = '" . $datos['comprension'] . "',
          lectoescritura = '" . $datos['lectoescritura'] . "',
          lengua = '" . $datos['lengua'] . "',
          lenguaje_actual = '" . $datos['lenguaje_actual'] . "',
          ludica = '" . $datos['ludica'] . "',
          afecto = '" . $datos['afecto'] . "',
          normas = '" . $datos['normas'] . "',
          reaccion = '" . $datos['reaccion'] . "',
          llora_fac = '" . $datos['llora_fac'] . "',
          pataleta = '" . $datos['pataleta'] . "',
          agresividad = '" . $datos['agresividad'] . "',
          tics = '" . $datos['tics'] . "',
          fobias = '" . $datos['fobias'] . "',
          mentiras = '" . $datos['mentiras'] . "',
          insomnio = '" . $datos['insomnio'] . "',
          alimentacion_hijo = '" . $datos['alimentacion_hijo'] . "',
          estomacal = '" . $datos['estomacal'] . "',
          alergias = '" . $datos['alergias'] . "',
          esfinteres = '" . $datos['esfinteres'] . "',
          edad_escolar = '" . $datos['edad_escolar'] . "',
          nombre_coleg = '" . $datos['nombre_coleg'] . "',
          adaptacion = '" . $datos['adaptacion'] . "',
          rel_comp = '" . $datos['rel_comp'] . "',
          rel_prof = '" . $datos['rel_prof'] . "',
          fortaleza = '" . $datos['fortaleza'] . "',
          dif_academico = '" . $datos['dif_academico'] . "',
          ref_academico = '" . $datos['ref_academico'] . "',
          anio_perd = '" . $datos['anio_perd'] . "',
          neurodesarrollo = '" . $datos['neurodesarrollo'] . "',
          fono = '" . $datos['fono'] . "',
          psico_clinica = '" . $datos['psico_clinica'] . "',
          psico_aprend = '" . $datos['psico_aprend'] . "',
          terapia = '" . $datos['terapia'] . "',
          otra = '" . $datos['otra'] . "',
          observacion = '" . $datos['observacion'] . "',
          con_duerme = '" . $datos['con_duerme'] . "',
          confirmado = 'si'
        WHERE id = :id;";
        try {
            $preparado = $cnx->preparar($cmdsql);
            $preparado->bindValue(':id', $datos['id_formato']);
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
