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

include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';

require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia_proovedor = ControlProveedor::singleton_proveedor();
$instancia_usuario   = ControlUsuarios::singleton_usuarios();

$datos_usuario = $instancia_usuario->mostrarUsuariosControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if(isset($_GET['proveedor'])){
	$id_proveedor = base64_decode($_GET['proveedor']);
    $id_calificacion = base64_decode($_GET['id_calificacion']);

	$datos_proveedor = $instancia_proovedor->mostrarDatosProveedorIdControl($id_proveedor);

    $reevaluacion_proveedor = $instancia_proovedor->getEvaluacionProveedor($id_proveedor,$id_calificacion);

    $pregunta_1 = $reevaluacion_proveedor[0]['pregunta_1'];
    $pregunta_2 = $reevaluacion_proveedor[0]['pregunta_2'];
    $pregunta_3 = $reevaluacion_proveedor[0]['pregunta_3'];
    $pregunta_4 = $reevaluacion_proveedor[0]['pregunta_4'];
    $pregunta_5 = $reevaluacion_proveedor[0]['pregunta_5'];
    
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>proveedor/index"  class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Detalles de la evaluación al proveedor: (<?=$datos_proveedor['nombre']?>) - <?=$datos_proveedor['identificacion']?>: <?=$datos_proveedor['num_identificacion']?>
                    </h4>
                </div>
                <div>
                    <div class="card-body">
                        <div class="row p-2">
                        <html>
                                <head>
                                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                    <script type="text/javascript">
                                        google.charts.load('current', {
                                            'packages': ['corechart']
                                        });
                                        google.charts.setOnLoadCallback(drawChart);

                                        function drawChart() {
                                            var data = google.visualization.arrayToDataTable([
                                                ['Pregunta', 'Porcentaje', { role: 'style' }],
                                                <?php
                                                echo "
                                                ['Las productos o servicios comprados o contratados cumplen con las especificaciones.', " . $pregunta_1 . ", '#3366CC'],
                                                ['El producto y/o servicio cumple los precios acotados en la negociación?', " . $pregunta_2 . ", '#DC3912'],
                                                ['¿El proveedor externo atiende de forma oportuna las solicitudes, quejas y/o reclamos?', " . $pregunta_3 . ", '#FF9900'],
                                                ['¿El proveedor se responsabiliza por las garantias de los productos y/o servicios adquiridos por la institución?', " . $pregunta_4 . ", '#109618'],
                                                ['¿El proveedor brinda plazos y facilidades de pago?', " . $pregunta_5 . ", '#990099']
                                                ";
                                                ?>
                                            ]);

                                            var options = {
                                                title: 'Evaluación de Proveedores',
                                                titleTextStyle: {
                                                    fontSize: 18,
                                                    bold: true
                                                },
                                                chartArea: {
                                                    width: '70%',
                                                    left: '20%',
                                                    top: 60,
                                                    right: 20,
                                                    bottom: 50
                                                },
                                                hAxis: {
                                                    title: 'Calificación',
                                                    minValue: 0,
                                                    maxValue: 5,
                                                    titleTextStyle: { italic: false, bold: true }
                                                },
                                                vAxis: {
                                                    title: '',
                                                    textStyle: { fontSize: 12 },
                                                    gridlines: { count: 0 },
                                                    format: '0'
                                                },
                                                bars: 'horizontal',
                                                legend: { position: 'none' },
                                                colors: ['#3366CC', '#DC3912', '#FF9900', '#109618', '#990099'],
                                                bar: { groupWidth: '80%' },
                                                animation: {    
                                                    startup: true,
                                                    duration: 1000,
                                                    easing: 'out'
                                                }
                                            };

                                            var chart = new google.visualization.BarChart(document.getElementById('barchart'));
                                            chart.draw(data, options);
                                        }
                                    </script>
                                </head>

                                <body style="text-align: center;">
                                    <div id="barchart" style="width: 1100px; height: 550px; margin: 0 auto;"></div> 
                                </body>
                            </html>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>