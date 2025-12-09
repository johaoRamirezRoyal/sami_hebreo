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
    header('Location:../../login?er=' . $error);
    exit();
}
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia = ControlPerfil::singleton_perfil();

if (isset($_GET['id'])) {
    $id    = base64_decode($_GET['id']);
    $datos = $instancia->mostrarDatosPerfilControl($id);
}

class MYPDF extends TCPDF
{

    public function setData($logo)
    {
        $this->logo = $logo;
    }

    public function Header()
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = PUBLIC_PATH . 'img/fondo_prom.png';
        $this->Image($img_file, 0, 0, 73.8, 104.8, '', '', '', false, 100, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }

    public function Footer()
    {
    }
}

// create a PDF object
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jesus Polo');
$pdf->SetTitle('Codigo');
$pdf->SetSubject('Codigo');
$pdf->SetKeywords('Codigo');
$pdf->AddPage('P', 'A7');

$style = array(
    'border'        => 0,
    'vpadding'      => 'auto',
    'hpadding'      => 'auto',
    'fgcolor'       => array(0, 0, 0),
    'bgcolor'       => false, //array(255,255,255)
    'module_width'  => 1, // width of a single module in points
    'module_height' => 1, // height of a single module in points
);

$pdf->write2DBarcode($id, 'QRCODE,Q', 19, 33, 35, 35, $style, 'N');

$pdf->Ln(3);
$pdf->Text(32, 65, $id, 0, false);

ob_end_clean();
$pdf->Output($datos['documento'] . '_' . $datos['nombre'] . '_' . $datos['apellido'] . '_' . $datos['nom_curso'] . '.pdf', 'I');
