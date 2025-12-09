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
require_once VISTA_PATH . 'cabeza.php';
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia = ControlBiblioteca::singleton_biblioteca();

if (isset($_GET['paquete'])) {

    $id_paquete    = base64_decode($_GET['paquete']);
    $datos_paquete = $instancia->moatrarDatosPaqueteControl($id_paquete);

    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();

    $blackColor = [0, 0, 0];
    ?>
    <body>
        <div class="container-fluid">
            <div class="row p-2" style="text-align: center;">
                <?php
                $codigo = $datos_paquete['codigo'];
                $img    = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($codigo, $generator::TYPE_CODE_128, 3, 81, $blackColor)) . '">';

                for ($i = 0; $i < 2; $i++) {
                    ?>
                    <div class="col-lg-3 p-2 form-group ml-2 text-center border mb-5" style="width: 40%; page-break-after: always;">
                        <?=$img?>
                        <h4><?=$codigo?></h4>
                    </div>
                    <br>
                    <?php
                }
                ?>
            </div>
        </div>
    </body>
    <?php
}
include_once VISTA_PATH . 'script_and_final.php';
?>
<style>
    @media print { div{ page-break-inside: avoid; } }
</style>
<script type="text/javascript">
    $(".loader").hide();
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close();
    });
</script>