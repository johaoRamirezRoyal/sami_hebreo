<?php
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

$instancia        = ControlUsuarios::singleton_usuarios();
$instancia_perfil = ControlPerfil::singleton_perfil();

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

if (isset($_GET['usuario'])) {
    $id_user = base64_decode($_GET['usuario']);

    $datos_usuario = $instancia_perfil->mostrarDatosPerfilControl($id_user);

    $cargo = (empty($datos_usuario['nom_curso'])) ? $datos_usuario['nom_nivel'] : $datos_usuario['nom_curso'];

    $nombre    = mb_strtoupper($datos_usuario['apellido'] . ' ' . $datos_usuario['nombre']);
    $documento = $datos_usuario['documento'];
    $curso     = mb_strtoupper($cargo);

    $img = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($documento, $generator::TYPE_CODE_128, 1.6, 35)) . '">';
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Carnet</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    </head>
    <body>
        <div class="bordes">
            <div class="contenido">
                <center>
                    <p style="text-transform: uppercase; margin-bottom: 15%;">
                        <b><?=$curso?></b>
                        <br>
                        <b><?=$nombre?></b>
                        <br>
                        <b><?=$documento?></b>

                    </p>
                    <p style="margin-bottom: 15%;">
                        El presente carné es personal e intrasferible, tiene como único objetivo identificar a su portador dentro de las instalaciones.
                        <br>
                        Válido hasta junio del 2022.
                    </p>

                    <p style="margin-bottom: 1%;">
                        Autopista vìa al Mar - Poste 66
                        <br>
                        PBX: (57) 5 359 9494 - 359 9516
                        <br>
                        www.realroyalschool.edu.co
                        <br>
                        Barranquilla - Colombia
                    </p>
                    <p style="margin-top: 16.3%;">
                        <?=$img?>
                        <br>
                        <b><?=$documento?></b>

                    </p>
                </center>
            </div>
        </div>
    </body>
    </html>
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .contenido{
            width: 100%;
            margin-top: -40%;
        }
        .bordes{
            padding: 5px;
            width: 93%;
            margin-top: 40%;
            border-left:  1px solid #000;
            border-right:  1px solid #000;
            height: 170px;
        }

        p{
            font-size: 0.60em;
        }
    </style>
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
}
?>
<script>
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close();
    });
</script>