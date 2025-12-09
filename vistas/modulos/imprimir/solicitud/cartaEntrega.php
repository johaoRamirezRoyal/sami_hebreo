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
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';

$instancia = ControlSolicitud::singleton_solicitud();

if (isset($_GET['solicitud'])) {
    $id_solicitud = base64_decode($_GET['solicitud']);

    $datos_solicitud  = $instancia->mostrarDatosSolicitudIdControl($id_solicitud);
    $productos        = $instancia->mostrarProdcutosSolicitudControl($id_solicitud);
    $datos_cotizacion = $instancia->mostrarCotizacionControl($id_solicitud);
}

class MYPDF extends TCPDF
{

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
$pdf->SetAuthor('Haj Soft');
$pdf->SetTitle('Carta Entrega');
$pdf->SetSubject('Carta Entrega');
$pdf->SetKeywords('Carta Entrega');
$pdf->AddPage();

$pdf->Cell(5);
$pdf->Image(PUBLIC_PATH . 'img/logo.png', '', '', 30, 30, '', '', 'T', false, 90, '', false, false, 1, false, false, false);

$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 11);
$pdf->Cell(150, 10, 'COLEGIO HEBREO UNION', 1, 0, 'C');

$pdf->Ln(10);
$pdf->Cell(35);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(150, 10, 'CARTA DE ENTREGA SOLICITUD NO. ' . $id_solicitud, 1, 0, 'C');

$pdf->Ln(10);

$pdf->Cell(35);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(50, 10, 'FECHA REALIZADO: ' . date('Y-m-d', strtotime($datos_solicitud['fechareg'])), 1, 0, 'C');

$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(50, 10, 'FECHA ENTREGA: ' . $datos_solicitud['fecha_recibido'], 1, 0, 'C');

$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(50, 10, 'VERSION: 1.0', 1, 0, 'C');

$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Ln(15);
$pdf->Cell(5);
$tabla_informacion = '
<table style="width:98.5%;" border="1" cellpadding="3">
<tr style="text-align:center; font-size: 1.2em; font-weight: bold;">
<td colspan="3">DETALLES DE SOLICITUD</td>
</tr>
<tr style="text-align:left; font-size: 1.2em;">
<td><span style="font-weight:bold;">Area:</span> ' . $datos_solicitud['area_nom'] . '</td>
<td><span style="font-weight:bold;">Grado:</span> ' . $datos_solicitud['curso_nom'] . '</td>
<td><span style="font-weight:bold;">Nombre del Solicitante:</span> ' . $datos_solicitud['nom_usuario'] . '</td>
</tr>
<tr style="text-align:left; font-size: 1.2em;">
<td colspan="3"><span style="font-weight:bold;">Justificacion:</span> ' . $datos_solicitud['justificacion'] . '</td>
</tr>
</table>';

$pdf->writeHTMLCell(185, 0, '', '', $tabla_informacion, '', 1, 0, true, 'C', true);

$pdf->Ln(10);
$pdf->Cell(5);
$tabla_materiales = '
<table style="width:98.5%;" border="1" cellpadding="3">
<tr style="text-align:center; font-size: 1.2em; font-weight: bold;">
<td colspan="4">MATERIALES A ENTREGAR</td>
</tr>
<tr style="text-align:center">
<th style="font-weight: bold;">MATERIAL</th>
<th style="font-weight: bold;">CANTIDAD</th>
<th style="font-weight: bold;">FECHA RECIBIDO</th>
<th style="font-weight: bold;">FIRMA RECIBIDO</th>
</tr>';

foreach ($productos as $pro) {

    $id_detalle        = $pro['id'];
    $nom_producto      = $pro['producto'];
    $cantidad          = $pro['cantidad'];
    $cantidad_recibida = ($pro['cantidad_recibida'] == '') ? $pro['cantidad'] : ($pro['cantidad_recibida'] + $pro['cantidad_existencia']);
    $existencia        = $pro['existencia'];

    $tabla_materiales .= '
    <tr style="text-align:center;">
    <td>' . $nom_producto . '</td>
    <td>' . $cantidad_recibida . '</td>
     <td></td>
    <td></td>
    </tr>
    ';

}

$tabla_materiales .= '</table>';

$pdf->writeHTMLCell(185, 0, '', '', $tabla_materiales, '', 1, 0, true, 'C', true);

$pdf->Ln(20);
$pdf->Cell(5);
//$tabla_firmas = '
//<table style="width:98.5%;" border="0" cellpadding="3">
//<tr style="text-align:center; font-size: 1.2em; font-weight: bold;">
//<td>_______________________________</td>
//<td>_______________________________</td>
//</tr>
//<tr style="text-align:center; font-size: 1.2em; font-weight: bold;">
//<td>FIRMA RECIBIDO</td>
//<td>FIRMA ENTREGADO</td>
//</tr>
//</table>';

$pdf->writeHTMLCell(185, 0, '', '', $tabla_firmas, '', 1, 0, true, 'C', true);

$pdf->Output('carta_entrega_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
