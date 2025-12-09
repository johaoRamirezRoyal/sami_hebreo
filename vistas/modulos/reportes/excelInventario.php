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
require_once LIB_PATH . 'PhpSpreadsheet' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();

$instancia = ControlInventario::singleton_inventario();

if (isset($_GET['area'])) {

    $area     = base64_decode($_GET['area']);
    $usuario  = base64_decode($_GET['usuario']);
    $articulo = base64_decode($_GET['articulo']);

    $datos = array(
        'area'     => $area,
        'usuario'  => $usuario,
        'articulo' => $articulo,
    );

    $buscar = $instancia->buscarInventarioDetalleControl($datos);

    $spreadsheet->getProperties()
    ->setTitle('Reporte inventario')
    ->setDescription('Este documento fue generado por cronograma');

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

    $sheet->getStyle('A1:Z1')->applyFromArray($estilos_cabecera);
    $sheet->getStyle('A:Z')->applyFromArray($estilos_datos);

    foreach (range('A', 'J') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    $sheet->setCellValue('A1', 'ID')
    ->setCellValue('B1', 'AREA')
    ->setCellValue('C1', 'DESCRIPCION')
    ->setCellValue('D1', 'MARCA')
    ->setCellValue('E1', 'MODELO')
    ->setCellValue('F1', 'PRECIO')
    ->setCellValue('G1', 'ESTADO')
    ->setCellValue('H1', 'FECHA COMPRA')
    ->setCellValue('I1', 'USUARIO')
    ->setCellValue('J1', 'CODIGO');

    $cont = 2;

    foreach ($buscar as $inventario) {
        $id_inventario = $inventario['id'];
        $descripcion   = $inventario['descripcion'];
        $area          = $inventario['area'];
        $marca         = $inventario['marca'];
        $modelo        = $inventario['modelo'];
        $precio        = $inventario['precio'];
        $estado        = $inventario['estado_nombre'];
        $fecha         = $inventario['fecha_compra'];
        $usuario       = $inventario['usuario'];
        $codigo        = $inventario['codigo'];

        $sheet->setCellValue('A' . $cont, $id_inventario)
        ->setCellValue('B' . $cont, $area)
        ->setCellValue('C' . $cont, $descripcion)
        ->setCellValue('D' . $cont, $marca)
        ->setCellValue('E' . $cont, $modelo)
        ->setCellValue('F' . $cont, $precio)
        ->setCellValue('G' . $cont, $estado)
        ->setCellValue('H' . $cont, $fecha)
        ->setCellValue('I' . $cont, $usuario)
        ->setCellValue('J' . $cont, $codigo);

        $cont++;
    }

    $spreadsheet->getActiveSheet()->setTitle('Hoja 1');
    $spreadsheet->setActiveSheetIndex(0);
    $nombreDelDocumento = "Reporte_inventario.xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
}
