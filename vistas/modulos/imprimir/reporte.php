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
require_once CONTROL_PATH . 'reportes' . DS . 'ControlReportes.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlReporte::singleton_reporte();
$instancia_perfil = ControlPerfil::singleton_perfil();

if (isset($_GET['id'])) {

    $id_reporte    = base64_decode($_GET['id']);
    $datos_reporte = $instancia->mostrarArticuloReportadoControl($id_reporte);

    //Reporte Generado
    $id_reporte_anterior = $datos_reporte['id_reporte'];

    $datos_reporte_anterior = $instancia->mostrarArticuloReportadoControl($id_reporte_anterior);
    $fecha_reporte = ($datos_reporte_anterior['fecha_reporte'] != null) ? $datos_reporte_anterior['fecha_reporte'] : $datos_reporte['fecha_reporte'];
    
    

    $firmas_reporte = $instancia->mostrarInformacionSolucionReporteControl($id_reporte);
    $firma_reportado = $firmas_reporte['firma_responsable'];
    $firma_solucionado = ($firmas_reporte['firma_solucionado'] != null) ? $firmas_reporte['firma_solucionado'] : '';

    $estado = ($datos_reporte['estado'] == 2) ? 'Dañado' : 'Mantenimiento';

    
    $fecha_respuesta = ($datos_reporte['fecha_respuesta'] != null) ? $datos_reporte['fecha_respuesta'] : '';
    $fecha_respuesta_formateada = ($fecha_respuesta != '') ? date('Y-m-d', strtotime($fecha_respuesta)) : '';

    //Observación del reporte
    $observacion_reporte = ($datos_reporte_anterior['observacion'] != null) ? $datos_reporte_anterior['observacion'] : $datos_reporte['observacion'];

    //Respuesta del reporte
    $respuesta_reporte = ($datos_reporte_anterior['observacion'] != null) ? $datos_reporte['observacion'] : '';
}

class MYPDF extends TCPDF
{

    public function setData($logo)
    {
        $this->logo = $logo;
    }

    public function Header()
    {
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFillColor(127);
        $this->SetTextColor(127);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 10);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SAMI');
$pdf->SetTitle('Reporte');
$pdf->SetSubject('Reporte');
$pdf->SetKeywords('Reporte');
$pdf->AddPage();

$pdf->Ln(0);
$pdf->Cell(5);
$html = '
<table style="width:98%;" border="1" cellpadding="2">
<tr style="text-align:center; font-size: 0.8em; font-weight: bold;">
<td colspan="2" style="border:none; width:33%;" rowspan="1"><img src="' . PUBLIC_PATH . 'img/logo.png" border="0" width="55"></td>
<td colspan="3" rowspan="1" style="border:none; width:46%;">
<br>
<br>
REPORTE OPERATIVO
</td>
<td colspan="1" rowspan="1" style="border:none; width:20%;">
<br>
VERS&Oacute;N 01
<br>
15-08-2017
<br>
1-1
</td>
</tr>
</table>';

$pdf->writeHTMLCell(185, 0, '', '', $html, '', 1, 0, true, 'C', true);

$pdf->Ln(5);
$encabezado = '
<table cellpadding="2" cellspacing="10" style="width: 100%; font-size: 0.9em;">
<tr>
<td style="width: 33%;"><strong>Nombre:</strong> ' . $datos_reporte['nom_user'] . '</td>
<td style="width: 33%;"><strong>Area:</strong> ' . $datos_reporte['nom_area'] . '</td>
<td style="width: 33%;"><strong>Fecha:</strong> ' . $datos_reporte['fechareg'] = date('d-m-Y', strtotime($datos_reporte['fechareg'])) . '</td>
</tr>
</table>
';

$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->Cell(10);
$pdf->writeHTMLCell(200, 0, '', '', $encabezado, '', 1, 0, true, 'L', true);

$pdf->Ln(5);
$pdf->Cell(5);

$tabla = '
<table cellpadding="2" border="1" style="font-size:8.5px; width:100%; font-size: 0.8em; ">
<tr style="text-align:center; font-weight:bold; text-transform: uppercase;">
<th style="width: 10%;">ID</th>
<th style="width: 33%;">DESCRIPCION</th>
<th style="width: 20%;">MARCA</th>
<th style="width: 10%;">CANTIDAD</th>
<th style="width: 26%;">ESTADO</th>
</tr>
<tr style="text-align:center;">
<td>' . $datos_reporte['id'] . '</td>
<td>' . $datos_reporte['descripcion'] . '</td>
<td>' . $datos_reporte['marca'] . '</td>
<td>' . 1 . '</td>
<td>' . $estado . '</td>
</tr>
<tr style="text-align:center;">
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<tr style="text-align:center;">
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>
';
$pdf->writeHTML($tabla, true, false, true, false, '');

$pdf->Ln(5);
$pdf->Cell(25);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
$pdf->Cell(15, 5, $datos_reporte['nom_user'], 0, 0, 'C');

$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
$pdf->Ln(-2);

$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');



$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');



$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(65, 12, 'Reporte Realizado Por', 0, 0, 'C');
$pdf->Cell(65, 12, 'V°B° Directora Administrativa', 0, 0, 'C');
$pdf->Cell(65, 12, 'Reporte Remitido A', 0, 0, 'C');
$ln = 15;
$pdf->Ln($ln);
$observacion = '
<p>
<b>Observacion:</b> ' . $datos_reporte['observacion'] . '
</p>
';
$pdf->Cell(5);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->writeHTML($observacion, true, false, true, false, '');
$pdf->Ln(5);
$pie = '
<table cellpadding="3"  style="width:100%; font-size: 0.9em; ">
<tr>
<td style="width: 60%;"><b>Solicitud Recibida Por:</b> _______________________________________________</td>
<td style="width: 20%;"><b>Hora:</b> ____________</td>
<td style="width: 20%;"><b>Fecha:</b> ____________</td>
</tr>
<tr>
<td colspan="2"><b>Solucionado Por:</b> _______________________________________________________________________</td>
<td><b>Fecha:</b> ____________</td>
</tr>
<tr>
<td colspan="2"><b>Recibido Conforme Por:</b> _________________________________________________________________</td>
<td><b>Fecha:</b> ____________</td>
</tr>
</table>
';
$pdf->Cell(5);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->writeHTML($pie, true, false, true, false, '');
ob_end_clean();
$pdf->Output('reporte_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
