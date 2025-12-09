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
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlInventario::singleton_inventario();
$instancia_perfil = ControlPerfil::singleton_perfil();

if (isset($_GET['id_log'])) {

    $id_log        = base64_decode($_GET['id_log']);
    $super_empresa = base64_decode($_GET['super_empresa']);

    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'encabezado');
    $datos_temporales    = $instancia->mostrarDatosTemporalesControl($id_log, $super_empresa);

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
    $pdf->SetTitle('Carta de entrega');
    $pdf->SetSubject('Carta de entrega');
    $pdf->SetKeywords('Carta de entrega');
    $pdf->AddPage();

    $pdf->setJPEGQuality(90);
    $pdf->Image(PUBLIC_PATH . 'img/logo.png', 0, 0, 210, 35);
    $pdf->Ln(30);
    $pdf->Cell(90);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);
    $pdf->Cell(12, 50, 'ENTREGA DE INVENTARIO', 0, 0, 'C');

    $pdf->Ln(15);
    $pdf->Cell(15);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(10, 50, date('d/m/Y'), 0, 0, 'C');

    $parrafo = '
<p style="text-align:justify;">Por este conducto recibo del
<span style="font-weight:bold;">' . $datos_super_empresa['nombre'] . '</span>,
el equipo que abajo se describe, mismo que será utilizado
con fines de la institución ' . $datos_super_empresa['nombre'] . '
de igual forma acepto y me comprometo a no hacer mal uso del mismo.
</p>
';

    $pdf->Ln(40);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $parrafo, '', 1, 0, true, 'C', true);

    $parrafo = '
<p style="text-align:justify;">En caso de que el equipo sufra algún daño,
pérdida, me comprometo a dar aviso en forma inmediata
al área Administrativa del <span style="font-weight:bold;">' . $datos_super_empresa['nombre'] . '</span>
y cubrir la reparación o reposición total del equipo según sea el caso.
</p>
';

    $pdf->Ln(5);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $parrafo, '', 1, 0, true, 'C', true);

    $pdf->Ln(10);
    $pdf->Cell(10);

    $tabla = '
<table border="1" style="font-size:8.5px; width:94%;">
<tr style="text-align:center; font-weight:bold;">
<th>USUARIO</th>
<th>AREA</th>
<th>CANT</th>
<th>DESCRIPCION</th>
<th>MARCA</th>
<th>ESTADO </th>
<th>OBSERVACION</th>
</tr>
';

    foreach ($datos_temporales as $inventario) {
        $nombre      = $inventario['descripcion'];
        $cantidad    = $inventario['cantidad'];
        $usuario     = $inventario['usuario'];
        $marca       = $inventario['marca'];
        $estado      = $inventario['estado'];
        $observacion = $inventario['observacion'];
        $area        = $inventario['area_nom'];

        $tabla .= '
    <tr style="text-align: center;">
    <td>' . $usuario . '</td>
    <td>' . $area . '</td>
    <td>' . $cantidad . '</td>
    <td>' . $nombre . '</td>
    <td>' . $marca . '</td>
    <td>' . $estado . '</td>
    <td>' . $observacion . '</td>
    </tr>
    ';
    }

    $tabla .= '
</table>
';

    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla, true, false, true, false, '');

    $parrafo = '
<p style="text-align:justify;">Me comprometo a entregar el equipo del área en el
momento en que el <span style="font-weight:bold;">' . $datos_super_empresa['nombre'] . '</span> me lo requiera.
</p>
';

    $pdf->Ln(5);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $parrafo, '', 1, 0, true, 'C', true);

    $pie = '
<table cellpadding="1" cellspacing="2" style="width: 100%;">
<tr>
<td style="width: 50%;"><strong>________________________________</strong></td>
<td style="width: 50%;"><strong>________________________________</strong></td>
</tr>
<tr>
<td><strong>RESPONSABLE DEL SALON</strong></td>
<td><strong>ENTREGADO POR</strong></td>
</tr>
</table>
';

    $pdf->Ln(40);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
    $pdf->Cell(10);
    $pdf->writeHTMLCell(170, 0, '', '', $pie, '', 1, 0, true, 'C', true);

    $pdf->Output('Carta_entrega_' . date('Y-m-d-H-i-s') . '.pdf', 'D');

    $temporal = array(
        'id_log'           => $id_log,
        'id_super_empresa' => $super_empresa,
    );

    $eliminar_temporal = $instancia->eliminarTemporalControl($temporal);
}
