<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'padres' . DS . 'ModeloPadres.php';
require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';
require_once CONTROL_PATH . 'hash.php';

class ControlPadres
{

    private static $instancia;

    public static function singleton_padres()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function consultarFormatoLlenoControl($id)
    {
        $mostrar = ModeloPadres::consultarFormatoLlenoModel($id);
        return $mostrar;
    }

    public function consultarFormatoActivoControl($id)
    {
        $mostrar = ModeloPadres::consultarFormatoActivoModel($id);
        return $mostrar;
    }

    public function mostrarDatosFormatoIdControl($id)
    {
        $mostrar = ModeloPadres::mostrarDatosFormatoIdModel($id);
        return $mostrar;
    }

    public function guardarFormatoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['nombre_form']) &&
            !empty($_POST['nombre_form']) &&
            isset($_POST['id_acudiente']) &&
            !empty($_POST['id_acudiente']) &&
            isset($_POST['edad_form']) &&
            !empty($_POST['edad_form'])
        ) {
            $id_acudiente         = $_POST['id_acudiente'];
            $nombre_form          = $_POST['nombre_form'];
            $fecha_lug            = $_POST['fecha_lug'];
            $edad_form            = $_POST['edad_form'];
            $nombre_padre         = $_POST['nombre_padre'];
            $edad_padre           = $_POST['edad_padre'];
            $prof_padre           = $_POST['prof_padre'];
            $ocup_padre           = $_POST['ocup_padre'];
            $nombre_madre         = $_POST['nombre_madre'];
            $edad_madre           = $_POST['edad_madre'];
            $prof_madre           = $_POST['prof_madre'];
            $ocup_madre           = $_POST['ocup_madre'];
            $tipo_union           = $_POST['tipo_union'];
            $cant_hermanos        = $_POST['cant_hermanos'];
            $per_sign             = $_POST['per_sign'];
            $psicol               = $_POST['psicol'];
            $nivel_familiar       = $_POST['nivel_familiar'];
            $embarazo             = $_POST['embarazo'];
            $tiempo_gest          = $_POST['tiempo_gest'];
            $parto                = $_POST['parto'];
            $forcep               = $_POST['forcep'];
            $dificultades         = $_POST['dificultades'];
            $req_ox               = $_POST['req_ox'];
            $lloro                = $_POST['lloro'];
            $ictericia            = $_POST['ictericia'];
            $anoxia               = $_POST['anoxia'];
            $convulsiono          = $_POST['convulsiono'];
            $erupciones           = $_POST['erupciones'];
            $tiempo_hos           = $_POST['tiempo_hos'];
            $estado_madre         = $_POST['estado_madre'];
            $estado_emocion_madre = $_POST['estado_emocion_madre'];
            $estado_nino          = $_POST['estado_nino'];
            $aliment_nino         = $_POST['aliment_nino'];
            $juega_nino           = $_POST['juega_nino'];
            $tetero               = $_POST['tetero'];
            $cuchara              = $_POST['cuchara'];
            $alimentacion_hijo    = $_POST['alimentacion_hijo'];
            $rabietas             = $_POST['rabietas'];
            $llora                = $_POST['llora'];
            $sostenimiento        = $_POST['sostenimiento'];
            $sentarse             = $_POST['sentarse'];
            $equilibrio           = $_POST['equilibrio'];
            $gateo                = $_POST['gateo'];
            $caminar              = $_POST['caminar'];
            $seguimiento          = $_POST['seguimiento'];
            $agarre               = $_POST['agarre'];
            $abotonarse           = $_POST['abotonarse'];
            $recorte              = $_POST['recorte'];
            $trazo                = $_POST['trazo'];
            $lateralidad          = $_POST['lateralidad'];
            $comprension          = $_POST['comprension'];
            $lectoescritura       = $_POST['lectoescritura'];
            $lengua               = $_POST['lengua'];
            $lenguaje_actual      = $_POST['lenguaje_actual'];
            $ludica               = $_POST['ludica'];
            $afecto               = $_POST['afecto'];
            $normas               = $_POST['normas'];
            $reaccion             = $_POST['reaccion'];
            $llora_fac            = $_POST['llora_fac'];
            $pataleta             = $_POST['pataleta'];
            $agresividad          = $_POST['agresividad'];
            $tics                 = $_POST['tics'];
            $fobias               = $_POST['fobias'];
            $mentiras             = $_POST['mentiras'];
            $insomnio             = $_POST['insomnio'];
            $con_duerme           = $_POST['con_duerme'];
            $alimentacion         = $_POST['alimentacion'];
            $estomacal            = $_POST['estomacal'];
            $alergias             = $_POST['alergias'];
            $esfinteres           = $_POST['esfinteres'];
            $edad_escolar         = $_POST['edad_escolar'];
            $nombre_coleg         = $_POST['nombre_coleg'];
            $adaptacion           = $_POST['adaptacion'];
            $rel_comp             = $_POST['rel_comp'];
            $rel_prof             = $_POST['rel_prof'];
            $fortaleza            = $_POST['fortaleza'];
            $dif_academico        = $_POST['dif_academico'];
            $ref_academico        = $_POST['ref_academico'];
            $anio_perd            = $_POST['anio_perd'];
            $neurodesarrollo      = $_POST['neurodesarrollo'];
            $fono                 = $_POST['fono'];
            $psico_clinica        = $_POST['psico_clinica'];
            $psico_aprend         = $_POST['psico_aprend'];
            $terapia              = $_POST['terapia'];
            $otra                 = $_POST['otra'];
            $observacion          = $_POST['observacion'];

            $datos = array(
                'id_acudiente'         => $id_acudiente,
                'nombre_form'          => $nombre_form,
                'fecha_lug'            => $fecha_lug,
                'edad_form'            => $edad_form,
                'nombre_padre'         => $nombre_padre,
                'edad_padre'           => $edad_padre,
                'prof_padre'           => $prof_padre,
                'ocup_padre'           => $ocup_padre,
                'nombre_madre'         => $nombre_madre,
                'edad_madre'           => $edad_madre,
                'prof_madre'           => $prof_madre,
                'ocup_madre'           => $ocup_madre,
                'tipo_union'           => $tipo_union,
                'cant_hermanos'        => $cant_hermanos,
                'per_sign'             => $per_sign,
                'psicol'               => $psicol,
                'nivel_familiar'       => $nivel_familiar,
                'embarazo'             => $embarazo,
                'tiempo_gest'          => $tiempo_gest,
                'parto'                => $parto,
                'forcep'               => $forcep,
                'dificultades'         => $dificultades,
                'req_ox'               => $req_ox,
                'lloro'                => $lloro,
                'ictericia'            => $ictericia,
                'anoxia'               => $anoxia,
                'convulsiono'          => $convulsiono,
                'erupciones'           => $erupciones,
                'tiempo_hos'           => $tiempo_hos,
                'estado_madre'         => $estado_madre,
                'estado_emocion_madre' => $estado_emocion_madre,
                'estado_nino'          => $estado_nino,
                'aliment_nino'         => $aliment_nino,
                'juega_nino'           => $juega_nino,
                'tetero'               => $tetero,
                'cuchara'              => $cuchara,
                'alimentacion_hijo'    => $alimentacion_hijo,
                'rabietas'             => $rabietas,
                'llora'                => $llora,
                'sostenimiento'        => $sostenimiento,
                'sentarse'             => $sentarse,
                'equilibrio'           => $equilibrio,
                'gateo'                => $gateo,
                'caminar'              => $caminar,
                'seguimiento'          => $seguimiento,
                'agarre'               => $agarre,
                'abotonarse'           => $abotonarse,
                'recorte'              => $recorte,
                'trazo'                => $trazo,
                'lateralidad'          => $lateralidad,
                'comprension'          => $comprension,
                'lectoescritura'       => $lectoescritura,
                'lengua'               => $lengua,
                'lenguaje_actual'      => $lenguaje_actual,
                'ludica'               => $ludica,
                'afecto'               => $afecto,
                'normas'               => $normas,
                'reaccion'             => $reaccion,
                'llora_fac'            => $llora_fac,
                'pataleta'             => $pataleta,
                'agresividad'          => $agresividad,
                'tics'                 => $tics,
                'fobias'               => $fobias,
                'mentiras'             => $mentiras,
                'insomnio'             => $insomnio,
                'alimentacion'         => $alimentacion,
                'estomacal'            => $estomacal,
                'alergias'             => $alergias,
                'esfinteres'           => $esfinteres,
                'edad_escolar'         => $edad_escolar,
                'nombre_coleg'         => $nombre_coleg,
                'adaptacion'           => $adaptacion,
                'rel_comp'             => $rel_comp,
                'rel_prof'             => $rel_prof,
                'fortaleza'            => $fortaleza,
                'dif_academico'        => $dif_academico,
                'ref_academico'        => $ref_academico,
                'anio_perd'            => $anio_perd,
                'neurodesarrollo'      => $neurodesarrollo,
                'fono'                 => $fono,
                'psico_clinica'        => $psico_clinica,
                'psico_aprend'         => $psico_aprend,
                'terapia'              => $terapia,
                'otra'                 => $otra,
                'observacion'          => $observacion,
                'con_duerme'           => $con_duerme,
            );

            $guardar = ModeloPadres::guardarFormatoModel($datos);

            if ($guardar == true) {

                $formato = ModeloPadres::formatoLlenoModel($id_acudiente);

                if ($formato == true) {

                    if ($_POST['nivel'] == 2) {
                        $correo = 'alexandra.salzedo@royalschool.edu.co';
                    }

                    if ($_POST['nivel'] == 3) {
                        $correo = 'zuleima.walker@royalschool.edu.co';
                    }

                    if ($_POST['nivel'] == 4) {
                        $correo = 'gloria.romero@royalschool.edu.co';
                    }

                    $datos_correo = array(
                        'asunto'  => 'Admisiones - Formato clinico',
                        'mensaje' => 'El usuario ' . $_POST['nombre_completo'] . ' ha completado el formato de historia clinica',
                        'user'    => 'Admisiones colegio real',
                        //'correo' => 'jesuspolo00@gmail.com'
                        'correo'  => $correo,
                        'archivo' => array(''),
                    );

                    $envio = Correo::enviarCorreoModel($datos_correo);

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("salir");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Ha ocurrido un error", {color: "red"});
                    </script>
                    ';
                }
            }
        }
    }

    public function guardarHermanoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['nombre']) &&
            !empty($_POST['nombre']) &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log']) &&
            isset($_POST['tipo_rel']) &&
            !empty($_POST['tipo_rel'])
        ) {
            $datos = array(
                'nombre'   => $_POST['nombre'],
                'edad'     => $_POST['edad'],
                'tipo_rel' => $_POST['tipo_rel'],
                'id_log'   => $_POST['id_log'],
            );

            $guardar = ModeloPadres::guardarHermanoModel($datos);

            return $guardar;
        }
    }

    public function confirmarFormatoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_formato']) &&
            !empty($_POST['id_formato'])
        ) {
            //$id_acudiente = $_POST['id_acudiente'];
            $nombre_form          = $_POST['nombre_form'];
            $fecha_lug            = $_POST['fecha_lug'];
            $edad_form            = $_POST['edad_form'];
            $nombre_padre         = $_POST['nombre_padre'];
            $edad_padre           = $_POST['edad_padre'];
            $prof_padre           = $_POST['prof_padre'];
            $ocup_padre           = $_POST['ocup_padre'];
            $nombre_madre         = $_POST['nombre_madre'];
            $edad_madre           = $_POST['edad_madre'];
            $prof_madre           = $_POST['prof_madre'];
            $ocup_madre           = $_POST['ocup_madre'];
            $tipo_union           = $_POST['tipo_union'];
            $cant_hermanos        = $_POST['cant_hermanos'];
            $per_sign             = $_POST['per_sign'];
            $psicol               = $_POST['psicol'];
            $nivel_familiar       = $_POST['nivel_familiar'];
            $embarazo             = $_POST['embarazo'];
            $tiempo_gest          = $_POST['tiempo_gest'];
            $parto                = $_POST['parto'];
            $forcep               = $_POST['forcep'];
            $dificultades         = $_POST['dificultades'];
            $req_ox               = $_POST['req_ox'];
            $lloro                = $_POST['lloro'];
            $ictericia            = $_POST['ictericia'];
            $anoxia               = $_POST['anoxia'];
            $convulsiono          = $_POST['convulsiono'];
            $erupciones           = $_POST['erupciones'];
            $tiempo_hos           = $_POST['tiempo_hos'];
            $estado_madre         = $_POST['estado_madre'];
            $estado_emocion_madre = $_POST['estado_emocion_madre'];
            $estado_nino          = $_POST['estado_nino'];
            $aliment_nino         = $_POST['aliment_nino'];
            $juega_nino           = $_POST['juega_nino'];
            $tetero               = $_POST['tetero'];
            $cuchara              = $_POST['cuchara'];
            $alimentacion_hijo    = $_POST['alimentacion_hijo'];
            $rabietas             = $_POST['rabietas'];
            $llora                = $_POST['llora'];
            $sostenimiento        = $_POST['sostenimiento'];
            $sentarse             = $_POST['sentarse'];
            $equilibrio           = $_POST['equilibrio'];
            $gateo                = $_POST['gateo'];
            $caminar              = $_POST['caminar'];
            $seguimiento          = $_POST['seguimiento'];
            $agarre               = $_POST['agarre'];
            $abotonarse           = $_POST['abotonarse'];
            $recorte              = $_POST['recorte'];
            $trazo                = $_POST['trazo'];
            $lateralidad          = $_POST['lateralidad'];
            $comprension          = $_POST['comprension'];
            $lectoescritura       = $_POST['lectoescritura'];
            $lengua               = $_POST['lengua'];
            $lenguaje_actual      = $_POST['lenguaje_actual'];
            $ludica               = $_POST['ludica'];
            $afecto               = $_POST['afecto'];
            $normas               = $_POST['normas'];
            $reaccion             = $_POST['reaccion'];
            $llora_fac            = $_POST['llora_fac'];
            $pataleta             = $_POST['pataleta'];
            $agresividad          = $_POST['agresividad'];
            $tics                 = $_POST['tics'];
            $fobias               = $_POST['fobias'];
            $mentiras             = $_POST['mentiras'];
            $insomnio             = $_POST['insomnio'];
            $con_duerme           = $_POST['con_duerme'];
            $alimentacion         = $_POST['alimentacion'];
            $estomacal            = $_POST['estomacal'];
            $alergias             = $_POST['alergias'];
            $esfinteres           = $_POST['esfinteres'];
            $edad_escolar         = $_POST['edad_escolar'];
            $nombre_coleg         = $_POST['nombre_coleg'];
            $adaptacion           = $_POST['adaptacion'];
            $rel_comp             = $_POST['rel_comp'];
            $rel_prof             = $_POST['rel_prof'];
            $fortaleza            = $_POST['fortaleza'];
            $dif_academico        = $_POST['dif_academico'];
            $ref_academico        = $_POST['ref_academico'];
            $anio_perd            = $_POST['anio_perd'];
            $neurodesarrollo      = $_POST['neurodesarrollo'];
            $fono                 = $_POST['fono'];
            $psico_clinica        = $_POST['psico_clinica'];
            $psico_aprend         = $_POST['psico_aprend'];
            $terapia              = $_POST['terapia'];
            $otra                 = $_POST['otra'];
            $observacion          = $_POST['observacion'];
            $id_formato           = $_POST['id_formato'];

            $datos = array(
                'id_formato'           => $id_formato,
                'nombre_form'          => $nombre_form,
                'fecha_lug'            => $fecha_lug,
                'edad_form'            => $edad_form,
                'nombre_padre'         => $nombre_padre,
                'edad_padre'           => $edad_padre,
                'prof_padre'           => $prof_padre,
                'ocup_padre'           => $ocup_padre,
                'nombre_madre'         => $nombre_madre,
                'edad_madre'           => $edad_madre,
                'prof_madre'           => $prof_madre,
                'ocup_madre'           => $ocup_madre,
                'tipo_union'           => $tipo_union,
                'cant_hermanos'        => $cant_hermanos,
                'per_sign'             => $per_sign,
                'psicol'               => $psicol,
                'nivel_familiar'       => $nivel_familiar,
                'embarazo'             => $embarazo,
                'tiempo_gest'          => $tiempo_gest,
                'parto'                => $parto,
                'forcep'               => $forcep,
                'dificultades'         => $dificultades,
                'req_ox'               => $req_ox,
                'lloro'                => $lloro,
                'ictericia'            => $ictericia,
                'anoxia'               => $anoxia,
                'convulsiono'          => $convulsiono,
                'erupciones'           => $erupciones,
                'tiempo_hos'           => $tiempo_hos,
                'estado_madre'         => $estado_madre,
                'estado_emocion_madre' => $estado_emocion_madre,
                'estado_nino'          => $estado_nino,
                'aliment_nino'         => $aliment_nino,
                'juega_nino'           => $juega_nino,
                'tetero'               => $tetero,
                'cuchara'              => $cuchara,
                'alimentacion_hijo'    => $alimentacion_hijo,
                'rabietas'             => $rabietas,
                'llora'                => $llora,
                'sostenimiento'        => $sostenimiento,
                'sentarse'             => $sentarse,
                'equilibrio'           => $equilibrio,
                'gateo'                => $gateo,
                'caminar'              => $caminar,
                'seguimiento'          => $seguimiento,
                'agarre'               => $agarre,
                'abotonarse'           => $abotonarse,
                'recorte'              => $recorte,
                'trazo'                => $trazo,
                'lateralidad'          => $lateralidad,
                'comprension'          => $comprension,
                'lectoescritura'       => $lectoescritura,
                'lengua'               => $lengua,
                'lenguaje_actual'      => $lenguaje_actual,
                'ludica'               => $ludica,
                'afecto'               => $afecto,
                'normas'               => $normas,
                'reaccion'             => $reaccion,
                'llora_fac'            => $llora_fac,
                'pataleta'             => $pataleta,
                'agresividad'          => $agresividad,
                'tics'                 => $tics,
                'fobias'               => $fobias,
                'mentiras'             => $mentiras,
                'insomnio'             => $insomnio,
                'alimentacion'         => $alimentacion,
                'estomacal'            => $estomacal,
                'alergias'             => $alergias,
                'esfinteres'           => $esfinteres,
                'edad_escolar'         => $edad_escolar,
                'nombre_coleg'         => $nombre_coleg,
                'adaptacion'           => $adaptacion,
                'rel_comp'             => $rel_comp,
                'rel_prof'             => $rel_prof,
                'fortaleza'            => $fortaleza,
                'dif_academico'        => $dif_academico,
                'ref_academico'        => $ref_academico,
                'anio_perd'            => $anio_perd,
                'neurodesarrollo'      => $neurodesarrollo,
                'fono'                 => $fono,
                'psico_clinica'        => $psico_clinica,
                'psico_aprend'         => $psico_aprend,
                'terapia'              => $terapia,
                'otra'                 => $otra,
                'observacion'          => $observacion,
                'con_duerme'           => $con_duerme,
            );

            $confirmar = ModeloPadres::confirmarFormatoModel($datos);

            if ($confirmar == true) {
                echo '
                <script>
                ohSnap("Confirmado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("../admisiones/index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Ha ocurrido un error", {color: "red"});
                </script>
                ';
            }
        }
    }
}
