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
    header('Location:../login?er=' . $error);
    exit();
}
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';

$instancia = ControlProveedor::singleton_proveedor();

if (isset($_GET['proveedor'])) {

    $id_proveedor = base64_decode($_GET['proveedor']);

    $datos_proveedor     = $instancia->mostrarDatosProveedorIdControl($id_proveedor);
    $contactos_proveedor = $instancia->mostrarContactosProveedorControl($id_proveedor);
    $banco_proveedor     = $instancia->mostrarBancoProveedorControl($id_proveedor);

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
    $pdf->setData('encabezado.png');
    $pdf->SetAuthor('Jesus Polo');
    $pdf->SetTitle('Hoja de registro');
    $pdf->SetSubject('Hoja de registro');
    $pdf->SetKeywords('Hoja de registro');
    $pdf->AddPage();

    $pdf->Ln(-5);
    $pdf->Cell(10);
    $pdf->Image(PUBLIC_PATH . 'img/logo.png', '', '', 30, 30, '', '', 'T', false, 90, '', false, false, 0, false, false, false);

    $pdf->Ln(5);
    $pdf->Cell(45);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(142.5, 5, 'COLEGIO HEBREO UNIÒN', 'B', 0, 'C');

    $pdf->Ln(6);
    $pdf->Cell(45);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);
    $pdf->Cell(142.5, 5, 'HOJA DE REGISTRO', 'B', 0, 'C');

    $pdf->Ln(6);
    $pdf->Cell(45);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 9);
    $pdf->Cell(47.5, 5, 'Codigo:', 'B', 0, 'C');
    $pdf->Cell(47.5, 5, 'Version: 1', 'B', 0, 'C');
    $pdf->Cell(47.5, 5, 'Fecha Version: 2021-11-10', 'B', 0, 'C');

    /*--------------------------------------------------------*/

    $tabla_informacion = '
    <table border="1" cellpadding="4" style="font-size:1em; width:98%;">
    <tr style="font-weight: bold; text-transform: uppercase; text-align: center;">
    <th colspan="2">INFORMACION DEL PROVEEDOR EXTERNO</th>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Nombre: </span>' . $datos_proveedor['nombre'] . '</td>
    <td><span style="font-weight:bold;">Fecha de actualizacion: </span>' . $datos_proveedor['fechareg'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Tipo de documento: </span>' . $datos_proveedor['identificacion'] . '</td>
    <td><span style="font-weight:bold;">Numero de identificacion: </span>' . $datos_proveedor['num_identificacion'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Direccion: </span>' . $datos_proveedor['direccion'] . '</td>
    <td><span style="font-weight:bold;">Ciudad: </span>' . $datos_proveedor['ciudad'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Departamento: </span>' . $datos_proveedor['departamento'] . '</td>
    <td><span style="font-weight:bold;">Pais: </span>' . $datos_proveedor['pais'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Telefono: </span>' . $datos_proveedor['telefono'] . '</td>
    <td><span style="font-weight:bold;">Correo: </span>' . $datos_proveedor['correo'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Fecha ingreso: </span>' . $datos_proveedor['fecha_ingreso'] . '</td>
    </tr>
    </table>
    ';

    $pdf->Ln(20);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_informacion, true, false, true, false, '');
    /*--------------------------------------------------------*/

    $tabla_informacion_producto = '
    <table border="1" cellpadding="4" style="font-size:1em; width:98%;">
    <tr style="font-weight: bold; text-transform: uppercase; text-align: center;">
    <th colspan="2">INFORMACION DEL PRODUCTO</th>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Tipo: </span>' . $datos_proveedor['tipo'] . '</td>
    <td><span style="font-weight:bold;">Tiempo de entrega (Dias): </span>' . $datos_proveedor['tiempo_entrega'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Garantia (Dias/Meses): </span>' . $datos_proveedor['garantia'] . '</td>
    <td><span style="font-weight:bold;">Plazo de pago (Dias/Meses): </span>' . $datos_proveedor['plazo_pago'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Detalle del producto: </span>' . $datos_proveedor['detalle_producto'] . '</td>
    </tr>
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_informacion_producto, true, false, true, false, '');

    /*---------------------------------------------------------*/
    $tabla_informacion_legal = '
    <table border="1" cellpadding="4" style="font-size:1em; width:98%;">
    <tr style="font-weight: bold; text-transform: uppercase; text-align: center;">
    <th colspan="2">INFORMACION DEL REPRESENTANTE LEGAL</th>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Nombre completo: </span>' . $datos_proveedor['nom_representante'] . '</td>
    <td><span style="font-weight:bold;">Identificacion: </span>' . $datos_proveedor['identificacion_representante'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Correo electronico: </span>' . $datos_proveedor['correo_representante'] . '</td>
    <td><span style="font-weight:bold;">Telefono: </span>' . $datos_proveedor['telefono_representante'] . '</td>
    </tr>
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_informacion_legal, true, false, true, false, '');
    /*---------------------------------------------------------*/

    $tabla_informacion_tributaria = '
    <table border="1" cellpadding="4" style="font-size:1em; width:98%;">
    <tr style="font-weight: bold; text-transform: uppercase; text-align: center;">
    <th colspan="2">INFORMACION TRIBUTARIA</th>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Regimen: </span>' . $datos_proveedor['regimen_proveedor'] . '</td>
    <td><span style="font-weight:bold;">Gran contribuyente: </span>' . $datos_proveedor['contribuyente_proveedor'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Autoretenedor: </span>' . $datos_proveedor['autoretenedor_proveedor'] . '</td>
    <td><span style="font-weight:bold;">Responsable industria y comercio: </span>' . $datos_proveedor['comercio_proveedor'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Actividad economica: </span>' . $datos_proveedor['actividad_proveedor'] . '</td>
    <td><span style="font-weight:bold;">Tarifa: </span>' . $datos_proveedor['tarifa_proveedor'] . '</td>
    </tr>
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_informacion_tributaria, true, false, true, false, '');

    /*---------------------------------------------------------*/

    $tabla_informacion_comercial = '
    <table border="1" cellpadding="4" style="font-size:1em; width:98%;">
    <tr style="font-weight: bold; text-transform: uppercase; text-align: center;">
    <th colspan="2">REFERENCIA COMERCIAL</th>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Nombre o razon social: </span>' . $datos_proveedor['comercial_nombre'] . '</td>
    <td><span style="font-weight:bold;">Identificacion: </span>' . $datos_proveedor['identificacion_comercial'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Correo electronico: </span>' . $datos_proveedor['correo_comercial'] . '</td>
    <td><span style="font-weight:bold;">Telefono: </span>' . $datos_proveedor['telefono_comercial'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Direccion: </span>' . $datos_proveedor['direccion_comercial'] . '</td>
    <td><span style="font-weight:bold;">Ciudad: </span>' . $datos_proveedor['ciudad_comercial'] . '</td>
    </tr>
    <tr style="text-align: left; font-size:0.9em;">
    <td><span style="font-weight:bold;">Departamento: </span>' . $datos_proveedor['departamento_comercial'] . '</td>
    </tr>
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_informacion_comercial, true, false, true, false, '');

    /*---------------------------------------------------------*/

    /*-------------------Hardware----------------------*/
    $tabla_contactos = '
    <table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
    <tr style="text-align:center; font-weight:bold;">
    <th colspan="4">INFORMACION DE CONTACTOS</th>
    </tr>
    <tr style="text-align:center; font-weight:bold;">
    <th>Nombre</th>
    <th>Telefono</th>
    <th>Correo</th>
    <th>Cargo</th>
    </tr>
    ';

    foreach ($contactos_proveedor as $contacto) {
        $id_contacto = $contacto['id'];
        $nombre      = $contacto['nombre_contacto'];
        $telefono    = $contacto['telefono_contacto'];
        $correo      = $contacto['correo_contacto'];
        $cargo       = $contacto['cargo_contacto'];
        $activo      = $contacto['activo'];

        $ver = ($activo == 1) ? '' : 'display:none;';

        $tabla_contactos .= '
        <tr style="text-align:center;' . $ver . '">
        <td>' . $nombre . '</td>
        <td>' . $telefono . '</td>
        <td>' . $correo . '</td>
        <td>' . $cargo . '</td>
        </tr>
        ';
    }

    $tabla_contactos .= '
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_contactos, true, false, true, false, '');
    /*--------------------------------------------------------*/

    /*-------------------Software----------------------*/
    $tabla_pagos = '
    <table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">
    <tr style="text-align:center; font-weight:bold;">
    <th colspan="3">INFORMACION BASICA PARA EFECTUAR PAGOS</th>
    </tr>
    <tr style="text-align:center; font-weight:bold;">
    <th>Nombre</th>
    <th>Numero de cuenta</th>
    <th>Tipo de cuenta</th>
    </tr>
    ';

    foreach ($banco_proveedor as $banco) {
        $id_banco = $banco['id'];
        $nombre   = $banco['nom_banco'];
        $numero   = $banco['num_banco'];
        $tipo     = $banco['tipo_cuenta'];
        $activo   = $banco['activo'];

        $ver = ($activo == 1) ? '' : 'display:none;';

        $tabla_pagos .= '
        <tr style="text-align:center;' . $ver . '">
        <td>' . $nombre . '</td>
        <td>' . $numero . '</td>
        <td>' . $tipo . '</td>
        </tr>
        ';
    }

    $tabla_pagos .= '
    </table>
    ';

    $pdf->Ln(-1);
    $pdf->Cell(6);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla_pagos, true, false, true, false, '');
    /*-----------------------------------------------------*/
    $pdf->Output('hoja_vida_' . date('Y-m-d-H-i-s') . '.pdf', 'I');
}
