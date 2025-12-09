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
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia        = ControlAsistencia::singleton_asistencia();

if (isset($_GET['buscar'])) {
    $datos          = array('buscar' => $_GET['buscar'], 'perfil' => $_GET['perfil'], 'fecha' => $_GET['fecha']);
    $datos_usuarios = $instancia->buscarUsuarioAsistenciaGestionControl($datos);
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()
->setTitle('Reporte de asistencia')
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

$sheet->getStyle('A1:E1')->applyFromArray($estilos_cabecera);
$sheet->getStyle('A:E')->applyFromArray($estilos_datos);

foreach (range('A', 'E') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

$sheet->setCellValue('A1', 'DOCUMENTO')
->setCellValue('B1', 'NOMBRE COMPLETO')
->setCellValue('C1', 'PERFIL')
->setCellValue('D1', 'FECHA DE ASISTENCIA')
->setCellValue('E1', 'HORA DE ASISTENCIA');

$cont = 2;

foreach ($datos_usuarios as $usuario) {

    $documento        = $usuario['documento'];
    $nombre_completo  = $usuario['nom_user'];
    $perfil           = $usuario['perfil'];
    $fecha_asistencia = $usuario['fecha_asistencia'];
    $hora             = $usuario['hora_asistencia'];

    $sheet->setCellValue('A'.$cont,$documento)
    ->setCellValue('B'.$cont,$nombre_completo)
    ->setCellValue('C'.$cont,$perfil)
    ->setCellValue('D'.$cont,$fecha_asistencia)
    ->setCellValue('E'.$cont,$hora);

    $cont++;
}


    $spreadsheet->getActiveSheet()->setTitle('Hoja 1');

    $fileName = "Reporte_Asistencia" . date('Y-m-d') . ".xlsx";
    $writer   = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
    $writer->save('php://output');