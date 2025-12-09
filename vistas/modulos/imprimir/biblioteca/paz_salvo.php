<?php



// require_once CONTROL_PATH . 'Session.php';



// $objss = new Session;



// $objss->iniciar();



// if (!$_SESSION['rol']) {

//     $er = '2';

//     $error = base64_encode($er);

//     $salir = new Session;

//     $salir->iniciar();

//     $salir->outsession();

//     header('Location:../login?er=' . $error);

//     exit();

// }



require_once LIB_PATH . 'tcpdf' . DS . 'tcpdf.php';

require_once LIB_PATH . 'bardcode' . DS . 'vendor' . DS . 'autoload.php';

require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';

require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';



$instancia = ControlBiblioteca::singleton_biblioteca();

$instancia_perfil = ControlPerfil::singleton_perfil();

$instancia_usuario = ControlUsuarios::singleton_usuarios();



$datos_super_empresa = $instancia_perfil->mostrarDatosSuperEmpresaControl(1, 'encabezado2');



if (isset($_GET['usuario'])) {

    $id_usuario = base64_decode($_GET['usuario']);

    $datos_usuarios = $instancia_usuario->mostrarUsuariosDatosControl($id_usuario);

    $datos_prestamos = $instancia->prestamosUsuarioControl($id_usuario);



    class MYPDF extends TCPDF

    {

        public function setData($logo)

        {

            $this->logo = $logo;

        }



        public function Header()

        {

            /* $this->setJPEGQuality(90);

            $this->Image(PUBLIC_PATH . 'img/' . $this->logo, 0, 0, 210, 35);

            $this->Ln(30);

            $this->Cell(90);

            $this->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);

            $this->Cell(12, 50, 'ENTREGA DE INVENTARIO', 0, 0, 'C'); */

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

    $pdf->setData($datos_super_empresa['imagen']);

    $pdf->SetAuthor('Colegio Real');

    $pdf->SetTitle('Paz y salvo');

    $pdf->SetSubject('Paz y salvo');

    $pdf->SetKeywords('Paz y salvo');

    $pdf->AddPage();



    $pdf->Cell(70);

    $pdf->Image(PUBLIC_PATH . 'img/' . $datos_super_empresa['imagen'], '', '', 50, 20, '', '', 'T', false, 90, '', false, false, 1, false, false, false);



    $pdf->Ln(25);

    $pdf->Cell(72);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 10);

    $pdf->Cell(50, 8, 'PAZ Y SALVO BIBLIOTECA', 0, 0, 'C');



    $pdf->Ln(10);

    $pdf->Cell(10);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);



    if ($datos_usuarios['perfil'] == 16) {

        $span = 'el estudiante <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . '</span> Perteneciente al curso <span style="font-weight:bold;">' . $datos_usuarios['nom_curso'] . '</span>';

        $firma = 'Estudiante';

    } else if ($datos_usuarios['perfil'] == 3) {

        $span = 'el docente <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . ',</span>';

        $firma = 'Docente';

    } else {

        $span = 'el trabajador <span style="font-weight:bold;">' . $datos_usuarios['nombre'] . ' ' . $datos_usuarios['apellido'] . ',</span>';

        $firma = 'Trabajador';

    }

    $prestamos_pendientes = array_filter($datos_prestamos, function($prestamo) {

            return empty($prestamo['fecha_devuelto']);

        });

    $parrafo = '

    <div style="width: 90%;">

    <p>El <span style="font-weight:bold;">Colegio Hebreo Union</span> certifica que ' . $span . ' identificado con número de documento <span style="font-weight:bold;">' . $datos_usuarios['documento'] . '</span>  se encuentra a paz y salvo por concepto de prestamo de libros.</p>

    </div>';



    $parrafoPendiente = '

    <div style="width: 90%;">

    <p>El <span style="font-weight:bold;">Colegio Hebreo Union</span> certifica que ' . $span . ' identificado con número de documento <span style="font-weight:bold;">' . $datos_usuarios['documento'] . '</span> Se encuentra en la obligación de hacer entrega de los libros que aún están en su posesión, los cuales se detallan en la siguiente tabla.</p>

    </div>';

    

    $tiene_pendientes = count($prestamos_pendientes) > 0;

    $mensaje_final = $tiene_pendientes ? $parrafoPendiente : $parrafo;



    $pdf->writeHTML($mensaje_final, true, false, true, false, '');



    $pdf->Ln(5);

    $pdf->Cell(1);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);

    $tabla_prestamos = '

    <table border="1" cellpadding="3" style="font-size:8.5px; width:98%;">

    <tr style="text-align:center; font-weight:bold; text-transform: uppercase;">

    <th colspan="8">HISTORIAL DE PRESTAMOS</th>

    </tr>

    <tr style="text-align:center; font-weight:bold; text-transform: uppercase;">

    <th>No. PRESTAMO</th>

    <th>LIBRO</th>

    <th>#EJEMPLAR</th>

    <th>CATEGORÍA</th>

    <th>SUBCATEGORÍA</th>

    <th>FECHA DE PRESTAMO</th>

    <th>FECHA DEVOLUCIÓN</th>

    <th>ESTADO</th>

    </tr>';



    



    if (count($prestamos_pendientes) <= 0) {

        $tabla_prestamos .= '

        <tr style="text-align:center;">

        <td colspan="8">No hay prestamos pendientes</td>

        </tr>

        ';

    } else {

        foreach ($prestamos_pendientes as $prestamo) {

            $id_prestamo = $prestamo['id_prestamo'];

            $nom_libro = $prestamo['titulo'];

            $codigo_ejem = $prestamo['codigo'];

            $nom_categoria = $prestamo['nom_categoria'];

            $nom_subcategoria = $prestamo['nom_subcategoria'];

            $fecha_prestamo = $prestamo['fecha_prestamo'];

            $fecha_devolucion = $prestamo['fecha_devolucion'];

            $devuelto = $prestamo['id_devuelto'];



            if ($fecha_devolucion <= date('Y-m-d') && $devuelto == '') {

                $span_fecha = 'Por vencer';

            } else if (date('Y-m-d') < $fecha_devolucion && $devuelto == '') {

                $span_fecha = 'A tiempo';

            } else if (date('Y-m-d') > $fecha_devolucion && $devuelto == '') {

                $span_fecha = 'Retrasado';

            } else {

                $span_fecha = 'Desconocido';

            }



            $tabla_prestamos .= '

            <tr style="text-align:center;">

            <td>' . $id_prestamo . '</td>

            <td>' . $nom_libro . '</td>

            <td>' . $codigo_ejem . '</td>

            <td>' . $nom_categoria . '</td>

            <td>' . $nom_subcategoria . '</td>

            <td>' . $fecha_prestamo . '</td>

            <td>' . $fecha_devolucion . '</td>

            <td>' . $span_fecha . '</td>

            </tr>

            ';

        }

    }



    $tabla_prestamos .= '

    </table>';

    $pdf->writeHTML($tabla_prestamos, true, false, true, false, '');



    $pdf->Ln(10);

    $pdf->Cell(1);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);

    $pdf->Cell(40, 8, 'Nota: Este documento es válido como paz y salvo únicamente con firma y sello de biblioteca.', 0, 0, 'L');



    $pdf->Ln(30);

    $pdf->Cell(40);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 8);

    $pdf->Cell(40, 8, '__________________________', 0, 0, 'L');



    $pdf->Ln(5);

    $pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 9);

    $pdf->Cell(50);

    $pdf->Cell(40, 8, 'Nombre ' . $firma, 0, 0, 'L');



    if (!$tiene_pendientes) {

    $pdf->Image(PUBLIC_PATH . 'img/paz-y-salvo-sami.png', 130, $pdf->GetY(), 60, 40, '', '', '', false, 300, '', false, false, 0, false, false, false);

}



    ob_end_clean();

    $pdf->Output('paz_salvo_' . date('Y-m-d-H-i-s') . ' . pdf', 'I');

}

?>

