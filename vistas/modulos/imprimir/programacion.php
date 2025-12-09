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
require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$instancia        = ControlSalon::singleton_salon();
$instancia_perfil = ControlPerfil::singleton_perfil();

$super_empresa = $_SESSION['super_empresa'];

$datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl($super_empresa, 'logo');

if (isset($_GET['salon'])) {

    $buscar_salon = base64_decode($_GET['salon']);
    $buscar_fecha = base64_decode($_GET['fecha']);
    $buscar_user  = base64_decode($_GET['user']);

    $datos = array('salon' => $buscar_salon, 'fecha' => $buscar_fecha, 'user' => $buscar_user);

    $datos_reserva = $instancia->buscarDatosDetalleReservaControl($datos);
    $datos_salones = $instancia->mostrarSalonesIdReservaControl($datos);

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

    $pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->setData($datos_super_empresa['imagen']);
    $pdf->SetAuthor('Jesus Polo');
    $pdf->SetTitle('Programacion Diaria');
    $pdf->SetSubject('Programacion Diaria');
    $pdf->SetKeywords('Programacion Diaria');
    $pdf->setPageOrientation('L');
    $pdf->AddPage();

    $pdf->setJPEGQuality(90);
    $pdf->Image(PUBLIC_PATH . 'img/logo.png', 120, 5, 50);

    $pdf->Ln(25);
    $pdf->Cell(131);
    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 14);
    $pdf->Cell(12, 50, 'PROGRAMACION DIARIA', 0, 0, 'C');

    $pdf->Ln(30);

    $tabla = '';

    foreach ($datos_salones as $salon) {

        $id_salon  = $salon['id'];
        $nom_salon = $salon['nombre'];

        $tabla .= '
        <table border="1" cellpadding="3" style="font-size:1.12em; width:100%;">
        <tr style="text-align:center; text-transform: uppercase; font-weight:bold;">
        <th colspan="6">' . $nom_salon . '</th>
        </tr>
        <tr style="text-align:center; font-weight:bold;">
        <th>Usuario</th>
        <th>Complementos</th>
        <th>Fecha reserva</th>
        <th>Hora reserva</th>
        <th>Detalle reserva</th>
        <th>Nombre de actividad</th>
        <th>aforo</th>
        </tr>
        ';

        foreach ($datos_reserva as $reserva) {
            $id_reserva = $reserva['id_reserva'];
            $nom_salon  = $reserva['salon'];
            $fecha      = $reserva['fecha_reserva'];
            $complementos   = $reserva['complementos'];
            $hora       = $reserva['hora'];
            $usuario    = $reserva['usuario'];
            $detalle    = $reserva['detalle_reserva'];
            $activo     = $reserva['activo'];
            $confirmado = $reserva['confirmado'];
            $nombre_actividad = $reserva['nombre_actividad'];
            $aforo = $reserva['aforo'];

            if ($activo == 1 && $confirmado == 2 && $reserva['id_salon'] == $id_salon) {

                $tabla .= '<tr style="text-align:center;">
                <td>' . $usuario . '</td>
                <td>' . $complementos . '</td>
                <td>' . $fecha . '</td>
                <td>' . $hora . '</td>
                <td>' . $detalle . '</td>
                <td>' . $nombre_actividad . '</td>
                <td>' . $aforo . '</td>
                </tr>';

            }
        }
        $tabla .= '
        </table>
        ';
    }

    $tabla .= '';

    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
    $pdf->writeHTML($tabla, true, false, true, false, '');

    $nombre_archivo = 'Programacion_diaria' . date('Y_m_d');
    //$carpeta_destino = PUBLIC_PATH_ARCH . 'upload/' . $nombre_archivo;

    $pdf->Output($nombre_archivo . '.pdf', 'I');
    //$pdf->Output($carpeta_destino . '.pdf', 'F');
}
