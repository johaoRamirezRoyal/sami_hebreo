<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er    = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';

$instancia = ControlProveedor::singleton_proveedor();

$tipo       = (isset($_GET['tipo']) && $_GET['tipo'] == 'reevaluacion') ? 'reevaluacion' : 'evaluacion';
$id         = base64_decode($_GET['id']);
$evaluacion = $instancia->mostrarEvaluacionPdfControl($tipo, $id);

if (!$evaluacion) {
    exit('Evaluación no encontrada');
}

// Mismos aspectos que el formulario proveedor/evaluacion
$preguntas = array(
    1 => 'Las productos o servicios comprados o contratados cumplieron con las especificaciones.',
    2 => 'El producto y/o servicio cumple los precios acordados en la negociación',
    3 => 'El proveedor externo atiende de forma oportuna las solicitudes, quejas y/o reclamos',
    4 => 'El proveedor se responsabiliza por las garantias de los productos y/o servicios adquiridos por la institución',
    5 => 'El proveedor brinda plazos y facilidades de pago',
);

$titulo   = ($tipo == 'reevaluacion') ? 'REEVALUACIÓN' : 'EVALUACIÓN';
$total    = floatval($evaluacion['total']);
$criterio = ControlProveedor::criterioEvaluacion($total);

class MYPDF extends TCPDF
{
    public function Header()
    {
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetTextColor(127);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 10);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle($titulo . ' DE PROVEEDOR');
$pdf->SetSubject($titulo . ' DE PROVEEDOR');
$pdf->AddPage();

$pdf->setJPEGQuality(90);
$pdf->Image(PUBLIC_PATH . 'img/logo.png', 80, 10, 40);

$pdf->Ln(40);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 11);
$pdf->Cell(10);
$pdf->MultiCell(170, 0, $titulo . ' DE PROVEEDOR: ' . mb_strtoupper($evaluacion['nom_proveedor'], 'UTF-8'), 0, 'C');

if ($tipo == 'reevaluacion') {
    $fila_periodo = '<td><b>Periodo:</b> ' . $evaluacion['periodo'] . '</td>';
} else {
    $fila_periodo = '<td><b>Periodo evaluado:</b> ' . $evaluacion['fecha_inicio'] . ' a ' . $evaluacion['fecha_finalizacion'] . '</td>';
}

$datos = '
<table cellpadding="3" style="font-size:9px;">
<tr>
<td><b>Identificación:</b> ' . $evaluacion['num_identificacion'] . '</td>
<td><b>Fecha de evaluación:</b> ' . $evaluacion['fecha_evaluacion'] . '</td>
</tr>
<tr>
<td><b>Evaluador:</b> ' . $evaluacion['nom_evaluador'] . '</td>
' . $fila_periodo . '
</tr>
</table>
';

$pdf->Ln(8);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->Cell(10);
$pdf->writeHTMLCell(170, 0, '', '', $datos, '', 1, 0, true, 'L', true);

$tabla = '
<table border="1" cellpadding="4" style="font-size:8.5px;">
<tr style="text-align:center; font-weight:bold; background-color:#eeeeee;">
<th style="width:50%;">ASPECTO EVALUADO</th>
<th style="width:15%;">CALIFICACIÓN (1-5)</th>
<th style="width:35%;">OBSERVACIONES</th>
</tr>
';

foreach ($preguntas as $num => $pregunta) {
    $tabla .= '
    <tr>
    <td>' . $pregunta . '</td>
    <td style="text-align:center;">' . $evaluacion['pregunta_' . $num] . '</td>
    <td>' . htmlspecialchars((string) $evaluacion['observacion_' . $num]) . '</td>
    </tr>
    ';
}

$tabla .= '
<tr style="font-weight:bold;">
<td>RESULTADO</td>
<td style="text-align:center;">' . round($total, 2) . '</td>
<td>' . round($total * 20, 1) . '% - ' . mb_strtoupper($criterio, 'UTF-8') . '</td>
</tr>
</table>
';

$pdf->Ln(5);
$pdf->Cell(10);
$pdf->writeHTMLCell(170, 0, '', '', $tabla, '', 1, 0, true, 'L', true);

$escala = '
<p style="font-size:8px;">
<b>Criterio de confiabilidad:</b> Muy confiable (4.5 - 5) · Confiable (4.0 - 4.49) · Aceptable (3.6 - 3.99) · No confiable (menor a 3.6)
</p>
';

$pdf->Ln(3);
$pdf->Cell(10);
$pdf->writeHTMLCell(170, 0, '', '', $escala, '', 1, 0, true, 'L', true);

$pdf->Output($titulo . '_proveedor_' . $evaluacion['num_identificacion'] . '.pdf', 'I');
