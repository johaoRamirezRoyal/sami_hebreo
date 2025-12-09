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
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia         = ControlBiblioteca::singleton_biblioteca();
$instancia_perfil  = ControlPerfil::singleton_perfil();
$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl(1, 'encabezado2');

if (isset($_GET['usuario'])) {

    $id_usuario      = base64_decode($_GET['usuario']);
    $datos_usuarios  = $instancia_usuario->mostrarUsuariosDatosControl($id_usuario);
    $datos_prestamos = $instancia->historialPaquetesUsuariosControl($id_usuario);

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

// create a PDF object
$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->setData($datos_super_empresa['imagen']);
$pdf->SetAuthor('Jesus Polo');
$pdf->SetTitle('Listado Paquete');
$pdf->SetSubject('Listado Paquete');
$pdf->SetKeywords('Listado Paquete');
$pdf->AddPage();

$pdf->Cell(70);
$pdf->Image(PUBLIC_PATH . 'img/' . $datos_super_empresa['imagen'], '', '', 50, 20, '', '', 'T', false, 90, '', false, false, 1, false, false, false);

$pdf->Ln(25);
$pdf->Cell(72);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);
$pdf->Cell(50, 8, 'LISTADO DE PRESTAMOS (PAQUETES) BIBLIOTECA', 0, 0, 'C');

$pdf->Ln(10);
$pdf->Cell(10);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);

if ($datos_usuarios['perfil'] == 16) {
    $span  = 'el Estudiante <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . '</span> perteneciente al curso <span style="font-weight:bold;">' . $datos_usuarios['nom_curso'] . '</span>';
    $firma = 'Estudiante';
} else if ($datos_usuarios['perfil'] == 3) {
    $span  = 'el Docente <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . '</span>';
    $firma = 'Docente';
} else {
    $span  = 'el Trabajador <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . '</span>';
    $firma = 'Trabajador';
}

$parrafo = '
<div style="width: 90%;">
<p>El <span style="font-weight:bold;">Colegio Hebreo Union</span> certifica que ' . $span . ' identificado con numero de documento <span style="font-weight:bold;">' . $datos_usuarios['documento'] . '</span>  tiene en posesión los siguientes paquetes:</p>
</div>';
$pdf->writeHTML($parrafo, true, false, true, false, '');

$pdf->Ln(5);
$pdf->Cell(1);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$tabla_prestamos = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold; text-transform: uppercase;">
<th colspan="6">LISTADO DE PRESTAMOS (PAQUETES)</th>
</tr>
<tr style="text-align:center; font-weight:bold; text-transform: uppercase;">
<th>No. PRESTAMO</th>
<th>PAQUETE</th>
<th>#CODIGO</th>
<th>FECHA PRESTAMO</th>
<th>FECHA DEVOLUCION</th>
<th>ESTADO</th>
</tr>';

foreach ($datos_prestamos as $prestamo) {
    $id_prestamo      = $prestamo['id'];
    $nombre           = $prestamo['nombre'];
    $codigo           = $prestamo['codigo'];
    $fecha_devolucion = $prestamo['fecha_devolucion'];
    $fecha_prestamo   = $prestamo['fecha_prestamo'];
    $estado           = $prestamo['estado'];
    $devuelto         = $prestamo['id_devuelto'];
    $fecha_devuelto   = $prestamo['fecha_devuelto'];

    if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {
        $span_fecha = 'Por vencer';
    }

    if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {
        $span_fecha = 'A tiempo';
    }

    if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {
        $span_fecha = 'Retrasado';
    }

    if ($fecha_devuelto > $fecha_devolucion) {
        $span_fecha = 'Devuelto con retraso';
    }

    if ($fecha_devuelto < $fecha_devolucion && $fecha_devuelto != '') {
        $span_fecha = 'Devuelto antes de tiempo';
    }

    if ($fecha_devuelto == $fecha_devolucion) {
        $span_fecha = 'Devuelto justo a tiempo';
    }

    if ($devuelto == '') {

        $tabla_prestamos .= '
        <tr style="text-align:center;">
        <td>' . $id_prestamo . '</td>
        <td>' . $nombre . '</td>
        <td>' . $codigo . '</td>
        <td>' . $fecha_prestamo . '</td>
        <td>' . $fecha_devolucion . '</td>
        <td>' . $span_fecha . '</td>
        </tr>
        ';

    }
}

$tabla_prestamos .= '
</table>
';
$pdf->writeHTML($tabla_prestamos, true, false, true, false, '');

$pdf->Ln(30);
$pdf->Cell(40);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);
$pdf->Cell(40, 8, '__________________________', 0, 0, 'L');
$pdf->Cell(30);
$pdf->Cell(40, 8, '__________________________', 0, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(50);
$pdf->Cell(40, 8, 'Firma ' . $firma, 0, 0, 'L');
$pdf->Cell(25);
$pdf->Cell(40, 8, 'Firma responsable', 0, 0, 'L');

ob_end_clean();
$pdf->Output('listado_Paquetes_' . date('Y-m-d-H-i-s') . ' . pdf', 'I');
