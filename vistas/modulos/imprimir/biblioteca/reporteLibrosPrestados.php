<?php
date_default_timezone_set('America/Bogota');
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

require_once LIB_PATH . 'PhpSpreadsheet' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia = ControlBiblioteca::singleton_biblioteca();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['fecha_inicio'])) {

    $datos = array('nivel' => $_GET['nivel'], 'curso' => $_GET['curso'], 'fecha_inicio' => $_GET['fecha_inicio'], 'fecha_fin' => $_GET['fecha_fin']);

    $datos_prestamos = $instancia->reporteLibrosPrestadosControl($datos);

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getProperties()
    ->setTitle('Reporte de libros Prestados')
    ->setDescription('Este documento fue generado por el sistema');

    $sheet = $spreadsheet->setActiveSheetIndex(0);

    $estilos_cabecera = [
        'font'      => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $estilos_datos = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    $sheet->getStyle('A1:L1')->applyFromArray($estilos_cabecera);
    $sheet->getStyle('A:L')->applyFromArray($estilos_datos);

    foreach (range('A', 'L') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $sheet->setCellValue('A1', 'No. PRESTAMO')
    ->setCellValue('B1', 'USUARIO')
    ->setCellValue('C1', 'NIVEL')
    ->setCellValue('D1', 'CURSO')
    ->setCellValue('E1', 'LIBRO')
    ->setCellValue('F1', '#EJEMPLAR')
    ->setCellValue('G1', 'CATEGORIA')
    ->setCellValue('H1', 'SUBCATEGORIA')
    ->setCellValue('I1', 'FECHA PRESTAMO')
    ->setCellValue('J1', 'FECHA DEVOLUCION')
    ->setCellValue('K1', 'OBSERVACION')
    ->setCellValue('L1', 'ESTADO');

    $cont = 2;

    foreach ($datos_prestamos as $prestamo) {
        $id_prestamo      = $prestamo['id_prestamo'];
        $nom_libro        = $prestamo['titulo'];
        $codigo_ejem      = $prestamo['codigo'];
        $nom_categoria    = $prestamo['nom_categoria'];
        $nom_subcategoria = $prestamo['nom_subcategoria'];
        $fecha_prestamo   = $prestamo['fecha_prestamo'];
        $fecha_devolucion = $prestamo['fecha_devolucion'];
        $observacion      = $prestamo['observacion'];
        $nom_user         = $prestamo['nom_user'];
        $devuelto         = $prestamo['id_devuelto'];
        $fecha_devuelto   = $prestamo['fecha_devuelto'];
        $nivel            = $prestamo['nom_nivel'];
        $curso            = $prestamo['nom_curso'];

        if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {
            $span_fecha = 'Por vencer';
        }

        if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {
            $span_fecha = 'A tiempo para devolucion';
        }

        if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {
            $span_fecha = 'Retrasado';
        }

        if ($fecha_devuelto > $fecha_devolucion) {
            $span_fecha = 'Devuelto con retraso';
        }

        if ($fecha_devuelto < $fecha_devolucion && !empty($fecha_devuelto)) {
            $span_fecha = 'Devuelto antes de tiempo';
        }

        if ($fecha_devuelto == $fecha_devolucion) {
            $span_fecha = 'Devuelto justo a tiempo';
        }

        $sheet->setCellValue('A' . $cont, $id_prestamo)
        ->setCellValue('B' . $cont, $nom_user)
        ->setCellValue('C' . $cont, $nivel)
        ->setCellValue('D' . $cont, $curso)
        ->setCellValue('E' . $cont, $nom_libro)
        ->setCellValue('F' . $cont, $codigo_ejem)
        ->setCellValue('G' . $cont, $nom_categoria)
        ->setCellValue('H' . $cont, $nom_subcategoria)
        ->setCellValue('I' . $cont, $fecha_prestamo)
        ->setCellValue('J' . $cont, $fecha_devolucion)
        ->setCellValue('K' . $cont, $observacion)
        ->setCellValue('L' . $cont, $span_fecha);

        $cont++;
    }

    $spreadsheet->getActiveSheet()->setTitle('Hoja 1');

    $fileName = "Reporte_prestamos_" . date('Y-m-d') . ".xlsx";
    $writer   = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
    $writer->save('php://output');
}
