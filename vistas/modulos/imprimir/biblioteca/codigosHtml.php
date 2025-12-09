<?php
// etiquetas_barcodes.php
// Mejoras: seguridad (htmlspecialchars), manejo de sesión más claro, CSS de impresión limpio,
// generación de códigos de barra con control de errores y JS que espera al load.

require_once CONTROL_PATH . 'Session.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

// Iniciar sesión mediante la clase Session
$objss = new Session();
$objss->iniciar();

// Validar rol en sesión (evitar acceso si no existe)
if (empty($_SESSION['rol'])) {
    $er    = '2';
    $error = base64_encode($er);

    // Cerrar sesión localmente (si la clase Session ofrece método)
    $salir = new Session();
    $salir->iniciar();
    $salir->outsession();

    header('Location: ../login?er=' . $error);
    exit();
}

// Carga de datos y dependencias para la vista
require_once VISTA_PATH . 'cabeza.php';
$instancia = ControlBiblioteca::singleton_biblioteca();

// Solo continuar si viene el parámetro libro
if (!isset($_GET['libro'])) {
    echo '<div class="container p-4">Parámetro de libro faltante.</div>';
    include_once VISTA_PATH . 'script_and_final.php';
    exit();
}

$id_libro         = base64_decode($_GET['libro']);
$datos_libro      = $instancia->mostrarInformacionLibroControl($id_libro);
$datos_ejemplares = $instancia->mostrarEjemplaresControl($id_libro);

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etiquetas - <?= isset($datos_libro['titulo']) ? htmlspecialchars($datos_libro['titulo'], ENT_QUOTES, 'UTF-8') : 'Libro' ?></title>

    <!-- Puedes usar tu propio CSS o Bootstrap si ya lo cargas en cabeza.php -->
    <style>
        /* Contenedor flexible para etiquetas */
        .labels {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-start;
            align-items: stretch;
        }

        .label-card {
            box-sizing: border-box;
            width: 45%; /* dos etiquetas por fila en impresión tamaño A4 */
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
            margin: 6px 0;
        }

        .label-card img { display:block; margin: 0 auto 6px; }

        @media print {
            body { margin: 0; }
            .label-card { page-break-inside: avoid; }
            .labels { gap: 8px; }
        }

        /* Tamaño máximo para la imagen del código de barras */
        .barcode-img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row p-2" style="text-align:center;">
        <div class="labels" id="labels">
            <?php
            // Generar etiquetas; validar que datos_ejemplares sea iterable
            if (!empty($datos_ejemplares) && is_array($datos_ejemplares)) {
                foreach ($datos_ejemplares as $ejemplar) {
                    // Sanitizar el código mostrado
                    $codigo = isset($ejemplar['codigo']) ? $ejemplar['codigo'] : '';

                    // Generar imagen del código de barras dentro de un try/catch
                    $imgTag = '';
                    if ($codigo !== '') {
                        try {
                            $png = $generator->getBarcode($codigo, $generator::TYPE_CODE_128, 3, 81);
                            $b64 = base64_encode($png);
                            $imgTag = '<img class="barcode-img" src="data:image/png;base64,' . $b64 . '" alt="Codigo ' . htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8') . '">';
                        } catch (Exception $e) {
                            // En caso de error mostrar un placeholder
                            $imgTag = '<div style="height:81px; display:flex;align-items:center;justify-content:center;background:#f5f5f5;border:1px dashed #ccc;">Error generando código</div>';
                        }
                    }

                    // Crear dos etiquetas por ejemplar (si lo deseas) -- aquí se generan 2 copias
                    for ($i = 0; $i < 2; $i++) {
                        ?>
                        <div class="label-card">
                            <?php echo $imgTag; ?>
                            <h4><?= htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8') ?></h4>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<div class="col-12">No se encontraron ejemplares para este libro.</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include_once VISTA_PATH . 'script_and_final.php'; ?>

<script>
    // Esperar a que cargue todo para imprimir
    window.addEventListener('load', function () {
        // Oculta cualquier loader si existe (manteniendo compatibilidad con tu script)
        try { document.querySelector('.loader')?.classList?.add('d-none'); } catch (e) { }

        // Llamar a imprimir
        window.print();

        // Intentar cerrar la ventana después de imprimir (algunos navegadores bloquean window.close())
        window.addEventListener('afterprint', function () {
            try { window.close(); } catch (e) { /* silencioso */ }
        });
    });
</script>
</body>
</html>
