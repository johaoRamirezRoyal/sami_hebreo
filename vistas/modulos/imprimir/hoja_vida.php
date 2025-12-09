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
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'hoja_vida' . DS . 'ControlHojaVida.php';

$instancia        = ControlHojaVida::singleton_hoja_vida();
$instancia_perfil = ControlPerfil::singleton_perfil();

$super_empresa = $_SESSION['super_empresa'];

if (isset($_GET['inventario'])) {

    $id_inventario = base64_decode($_GET['inventario']);

    $datos_articulo    = $instancia->mostrarDatosArticuloControl($id_inventario, $super_empresa);
    $datos_componentes = $instancia->mostrarComponentesHardwareControl($datos_articulo['id_user']);
    $datos_hardware    = $instancia->mostrarComponentesAsignadoHardwareControl($datos_articulo['id_hoja_vida']);
    $datos_software    = $instancia->mostrarComponentesAsignadoSoftwareControl($datos_articulo['id_hoja_vida']);
    $datos_reporte     = $instancia->mostrarReportesArticuloControl($id_inventario);
    $datos_copia       = $instancia->mostrarCopiasSeguridadArticuloControl($id_inventario);

    $datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'encabezado2');

    if ($datos_articulo['tipo_conexion'] == 0) {
        $tipo_conexion = 'N/A';
    }

    if ($datos_articulo['tipo_conexion'] == 1) {
        $tipo_conexion = 'Cable';
    }

    if ($datos_articulo['tipo_conexion'] == 2) {
        $tipo_conexion = 'Wifi';
    }

    $codigo_inv = $datos_articulo['codigo'];

    $fecha_update = (empty($datos_articulo['fecha_update'])) ? $datos_articulo['fechareg'] : $datos_articulo['fecha_update'];
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
$pdf->SetAuthor('Jesus Polo');
$pdf->SetTitle('Hoja Vida');
$pdf->SetSubject('Hoja Vida');
$pdf->SetKeywords('Hoja Vida');
$pdf->AddPage();

$pdf->Ln(0);
$pdf->Cell(5);
$html = '
<table style="width:98%;" border="1">
<tr style="text-align:center; font-size: 0.8em; font-weight: bold;">
<td colspan="2" style="border:none;" rowspan="1">
<br>
<br>
<img src="' . PUBLIC_PATH . 'img/logo.png" border="0" width="50"></td>
<td colspan="3" rowspan="1" style="border:none;">
<br>
<br>
HOJA DE VIDA
<br>
<br>
Fecha Ultima actualizacion: ' . $fecha_update . '
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

$pdf->writeHTMLCell(185, 0, '', '', $html, '', 1, 0, true, 'C', true);

$pdf->Ln(8);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Informacion general del equipo', 1, 0, 'C');

$ln = 5;

$pdf->Ln($ln + 3);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Cell(90, 5, 'Nombre: ' . $datos_articulo['descripcion'], 0, 0, 'L');
$pdf->Cell(90, 5, 'Modelo: ' . $datos_articulo['modelo'], 0, 0, 'L');

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Cell(90, 5, 'Marca: ' . $datos_articulo['marca'], 0, 0, 'L');
$pdf->Cell(90, 5, 'Numero de Serie: ' . $datos_articulo['numero_serie'], 0, 0, 'L');

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Cell(90, 5, 'Codigo inventario: ' . $codigo_inv, 0, 0, 'L');
$pdf->Cell(90, 5, 'Contacto garantia: ' . $datos_articulo['contacto_garantia'], 0, 0, 'L');

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Cell(90, 5, 'Frecuencia de mantenimiento: ' . $datos_articulo['frecuencia_mantenimiento'] . ' MESES', 0, 0, 'L');
/*$pdf->Cell(90, 5, 'Grupo de trabajo: ' . $datos_articulo['grupo_trabajo'], 0, 0, 'L');*/
$pdf->Cell(90, 5, 'Fecha vencimiento garantia: ' . $datos_articulo['fecha_garantia'], 0, 0, 'L');

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
$pdf->Cell(90, 5, 'Fecha de adquisicion: ' . $datos_articulo['fecha_adquisicion'], 0, 0, 'L');
$pdf->Cell(90, 5, 'Frecuencia de copia seguridad: ' . $datos_articulo['frecuencia_copia'] . ' MESES', 0, 0, 'L');

/*$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);*/

/*-------------------Hardware----------------------*/
$pdf->Ln($ln + 5);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Componentes de hardware', 1, 0, 'C');

$tabla_hard = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold;">
<th style="width:30%;">Descripcion</th>
<th style="width:15%;">Modelo</th>
<th style="width:15%;">Marca</th>
<th style="width:20%;">Codigo</th>
<th style="width:20%;">Observacion</th>
</tr>
';

foreach ($datos_hardware as $hardware) {
    $descripcion_hard = $hardware['descripcion'];
    $modelo_hard      = $hardware['modelo'];
    $marca_hard       = $hardware['marca'];
    $codigo_hard      = $hardware['codigo'];
    $observacion_hard = $hardware['observacion'];

    $tabla_hard .= '
    <tr style="text-align:center;">
    <td>' . $descripcion_hard . '</td>
    <td>' . $modelo_hard . '</td>
    <td>' . $marca_hard . '</td>
    <td>' . $codigo_hard . '</td>
    <td>' . $observacion_hard . '</td>
    </tr>
    ';
}

$tabla_hard .= '
</table>
';

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->writeHTML($tabla_hard, true, false, true, false, '');
/*--------------------------------------------------------*/

/*-------------------Software----------------------*/
/*$pdf->Ln(4);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Componentes de software', 1, 0, 'C');

$tabla_soft = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold;">
<th style="width:30%;">Descripcion</th>
<th style="width:15%;">Version</th>
<th style="width:15%;">Fabricante</th>
<th style="width:20%;">Licencia</th>
<th style="width:20%;">Observacion</th>
</tr>
';

foreach ($datos_software as $software) {
$descripcion_soft = $software['descripcion'];
$version          = $software['version'];
$fabricante       = $software['fabricante'];
$licencia         = $software['licencia'];
$observacion_soft = $software['observacion'];

$tabla_soft .= '
<tr style="text-align:center;">
<td>' . $descripcion_soft . '</td>
<td>' . $version . '</td>
<td>' . $fabricante . '</td>
<td>' . $licencia . '</td>
<td>' . $observacion_soft . '</td>
</tr>
';
}

$tabla_soft .= '
</table>
';

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->writeHTML($tabla_soft, true, false, true, false, '');*/
/*-----------------------------------------------------*/

/*-------------------Ubicacion----------------------*/
$pdf->Ln(4);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Ubicacion y asignacion del equipo', 1, 0, 'C');

$tabla_ub = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold;">
<th style="width:30%;">Dependencia</th>
<th style="width:25%;">Usuario o responsable</th>
<th style="width:25%;">Fecha de asignacion</th>
<th style="width:20%;">Observacion</th>
</tr>
<tr style="text-align:center;">
<td>' . $datos_articulo['area'] . '</td>
<td>' . $datos_articulo['usuario'] . '</td>
<td>' . $datos_articulo['fechareg'] . '</td>
<td>' . $datos_articulo['observacion'] . '</td>
</tr>
</table>
';

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->writeHTML($tabla_ub, true, false, true, false, '');
/*-----------------------------------------------------*/

/*-------------------Ubicacion----------------------*/
$pdf->Ln(4);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Control de mantenimientos', 1, 0, 'C');

$tabla_reporte = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold;">
<th style="width:30%;">Observacion</th>
<th style="width:20%;">Reporte</th>
<th style="width:25%;">Fecha</th>
<th style="width:25%;">Respuesta</th>
</tr>
';

foreach ($datos_reporte as $reporte) {
    $id_reporte      = $reporte['id'];
    $observacion     = $reporte['observacion'];
    $fecha           = $reporte['fechareg'];
    $tipo_reporte    = $reporte['nombre_estado'];
    $estado          = $reporte['estado'];
    $fecha_respuesta = $reporte['fecha_respuesta'];

    $fecha_ver = ($estado == 3) ? $fecha_respuesta : $fecha;

    $ver = ($estado == 3) ? '' : 'd-none';

    if ($fecha_respuesta == '') {
        $respuesta = '';
    } else {
        $fecha_reporte = $instancia->mostrarFechaReportadoControl($reporte['id_reporte']);
        $datetime1     = new DateTime($fecha_reporte['fechareg']);
        $datetime2     = new DateTime($fecha_respuesta);
        $interval      = $datetime1->diff($datetime2);
        $respuesta     = $interval->format('%d Dias %h Horas %i Minutos %s Segundos');
    }

    $tabla_reporte .= '
    <tr style="text-align:center;">
    <td>' . $observacion . '</td>
    <td>' . $tipo_reporte . '</td>
    <td>' . $fecha_ver . '</td>
    <td>' . $respuesta . '</td>
    </tr>
    ';
}

$tabla_reporte .= '
</table>
';

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->writeHTML($tabla_reporte, true, false, true, false, '');
/*-----------------------------------------------------*/

$pdf->Ln(4);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
$pdf->Cell(180.3, 5, 'Control de copias de seguridad', 1, 0, 'C');

$tabla_copia = '
<table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
<tr style="text-align:center; font-weight:bold;">
<th>Usuario Responsable</th>
<th>Area</th>
<th>Observacion</th>
<th>Fecha</th>
</tr>
';

foreach ($datos_copia as $copia) {
    $id_copia    = $copia['id'];
    $nom_usuario = $copia['nom_user'];
    $nom_area    = $copia['nom_area'];
    $observacion = $copia['observacion'];
    $fecha       = date('Y-m-d', strtotime($copia['fecha']));

    $tabla_copia .= '
    <tr style="text-align:center;">
    <td>' . $nom_usuario . '</td>
    <td>' . $nom_area . '</td>
    <td>' . $observacion . '</td>
    <td>' . $fecha . '</td>
    </tr>
    ';
}

$tabla_copia .= '
</table>
';

$pdf->Ln($ln);
$pdf->Cell(6);
$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
$pdf->writeHTML($tabla_copia, true, false, true, false, '');
/*-----------------------------------------------------*/

$style = array(
    'position'     => 'C',
    'align'        => 'C',
    'stretch'      => false,
    'fitwidth'     => true,
    'cellfitalign' => '',
    'border'       => false,
    'hpadding'     => 'auto',
    'vpadding'     => 'auto',
    'fgcolor'      => array(0, 0, 0),
    'bgcolor'      => false, //array(255,255,255),
    'text'         => true,
    'font'         => 'helvetica',
    'fontsize'     => 8,
    'stretchtext'  => 4,
);

// CODE 39
$pdf->Ln($ln);
$pdf->write1DBarcode($codigo_inv, 'C39', '', '', '', 18, 0.4, $style, 'N', 'C');

ob_end_clean();
$pdf->Output('hoja_vida_' . date('Y-m-d-H-i-s') . ' . pdf', 'I');
