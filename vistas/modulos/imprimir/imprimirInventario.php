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
require_once CONTROL_PATH . 'permisos' . DS . 'ControlPermisos.php';

$instancia         = ControlInventario::singleton_inventario();
$instancia_perfil  = ControlPerfil::singleton_perfil();
$instancia_permiso = ControlPermisos::singleton_permisos();

$super_empresa = $_SESSION['super_empresa'];

if (isset($_GET['area'])) {

    $area     = base64_decode($_GET['area']);
    $usuario  = base64_decode($_GET['usuario']);
    $articulo = base64_decode($_GET['articulo']);

    $datos = array(
        'area'     => $area,
        'usuario'  => $usuario,
        'articulo' => $articulo,
    );

    $buscar              = $instancia->buscarInventarioDetalleControl($datos);
    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'logo');
    $anio_escolar        = $instancia_permiso->mostrarAnioActivoControl();
}

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

$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Jesus Polo');
$pdf->SetTitle('Inventario');
$pdf->SetSubject('Inventario');
$pdf->SetKeywords('Inventario');
$pdf->AddPage();

$pdf->setJPEGQuality(90);
$pdf->Image(PUBLIC_PATH . 'img/logo.png', 80, 10, 40);

$pdf->Ln(20);
$pdf->Cell(85);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);
$pdf->Cell(12, 50, 'ENTREGA DE INVENTARIO DEL AREA ' . $buscar[0]['nom_area'], 0, 0, 'C');

$parrafo = '
<p style="text-align:justify;">Por este conducto recibo del
<span style="font-weight:bold;">' . $datos_super_empresa['nombre'] . '</span>,
el equipo que abajo se describe, mismo que será utilizado
con fines de la institución ' . $datos_super_empresa['nombre'] . '
de igual forma acepto y me comprometo a no hacer mal uso del mismo.
</p>
';

$pdf->Ln(35);
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

$pdf->Ln(5);
$pdf->Cell(10);

$tabla = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:94%;">
<tr style="text-align:center; font-weight:bold;">
<th style="width:20%;">USUARIO</th>
<th style="width:40%;">DESCRIPCION</th>
<th style="width:10%;">CANT</th>
<th style="width:10%;">ESTADO </th>
</tr>
';

foreach ($buscar as $inventario) {
    $nombre      = $inventario['descripcion'];
    $cantidad    = $inventario['cantidad'];
    $marca       = $inventario['marca'];
    $usuario     = $inventario['nom_user'];
    $estado      = $inventario['estado_nombre'];

    if ($inventario['estado'] == 5) {
        $tabla .= '';
    } else if ($inventario['estado'] == 4) {
        $tabla .= '';
    } else if ($inventario['confirmado'] != 1) {
        $tabla .= '';
    } else {
        $tabla .= '
        <tr style="text-align: center;">
        <td>' . $usuario . '</td>
        <td>' . $nombre . '</td>
        <td>' . $cantidad . '</td>
        <td>' . $estado . '</td>
        </tr>
        ';
    }
}

$firma_user = $instancia->mostrarFirmaUsuarioControl($inventario['id_user']);

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

$pdf->Ln(-2);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->Cell(10);
$pdf->writeHTMLCell(170, 0, '', '', $parrafo, '', 1, 0, true, 'C', true);

if ($firma_user['nombre'] != '') {

    $pie = '
    <table  style="width: 100%; text-transform: uppercase;">
    <tr>
    <td>
    <img src="' . PUBLIC_PATH . 'upload/' . $firma_user['nombre'] . '" width="110" height="50">
    <strong>________________________________</strong>
    </td>
    <td>
    <img src="' . PUBLIC_PATH . 'img/fondo_copia.jpg" width="150" height="50">
    <strong>________________________________</strong>
    </td>
    </tr>
    <tr>
    <td><strong>RESPONSABLE DEL SALON</strong></td>
    <td><strong>ENTREGADO POR</strong></td>
    </tr>
    </table>
    ';
} else {
    $pie = '
    <table  style="width: 100%; text-transform: uppercase;">
    <tr>
    <td>
    <strong>________________________________</strong>
    </td>
    <td>
    <strong>________________________________</strong>
    </td>
    </tr>
    <tr>
    <td><strong>RESPONSABLE DEL SALON</strong></td>
    <td><strong>ENTREGADO POR</strong></td>
    </tr>
    </table>
    ';
}

$pdf->Ln(10);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 8);
$pdf->Cell(10);
$pdf->writeHTMLCell(170, 0, '', '', $pie, '', 1, 0, true, 'C', true);

$nombre_archivo  = 'Carta_entrega_' . $buscar[0]['area_nom'] . '_' . $anio_escolar['anio_inicio'] . '_' . $anio_escolar['anio_fin'];
$carpeta_destino = PUBLIC_PATH_ARCH . 'upload/' . $nombre_archivo;

$pdf->Output($nombre_archivo . '.pdf', 'I');
$pdf->Output($carpeta_destino . '.pdf', 'F');
