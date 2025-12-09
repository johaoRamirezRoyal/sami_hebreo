<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['nombre_admin'] && $_SESSION['rol'] != 1 && $_SESSION['rol'] != 4) {
    $er = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}
require_once LIB_PATH . 'fpdf' . DS . 'fpdf.php';
require_once CONTROL_PATH . 'admisiones' . DS . 'ControlAdmisiones.php';

class PDF extends FPDF
{
    const LINE_PRECISION = 5;
    const DEBUG_PDF = 0;
    const BORDER = 0;

    function reducirT($tam, $alt, $string, $tamanio, $salto, $ali)
    {
        if ($string == "--" && !PDF::DEBUG_PDF) {
            return;
        }
        if (!isset($string)) {
            $string = "";
        }
        $tamanio = $tamanio * 10;

        $this->SetFont('Arial', '', $tamanio);  // Set the new font size

        while ($this->GetStringWidth(utf8_decode($string)) > $tam * 1.1) {
            $tamanio--;   // Decrease the variable which holds the font size
            $this->SetFont('Arial', '', $tamanio);  // Set the new font size
        }
        $this->Cell($tam, $alt, trim(utf8_decode($string)), PDF::BORDER, $salto, $ali);

        if (!isset($string)) {
            $string = "";
        }
    }


    function reducirMultiT($tam, $alt, $string, $tamanio, $ali)
    {
        if ($string == "--" && !PDF::DEBUG_PDF) {
            return;
        }
        if (!isset($string)) {
            $string = "";
        }
        $tamanio = $tamanio * 10;

        $this->SetFont('Arial', '', $tamanio);  // Set the new font size

        while ($this->GetStringWidth(utf8_decode($string)) > $tam * 1.1) {
            $tamanio--;   // Decrease the variable which holds the font size
            $this->SetFont('Arial', '', $tamanio);  // Set the new font size
        }
        $this->MultiCell($tam, $alt, trim(utf8_decode($string)), PDF::BORDER, $ali);

        if (!isset($string)) {
            $string = "";
        }
    }

    private function grilla()
    {
        $this->SetDrawColor(255, 0, 0);
        if (PDF::DEBUG_PDF) {
            for ($i = 0; $i < 300; $i += PDF::LINE_PRECISION) {
                $this->setXY(0, $i);
                $this->Cell(3.5, 2.5, $i, PDF::BORDER, 0, 'C');
                $this->SetLineWidth(0.005);
                $this->Line(0, $i, 500, $i);
            }
            for ($i = 0; $i < 2000; $i += PDF::LINE_PRECISION) {
                $this->setXY($i, 0);
                $this->Cell(3.5, 2.5, $i, PDF::BORDER, 0, 'C');
                $this->SetLineWidth(0.005);
                $this->Line($i, 0, $i, 2000);
            }
        }
        $this->SetDrawColor(0, 58, 255);
    }

    public function generar($id)
    {

        $instancia = ControlAdmisiones::singleton_admisiones();
        $datos_formato = $instancia->datosClinicosControl($id);

        $fecha = $datos_formato['fechareg'];
        $nombre_form = $datos_formato['nombre_form'];
        $fecha_lug = $datos_formato['fecha_lug'];
        $edad_form = $datos_formato['edad_form'];
        $nombre_padre = $datos_formato['nombre_padre'];
        $edad_padre = $datos_formato['edad_padre'];
        $prof_padre = $datos_formato['prof_padre'];
        $ocup_padre = $datos_formato['ocup_padre'];
        $nombre_madre = $datos_formato['nombre_madre'];
        $edad_madre = $datos_formato['edad_madre'];
        $prof_madre = $datos_formato['prof_madre'];
        $ocup_madre = $datos_formato['ocup_madre'];
        $tipo_union = $datos_formato['tipo_union'];
        $cant_hermanos = $datos_formato['cant_hermanos'];
        $per_sign = $datos_formato['per_sign'];
        $psicol = $datos_formato['psicol'];
        $nivel_familiar = $datos_formato['nivel_familiar'];
        $embarazo = $datos_formato['embarazo'];
        $tiempo_gest = $datos_formato['tiempo_gest'];
        $parto = $datos_formato['parto'];
        $forcep = $datos_formato['forcep'];
        $dificultades = $datos_formato['dificultades'];
        $req_ox = $datos_formato['req_ox'];
        $lloro = $datos_formato['lloro'];
        $ictericia = $datos_formato['ictericia'];
        $anoxia = $datos_formato['anoxia'];
        $convulsiono = $datos_formato['convulsiono'];
        $erupciones = $datos_formato['erupciones'];
        $tiempo_hos = $datos_formato['tiempo_hos'];
        $estado_madre = $datos_formato['estado_madre'];
        $estado_emocion_madre = $datos_formato['estado_emocion_madre'];
        $estado_nino = $datos_formato['estado_nino'];
        $aliment_nino = $datos_formato['aliment_nino'];
        $juega_nino = $datos_formato['juega_nino'];
        $tetero = $datos_formato['tetero'];
        $cuchara = $datos_formato['cuchara'];
        $alimentacion_hijo = $datos_formato['alimentacion_hijo'];
        $rabietas = $datos_formato['rabietas'];
        $llora = $datos_formato['llora'];
        $sostenimiento = $datos_formato['sostenimiento'];
        $sentarse = $datos_formato['sentarse'];
        $equilibrio = $datos_formato['equilibrio'];
        $gateo = $datos_formato['gateo'];
        $caminar = $datos_formato['caminar'];
        $seguimiento = $datos_formato['seguimiento'];
        $agarre = $datos_formato['agarre'];
        $abotonarse = $datos_formato['abotonarse'];
        $recorte = $datos_formato['recorte'];
        $trazo = $datos_formato['trazo'];
        $lateralidad = $datos_formato['lateralidad'];
        $comprension = $datos_formato['comprension'];
        $lectoescritura = $datos_formato['lectoescritura'];
        $lengua = $datos_formato['lengua'];
        $lenguaje_actual = $datos_formato['lenguaje_actual'];
        $ludica = $datos_formato['ludica'];
        $afecto = $datos_formato['afecto'];
        $normas = $datos_formato['normas'];
        $reaccion = $datos_formato['reaccion'];
        $llora_fac = $datos_formato['llora_fac'];
        $pataleta = $datos_formato['pataleta'];
        $agresividad = $datos_formato['agresividad'];
        $tics = $datos_formato['tics'];
        $fobias = $datos_formato['fobias'];
        $mentiras = $datos_formato['mentiras'];
        $insomnio = $datos_formato['insomnio'];
        $con_duerme = $datos_formato['con_duerme'];
        $alimentacion = $datos_formato['alimentacion'];
        $estomacal = $datos_formato['estomacal'];
        $alergias = $datos_formato['alergias'];
        $esfinteres = $datos_formato['esfinteres'];
        $edad_escolar = $datos_formato['edad_escolar'];
        $nombre_coleg = $datos_formato['nombre_coleg'];
        $adaptacion = $datos_formato['adaptacion'];
        $rel_comp = $datos_formato['rel_comp'];
        $rel_prof = $datos_formato['rel_prof'];
        $fortaleza = $datos_formato['fortaleza'];
        $dif_academico = $datos_formato['dif_academico'];
        $ref_academico = $datos_formato['ref_academico'];
        $anio_perd = $datos_formato['anio_perd'];
        $neurodesarrollo = $datos_formato['neurodesarrollo'];
        $fono = $datos_formato['fono'];
        $psico_clinica = $datos_formato['psico_clinica'];
        $psico_aprend = $datos_formato['psico_aprend'];
        $terapia = $datos_formato['terapia'];
        $otra = $datos_formato['otra'];
        $observacion = $datos_formato['observacion'];

        $this->SetAutoPageBreak(false);
        $this->AddPage();
        $this->pagina1(
            $id,
            $fecha,
            $nombre_form,
            $fecha_lug,
            $edad_form,
            $nombre_padre,
            $edad_padre,
            $prof_padre,
            $ocup_padre,
            $nombre_madre,
            $edad_madre,
            $prof_madre,
            $ocup_madre,
            $tipo_union,
            $cant_hermanos,
            $per_sign,
            $psicol
        );
        $this->AddPage();
        $this->pagina2(
            $nivel_familiar,
            $embarazo,
            $tiempo_gest,
            $parto,
            $forcep,
            $dificultades,
            $req_ox,
            $lloro,
            $ictericia,
            $anoxia,
            $convulsiono,
            $erupciones,
            $tiempo_hos,
            $estado_madre,
            $estado_emocion_madre,
            $estado_nino,
            $aliment_nino,
            $juega_nino,
            $tetero,
            $cuchara,
            $alimentacion_hijo,
            $rabietas,
            $llora,
            $sostenimiento,
            $sentarse,
            $equilibrio,
            $gateo,
            $caminar,
            $seguimiento,
            $agarre,
            $abotonarse,
            $recorte,
            $trazo,
            $lateralidad
        );
        $this->AddPage();
        $this->pagina3(
            $comprension,
            $lectoescritura,
            $lengua,
            $lenguaje_actual,
            $ludica,
            $afecto,
            $normas,
            $reaccion,
            $llora_fac,
            $pataleta,
            $agresividad,
            $tics,
            $fobias,
            $mentiras,
            $insomnio,
            $alimentacion,
            $estomacal,
            $alergias,
            $esfinteres,
            $edad_escolar,
            $nombre_coleg,
            $adaptacion,
            $rel_comp,
            $rel_prof,
            $fortaleza,
            $dif_academico,
            $ref_academico,
            $anio_perd,
            $con_duerme
        );
        $this->AddPage();
        $this->pagina4(
            $neurodesarrollo,
            $fono,
            $psico_clinica,
            $psico_aprend,
            $terapia,
            $otra,
            $observacion
        );
    }

    private function pagina1(
        $id,
        $fecha,
        $nombre_form,
        $fecha_lug,
        $edad_form,
        $nombre_padre,
        $edad_padre,
        $prof_padre,
        $ocup_padre,
        $nombre_madre,
        $edad_madre,
        $prof_madre,
        $ocup_madre,
        $tipo_union,
        $cant_hermanos,
        $per_sign,
        $psicol
    ) {
        $this->Image('../pdfs/historia_clinica/historia_clinica_page-0001.jpg', '0', '0', '210', '297', 'JPG');
        $this->grilla();
        // FECHA
        //DIA
        $this->SetXY(41, 58);
        $this->reducirT(32, 7, date('d', strtotime($fecha)), 1, 0, 'C');
        //MES
        $this->SetXY(73, 58);
        $this->reducirT(32, 7, date('m', strtotime($fecha)), 1, 0, 'C');
        //ANIO
        $this->SetXY(105, 58);
        $this->reducirT(32, 7, date('Y', strtotime($fecha)), 1, 0, 'C');


        //IDENTIFICACION
        //NOMBRE
        $this->SetXY(41, 79);
        $this->reducirT(140, 6, $nombre_form, 1, 0, 'C');

        //FECHA LUGAR DE NACIMIENTO
        $this->SetXY(81, 85);
        $this->reducirT(100, 6, $fecha_lug, 1, 0, 'C');

        //EDAD
        $this->SetXY(36, 91);
        $this->reducirT(25, 6, $edad_form, 1, 0, 'C');

        //ESTRUCTURA FAMILIAR
        //NOMBRE PADRE
        $this->SetXY(60, 110);
        $this->reducirT(90, 6, $nombre_padre, 1, 0, 'C');

        //EDAD PADRE
        $this->SetXY(162, 110);
        $this->reducirT(20, 6, $edad_padre, 1, 0, 'C');

        //PROFESION PADRE
        $this->SetXY(44, 116);
        $this->reducirT(70, 6, $prof_padre, 1, 0, 'C');

        //OPCUPACION PADRE
        $this->SetXY(138, 116);
        $this->reducirT(45, 6, $ocup_padre, 1, 0, 'C');

        //NOMBRE MADRE
        $this->SetXY(65, 122);
        $this->reducirT(83, 6, $nombre_madre, 1, 0, 'C');

        //EDAD MADRE
        $this->SetXY(163, 122);
        $this->reducirT(20, 6, $edad_madre, 1, 0, 'C');

        //PROFESION MADRE
        $this->SetXY(43, 129);
        $this->reducirT(72, 6, $prof_madre, 1, 0, 'C');

        //OCUPACION MADRE
        $this->SetXY(139, 129);
        $this->reducirT(45, 6, $ocup_madre, 1, 0, 'C');

        //RELACION PADRES
        $this->SetXY(101, 135);
        $this->reducirT(80, 6, $tipo_union, 1, 0, 'C');

        //NUMERO DE HERMANOS
        $this->SetXY(67, 141);
        $this->reducirT(22, 6, $cant_hermanos, 1, 0, 'C');


        $instancia = ControlAdmisiones::singleton_admisiones();
        $hermanos = $instancia->mostrarHermanosControl($id);
        $altura = 164;

        foreach ($hermanos as $row) {
            //NOMBRE HERMANO
            $this->SetXY(30, $altura);
            $this->reducirT(48, 8, $row['nombre'], 1, 0, 'C');

            //EDAD HERMANO
            $this->SetXY(79, $altura);
            $this->reducirT(26, 8, $row['edad'], 1, 0, 'C');

            //TIPO DE RELACION
            $this->SetXY(105, $altura);
            $this->reducirT(72, 8, $row['tipo_rel'], 1, 0, 'C');

            $altura = 8 + $altura;
        }


        //OTRAS PERSONAS
        $this->SetXY(25.2, 220);
        $this->reducirT(160, 11, $per_sign, 1, 0, 'L');


        //ANTECEDENTES
        $this->SetXY(25.2, 261);
        $this->reducirT(160, 15, $psicol, 1, 0, 'L');
    }

    private function pagina2(
        $nivel_familiar,
        $embarazo,
        $tiempo_gest,
        $parto,
        $forcep,
        $dificultades,
        $req_ox,
        $lloro,
        $ictericia,
        $anoxia,
        $convulsiono,
        $erupciones,
        $tiempo_hos,
        $estado_madre,
        $estado_emocion_madre,
        $estado_nino,
        $aliment_nino,
        $juega_nino,
        $tetero,
        $cuchara,
        $alimentacion_hijo,
        $rabietas,
        $llora,
        $sostenimiento,
        $sentarse,
        $equilibrio,
        $gateo,
        $caminar,
        $seguimiento,
        $agarre,
        $abotonarse,
        $recorte,
        $trazo,
        $lateralidad
    ) {
        $this->Image('../pdfs/historia_clinica/historia_clinica_page-0002.jpg', '0', '0', '210', '297', 'JPG');
        $this->grilla();

        //ALGO RELEVANTE
        $this->SetXY(25, 52);
        $this->reducirT(160, 15.5, $nivel_familiar, 1, 0, 'L');

        //EMBARAZO
        $this->SetXY(80, 84);
        $this->reducirT(32, 6, $embarazo, 1, 0, 'C');

        //TIEMPO DE GESTACION
        $this->SetXY(155, 84);
        $this->reducirT(39, 6, $tiempo_gest, 1, 0, 'C');

        //PARTO
        $this->SetXY(65, 91);
        $this->reducirT(45, 6, $parto, 1, 0, 'C');

        //FORCEPS
        $this->SetXY(145, 91);
        $this->reducirT(49, 6, $forcep, 1, 0, 'C');

        //DIFICULTADES
        $this->SetXY(80, 97);
        $this->reducirT(115, 6, $dificultades, 1, 0, 'C');

        //REQUIRIO OXIGENO
        $this->SetXY(75, 104);
        $this->reducirT(37, 6, $req_ox, 1, 0, 'C');

        //LLORO AL NACER
        $this->SetXY(175, 104);
        $this->reducirT(20, 6, $lloro, 1, 0, 'C');

        //PRESENTO ICTERIA
        $this->SetXY(85, 110);
        $this->reducirT(26, 6, $ictericia, 1, 0, 'C');

        //SUFRIO DE ANOXIA
        $this->SetXY(182, 110);
        $this->reducirT(12, 6, $anoxia, 1, 0, 'C');

        //CONVULSIONO
        $this->SetXY(43, 116.5);
        $this->reducirT(69, 6, $convulsiono, 1, 0, 'C');

        //ERUPCIONES
        $this->SetXY(176, 116.5);
        $this->reducirT(18, 6, $erupciones, 1, 0, 'C');

        //PERMANECIO
        $this->SetXY(120, 123);
        $this->reducirT(75, 6, $tiempo_hos, 1, 0, 'C');

        //ESTADO DE SALUD
        $this->SetXY(71, 130);
        $this->reducirT(124, 6, $estado_madre, 1, 0, 'C');

        //ESTADO EMOCIONAL
        $this->SetXY(71, 136);
        $this->reducirT(124, 6, $estado_emocion_madre, 1, 0, 'C');

        //ESTADO DEL NINO
        $this->SetXY(63, 142);
        $this->reducirT(132, 6, $estado_nino, 1, 0, 'C');

        //ALIMENTACION DEL NINO
        $this->SetXY(87, 149);
        $this->reducirT(108, 6, $aliment_nino, 1, 0, 'C');

        //JUEGA
        $this->SetXY(80, 155);
        $this->reducirT(32, 6, $juega_nino, 1, 0, 'C');

        //TETERO
        $this->SetXY(178, 155);
        $this->reducirT(17, 6, $tetero, 1, 0, 'C');

        //CUCHARA
        $this->SetXY(90, 162);
        $this->reducirT(105, 6, $cuchara, 1, 0, 'C');

        //ALIMENTACION DE SU HIJO
        $this->SetXY(15, 174);
        $this->reducirT(180, 6, $alimentacion_hijo, 1, 0, 'C');

        //RABIETAS
        $this->SetXY(85, 181);
        $this->reducirT(110, 6, $rabietas, 1, 0, 'C');

        //LLORA FACILMENTE
        $this->SetXY(50, 187);
        $this->reducirT(145, 6, $llora, 1, 0, 'C');

        //SOSTENIMIENTO DE LA CABEZA
        $this->SetXY(70, 200);
        $this->reducirT(45, 6, $sostenimiento, 1, 0, 'C');

        //SENTARSE POR SI MISMO
        $this->SetXY(160, 200);
        $this->reducirT(35, 6, $sentarse, 1, 0, 'C');

        //EQUILIBRIO
        $this->SetXY(40, 207);
        $this->reducirT(75, 6, $equilibrio, 1, 0, 'C');

        //GATEO
        $this->SetXY(130, 207);
        $this->reducirT(65, 6, $gateo, 1, 0, 'C');

        //CAMINAR
        $this->SetXY(35, 215);
        $this->reducirT(80, 14, $caminar, 1, 0, 'C');

        //GATEO
        $this->SetXY(135, 222);
        $this->reducirT(60, 8, $seguimiento, 1, 0, 'C');

        //AGARRE DE LA PINSA
        $this->SetXY(50, 230);
        $this->reducirT(65, 7, $agarre, 1, 0, 'C');

        //ABOTONARSE
        $this->SetXY(140, 230);
        $this->reducirT(55, 7, $abotonarse, 1, 0, 'C');

        //RECORTE
        $this->SetXY(35, 238);
        $this->reducirT(80, 7, $recorte, 1, 0, 'C');

        //TRAZO
        $this->SetXY(130, 238);
        $this->reducirT(65, 7, $trazo, 1, 0, 'C');

        if ($lateralidad == 'derecha') {
            //DERECHA
            $this->SetXY(55, 245);
            $this->reducirT(22, 6, 'X', 1, 0, 'C');
        } else {
            //IZQUIERDO
            $this->SetXY(97, 245);
            $this->reducirT(22, 6, 'X', 1, 0, 'C');
        }
    }

    private function pagina3(
        $comprension,
        $lectoescritura,
        $lengua,
        $lenguaje_actual,
        $ludica,
        $afecto,
        $normas,
        $reaccion,
        $llora_fac,
        $pataleta,
        $agresividad,
        $tics,
        $fobias,
        $mentiras,
        $insomnio,
        $alimentacion,
        $estomacal,
        $alergias,
        $esfinteres,
        $edad_escolar,
        $nombre_coleg,
        $adaptacion,
        $rel_comp,
        $rel_prof,
        $fortaleza,
        $dif_academico,
        $ref_academico,
        $anio_perd,
        $con_duerme
    ) {
        $this->Image('../pdfs/historia_clinica/historia_clinica_page-0003.jpg', '0', '0', '210', '297', 'JPG');
        $this->grilla();

        //COMPRENSION
        $this->SetXY(115, 62);
        $this->reducirT(80, 6, $comprension, 1, 0, 'C');

        //LECTO ESCRITURA
        $this->SetXY(91, 69);
        $this->reducirT(104, 6, $lectoescritura, 1, 0, 'C');

        //SEGUNDA LENGUA
        $this->SetXY(77, 75);
        $this->reducirT(116, 6, $lengua, 1, 0, 'C');

        if ($lenguaje_actual == 'fluido') {
            //FLUIDO
            $this->SetXY(82, 81);
            $this->reducirT(22, 5, 'X', 1, 0, 'C');
        } else {
            //ESCASO
            $this->SetXY(120, 81);
            $this->reducirT(22, 5, 'X', 1, 0, 'C');
        }


        //LUDICA
        $this->SetXY(65, 104);
        $this->reducirT(130, 5, $ludica, 1, 0, 'C');

        //AFECTO
        $this->SetXY(15, 115);
        $this->reducirT(180, 7.4, $afecto, 1, 0, 'L');

        //NORMAS
        $this->SetXY(15, 128);
        $this->reducirT(180, 7.4, $normas, 1, 0, 'L');

        //REACCION
        $this->SetXY(15, 140);
        $this->reducirT(180, 7.4, $reaccion, 1, 0, 'L');

        //LLORA
        $this->SetXY(47, 148);
        $this->reducirT(30, 6, $llora_fac, 1, 0, 'C');

        //PATALETA
        $this->SetXY(98, 148);
        $this->reducirT(30, 6, $pataleta, 1, 0, 'C');

        //AGRESIVIDAD
        $this->SetXY(156, 148);
        $this->reducirT(39, 6, $agresividad, 1, 0, 'C');

        //TICS
        $this->SetXY(26, 155);
        $this->reducirT(49, 6, $tics, 1, 0, 'C');

        //FOBIAS
        $this->SetXY(96, 155);
        $this->reducirT(35, 6, $fobias, 1, 0, 'C');

        //MENTIRAS
        $this->SetXY(156, 155);
        $this->reducirT(35, 6, $mentiras, 1, 0, 'C');

        //INSOMNIO
        $this->SetXY(46, 161);
        $this->reducirT(31, 6, $insomnio, 1, 0, 'C');

        //CON QUIEN DUERME
        $this->SetXY(120, 161);
        $this->reducirT(75, 6, $con_duerme, 1, 0, 'C');

        //ALIMENTACION
        $this->SetXY(45, 168);
        $this->reducirT(150, 6, $esfinteres, 1, 0, 'C');

        //DIFICULTADES ESTOMACALES
        $this->SetXY(65, 174);
        $this->reducirT(130, 6, $estomacal, 1, 0, 'C');

        //ALERGIAS
        $this->SetXY(35, 180);
        $this->reducirT(160, 6, $alergias, 1, 0, 'C');

        //ESFINTAS
        $this->SetXY(120, 187);
        $this->reducirT(75, 6, $esfinteres, 1, 0, 'C');

        //EDAD ESCOLAR
        $this->SetXY(76, 210);
        $this->reducirT(118, 6, $edad_escolar, 1, 0, 'C');

        //NOMBRE COLEGIO
        $this->SetXY(70, 217);
        $this->reducirT(125, 6, $nombre_coleg, 1, 0, 'C');

        //ADAPTACION
        $this->SetXY(40, 223);
        $this->reducirT(155, 6, $adaptacion, 1, 0, 'C');

        //RELACIOM COMPAÑEROS
        $this->SetXY(65, 230);
        $this->reducirT(130, 6, $rel_comp, 1, 0, 'C');

        //RELACIOM PROFESORES
        $this->SetXY(65, 236);
        $this->reducirT(130, 6, $rel_prof, 1, 0, 'C');

        //FORTALEZAS
        $this->SetXY(98, 242.5);
        $this->reducirT(97, 6, $fortaleza, 1, 0, 'C');

        //DIFICULTADES
        $this->SetXY(98, 249);
        $this->reducirT(97, 6, $dif_academico, 1, 0, 'C');

        //REFUERZO
        $this->SetXY(78, 255);
        $this->reducirT(117, 6, $ref_academico, 1, 0, 'C');

        //ANIOS PERDIDOS
        $this->SetXY(60, 262);
        $this->reducirT(135, 6, $anio_perd, 1, 0, 'C');
    }

    private function pagina4(
        $neurodesarrollo,
        $fono,
        $psico_clinica,
        $psico_aprend,
        $terapia,
        $otra,
        $observacion
    ) {
        $this->Image('../pdfs/historia_clinica/historia_clinica_page-0004.jpg', '0', '0', '210', '297', 'JPG');
        $this->grilla();

        //NEURODESARROLLO
        $this->SetXY(47, 51);
        $this->reducirT(148, 6.5, $neurodesarrollo, 1, 0, 'C');

        //FONOAUDIOLOGIA
        $this->SetXY(45, 57.5);
        $this->reducirT(150, 6.5, $fono, 1, 0, 'C');

        //PSICOLOGIA CLINICA
        $this->SetXY(52, 64);
        $this->reducirT(143, 6.5, $psico_clinica, 1, 0, 'C');

        //PSICOLOGIA NIVEL DE APRENDIZAJE
        $this->SetXY(77, 70);
        $this->reducirT(118, 6.5, $psico_aprend, 1, 0, 'C');

        //TERAPIA
        $this->SetXY(57, 77);
        $this->reducirT(138, 6.5, $terapia, 1, 0, 'C');

        //OTRA
        $this->SetXY(27, 83);
        $this->reducirT(168, 6.5, $otra, 1, 0, 'C');

        //OBSERVACION
        $this->SetXY(25, 110);
        $this->reducirMultiT(160, 15, $observacion, 0, 'L');
    }
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 8);
$pdf->SetTitle("Formato historial clinico", true);
$pdf->generar(base64_decode($_GET['id_acudiente']));
$pdf->Output();
