<?php

date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'reportes' . DS . 'ModeloReportes.php';

require_once MODELO_PATH . 'inventario' . DS . 'ModeloInventario.php';

require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';

require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';

require_once MODELO_PATH . 'usuarios' . DS . 'ModeloUsuarios.php';



class ControlReporte
{



    private static $instancia;



    public static function singleton_reporte()
    {

        if (!isset(self::$instancia)) {

            $miclase = __CLASS__;

            self::$instancia = new $miclase;

        }

        return self::$instancia;

    }



    public function mostrarReportesControl()
    {

        $consulta = ModeloReportes::comandoSQL();

        $mostrar = ModeloReportes::mostrarReportesModel();

        return $mostrar;

    }

    public function mostrarArticuloReportadoControl($id)
    {
        $consulta = ModeloReportes::comandoSQL();
        $mostrar  = ModeloReportes::mostrarArticuloReportadoModel($id);
        return $mostrar;
    }



    public function buscarReportesControl($datos)
    {



        $usuario = ($datos['usuario'] == '') ? '' : ' AND u.id_user = ' . $datos['usuario'];

        $area = ($datos['area'] == '') ? '' : ' AND ar.id = ' . $datos['area'];



        $datos = array('usuario' => $usuario, 'area' => $area, 'buscar' => $datos['buscar']);



        $consulta = ModeloReportes::comandoSQL();

        $mostrar = ModeloReportes::buscarReportesModel($datos);

        return $mostrar;

    }



    public function mostrarReportesSolucionadosControl()
    {

        $consulta = ModeloReportes::comandoSQL();

        $mostrar = ModeloReportes::mostrarReportesSolucionadosModel();

        return $mostrar;

    }



    public function vistoBuenoGeneralControl()
    {

        $consulta = ModeloReportes::comandoSQL();

        $mostrar = ModeloReportes::vistoBuenoGeneralModel();



        if ($mostrar == true) {

            echo '

            <script>

            ohSnap("Actualizados correctamente!", {color: "green", "duration": "1000"});

            setTimeout(recargarPagina,1050);



            function recargarPagina(){

                window.location.replace("visto");

            }

            </script>

            ';

        } else {

            echo '

            <script>

            ohSnap("Error al subir archivo", {color: "red"});

            </script>

            ';

        }

    }



    public function mostrarInformacionSolucionReporteControl($id)
    {

        $consulta = ModeloReportes::comandoSQL();

        $mostrar = ModeloReportes::mostrarInformacionSolucionReporteModel($id);

        return $mostrar;

    }



    public function solucionarReporteControl()


    {


        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['nom_inventario_sol']) &&

            !empty($_POST['nom_inventario_sol'])

        ) {



            $fecha_respuesta = (isset($_POST['fecha_respuesta'])) ? $_POST['fecha_respuesta'] . date(' H:i:s') : date('Y-m-d H:i:s');



            $datos = array(

                'nom_inventario_rep' => $_POST['nom_inventario_sol'],

                'id_area' => $_POST['id_area'],

                'id_user' => $_POST['user'],

                'estado' => 2,

                'cantidad' => 1,

            );



            $articulos_reportados = ModeloInventario::articulosReportadosModel($datos);



            foreach ($articulos_reportados as $reportado) {



                $datos_reporte_articulo = ModeloReportes::informacionReporteArticuloModel($reportado['id']);



                if (!empty($datos_reporte_articulo['id_inventario'])) {



                    $datos_solucion = array(

                        'id_inventario' => $datos_reporte_articulo['id_inventario'],

                        'id_resp' => $_POST['resp'],

                        'id_log' => $_POST['resp'],

                        'id_user' => $datos_reporte_articulo['id_user'],

                        'observacion' => $_POST['observacion'],

                        'estado' => 3,

                        'fecha_respuesta' => $fecha_respuesta,

                        'tipo_reporte' => $datos_reporte_articulo['tipo_reporte'],

                        'id_reporte' => $datos_reporte_articulo['id'],

                        'id_area' => $datos_reporte_articulo['id_area'],

                    );

                    $guardar_reporte = ModeloReportes::solucionarReporteModel($datos_solucion);

                } else {
                    echo '
                    <script>
                        ohSnap("Error al cargar los datos de reporte", {color: "red"});
                    </script>
                    ';
                }
            } 

            if ($guardar_reporte == true) {
                $datos_buscar = array(
                    'nom_inventario_rep' => $_POST['nom_inventario_sol'],
                    'id_area' => $_POST['id_area'],
                    'id_user' => $_POST['user'],
                    'estado' => 3,
                    'cantidad' => $_POST['cantidad'],
                );

                $datos_articulo = ModeloInventario::mostrarDatosAgrupadosArticulosModel($datos_buscar);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['resp']);
                $fecha = $datos_articulo['fecha_reporte'];
                $usuario_responsable = ModeloPerfil::mostrarDatosPerfilModel($_POST['user']);

                $mensaje = '
                <div>
                    <p style="font-size: 1.6em;">
                        El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha solucionado el reporte del siguiente articulo:
                    </p>
                    <p>
                        <ul style="font-size: 1.4em;">
                            <li><b>Descripciòn:</b> ' . $datos_articulo['descripcion'] . '</li>
                            <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>
                            <li><b>Cantidad:</b> ' . 1 . ' </li>
                            <li><b>Estado del articulo:</b> Arreglado</li>
                            <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>
                            <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>
                            <li><b>Fecha de respuesta:</b> ' . $fecha_respuesta . '</li>
                            <li><b>Observacion:</b> ' . $datos_articulo['observacion'] . '</li>
                        </ul>
                    </p>
                </div>
                ';



                $datos_correo = array(
                    'asunto' => 'Solucion de articulo',
                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com', $datos_usuario['correo'], $usuario_responsable['correo']),
                    'user' => 'Administrador',
                    'mensaje' => $mensaje,
                    'archivo' => array(''),
                );

                //if (!empty($_POST['enviar_correo']) && isset($_POST['enviar_correo'])) {
                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);

                //}

                if ($enviar_correo) {
                    echo '
                    <script>
                        ohSnap("Solucionado correctamente!", {color: "green", "duration": "1000"});
                        setTimeout(recargarPagina,1050);
                    </script>
                    ';
                } else {
                    echo "NO SE PUDO ENVIAR CORREO";
                }
            }
        }
    }



    public function vistoBuenoReporteControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])

        ) {

            $guardar = ModeloReportes::vistoBuenoReporteModel($_POST['id']);

            return $guardar;

        }

    }



    public function guardarFirmasUsuarioControl($datos)
    {

        $nom_arch = $datos['archivo'];

        //extraer la extencion del archivo de el archivo

        $ext_arch = explode(".", $nom_arch);

        $ext_arch = end($ext_arch);

        $fecha_arch = date('YmdHis');



        $nombre_archivo = strtolower(md5($datos['id_log'] . '_' . $fecha_arch)) . '.' . $ext_arch;



        $datos_firma = array(

            'nombre' => $nombre_archivo,

            'id_user' => $datos['responsable'],

            'user_log' => $datos['id_log'],

            'terminos' => 1,

            'firma_digital' => 0,

        );



        $guardar = ModeloUsuarios::guardarFirmaUsuarioModel($datos_firma);



        if ($guardar == true) {

            //ruta donde de alojamiento el archivo

            $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;

            $ruta_img = $carp_destino . $nombre_archivo;



            //verificar si subio el archivo y se mueve a su destino

            if (is_uploaded_file($_FILES[$datos['tipo']]['tmp_name'])) {

                move_uploaded_file($_FILES[$datos['tipo']]['tmp_name'], $ruta_img);

            }

            return true;

        }

    }


}

