<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();

if (empty($_SESSION['rol'])) {
    $error = base64_encode('2');
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}

require_once LIB_PATH . 'fpdf/fpdf.php';
require_once CONTROL_PATH . 'inventario/ControlInventario.php';
require_once CONTROL_PATH . 'hoja_vida/ControlHojaVida.php';

function toISO($text) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
}

$instancia_inventario = ControlInventario::singleton_inventario();
$instancia_hoja_vida = ControlHojaVida::singleton_hoja_vida();

if (!isset($_GET['inventario'])) {
    include_once VISTA_PATH . 'modulos/404.php';
    exit();
}

$id_inventario = base64_decode($_GET['inventario']);
$datos_articulo = $instancia_inventario->mostrarDatosArticuloIdControl($id_inventario);
$datos_historial = $instancia_inventario->historialArticuloControl($id_inventario);
$fecha_actual = date('d/m/Y');

// Crear PDF horizontal
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(20, 10);
$pdf->SetAutoPageBreak(true, 20);

// Colores corporativos
$azul = [0, 51, 102];
$azul_claro = [220, 230, 241];
$gris_borde = [200, 200, 200];
$gris_fila = [245, 245, 245];

// Logo
$logo = PUBLIC_PATH_ARCH . 'img/logo.png';
if (file_exists($logo)) {
    $pdf->Image($logo, 15, 10, 30);
}
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
$pdf->Cell(0, 10, strtoupper(toISO('Colegio Hebreo Unión')), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'HOJA DE VIDA DEL EQUIPO', 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 6, toISO('Fecha de emisión: ') . $fecha_actual, 0, 1, 'C');
$pdf->Ln(8);

// Encabezado información
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor($azul_claro[0], $azul_claro[1], $azul_claro[2]);
$pdf->SetDrawColor($gris_borde[0], $gris_borde[1], $gris_borde[2]);
$pdf->Cell(0, 8, toISO('INFORMACIÓN DEL EQUIPO ' . strtoupper($datos_articulo['descripcion'])), 1, 1, 'C', true);
$pdf->Ln(3);

// Función para fila
function filaInfo($pdf, $etiqueta, $valor) {
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(137, 7, $etiqueta, 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(120, 7, $valor, 1, 1, 'L');
}

// Datos del equipo
filaInfo($pdf, 'Nombre del equipo:', toISO($datos_articulo['descripcion']));
filaInfo($pdf, 'Identificador:', $datos_articulo['id']);
filaInfo($pdf, 'Marca:', toISO($datos_articulo['marca']));
filaInfo($pdf, 'Modelo:', toISO($datos_articulo['modelo']));
filaInfo($pdf, toISO('Código Serial:'), !empty($datos_articulo['codigo']) ? $datos_articulo['codigo'] : 'No registrado');
filaInfo($pdf, 'Usuario responsable:', toISO($datos_articulo['usuario']));
filaInfo($pdf, toISO('Área:'), $datos_articulo['area']);

$pdf->Ln(10);

// Historial
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor($azul[0], $azul[1], $azul[2]);
$pdf->Cell(0, 8, 'HISTORIAL DE REPORTES / MANTENIMIENTOS', 0, 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor($azul[0], $azul[1], $azul[2]);
$pdf->SetTextColor(255);
$pdf->Cell(35, 7, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Usuario', 1, 0, 'C', true);
$pdf->Cell(35, 7, toISO('Área'), 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Estado', 1, 0, 'C', true);
$pdf->Cell(90, 7, toISO('Observación'), 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Tiempo Resp.', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0);
$fill = false;

foreach ($datos_historial as $item) {
    $respuesta = '';
    if (!empty($item['fecha_respuesta'])) {
        $inicio = new DateTime($instancia_hoja_vida->mostrarFechaReportadoControl($item['id_reporte'])['fechareg']);
        $fin = new DateTime($item['fecha_respuesta']);
        $diff = $inicio->diff($fin);
        $respuesta = $diff->format('%d días %h hrs');
    }

    // Corregir errores comunes
    $area = str_replace('Pracicante', 'Practicante', $item['area']);
    $estado = str_replace(['Correciñonado', 'Correciñoporte'], 'Correctivo', $item['estado_nombre']);
    $obs = str_replace(['Correciñonado', 'Correciñoporte'], 'Correctivo', $item['observacion_reporte']);

    $pdf->SetFillColor($fill ? $gris_fila[0] : 255, $gris_fila[1], $gris_fila[2]);
    $fill = !$fill;

    $pdf->Cell(35, 6, $item['fecha_reporte'], 1, 0, 'C', true);
    $pdf->Cell(35, 6, toISO($item['usuario']), 1, 0, 'L', true);
    $pdf->Cell(35, 6, toISO($area), 1, 0, 'L', true);
    $pdf->Cell(35, 6, toISO($estado), 1, 0, 'L', true);
    $pdf->Cell(90, 6, toISO($obs), 1, 0, 'L', true);
    $pdf->Cell(35, 6, toISO($respuesta), 1, 1, 'C', true);
}

// Output
$nombre_pdf = 'hoja_vida_equipo_' . (!empty($datos_articulo['codigo']) ? $datos_articulo['codigo'] : 'sin_codigo') . '.pdf';
$pdf->Output('I', $nombre_pdf);
