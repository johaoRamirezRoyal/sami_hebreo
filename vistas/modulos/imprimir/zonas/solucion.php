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
require_once CONTROL_PATH . 'zonas' . DS . 'ControlZonas.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlZonas::singleton_zonas();
$instancia_perfil = ControlPerfil::singleton_perfil();

$super_empresa = $_SESSION['super_empresa'];

if (isset($_GET['reporte'])) {

    $id_reporte = base64_decode($_GET['reporte']);

    $datos_reporte       = $instancia->mostrarInformacionSolucionReporteControl($id_reporte);
    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'encabezado2');

    $firma_responsable = ($datos_reporte['firma_responsable'] == '') ? '<br><br><br>' . $datos_reporte['usuario_reporta'] : '<img style="width: 100px;
    height: 30px;
    padding: 10px;" src="' . PUBLIC_PATH . 'upload/' . $datos_reporte['firma_responsable'] . '" border="0" width="80">';

    $firma_solucionado_reporte = ($datos_reporte['firma_solucion'] == '') ? '<br><br><br>' . $datos_reporte['nom_user_respuesta'] : '<img style="width: 100px;
    height: 30px;
    padding: 10px;" src="' . PUBLIC_PATH . 'upload/' . $datos_reporte['firma_solucion'] . '" border="0" width="80">';

    $firma_administrativa    = ($datos_reporte['visto_bueno_respuesta'] == 0) ? '' : PUBLIC_PATH . 'img/firma_administrativa.jpg';
    $firma_administrativa_br = ($datos_reporte['visto_bueno_respuesta'] == 0) ? '<br><br><br>' : '';

    $fecha_reportado = ($datos_reporte['estado'] == 6) ? $datos_reporte['fecha_mantenimiento'] : $datos_reporte['fechareg'];

    $horas = date('H:i:s', strtotime($fecha_reportado));
    $fecha = date('Y-m-d', strtotime($fecha_reportado));

    $horas_solucion = date('H:i:s', strtotime($datos_reporte['fecha_respuesta_con']));
    $fecha_solucion = date('Y-m-d', strtotime($datos_reporte['fecha_respuesta_con']));

    class MYPDF extends TCPDF
    {

        public function setData($logo)
        {
            $this->logo = $logo;
        }

        public function Header()
        {
            /* $this->setJPEGQuality(90);
        $this->Image(PUBLIC_PATH . 'img/' . $this->logo, 0, 0, 210, 35);
        $this->Ln(30);
        $this->Cell(90);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);
        $this->Cell(12, 50, 'ENTREGA DE INVENTARIO', 0, 0, 'C'); */
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

    // create a PDF object
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->setData($datos_super_empresa['imagen']);
$pdf->SetAuthor('Jesus Polo');
$pdf->SetTitle('Reporte solucion');
$pdf->SetSubject('Reporte solucion');
$pdf->SetKeywords('Reporte solucion');
$pdf->AddPage();

$pdf->Ln(0);
$pdf->Cell(5);
$html = '
<table style="width:98%;" border="1" cellpadding="2">
<tr style="text-align:center; font-size: 0.8em; font-weight: bold;">
<td colspan="2" style="border:none; width:33%;" rowspan="1"><img src="' . PUBLIC_PATH . 'img/' . $datos_super_empresa['imagen'] . '" border="0" width="120"></td>
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

    // output the HTML content
$pdf->writeHTMLCell(185, 0, '', '', $html, '', 1, 0, true, 'C', true);

$pdf->Ln(5);
$encabezado = '
<table cellpadding="2" cellspacing="10" style="width: 100%; font-size: 0.9em;">
<tr>
<td style="width: 33%;"><strong>Nombre:</strong> ' . $datos_reporte['usuario_reporta'] . '</td>
<td style="width: 33%;"><strong>Area:</strong> ' . $datos_reporte['nom_zona'] . '</td>
<td style="width: 33%;"><strong>Fecha:</strong> ' . $fecha_reportado . '</td>
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
<th style="width: 43%;">DESCRIPCION</th>
<th style="width: 20%;">MARCA</th>
<th style="width: 26%;">ESTADO</th>
</tr>
<tr style="text-align:center;">
<td>' . $datos_reporte['id_zona'] . '</td>
<td>' . $datos_reporte['nom_area'] . '</td>
<td></td>
<td>' . $datos_reporte['nom_estado'] . '</td>
</tr>
<tr style="text-align:center;">
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
</tr>
</table>
';

$pdf->writeHTML($tabla, true, false, true, false, '');

$pdf->Ln(-3);
$encabezado = '
<table cellpadding="2" cellspacing="10" style="width: 87%; font-size: 0.9em;">
<tr>
<td style="width: 33%; text-align: center;">' . $firma_responsable . '<br>_________________________<br><b>Reporte Realizado Por</b></td>
<td style="width: 33%; text-align: center;"><img style="width: 100px;
height: 30px;
padding: 10px;" src="' . $firma_administrativa . '" border="0" width="80">' . $firma_administrativa_br . '_________________________<br><b>V°B° Directora Administrativa</b></td>
<td style="width:33 % ;
text-align:center;">' . $firma_solucionado_reporte . '<br>_________________________<br><b>Reporte Remitido a</b></td>
</tr>
</table>
';

$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->Cell(10);
$pdf->writeHTMLCell(200, 0, '', '', $encabezado, '', 1, 0, true, 'L', true);

$ln = 5;

$pdf->Ln($ln);
$observacion = '
<p>
<b>Observacion:</b> ' . $datos_reporte['observacion'] . '
</p>
';

$pdf->Cell(5);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->writeHTML($observacion, true, false, true, false, '');

$firma_solucionado = (empty($datos_reporte['firma_solucionado'])) ? '<u>' . $datos_reporte['nom_user_respuesta'] . '</u>' : '<img src="' . PUBLIC_PATH . 'upload/' . $datos_reporte['firma_solucionado'] . '" style="width:100px;
height:20px;
padding:10px;
margin-top:-5%;">';

$firma_responsable_reporte = (empty($datos_reporte['firma_responsable'])) ? '<u>' . $datos_reporte['usuario_reporta'] . '</u>' : '<img src="' . PUBLIC_PATH . 'upload/' . $datos_reporte['firma_responsable'] . '" style="width:100px;
height:20px;
padding:10px;
margin-top:-5%;">';

$pdf->Ln($ln);
$pie = '
<table cellpadding="3"  style="width:100%;font-size:0.9em;" border="0">
<tr>
<td style="width:60%;"><b>Solicitud Recibida Por:</b> ________' . $firma_solucionado . '__________________</td>
<td style="width:20%;" align="center" valign="bottom"><b>Hora:</b> ___<u>' . $horas . '</u>___</td>
<td style="width:20%;" align="center" valign="bottom"><b>Fecha:</b> __<u>' . $fecha . '</u>__</td>
</tr>
<tr>
<td colspan="2"><b>Solucionado Por:</b> ________' . $firma_solucionado . '__________________</td>
<td align="center" valign="bottom"><b>Fecha:</b> __<u>' . $fecha_solucion . '</u>__</td>
</tr>
<tr>
<td colspan="2"><b>Recibido Conforme Por:</b> ________' . $firma_responsable_reporte . '__________________</td>
<td align="center" valign="bottom"><b>Fecha:</b> __<u>' . $fecha_solucion . '</u>__</td>
</tr>
</table>
';

$pdf->Cell(5);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->writeHTML($pie, true, false, true, false, '');

$pdf->Output('reporte_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
}
