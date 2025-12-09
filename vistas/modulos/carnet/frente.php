<?php
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

$instancia        = ControlUsuarios::singleton_usuarios();
$instancia_perfil = ControlPerfil::singleton_perfil();

if (isset($_GET['usuario'])) {
    $id_user = base64_decode($_GET['usuario']);

    $datos_usuario = $instancia_perfil->mostrarDatosPerfilControl($id_user);

    $cargo = (empty($datos_usuario['nom_curso'])) ? $datos_usuario['nom_nivel'] : $datos_usuario['nom_curso'];

    $nombre    = mb_strtoupper($datos_usuario['apellido'] . ' ' . $datos_usuario['nombre']);
    $documento = $datos_usuario['documento'];
    $curso     = mb_strtoupper($cargo);
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
                    <img src="<?=PUBLIC_PATH?>img/fondo_carnet.png" class="fondo" alt="">
                    <p class="nombre"><?=$nombre?></p>
                    <p class="cargo"><?=$curso?></p>
                    <div class="circular--portrait">
                        <img src="<?=PUBLIC_PATH?>upload/<?=$datos_usuario['foto_carnet']?>" alt="">
                    </div>
                </center>
            </div>
        </div>
    </body>
    </html>
    <style type="text/css">
        *{
            font-family: 'Open Sans', sans-serif;
        }
        body{
            outline: 0;
            padding: 0;
            margin: 0;
        }
        .fondo{
            width: 100%;
            height: 319px;
        }
        .nombre{
            position: relative;
            margin-top: -52%;
            font-size: 0.70em;
        }
        .cargo{
            position: relative;
            margin-top: 13%;
            font-size: 0.65em;
        }

        .circular--portrait {
            position: relative;
            width: 122px;
            height: 122px;
            overflow: hidden;
            border-radius: 50%;
            margin-top: -99.5%;
            z-index: -1000 !important;
            overflow: hidden;
        }

        .circular--portrait img {
            width: 100%;
            height: auto;
        }
    </style>
    <?php
    include_once VISTA_PATH . 'script_and_final.php';
}
?>
<script>
    $(".loader").hide();
    window.print();
    window.addEventListener("afterprint", function(event) {
        window.close();
    });
</script>