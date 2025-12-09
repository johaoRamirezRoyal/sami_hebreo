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
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

$instancia        = ControlBiblioteca::singleton_biblioteca();
$instancia_perfil = ControlPerfil::singleton_perfil();

if (isset($_GET['libro'])) {

    $id_libro = base64_decode($_GET['libro']);

    $datos_libro         = $instancia->mostrarInformacionLibroControl($id_libro);
    $datos_categoria     = $instancia->mostrarCategoriasBibliotecaControl();
    $datos_ejemplares    = $instancia->mostrarEjemplaresControl($id_libro);
    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl(1, 'encabezado2');

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
    $pdf->SetTitle('Codigos');
    $pdf->SetSubject('Codigos');
    $pdf->SetKeywords('Codigos');
    $pdf->AddPage();

    $pdf->Ln(0);
    $pdf->Cell(5);
    $pdf->Image(PUBLIC_PATH . 'img' . DS . $datos_super_empresa['imagen'], '', '', 50, 20, '', '', 'T', false, 100, '', false, false, 1, false, false, false);

    $pdf->Cell(150);
    $pdf->Cell();

    ob_end_clean();
    $pdf->Output('reporte_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
}
