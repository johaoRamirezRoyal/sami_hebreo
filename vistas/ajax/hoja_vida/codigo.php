<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

if (isset($_POST['codigo'])) {

    $codigo = $_POST['codigo'];

    $ruta = PUBLIC_PATH_ARCH . 'upload' . DS . $codigo . '.png';

    $blackColor = [0, 0, 0];

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    file_put_contents($ruta, $generator->getBarcode($codigo, $generator::TYPE_CODE_39, 3, 50, $blackColor));

    echo $codigo . '.png';
}
