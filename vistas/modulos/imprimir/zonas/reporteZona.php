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

    $datos_reporte       = $instancia->datosReporteZonaControl($id_reporte);
    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'encabezado2');

    $fecha = ($datos_reporte['estado'] == 2) ? $datos_reporte['fechareg'] : $datos_reporte['fecha_mantenimiento'];

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
$pdf->SetTitle('Inventario');
$pdf->SetSubject('Inventario');
$pdf->SetKeywords('Inventario');
$pdf->AddPage();

$pdf->Ln(0);
$pdf->Cell(5);
$html = '
<table style="width:98%;" border="1">
<tr style="text-align:center; font-size: 0.8em; font-weight: bold;">
<td colspan="2" style="border:none;" rowspan="1"><img src="' . PUBLIC_PATH . 'img/' . $datos_super_empresa['imagen'] . '" border="0" width="120"></td>
<td colspan="3" rowspan="1" style="border:none;">
<br>
<br>
REPORTE OPERATIVO
</td>
<td colspan="1" rowspan="1">
<br>
<br>
Version 1
<br>
30-03-2020
</td>
</tr>
</table>';

// output the HTML content
$pdf->writeHTMLCell(185, 0, '', '', $html, '', 1, 0, true, 'C', true);

$pdf->Ln(5);
$encabezado = '
<table cellpadding="2" cellspacing="10" style="width: 100%; font-size: 0.9em;">
<tr>
<td style="width: 33%;"><strong>Nombre: </strong>' . $datos_reporte['nom_usuario'] . ' </td>
<td style="width: 33%;"><strong>Area: </strong>' . $datos_reporte['area_nom'] . ' </td>
<td style="width: 33%;"><strong>Fecha: </strong>' . $fecha . ' </td>
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
<td>' . $datos_reporte['id'] . '</td>
<td>' . $datos_reporte['zona_nom'] . '</td>
<td></td>
<td>' . $datos_reporte['estado_nom'] . '</td>
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

$pdf->Ln(-2);
$pdf->Cell(25);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
$pdf->Cell(15, 5, '', 0, 0, 'C');

$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
$pdf->Ln(-2);
$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');
$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');
$pdf->Cell(65, 12, '__________________________', 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(65, 12, 'Reporte Realizado Por', 0, 0, 'C');
$pdf->Cell(65, 12, 'V°B° Directora Administrativa', 0, 0, 'C');
$pdf->Cell(65, 12, 'Reporte Remitido a', 0, 0, 'C');

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

$pdf->Ln($ln - 10);
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

$pdf->Output('reporte_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
}
