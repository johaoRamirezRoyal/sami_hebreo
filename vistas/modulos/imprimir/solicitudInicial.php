<?php
date_default_timezone_set('America/Bogota');
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['nombre_admin'] && $_SESSION['rol'] != 1) {
    $er    = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';

$instancia           = ControlSolicitud::singleton_solicitud();
$instancia_proveedor = ControlProveedor::singleton_proveedor();

if (isset($_GET['solicitud'])) {

    $id_solicitud = base64_decode($_GET['solicitud']);

    $datos_solicitud    = $instancia->mostrarDatosSolicitudInicialIdControl($id_solicitud);
    $productos          = $instancia->mostrarProdcutosSolicitudInicialControl($id_solicitud);
    $datos_veriifcacion = $instancia->mostrarDatosVerificacionInicialControl($id_solicitud);
    $datos_proveedor    = $instancia_proveedor->mostrarDatosProveedorIdControl($datos_solicitud['id_proveedor']);

    $cumple_cant    = ($datos_veriifcacion['cantidad'] == 'Si') ? 'X' : '';
    $no_cumple_cant = ($datos_veriifcacion['cantidad'] == 'No') ? 'X' : '';

    $cumple_calidad    = ($datos_veriifcacion['calidad'] == 'Si') ? 'X' : '';
    $no_cumple_calidad = ($datos_veriifcacion['calidad'] == 'No') ? 'X' : '';

    $cumple_precio    = ($datos_veriifcacion['precios'] == 'Si') ? 'X' : '';
    $no_cumple_precio = ($datos_veriifcacion['precios'] == 'No') ? 'X' : '';

    $cumple_plazo    = ($datos_veriifcacion['plazos'] == 'Si') ? 'X' : '';
    $no_cumple_plazo = ($datos_veriifcacion['plazos'] == 'No') ? 'X' : '';

    $diassemana = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
    $meses      = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    $fecha_texto = $meses[date('n', strtotime($datos_solicitud['fecha_solicitud'])) - 1] . ' ' . date('d', strtotime($datos_solicitud['fecha_solicitud'])) . " de " . date('Y', strtotime($datos_solicitud['fecha_solicitud']));

    class MYPDF extends TCPDF
    {

        public function setData($logo)
        {
            $this->logo = $logo;
        }

        public function Header()
        {
        }

        public function Footer()
        {
            $this->SetY(-15);
            $this->SetFillColor(127);
            $this->SetTextColor(127);
            $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 10);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
    }

// create a PDF object
    $pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document (meta) information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Jesus Polo');
    $pdf->SetTitle('Solicitud Inicial');
    $pdf->SetSubject('Solicitud Inicial');
    $pdf->SetKeywords('Solicitud Inicial');
    $pdf->AddPage();

    /*$pdf->Ln(5);*/
    $pdf->Cell(5);
    $pdf->Image(PUBLIC_PATH . 'img/logo.png', '', '', 50, 20, '', '', 'T', false, 90, '', false, false, 1, false, false, false);

    $pdf->Cell(46);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(85, 8, 'Orden de Compra No: ' . $id_solicitud . ' - ' . date('Ymd', strtotime($datos_solicitud['fecha_solicitud'])), 1, 0, 'L');

    $pdf->Ln(8);
    $pdf->Cell(101);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(85, 8, 'Forma: MINERVA', 1, 0, 'L');

    $ln = 5;
    /*-------------------------IZQUIERDO------------------------*/
    $pdf->Ln(20);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'CIUDAD Y FECHA: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, 'Barranquilla, ' . $fecha_texto, 1, 0, 'C');

    /*---------------------DERECHO----------------------------*/
    $pdf->Cell(10);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'PROVEEDOR: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '', 1, 0, 'C');

    /*-------------------------IZQUIERDO------------------------*/
    $pdf->Ln($ln);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'COMPRADOR: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, 'AURORA MONTES HERVAS', 1, 0, 'C');

    /*---------------------DERECHO----------------------------*/
    $pdf->Cell(10);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'VENDEDOR: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '', 1, 0, 'C');

    /*-------------------------IZQUIERDO------------------------*/
    $pdf->Ln($ln);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'DIRECCION: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, 'Autopista vía al mar poste 66', 1, 0, 'C');

    /*---------------------DERECHO----------------------------*/
    $pdf->Cell(10);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'TELÉFONO: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '', 1, 0, 'C');

    /*-------------------------IZQUIERDO------------------------*/
    $pdf->Ln($ln);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'NIT: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '680,166,534-1', 1, 0, 'C');

    /*---------------------DERECHO----------------------------*/
    $pdf->Cell(10);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'CIUDAD: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '', 1, 0, 'C');

    /*---------------------DERECHO----------------------------*/
    $pdf->Ln($ln);
    $pdf->Cell(101);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(30, 5, 'NIT: ', 1, 0, 'L');
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(55, 5, '', 1, 0, 'C');

    $tabla = '
    <table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
    <tr style="text-align:center; font-weight:bold;">
    <th style="width: 10%;">Cantidad</th>
    <th style="width: 90%;">Descripcion</th>
    </tr>
    ';

    $subtotal = 0;
    $iva      = $datos_solicitud['iva'];

    foreach ($productos as $producto) {
        $id_producto = $producto['id'];
        $nombre      = $producto['producto'];
        $cantidad    = $producto['cantidad'];
        $precio      = $producto['precio'];

        $total_unidad = ($precio * $cantidad);
        $subtotal += $total_unidad;

        $tabla .= '
        <tr style="text-align: center;">
        <td>' . $cantidad . '</td>
        <td>' . $nombre . '</td>
        </tr>
        ';

    }

    $tabla .= '
    <tr>
    <td style="width: 33.3%;"><span style="font-weight:bold;">ELABORADO POR:</span> ' . $datos_solicitud['nom_aprobado'] . '</td>
    <td style="width: 33.3%;"><span style="font-weight:bold;">APROBADO POR:</span></td>
    <td style="width: 33.3%;"><span style="font-weight:bold;">ACEPTADO PROVEEDOR:</span></td>
    </tr>
    <tr>
    <td style="width: 100%;">
    LAS ESPECIFICAIONES DE LA MERCANCIAS ANOTADAS EN ESTA ORDEN, DEBERÁN SER IGUALES A LO SUMINISTRADO Y COINCIDIR CON LA FACTURA.  FAVOR ANOTAR
    EL NÚMERO DE ESTA ORDEN EN LAS FACTURAS, PLANILLAS, RELACIONES, REMISIONES Y DEMÁS DOCUMENTOS.  LAS COMPRAS QUE SE EFECTUEN CON LA PRESENTE
    ORDEN SERÁN PAGADAS CON EL VALOR NETO, DESCUENTOS E IMPUESTOS QUE EN ELLA APAREZCAN, PASADAS LAS FECHAS DE ENTREGA ESTIPULADAS EN LA MISMA,
    NOS RESERVAMOS EL DERECHO DE RECIBIR LA MERCANCIA.  DE NO ESTAR DE ACUERDO CON ESTA ORDEN, FAVOR NO ACEPTARLA.
    </td>
    </tr>
    <tr>
    <td style="width: 100%;">
    Forma MINERVA 30 - 02   Diseñadas y actualizadas según la Ley © por LEGIS
    </td>
    </tr>
    </table>
    ';

    $pdf->Ln(15);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla, true, false, true, false, '');

    $style = array(
        'position'     => 'C',
        'align'        => 'C',
        'stretch'      => false,
        'fitwidth'     => true,
        'cellfitalign' => '',
        'border'       => false,
        'hpadding'     => 'auto',
        'vpadding'     => 'auto',
        'fgcolor'      => array(0, 0, 0),
        'bgcolor'      => false, //array(255,255,255),
        'text'         => true,
        'font'         => 'helvetica',
        'fontsize'     => 8,
        'stretchtext'  => 4,
    );

    $nombre_archivo = 'solicitud_inicial_' . md5($id_solicitud);

    $pdf->Output($nombre_archivo . '.pdf', 'I');
}
