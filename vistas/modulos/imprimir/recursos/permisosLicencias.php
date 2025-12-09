<?php

ob_start();
date_default_timezone_set('America/Bogota');
require_once CONTROL_PATH . 'Session.php';

$objss = new Session;
$objss->iniciar();

if (!$_SESSION['rol']) {
    $er = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../../login?er=' . $error);
    exit();
}

require_once LIB_PATH . 'PhpSpreadsheet' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$instancia = ControlRecursos::singleton_recursos();

$datos_get = array();

if (isset($_GET['usuario']) || isset($_GET['fecha']) || isset($_GET['nivel']) || isset($_GET['buscar']) || isset($_GET['perfil'])) {
    $datos_get = array(
        'usuario' => $_GET['usuario'] ?? '',
        'fecha'   => $_GET['fecha']  ?? '',
        'nivel'   => $_GET['nivel']  ?? '',
        'buscar'  => $_GET['buscar'] ?? '',
        'perfil'  => $_GET['perfil'] ?? ''
      
    );
}


if (isset($datos_get['perfil']) && in_array($datos_get['perfil'], [26, 7, 11, 2])) {
    $datos_permiso = $instancia->mostrarPermisosLicenciasUsuarioNivelControl($datos_get['nivel']);
} else {
    $datos_permiso = $instancia->mostrarListadoPermisosControl();
}


//Busqueda filtrado 
if (isset($datos_get['buscar']) || isset($datos_get['usuario']) || isset($datos_get['fecha'])) {
    $datos = array(
        'buscar' => $datos_get['buscar'],
        'usuario' => $datos_get['usuario'],
        'fecha' => ($datos_get['fecha'] == '') ? date("Y-m") : $datos_get['fecha'],
        'id_nivel' => $datos_get['nivel']
    );

    if ($datos_get['perfil'] != 1 && $datos_get['perfil'] != 8) {
        $datos_permiso = $instancia->mostrarPermisosLicenciasUsuarioNivelFiltroControl($datos);
        
    } else {
        $datos_permiso = $instancia->mostrarPermisosLicenciasUsuariosFiltroControl($datos);
    }
}

// Limpiar cualquier salida previa
if (ob_get_length()) {
    ob_clean();
}

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()
    ->setTitle('Permisos/Licencias')
    ->setDescription('Este documento fue generado por el sistema');

$sheet = $spreadsheet->setActiveSheetIndex(0);

// Configuración inicial y estilos
$meses = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];
$mesActual = $meses[date('F')];

//Logo royal 
$sheet->mergeCells('A1:A3');

// Ajustar ancho de columna y alto de fila (opcional, para que la imagen quepa bien)
$sheet->getColumnDimension('A')->setWidth(18);
$sheet->getRowDimension(1)->setRowHeight(90);
$sheet->getRowDimension(2)->setRowHeight(90);
$sheet->getRowDimension(3)->setRowHeight(90);

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setPath(PUBLIC_PATH_ARCH . 'img/logo.png'); // ruta de tu imagen
$drawing->setCoordinates('A1');                 // empieza en A1
$drawing->setOffsetX(7);                        // opcional: mover un poco en X
$drawing->setOffsetY(15);                        // opcional: mover un poco en Y
$drawing->setHeight(90);                       // tamaño de la imagen (alto en px)
$drawing->setWorksheet($sheet);

// Encabezado principal
$sheet->mergeCells('B1:G1');
$titulo = "FORMATO DE NOVEDADES DE NOMINA";
$sheet->setCellValue('B1', $titulo);

$sheet->mergeCells('B2:G2');
$sheet->setCellValue('B2', "Mes de " . $mesActual );

$sheet->mergeCells('B3:G3');
$sheet->setCellValue('B3', date('Y'));

$sheet->getStyle('B1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 12],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
    ],
]);
$sheet->getStyle('B2')->applyFromArray([
    'font' => ['bold' => false, 'size' => 12],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
    ],
]);
$sheet->getStyle('B3')->applyFromArray([
    'font' => ['bold' => false, 'size' => 12],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
        'wrapText' => true,
    ],
]);

// Ajustar altura de filas
$sheet->getRowDimension(1)->setRowHeight(30);
$sheet->getRowDimension(2)->setRowHeight(30);
$sheet->getRowDimension(3)->setRowHeight(30);

$sheet->setCellValue('H1', 'Elaborado por:' );
$sheet->setCellValue('H2', 'Versión:');
$sheet->setCellValue('H3', 'fecha: ' . date('Y-m-d'));

// Estilos comunes
$estilos_cabecera = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
];

$estilos_datos = [
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
];

foreach (range('A', 'K') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// PERMISOS DE DIA COMPLETO
$sheet->mergeCells('B6:F6');
$sheet->setCellValue('B6', "PERMISOS DE DIA COMPLETO");
$sheet->getStyle('B6')->applyFromArray([
    'font' => ['bold' => true, 'size' => 12],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
]);

// Encabezados tabla día completo
$sheet->setCellValue('A7', 'NOMBRE')
    ->setCellValue('B7', 'DOCUMENTO')
    ->setCellValue('C7', 'FECHA INICIO')
    ->setCellValue('D7', 'FECHA FINAL')
    ->setCellValue('E7', 'NUMERO DE DIAS')
    ->setCellValue('F7', 'MOTIVO')
    ->setCellValue('G7', 'DESCRIPCION')
    ->setCellValue('H7', 'REMUNERADO')
    ->setCellValue('I7', 'EVIDENCIA')
    ->setCellValue('J7', 'INCAPACIDAD');

$sheet->getStyle('A7:J7')->applyFromArray($estilos_cabecera);

$cont = 8;
foreach ($datos_permiso as $permiso) {
    if ($permiso['tipo_permiso'] == 2) {
        $evidencia_permiso = ($permiso['evidencia_permiso'] != '') ? 'SI' : 'NO';
        
        $estado = 'No Incapacidad';
        $colorFondo = 'FFFF00';

        if($permiso['motivo_permiso'] == 3 && ($permiso['tipo_permiso_detalle'] == "Incapacidad Medica EPS" || $permiso['tipo_permiso_detalle'] == "Incapacidad Medica ARL")){
            $incapacidad = 'Incapacidad';
            $colorFondo = 'A5D6A7';
        }
        else{
            $incapacidad = 'No Incapacidad';
            $colorFondo = '90CAF9';
        }

        $sheet->setCellValue('A' . $cont, $permiso['nom_user'] ?? '')
            ->setCellValue('B' . $cont, $permiso['documento'] ?? '')
            ->setCellValue('C' . $cont, $permiso['fecha_permiso'] ?? '')
            ->setCellValue('D' . $cont, $permiso['fecha_retorno'] ?? '')
            ->setCellValue('E' . $cont, $permiso['dias_permiso'] ?? '')
            ->setCellValue('F' . $cont, $permiso['nom_motivo'] ?? '')
            ->setCellValue('G' . $cont, $permiso['descripcion'] ?? '')
            ->setCellValue('H' . $cont, strtoupper($permiso['remunerado']) ?? '')
            ->setCellValue('I' . $cont, $evidencia_permiso ?? '')
            ->setCellValue('J' . $cont, $incapacidad);

        $sheet->getStyle('A' . $cont . ':J' . $cont)->applyFromArray($estilos_datos);

        $sheet->getStyle('J' . $cont)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($colorFondo);

        $cont++;
    }
}

// Espacio entre tablas
$cont += 2;

// PERMISOS PARCIALES
$sheet->mergeCells('B' . $cont . ':F' . $cont);
$sheet->setCellValue('B' . $cont, "PERMISOS PARCIALES");
$sheet->getStyle('B' . $cont)->applyFromArray([
    'font' => ['bold' => true, 'size' => 12],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
]);

$cont++;

// Encabezados tabla parcial
$sheet->setCellValue('A' . $cont, 'NOMBRE')
    ->setCellValue('B' . $cont, 'DOCUMENTO')
    ->setCellValue('C' . $cont, 'FECHA PERMISO')
    ->setCellValue('D' . $cont, 'HORA DE SALIDA')
    ->setCellValue('E' . $cont, 'HORA DE REGRESO')
    ->setCellValue('F' . $cont, 'TIEMPO SOLICITADO (EN MINUTOS)')
    ->setCellValue('G' . $cont, 'MOTIVO')
    ->setCellValue('H' . $cont, 'DESCRIPCION')
    ->setCellValue('I' . $cont, 'REMUNERADO')
    ->setCellValue('J' . $cont, 'EVIDENCIA')
    ->setCellValue('K' . $cont, 'INCAPACIDAD');

$sheet->getStyle('A' . $cont . ':K' . $cont)->applyFromArray($estilos_cabecera);

$cont++; 

// Datos parciales
foreach ($datos_permiso as $permiso) {

    if ($permiso['tipo_permiso'] == 1) {
        $evidencia_permiso = ($permiso['evidencia_permiso'] != '') ? 'SI' : 'NO';

        $estado = 'No Incapacidad';
        $colorFondo = 'FFFF00';

        if($permiso['motivo_permiso'] == 3 && ($permiso['tipo_permiso_detalle'] == "Incapacidad Medica EPS" || $permiso['tipo_permiso_detalle'] == "Incapacidad Medica ARL")){
            $incapacidad = 'Incapacidad';
            $colorFondo = 'A5D6A7';
        }
        else{
            $incapacidad = 'No Incapacidad';
            $colorFondo = '90CAF9';
        }

        $horaSalidaStr = $permiso['hora_salida'] ?? '00:00:00'; // Asegúrate de tener un valor por defecto
        $tiempoSolicitado = (int)($permiso['tiempo_permiso'] ?? 0); // Asegúrate de que sea un entero

        // Convertir la hora de salida a un objeto DateTime
        try {
            $horaSalida = new DateTime($horaSalidaStr);
        } catch (Exception $e) {
            // Manejo de errores si la hora de salida no es válida
            $horaSalida = new DateTime('00:00:00'); // O el valor que consideres apropiado
        }

        // Calcular la duración
        // Asumimos que 'tiempo_permiso' está en minutos. Si está en horas, cambia 'minutes' por 'hours'.
        $horaSalida->modify('+' . $tiempoSolicitado . ' minutes');

        // Formatear la duración para mostrarla en el Excel (ej. 'HH:MM')
        $duracionFormateada = $horaSalida->format('H:i:s');

        $sheet->setCellValue('A' . $cont, $permiso['nom_user'] ?? '')
            ->setCellValue('B' . $cont, $permiso['documento'] ?? '')
            ->setCellValue('C' . $cont, $permiso['fecha_permiso'] ?? '')
            ->setCellValue('D' . $cont, $permiso['hora_salida'] ?? 'N/A')
            ->setCellValue('E' . $cont, $duracionFormateada)
            ->setCellValue('F' . $cont, $permiso['tiempo_permiso'] ?? 'N/A')
            ->setCellValue('G' . $cont, $permiso['nom_motivo'] ?? 'N/A')
            ->setCellValue('H' . $cont, ($permiso['tipo_permiso_detalle'] ?? '') . ' - ' . ($permiso['descripcion'] ?? ''))
            ->setCellValue('I' . $cont, strtoupper($permiso['remunerado']) ?? '')
            ->setCellValue('J' . $cont, $evidencia_permiso ?? '')
            ->setCellValue('K' . $cont, $incapacidad ?? '');

        $sheet->getStyle('A' . $cont . ':K' . $cont)->applyFromArray($estilos_datos);

        $sheet->getStyle('K' . $cont)
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB($colorFondo);

        $cont++;
    }
}

$spreadsheet->getActiveSheet()->setTitle('Hoja Permisos y Licencias');
$fileName = "Permisos_licencias_" . date('Y-m-d') . ".xlsx";

// Limpiar buffer y enviar headers
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
