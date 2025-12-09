<?php
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

if (isset($_POST['codigo'])) {

    $codigo = $_POST['codigo'];

    $ruta = PUBLIC_PATH_ARCH . 'upload' . DS . $codigo . '.png';

    $blackColor = [0, 0, 0];

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    file_put_contents($ruta, $generator->getBarcode($codigo, $generator::TYPE_CODE_39, 3, 50, $blackColor));

    $img = $ruta;

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($img));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($img));
    ob_clean();
    flush();
    readfile($img);
}
