<?php

date_default_timezone_set('America/Bogota');

require_once MODELO_PATH . 'inventario' . DS . 'ModeloInventario.php';

require_once CONTROL_PATH . 'hash.php';

require_once MODELO_PATH . 'correo' . DS . 'ModeloCorreos.php';

require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';

require_once MODELO_PATH . 'reportes' . DS . 'ModeloReportes.php';



class ControlInventario
{



    private static $instancia;



    public static function singleton_inventario()
    {

        if (!isset(self::$instancia)) {

            $miclase = __CLASS__;

            self::$instancia = new $miclase;
        }

        return self::$instancia;
    }



    public function mostrarDatosTemporalesControl($id_log, $super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarDatosTemporalesModel($id_log, $super_empresa);

        return $mostrar;
    }



    public function mostrarDatosCartaEntregaControl($usuario, $area)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarDatosCartaEntregaModel($usuario, $area);

        return $mostrar;
    }



    public function mostrarCategoriasControl($super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarCategoriasModel($super_empresa);

        return $mostrar;
    }



    public function informacionReporteControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::informacionReporteModel($id);

        return $mostrar;
    }



    public function mostrarArticulosLiberadosControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarArticulosLiberadosModel();

        return $mostrar;
    }



    public function mostrarDatosEquipoComputoControl($datos)
    {



        $area = ($datos['area'] == '') ? '' : ' AND iv.id_area = ' . $datos['area'];

        $usuario = ($datos['usuario'] == '') ? '' : ' AND iv.id_area = ' . $datos['usuario'];



        $datos = array('area' => $area, 'usuario' => $usuario, 'buscar' => $datos['buscar']);



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarDatosEquipoComputoModel($datos);

        return $mostrar;
    }

    public function mostrarArticulosAgrupadosModalControl ($descripcion,$idarea,$id_usuario){
        $comando = ModeloInventario::ComandoSQL();
        $mostrar = ModeloInventario::mostrarArticulosAgrupadosModalModel($descripcion,$idarea, $id_usuario);
        return $mostrar;
    }

    
    public function guardarInventarioDirecto()
    {   

        $fecha_compra  = ($_POST['fecha'] == '') ? '0000-00-00' : $_POST['fecha'];
        $fecha_ingreso = ($_POST['fecha_ingreso'] == '') ? '0000-00-00' : $_POST['fecha_ingreso'] . date(' H:i:s');

        $codigo = $this->numeroAleatorio();

        $cantidad = $_POST['cantidad'];

        $datos = array(
            'descripcion'   => $_POST['descripcion'],
            'marca'         => $_POST['marca'],
            'modelo'        => $_POST['modelo'],
            'precio'        => $_POST['precio'],
            'estado'        => 1,
            'activo'        => 1,
            'fecha_compra'  => $fecha_compra,
            'id_user'       => $_POST['usuario'],
            'id_area'       => $_POST['area'],
            'id_log'        => $_POST['id_log'],
            'super_empresa' => 1,
            'codigo'        => $codigo,
            'id_categoria'  => $_POST['categoria'],
            'cantidad'      => 1,
            'fecha_ingreso' => $fecha_ingreso,
        );

        $guardar_evidencia = ModeloInventario::guardarInventarioDirectoModel($datos);

        if ($cantidad > 1) {
            for ($i = 0; $i < $cantidad - 1; $i++) {
                $guardar_evidencia = ModeloInventario::guardarInventarioDirectoModel($datos);
            }
        }
        

        if ($guardar_evidencia) {
            echo '
            <script>
            ohSnap("Guardado correctamente!", {color: "green", "duration": "5000"});
            setTimeout(recargarPagina, 1050);
            
            function recargarPagina(){
                window.location.replace("index");
            }
            </script>';
        } else {
            echo '
            <script>
            ohSnap("Ha ocurrido un error!", {color: "red", "duration": "5000"});
            </script>';
        }

    }




    public function mostrarFechasMantenimientosControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarFechasMantenimientosModel();

        return $mostrar;
    }



    public function mostrarFechasMantenimientosTodosControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarFechasMantenimientosTodosModel();

        return $mostrar;
    }



    public function mostrarFechasCopiasSeguridadControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarFechasCopiasSeguridadModel();

        return $mostrar;
    }



    public function mostrarEquipoComputoControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarEquipoComputoModel();

        return $mostrar;
    }



    public function mostrarDatosArticulosControl($super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarDatosArticulosModel($super_empresa);

        return $mostrar;
    }



    public function mostrarDatosArticuloIdControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarDatosArticuloIdModel($id);

        return $mostrar;
    }



    public function historialArticuloControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::historialArticuloModel($id);

        return $mostrar;
    }



    public function buscarReporteLiberadoControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarReporteLiberadoModel($id);

        return $mostrar;
    }



    public function historialReportesControl($super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::historialReportesModel($super_empresa);

        return $mostrar;
    }



    public function buscarHistorialReportesControl($datos)
    {



        $area = (empty($datos['area'])) ? '' : ' AND iv.id_area = ' . $datos['area'];

        $usuario = (empty($datos['usuario'])) ? '' : ' AND iv.id_user = ' . $datos['usuario'];



        $datos = array('usuario' => $usuario, 'area' => $area, 'buscar' => $datos['buscar']);



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarHistorialReportesControl($datos);

        return $mostrar;
    }



    public function articulosComputoAreaControl($datos)
    {

        $area = (!empty($datos['area'])) ? ' AND iv.id_area = ' . $datos['area'] : '';

        $usuario = (!empty($datos['usuario'])) ? ' AND iv.id_user = ' . $datos['usuario'] : '';



        $datos = array('area' => $area, 'usuario' => $usuario, 'buscar' => $datos['buscar']);



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::articulosComputoAreaModel($datos);

        return $mostrar;
    }



    public function mostrarArticulosComputoAreaControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarArticulosComputoAreaModel();

        return $mostrar;
    }



    public function mostrarArticulosUsuarioControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarArticulosUsuarioModel($id);

        return $mostrar;
    }



    public function mostrarArticulosBuscarUsuarioControl($id, $buscar)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarArticulosBuscarUsuarioModel($id, $buscar);

        return $mostrar;
    }



    public function mostrarCantidadesControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarCantidadesModel($id);

        return $mostrar;
    }



    public function mostrarMaterialDidacticoControl($super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarMaterialDidacticoModel($super_empresa);

        return $mostrar;
    }



    public function trabajoCasaControl($super_empresa)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::trabajoCasaModel($super_empresa);

        return $mostrar;
    }



    public function buscarInventarioNoConfirmadoControl($datos)
    {

        $area = (!empty($datos['area'])) ? ' AND iv.id_area = ' . $datos['area'] : '';

        $usuario = (!empty($datos['usuario'])) ? ' AND iv.id_user = ' . $datos['usuario'] : '';



        $datos = array('area' => $area, 'usuario' => $usuario, 'buscar' => $datos['buscar']);



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarInventarioNoConfirmadoModel($datos);

        return $mostrar;
    }



    public function mostrarInventarioNoConfirmadoControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarInventarioNoConfirmadoModel();

        return $mostrar;
    }



    public function mostrarInventarioControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarInventarioModel();

        return $mostrar;
    }



    public function mostrarCantidadesInventarioControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarCantidadesInventarioModel($id);

        return $mostrar;
    }



    public function mostrarFirmaUsuarioControl($id)
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarFirmaUsuarioModel($id);

        return $mostrar;
    }



    public function buscarInventarioControl($datos)
    {



        $area = ($datos['area'] == '') ? '' : ' AND iv.id_area = ' . $datos['area'];

        $usuario = ($datos['usuario'] == '') ? '' : ' AND iv.id_user = ' . $datos['usuario'];



        $datos_buscar = array(

            'area' => $area,

            'usuario' => $usuario,

            'articulo' => $datos['articulo'],

        );



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarInventarioModel($datos_buscar);

        return $mostrar;
    }



    public function cantidadesInventarioControl($datos)
    {



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::cantidadesInventarioModel($datos['articulo']);

        return $mostrar;
    }



    public function mostrarInventarioDetalleControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarInventarioDetalleModel();

        return $mostrar;
    }

    public function obtenerInventarioDesagrupadoControl($desc_inventario,$id_area, $id_user){
        $result = ModeloInventario::obtenerInventarioDesagrupadoModel($desc_inventario,$id_area, $id_user);
        return $result;
    }

    public static function getInventarioAires()
    {
        $result = ModeloInventario::getInventarioAires();

        return $result;
    }



    public function mostrarInventarioDescontinuadoDetalleControl()
    {

        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::mostrarInventarioDescontinuadoDetalleModel();

        return $mostrar;
    }



    public function buscarInventarioDetalleControl($datos)
    {



        $area = ($datos['area'] == '') ? '' : ' AND iv.id_area = ' . $datos['area'];

        $usuario = ($datos['usuario'] == '') ? '' : ' AND iv.id_user = ' . $datos['usuario'];



        $datos_buscar = array(

            'area' => $area,

            'usuario' => $usuario,

            'articulo' => $datos['articulo'],

        );



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarInventarioDetalleModel($datos_buscar);

        return $mostrar;
    }



    public function buscarInventarioDescontinuadoDetalleControl($datos)
    {



        $area = ($datos['area'] == '') ? '' : ' AND iv.id_area = ' . $datos['area'];

        $usuario = ($datos['usuario'] == '') ? '' : ' AND iv.id_user = ' . $datos['usuario'];



        $datos_buscar = array(

            'area' => $area,

            'usuario' => $usuario,

            'articulo' => $datos['articulo'],

        );



        $comando = ModeloInventario::comandoSQL();

        $mostrar = ModeloInventario::buscarInventarioDescontinuadoDetalleModel($datos_buscar);

        return $mostrar;
    }



    public function eliminarTemporalControl($datos)
    {

        $eliminar = ModeloInventario::eliminarTemporalModel($datos);

        return $eliminar;
    }



    public function mostrarDatosAgrupadosArticulosControl($datos)
    {

        $mostrar = ModeloInventario::mostrarDatosAgrupadosArticulosModel($datos);

        return $mostrar;
    }

    public function mostrarDatosReporteImprimir($datos)
    {
   

        $mostrar = ModeloInventario::mostrarDatosReporteImprimirModel($datos);

        return $mostrar;
    }



    public function guardarInventarioControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_temp_log']) &&

            !empty($_POST['id_temp_log']) &&

            isset($_POST['id_temp_super_empresa']) &&

            !empty($_POST['id_temp_super_empresa'])

        ) {



            echo '

            <script>

            window.open("' . BASE_URL . 'imprimir/cartaEntrega?id_log=' . base64_encode($_POST['id_temp_log']) .

                '&super_empresa=' . base64_encode($_POST['id_temp_super_empresa']) . '");

            </script>';



            $temporal = array(

                'id_log' => $_POST['id_temp_log'],

                'id_super_empresa' => $_POST['id_temp_super_empresa'],

            );



            $guardar_articulo = ModeloInventario::guardarInventarioModel($temporal);



            if ($guardar_articulo == true) {

                $guardar_evidencias = ModeloInventario::guardarEvidenciaModel($temporal);

                if ($guardar_evidencias == true) {

                    $actualizar_temporal = ModeloInventario::actualizarTemporalModel($temporal);

                    if ($actualizar_temporal == true) {

                        $eliminar = ModeloInventario::eliminarTemporalModel($temporal);

                        echo '

                        <script>

                        ohSnap("Registrados Correctamente!", {color: "green", "duration": "1000"});

                        setTimeout(recargarPagina,1050);



                        function recargarPagina(){

                            window.location.replace("' . BASE_URL . 'inventario/index");

                        }

                        </script>';
                    }
                }
            }
        }
    }
    

    public function agregarInvProduct()
    {

        $id_producto = $_POST['id_producto'];
        $id_compra = $_POST['id_compra'];
        $cantidad = $_POST['cantidad'];

        $result = ModeloInventario::getInvProduct($id_compra, $id_producto);

        if ($result) {
            $consumo = $result[0]['consumo'] + $cantidad;
            $updte = ModeloInventario::updateInvProduct($id_compra, $id_producto, $consumo);

            if ($updte == true) {
                echo '

                        <script>

                        ohSnap("Registrado Correctamente!", {color: "green", "duration": "1000"});

                        setTimeout(recargarPagina,1050);



                        function recargarPagina(){
                            

                            window.location.replace("' . BASE_URL . 'solicitud/listado");

                        }

                        </script>';

                return $updte;
            }
        } else {
            $insert = ModeloInventario::agregarInvProduct($id_compra, $id_producto, $cantidad);
            if ($insert == true) {
                echo '

                        <script>

                        ohSnap("Registrado Correctamente!", {color: "green", "duration": "1000"});

                        setTimeout(recargarPagina,1050);

                        function recargarPagina(){
                            

                            window.location.replace("' . BASE_URL . 'solicitud/productos?solicitud="++btoa(' . $id_compra . '));

                        }

                        </script>';

                return $insert;
            }
        }
    }

    public function getInvProduct($id_compra, $id_producto)
    {

        $result = ModeloInventario::getInvProduct($id_compra, $id_producto);

        if ($result) {
            return $result[0]['consumo'];
        } else {
            return 0;
        }
    }



    public function guardarInventarioTempControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $cantidad = $_POST['cantidad'];

            mt_srand(6);



            $fecha_compra = ($_POST['fecha'] == '') ? '0000-00-00' : $_POST['fecha'];

            $fecha_ingreso = ($_POST['fecha_ingreso'] == '') ? '0000-00-00' : $_POST['fecha_ingreso'] . date(' H:i:s');



            for ($i = 0; $i < $cantidad; $i++) {



                $codigo = $this->numeroAleatorio();



                $datos = array(

                    'descripcion' => $_POST['descripcion'],

                    'marca' => $_POST['marca'],

                    'modelo' => $_POST['modelo'],

                    'precio' => $_POST['precio'],

                    'estado' => 1,

                    'fecha_compra' => $fecha_compra,

                    'id_user' => $_POST['usuario'],

                    'id_area' => $_POST['area'],

                    'id_log' => $_POST['id_log'],

                    'super_empresa' => $_POST['super_empresa'],

                    'codigo' => $codigo,

                    'id_categoria' => $_POST['categoria'],

                    'fecha_ingreso' => $fecha_ingreso,

                );



                $guardar_temp = ModeloInventario::guardarInventarioTempModel($datos);



                if ($guardar_temp['guardar'] == true) {



                    $id_temp = $guardar_temp['id'];



                    if (!empty($_FILES['archivo']['name'])) {

                        $datos_evidencia_temp = array(

                            'id_temp' => $id_temp,

                            'archivo' => $_FILES['archivo']['name'],

                            'id_log' => $_POST['id_log'],

                            'id_super_empresa' => $_POST['super_empresa'],

                        );



                        $guardo_evidencia_temp = $this->guardarDocumentoControl($datos_evidencia_temp);
                    }
                }
            }



            if ($guardar_temp['guardar'] == true) {



                $datos_return = array(

                    'descripcion' => $_POST['descripcion'],

                    'marca' => $_POST['marca'],

                    'modelo' => $_POST['modelo'],

                    'precio' => $_POST['precio'],

                    'fecha_compra' => $_POST['fecha'],

                    'cantidad' => $_POST['cantidad'],

                );



                $temporal = array(

                    'id_log' => $_POST['id_log'],

                    'id_super_empresa' => $_POST['super_empresa'],

                );



                $guardar_articulo = ModeloInventario::guardarInventarioModel($temporal);



                if ($guardar_articulo == true) {

                    $guardar_evidencias = ModeloInventario::guardarEvidenciaModel($temporal);

                    if ($guardar_evidencias == true) {

                        $actualizar_temporal = ModeloInventario::actualizarTemporalModel($temporal);

                        if ($actualizar_temporal == true) {
                        }
                    }
                }
            } else {

                $datos_return = array();
            }



            return $datos_return;
        }
    }



    public function numeroAleatorio()
    {

        $numero = rand();



        $comando = ModeloInventario::comandoSQL();

        $codigo = ModeloInventario::verificarCodigoModel($numero);



        if ($codigo['codigo'] != '') {

            $this->numeroAleatorio();
        } else {

            $num_codigo = $numero;
        }



        return $num_codigo;
    }



    public function guardarDocumentoControl($datos)
    {

        //obtener el nombre del archivo

        $nom_arch = $datos['archivo'];

        //extraer la extencion del archivo de el archivo

        $ext_arch = pathinfo($nom_arch, PATHINFO_EXTENSION);

        $fecha_arch = date('YmdHis');



        $nombre_archivo = strtolower(md5($datos['id_temp'] . '_' . $fecha_arch)) . '.' . $ext_arch;



        $datos_temp = array(

            'nombre' => $nombre_archivo,

            'id_inventario_temp' => $datos['id_temp'],

            'id_log' => $datos['id_log'],

            'id_super_empresa' => $datos['id_super_empresa'],

        );



        $guardar_evidencia = ModeloInventario::guardarEvidenciaTempModel($datos_temp);



        if ($guardar_evidencia == true) {

            //ruta donde de alojamiento el archivo

            $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;

            $ruta_img = $carp_destino . $nombre_archivo;



            //verificar si subio el archivo y se mueve a su destino

            if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {

                move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
            }



            return true;
        }
    }



    public function liberarArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log_lib']) &&

            !empty($_POST['id_log_lib']) &&

            isset($_POST['super_empresa_lib']) &&

            !empty($_POST['super_empresa_lib']) &&

            isset($_POST['id_inventario_lib']) &&

            !empty($_POST['id_inventario_lib'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario_lib'],

                'id_log' => $_POST['id_log_lib'],

                'id_super_empresa' => $_POST['super_empresa_lib'],

                'id_user' => $_POST['id_user_lib'],

                'id_area' => $_POST['id_area_lib'],

            );

            $liberar = ModeloInventario::liberarArticuloModel($datos);



            if ($liberar == true) {

                echo '

                <script>

                ohSnap("Liberado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'listado/index");

                }

                </script>';



                $comando = ModeloInventario::comandoSQL();

                $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id_inventario_lib']);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log_lib']);



                $fecha = $datos_articulo['fecha_reporte'];

                $nuevafecha = strtotime('-1 hour', strtotime($fecha));

                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);



                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha liberado el siguiente articulo:

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                <li><b>Modelo:</b> ' . $datos_articulo['marca'] . '</li>

                <li><b>Estado del articulo:</b> Liberado</li>

                <li><b>Fecha de reporte:</b> ' . $nuevafecha . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Codigo:</b> ' . $datos_articulo['codigo'] . ' </li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Liberacion de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function descontinuarArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['nom_inventario_desc']) &&

            !empty($_POST['nom_inventario_desc'])

        ) {



            $url = ($_POST['inicio'] == 1) ? 'listado/index' : 'reportes/index';



            $datos = array(

                'nom_inventario_rep' => $_POST['nom_inventario_desc'],

                'id_log' => $_POST['id_log_desc'],

                'id_user' => $_POST['id_user_desc'],

                'id_area' => $_POST['id_area_desc'],

                'id_inventario' => $_POST['id_inventario_desc'],

                'observacion' => $_POST['observacion'],

                'estado' => 5,

                'fechareg' => $_POST['fecha']

            );

            $descontinuar = ModeloInventario::descontinuarArticuloModel($datos);

            //$datos_articulo_reportado = ModeloInventario::articulosReportadosModel($datos);


            /*
            foreach ($datos_articulo_reportado as $reportado) {



                $datos_reporte_articulo = ModeloReportes::informacionReporteArticuloModel($reportado['id']);



                if ($datos_reporte_articulo['id'] == '') {



                    $datos_desc = array(

                        'id_inventario' => $reportado['id'],

                        'resp' => $_POST['resp'],

                        'observacion' => $_POST['observacion'],

                        'id_user' => $reportado['id_user'],

                        'id_area' => $reportado['id_area'],

                        'estado' => 5,

                        'fechareg' => $_POST['fecha'],

                    );
                } else {



                    $datos_desc = array(

                        'id_inventario' => $datos_reporte_articulo['id_inventario'],

                        'resp' => $_POST['resp'],

                        'observacion' => $_POST['observacion'],

                        'id_user' => $datos_reporte_articulo['id_user'],

                        'id_area' => $datos_reporte_articulo['id_area'],

                        'estado' => 5,

                        'fechareg' => $_POST['fecha'],

                    );
                }



                $descontinuar = ModeloInventario::descontinuarArticuloModel($datos_desc);
            }
            */


            if ($descontinuar == true) {



                $datos_buscar = array(

                    'nom_inventario_rep' => $_POST['nom_inventario_desc'],

                    'id_area' => $_POST['id_area_desc'],

                    'id_user' => $_POST['id_user_desc'],

                    'estado' => 5,

                );



                $comando = ModeloInventario::comandoSQL();

                $datos_articulo = ModeloInventario::mostrarDatosAgrupadosArticulosModel($datos_buscar);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['resp']);



                $fecha = $datos_articulo['fecha_reporte'];



                $mensaje = '

                <div>

                <p style="font-size: 1.6em;">

                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha descontinuado el siguiente articulo:

                </p>

                <p>

                <ul style="font-size: 1.4em;">

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                <li><b>Cantidad:</b> ' . $datos_articulo['cantidad'] . ' </li>

                <li><b>Estado del articulo:</b> Descontinuado</li>

                <li><b>Fecha de reporte:</b> ' . $fecha . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Descarte de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);



                echo '

                <script>

                ohSnap("Descontinuado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . $url . '");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function copiaSeguridadArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_copia']) &&

            !empty($_POST['id_inventario_copia'])

        ) {



            $fecha = ($_POST['fecha'] == '') ? date('Y-m-d H:i:s') : $_POST['fecha'];



            $datos = array(

                'id_inventario' => $_POST['id_inventario_copia'],

                'observacion' => $_POST['observacion'],

                'id_log' => $_POST['id_log_copia'],

                'id_user' => $_POST['id_user_copia'],

                'fecha' => $fecha . date(' H:i:s'),

                'id_area' => $_POST['id_area_copia'],

            );



            $guardar = ModeloInventario::copiaSeguridadArticuloModel($datos);



            if ($guardar == true) {

                echo '

                <script>

                ohSnap("Copia creada Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function mantenimientoArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_mant']) &&

            !empty($_POST['id_inventario_mant']) &&

            isset($_POST['fecha']) &&

            !empty($_POST['fecha'])

        ) {



            $fecha = ($_POST['fecha'] == '') ? date('Y-m-d H:i:s') : $_POST['fecha'];



            $datos = array(

                'id_inventario' => $_POST['id_inventario_mant'],

                'observacion' => $_POST['observacion'],

                'id_log' => $_POST['id_log_mant'],

                'id_user' => $_POST['id_user_mant'],

                'fechareg' => $fecha . date(' H:i:s'),

                'id_super_empresa' => $_POST['id_super_empresa'],

                'estado' => 6,

                'tipo_reporte' => 2,

                'id_area' => $_POST['id_area_mant'],

            );



            $mantenimiento = ModeloInventario::mantenimientoArticuloModel($datos);



            if ($mantenimiento == true) {

                echo '

                <script>

                ohSnap("Reportado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>';



                $comando = ModeloInventario::comandoSQL();

                $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id_inventario_mant']);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log_mant']);



                $fecha = $datos_articulo['fecha_reporte'];

                $nuevafecha = strtotime('-1 hour', strtotime($fecha));

                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);



                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado un mantenimiento del siguiente articulo:

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                <li><b>Modelo:</b> ' . $datos_articulo['marca'] . '</li>

                <li><b>Estado del articulo:</b> Mantemnimiento</li>

                <li><b>Fecha de reporte:</b> ' . $nuevafecha . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Codigo:</b> ' . $datos_articulo['codigo'] . ' </li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Mantenimiento de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function reportarArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['nom_inventario_rep']) &&

            !empty($_POST['nom_inventario_rep'])

        ) {



            $fecha = (isset($_POST['fecha_reporte']) && !empty($_POST['fecha_reporte'])) ? $_POST['fecha_reporte'] . date(' H:i:s') : date('Y-m-d H:i:s');



            $datos = array(

                'nom_inventario_rep' => $_POST['nom_inventario_rep'],

                'observacion' => $_POST['observacion'],

                'id_user' => $_POST['id_user_rep'],

                'id_area' => $_POST['id_area_rep'],

                'id_inventario' => $_POST['id_inventario_rep'],

                'estado' => 2,

                'cantidad' => 1,

            );




            $reporte = ModeloInventario::reportarArticuloModel($datos);


            if ($reporte == true) {



                $articulos_reportados = ModeloInventario::articulosReportadosModel($datos);



                foreach ($articulos_reportados as $reportados) {



                    $datos_reporte = array(

                        'id_inventario' => $reportados['id'],

                        'observacion' => $reportados['observacion'],

                        'estado' => $reportados['estado'],

                        'id_area' => $reportados['id_area'],

                        'id_user' => $reportados['id_user'],

                        'id_log' => $_POST['id_log_rep'],

                        'tipo_reporte' => 1,

                        'fecha' => $fecha,

                    );


            

                    $guardar_reporte = ModeloInventario::insertarReporteModel($datos_reporte);


                }



                if ($guardar_reporte == true) {



                    echo '

                    <script>

                    ohSnap("Reportado Correctamente!", {color: "green", "duration": "1000"});

                    setTimeout(recargarPagina,1050);



                    function recargarPagina(){

                        window.location.replace("' . BASE_URL . 'inventario/panelControl");

                    }

                    </script>';



                    $comando = ModeloInventario::comandoSQL();

                    $datos_articulo = ModeloInventario::mostrarDatosAgrupadosArticulosModel($datos);

                    $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log_rep']);



                    $fecha = $datos_articulo['fecha_reporte'];



                    $mensaje = '

                    <div>

                    <p style="font-size: 1.6em;">

                    El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado un reporte del siguiente articulo:

                    </p>

                    <p>

                    <ul style="font-size: 1.4em;">

                    <li><b>Descripcion:</b> ' . $datos_articulo['descripcion'] . '</li>

                    <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                    <li><b>Cantidad:</b> ' . $datos_articulo['cantidad'] . ' </li>

                    <li><b>Estado del articulo:</b> Dañado</li>

                    <li><b>Fecha de reporte:</b> ' . $fecha . '</li>

                    <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                    <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                    <li><b>Observacion:</b> ' . $datos_articulo['observacion'] . '</li>

                    </ul>

                    </p>

                    </div>

                    ';



                    $datos_correo = array(

                        'asunto' => 'Reporte de articulo',

                        'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                        'user' => 'Administrador',

                        'mensaje' => $mensaje,

                        'archivo' => array(''),

                    );



                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                } else {
                    echo '

                    <script>
    
                    ohSnap("Ha ocurrido un error al guardar el reporte", {color: "red", "duration": "1000"});
    
                    </script>
    
                    ';
                }
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }

    public function reportarArticuloIdControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['nom_inventario_rep']) &&

            !empty($_POST['nom_inventario_rep'])

        ) {

            $fecha = (isset($_POST['fecha_reporte']) && !empty($_POST['fecha_reporte'])) ? $_POST['fecha_reporte'] . date(' H:i:s') : date('Y-m-d H:i:s');

            $datos = array(

                'nom_inventario_rep' => $_POST['nom_inventario_rep'],

                'observacion' => $_POST['observacion'],

                'id_user' => $_POST['id_user_rep'],

                'id_area' => $_POST['id_area_rep'],

                'id_inventario' => $_POST['id_inventario_rep'],

                'estado' => 2,

                'cantidad' => 1,

            );
            $reporte = ModeloInventario::reportarArticuloIdModel($datos);

            if ($reporte == true) {

                $articulos_reportados = ModeloInventario::articulosReportadosModel($datos);


                foreach ($articulos_reportados as $reportados) {

                    $datos_reporte = array(

                        'id_inventario' => $reportados['id'],

                        'observacion' => $reportados['observacion'],

                        'estado' => $reportados['estado'],

                        'id_area' => $reportados['id_area'],

                        'id_user' => $reportados['id_user'],

                        'id_log' => $_POST['id_log_rep'],

                        'tipo_reporte' => 1,

                        'fecha' => $fecha,

                        'cantidad' => 1

                    );

                    $guardar_reporte = ModeloInventario::insertarReporteModel($datos_reporte);
                    

                }
                
                if ($guardar_reporte == true) {

                    echo '

                    <script>

                    ohSnap("Reportado Correctamente!", {color: "green", "duration": "1000"});

                    setTimeout(recargarPagina,1050);



                    function recargarPagina(){

                        window.location.replace("' . BASE_URL . 'listado/index");

                    }

                    </script>';



                    $comando = ModeloInventario::comandoSQL();

                    $datos_articulo = ModeloInventario::mostrarDatosAgrupadosArticulosModel($datos);

                    $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log_rep']);



                    $fecha = $datos_articulo['fecha_reporte'];



                    $mensaje = '

                    <div>

                    <p style="font-size: 1.6em;">

                    El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha realizado un reporte del siguiente articulo:

                    </p>

                    <p>

                    <ul style="font-size: 1.4em;">

                    <li><b>Descripcion:</b> ' . $datos_articulo['descripcion'] . '</li>

                    <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                    <li><b>Cantidad:</b> ' . $datos_articulo['cantidad'] . ' </li>

                    <li><b>Estado del articulo:</b> Dañado</li>

                    <li><b>Fecha de reporte:</b> ' . $fecha . '</li>

                    <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                    <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                    <li><b>Observacion:</b> ' . $datos_articulo['observacion'] . '</li>

                    </ul>

                    </p>

                    </div>

                    ';

                    $datos_correo = array(

                        'asunto' => 'Reporte de articulo',

                        'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                        'user' => 'Administrador',

                        'mensaje' => $mensaje,

                        'archivo' => array(''),

                    );

                    $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                } else {
                    echo '

                    <script>
    
                    ohSnap("Ha ocurrido un error al guardar el reporte", {color: "red", "duration": "1000"});
    
                    </script>
    
                    ';
                }
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function reportarArticuloListadoControl($id_inv, $desc_report, $id_user_modal, $nom_art, $id_log) {
        try {
            // Cambiar estado en Inventario
            $estado_inventario = ModeloInventario::actualizarEstadoInventarioModel($id_inv, $desc_report);
        
            if ($estado_inventario) {

                //echo $id_inv." - ";

                // Obtener datos del inventario
                $datos_inv = ModeloInventario::obtenerDatosInventarioModel($id_inv);
        
                if ($datos_inv) {
                    $id_area = $datos_inv['id_area'];
                    $id_user = $datos_inv['id_user'];
        
                    // Ingresar registro de reporte
                    $ingresar_reporte = ModeloInventario::ingresarReporteModel(
                        $id_inv, 
                        $desc_report, 
                        $id_area, 
                        $id_user,
                        $id_log
                    );
        
                    if ($ingresar_reporte) {
                        // Configurar y enviar correo
                        $fecha = date('Y-m-d H:i:s');
                        
                        $mensaje = '
                        <div>
                        <p style="font-size: 1.6em;">
                        El usuario <b>' . htmlspecialchars($id_user_modal) . '</b> ha realizado un reporte del siguiente articulo:
                        </p>
                        <p>
                        <ul style="font-size: 1.4em;">
                        <li><b>Descripción:</b> ' . htmlspecialchars($nom_art) . '</li>
                        <li><b>Cantidad:</b> 1</li>
                        <li><b>Estado del artículo:</b> Dañado</li>
                        <li><b>Fecha de reporte:</b> ' . htmlspecialchars($fecha) . '</li>
                        <li><b>Área/Oficina:</b> ' . htmlspecialchars($datos_inv['nombre']) . '</li>
                        <li><b>Responsable:</b> ' . htmlspecialchars($datos_inv['responsable']) . '</li>
                        <li><b>Observación:</b> ' . htmlspecialchars($desc_report) . '</li>
                        </ul>
                        </p>
                        </div>
                        ';
        
                        // Configurar datos para el correo
                        $datos_correo = [
                            'asunto' => 'Reporte de Articulo - ' . htmlspecialchars($nom_art),
                            'mensaje' => $mensaje,
                            'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),
                            'archivo' => ['']
                        ];
        
                        // Enviar correo
                        $enviar_correo = Correo::enviarCorreoModel($datos_correo);
                        
                        echo '
                        <script>
                        ohSnap("Reporte ingresado correctamente!", {color: "green", "duration": "3000"});
                        
                        setTimeout(function() {
                            window.location.replace("'.BASE_URL.'inventario/listado");
                        }, 1000);
                        </script>
                        ';
                    } else {
                        throw new Exception("Error al ingresar el reporte en la base de datos");
                    }
                } else {
                    throw new Exception("No se pudieron obtener los datos del inventario");
                }
            } else {
                throw new Exception("Error al actualizar el estado del inventario");
            }
        } catch (Exception $e) {
            error_log("Error en reportarArticuloListadoControl: " . $e->getMessage());
            echo '
            <script>
            ohSnap("' . addslashes($e->getMessage()) . '", {color: "red", "duration": "3000"});
            </script>
            ';
        }
    }



    public function reasignarArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $datos = array(

                'id_inventario' => $_POST['id_inventario'],

                'id_log' => $_POST['id_log'],

                'id_super_empresa' => $_POST['id_super_empresa'],

                'id_user' => $_POST['id_user'],

                'id_area' => $_POST['id_area'],

                'id_reporte' => $_POST['id_reporte'],

                'fecha_respuesta' => date('Y-m-d H:i:s'),

            );



            $reporte = ModeloInventario::reasignarArticuloModel($datos);



            if ($reporte == true) {

                echo '

                <script>

                ohSnap("Re-asignado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'inventario/reasignar");

                }

                </script>';

                $comando = ModeloInventario::comandoSQL();

                $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id_inventario']);

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);



                $fecha = $datos_articulo['fecha_reporte'];

                $nuevafecha = strtotime('-1 hour', strtotime($fecha));

                $nuevafecha = date('Y-m-d H:i:s', $nuevafecha);



                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_usuario['nombre'] . ' ' . $datos_usuario['apellido'] . '</b> ha reasignado el siguiente articulo:

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Marca:</b> ' . $datos_articulo['marca'] . ' </li>

                <li><b>Modelo:</b> ' . $datos_articulo['marca'] . '</li>

                <li><b>Estado del articulo:</b> Re - Asignado</li>

                <li><b>Fecha de asignacion:</b> ' . date('Y-m-d H:i:s') . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Codigo:</b> ' . $datos_articulo['id'] . ' </li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Reasignacion de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function programarMantenimientoArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario']) &&

            !empty($_POST['id_inventario']) &&

            isset($_POST['frec_mant']) &&

            !empty($_POST['frec_mant'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario'],

                'frec_mant' => $_POST['frec_mant'],

                'frec_copia' => $_POST['frec_copia'],

            );



            $actualizar = ModeloInventario::programarMantenimientoArticuloModel($datos);



            if ($actualizar == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'mantenimientos/index?pagina=1");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function programarMantenimientoAreaControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log_area']) &&

            !empty($_POST['id_log_area']) &&

            isset($_POST['frec_mant']) &&

            !empty($_POST['frec_mant'])

        ) {



            $array_inventario = array();

            $array_inventario = $_POST['id_inventario_area'];



            foreach ($array_inventario as $a) {



                $id_inventario = $a;



                $datos = array(

                    'id_inventario' => $id_inventario,

                    'frec_mant' => $_POST['frec_mant'],

                    'frec_copia' => $_POST['frec_copia'],

                );



                $actualizar = ModeloInventario::programarMantenimientoArticuloModel($datos);
            }



            if ($actualizar == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'mantenimientos/areas");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function trabajoCasaListadoArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_trab_home']) &&

            !empty($_POST['id_inventario_trab_home'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario_trab_home'],

                'observacion' => 'Trabajo en casa',

                'id_log' => $_POST['id_log_trab_home'],

                'id_user' => $_POST['id_user_trab_home'],

                'id_area' => $_POST['id_area_trab_home'],

                'estado' => 8,

                'tipo_reporte' => 3,

                'super_empresa' => $_POST['super_empresa_trab_home'],

            );



            $reporte = ModeloInventario::trabajoCasaArticuloModel($datos);



            if ($reporte == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'inventario/listado");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function trabajoCasaArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_trab_home']) &&

            !empty($_POST['id_inventario_trab_home'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario_trab_home'],

                'observacion' => 'Trabajo en casa',

                'id_log' => $_POST['id_log_trab_home'],

                'id_user' => $_POST['id_user_trab_home'],

                'id_area' => $_POST['id_area_trab_home'],

                'estado' => 8,

                'tipo_reporte' => 3,

                'super_empresa' => $_POST['super_empresa_trab_home'],

            );

            $comando = ModeloInventario::comandoSQL();

            $reporte = ModeloInventario::trabajoCasaArticuloModel($datos);



            if ($reporte == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'inventario/panelControl");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function trabajoCasaMaterialControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_trab_home']) &&

            !empty($_POST['id_inventario_trab_home'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario_trab_home'],

                'observacion' => 'Trabajo en casa',

                'id_log' => $_POST['id_log_trab_home'],

                'id_user' => $_POST['id_user_trab_home'],

                'id_area' => $_POST['id_area_trab_home'],

                'estado' => 8,

                'tipo_reporte' => 3,

                'super_empresa' => $_POST['super_empresa_trab_home'],

            );

            $comando = ModeloInventario::comandoSQL();

            $reporte = ModeloInventario::trabajoCasaArticuloModel($datos);



            if ($reporte == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'material/index");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function removerTrabajoCasaArticuloControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_rem_home']) &&

            !empty($_POST['id_inventario_rem_home'])

        ) {

            $datos = array(

                'id_inventario' => $_POST['id_inventario_rem_home'],

                'observacion' => 'Removido de trabajo en casa',

                'id_log' => $_POST['id_log_rem_home'],

                'id_user' => $_POST['id_user_rem_home'],

                'id_area' => $_POST['id_area_rem_home'],

                'estado' => 9,

                'tipo_reporte' => 3,

                'super_empresa' => $_POST['super_empresa_rem_home'],

            );



            $reporte = ModeloInventario::removerTrabajoCasaArticuloModel($datos);



            if ($reporte == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("' . BASE_URL . 'inventario/panelControl");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function editarInventarioControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_inventario_edit']) &&

            !empty($_POST['id_inventario_edit']) &&

            isset($_POST['descripcion_edit']) &&

            !empty($_POST['descripcion_edit']) &&

            isset($_POST['area_edit']) &&

            !empty($_POST['area_edit'])

        ) {



            $codigo = ($_POST['codigo'] == '') ? $this->numeroAleatorio() : $_POST['codigo'];



            $datos = array(

                'id_inventario' => $_POST['id_inventario_edit'],

                'descripcion' => $_POST['descripcion_edit'],

                'marca' => $_POST['marca_edit'],

                'modelo' => $_POST['modelo_edit'],

                'precio' => $_POST['precio_edit'],

                'id_user' => $_POST['user_edit'],

                'id_area' => $_POST['area_edit'],

                'id_categoria' => $_POST['id_categoria'],

                'codigo' => $codigo,

            );



            $guardar = ModeloInventario::editarInventarioModel($datos);



            if ($guardar == true) {

                echo '

                <script>

                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                </script>

                ';
            }
        }
    }



    public function agregarMaterialControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_super_empresa']) &&

            !empty($_POST['id_super_empresa']) &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log']) &&

            isset($_POST['descripcion']) &&

            !empty($_POST['descripcion']) &&

            isset($_POST['usuario']) &&

            !empty($_POST['usuario'])

        ) {



            $comando = ModeloInventario::comandoSQL();

            $area = ModeloInventario::buscarAreaUsuarioControl($_POST['usuario']);

            $id_area = $area['id_area'];

            mt_srand(6);



            for ($i = 0; $i < $_POST['cantidad']; $i++) {



                $codigo = $this->numeroAleatorio();



                $datos = array(

                    'id_super_empresa' => $_POST['id_super_empresa'],

                    'descripcion' => $_POST['descripcion'],

                    'id_user' => $_POST['usuario'],

                    'id_area' => $id_area,

                    'id_categoria' => 6,

                    'estado' => $_POST['estado'],

                    'codigo' => $codigo,

                    'id_log' => $_POST['id_log'],

                    'observacion' => 'Trabajo en casa',

                );



                $guardar = ModeloInventario::agregarMaterialTempModel($datos);
            }



            if ($guardar == true) {



                $temporal = array(

                    'id_log' => $_POST['id_log'],

                    'id_super_empresa' => $_POST['id_super_empresa'],

                );



                $guardar_articulo = ModeloInventario::guardarMaterialControl($temporal);



                if ($guardar_articulo == true) {

                    echo '

                    <script>

                    ohSnap("Registrado Correctamente!", {color: "green", "duration": "1000"});

                    setTimeout(recargarPagina,1050);



                    function recargarPagina(){

                        window.location.replace("' . BASE_URL . 'material/index");

                    }

                    </script>';
                } else {

                    echo '

                    <script>

                    ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});

                    </script>

                    ';
                }
            }
        }
    }



    public function confirmarInventarioControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])
        ) {



            $comando = ModeloInventario::comandoSQL();

            $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id']);



            if ($_POST['session'] == 1) {

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['user']);

                $datos = array(

                    'id_log' => $_POST['user'],

                    'descripcion' => $_POST['descripcion'],

                    'id_area' => $_POST['id_area'],

                );



                $guardar = ModeloInventario::confirmarAgregarInventarioModel($datos);
            } else {

                $datos_usuario = ModeloPerfil::mostrarDatosPerfilModel($_POST['id_log']);

                $datos = array(

                    'id_log' => $_POST['id_log'],

                    'descripcion' => $_POST['descripcion'],

                    'id_area' => $_POST['id_area'],

                );



                $guardar = ModeloInventario::confirmarInventarioModel($datos);
            }



            if ($guardar == true && $_POST['session'] == 1) {

                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                Se han confirmado los siguientes articulos de su inventario:

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                <li><b>Inconformidad resuelta:</b> ' . $datos_articulo['observacion'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Confirmacion de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



               $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            }

            return $guardar;
        }
    }



    public function noConfirmarInventarioControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])
        ) {



            $comando = ModeloInventario::comandoSQL();

            $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id']);



            $datos = array(

                'id_log' => $_POST['id_log'],

                'descripcion' => $_POST['descripcion'],

                'id_area' => $_POST['id_area'],

                'observacion' => $_POST['observacion'],

            );



            $guardar = ModeloInventario::noConfirmarInventarioModel($datos);



            if ($guardar == true) {



                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_articulo['usuario'] . '</b> ha reportado un <b>NO CONFIRMADO</b> para el siguiente articulo.

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                <li><b>Inconformidad pendiente:</b> ' . $_POST['observacion'] . ' </li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'No confirmacion de articulo',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            }

            return $guardar;
        }
    }



    public function cantidadesGeneralSolucionadasControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['tipo']) &&

            !empty($_POST['tipo'])
        ) {



            $datos = array(

                'fecha_inicio' => $_POST['fecha_inicio'],

                'fecha_fin' => $_POST['fecha_fin'],

                'tipo' => $_POST['tipo'],

            );



            $comando = ModeloInventario::comandoSQL();

            $mostrar = ModeloInventario::cantidadesGeneralSolucionadasModel($datos);

            $mostrar_pend = ModeloInventario::cantidadesGeneralPendientesModel($datos);



            $resultado = array('solucionados' => $mostrar['solucion'], 'pendientes' => $mostrar_pend['cantidad']);

            return $resultado;
        }
    }



    public function cantidadesSolucionadasControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['tipo']) &&

            !empty($_POST['tipo'])
        ) {



            $datos = array(

                'fecha_inicio' => $_POST['fecha_inicio'],

                'fecha_fin' => $_POST['fecha_fin'],

                'tipo' => $_POST['tipo'],

            );



            $comando = ModeloInventario::comandoSQL();

            $mostrar = ModeloInventario::cantidadesSolucionadasModel($datos);

            $mostrar_pend = ModeloInventario::cantidadesPendienteModel($datos);



            $resultado = array('solucionados' => $mostrar['solucion'], 'pendientes' => $mostrar_pend['cantidad']);

            return $resultado;
        }
    }



    public function agregarInventarioPendienteControl()
    {

        if (

            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id']) &&

            !empty($_POST['id'])
        ) {



            $codigo = $this->numeroAleatorio();

            $comando = ModeloInventario::comandoSQL();

            $datos_articulo = ModeloInventario::mostrarDatosArticuloIdModel($_POST['id']);



            $guardar = ModeloInventario::agregarInventarioPendienteModel($_POST['id'], $codigo);



            if ($guardar == true) {



                $mensaje = '

                <div>

                <p style="font-size: 1.2em;">

                El usuario <b>' . $datos_articulo['usuario'] . '</b> ha agregado un articulo <b>PENDIENTE DE REVISION</b> para el siguiente articulo.

                </p>

                <p style="font-size: 1.2em;">

                <ul>

                <li><b>Descripci&oacute;n:</b> ' . $datos_articulo['descripcion'] . '</li>

                <li><b>Area/Oficina:</b> ' . $datos_articulo['area'] . '</li>

                <li><b>Responsable:</b> ' . $datos_articulo['usuario'] . ' </li>

                <li><b>Inconformidad pendiente:</b> Se ha agregado un articulo nuevo, favor de verificar.</li>

                </ul>

                </p>

                </div>

                ';



                $datos_correo = array(

                    'asunto' => 'Articulo pendiente de confirmar',

                    'correo' => array('ghumana@colegiohebreounion.edu.co', 'japaricio@colegiohebreounion.edu.co', 'jrodriguez@colegiohebreounion.edu.co', 'jcervantes@colegiohebreounion.edu.co', 'angel_vargas_contreras@hotmail.com'),

                    'user' => 'Administrador',

                    'mensaje' => $mensaje,

                    'archivo' => array(''),

                );



                $enviar_correo = Correo::enviarCorreoModel($datos_correo);
            }



            return $guardar;
        }
    }



    public static function getHistorialMantenimiento()
    {

        $result = ModeloInventario::getHistorialMantenimiento();
        return $result;
    }

    public static function getHistorialMantenimientoAires()
    {

        $result = ModeloInventario::getHistorialMantenimientoAires();
        return $result;
    }




public function programarMantenimientosControl()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    // Validar campos mínimos
    if (!isset($_POST['id_log']) || empty($_POST['id_log']) ||
        !isset($_POST['fecha_desde']) || !isset($_POST['fecha_hasta'])) {
        echo '<script>ohSnap("Faltan campos obligatorios.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    // Sanitizar entradas
    $id_log = intval($_POST['id_log']);
    $id_categoria = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    // Parsear fechas
    $fecha_desde_ts = strtotime($_POST['fecha_desde']);
    $fecha_hasta_ts = strtotime($_POST['fecha_hasta']);

    if ($fecha_desde_ts === false || $fecha_hasta_ts === false) {
        echo '<script>ohSnap("Formato de fecha inválido.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    if ($fecha_desde_ts > $fecha_hasta_ts) {
        echo '<script>ohSnap("La fecha desde no puede ser mayor a la fecha hasta.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    // Generar array de fechas hábiles entre los rangos (lunes a viernes)
    $fechas_array = [];
    for ($i = $fecha_desde_ts; $i <= $fecha_hasta_ts; $i += 86400) {
        $dia_semana = date('w', $i); // 0 = domingo, 6 = sábado
        if ($dia_semana != 0 && $dia_semana != 6) {
            $fechas_array[] = date('Y-m-d', $i);
        }
    }

    $conteo = count($fechas_array);
    if ($conteo === 0) {
        echo '<script>ohSnap("No hay días hábiles en el rango seleccionado.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    // Mapear categoría
    switch ($id_categoria) {
        case 1: $categoria = 'computador'; break;
        case 15: $categoria = 'portatil'; break;
        case 5: $categoria = 'video'; break;
        case 3: $categoria = 'impresora'; break;
        default: $categoria = null; break;
    }

    if ($categoria === null) {
        echo '<script>ohSnap("Categoría inválida.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    // Obtener equipos
    $equipos_computo = ModeloInventario::mostrarEquipoTodosComputoModel($_POST['fecha_hasta'], $id_categoria);

    if (empty($equipos_computo) || !is_array($equipos_computo)) {
        echo '<script>ohSnap("No se encontraron equipos para programar mantenimientos.", {color: "red", "duration": "2000"});</script>';
        return;
    }

    // Inicializar contadores
    $registrados = 0;
    $errores = 0;

    foreach ($equipos_computo as $equipo) {
        $id_inventario = isset($equipo['id']) ? intval($equipo['id']) : null;
        $id_user = isset($equipo['id_user']) ? intval($equipo['id_user']) : null;
        $id_area = isset($equipo['id_area']) ? intval($equipo['id_area']) : null;

        if (!$id_inventario) {
            $errores++;
            continue;
        }

        // Elegir fecha aleatoria de las disponibles
        $fecha_aleatorio_index = mt_rand(0, $conteo - 1);
        $fecha_final = $fechas_array[$fecha_aleatorio_index];

        // Generar hora aleatoria entre 08:00 y 15:59 para el mantenimiento
        $hora_random = mt_rand(8, 15);
        $minuto_random = str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT);
        $hora_final = str_pad($hora_random, 2, '0', STR_PAD_LEFT) . ':' . $minuto_random . ':00';

        $datos = array(
            'id_log' => $id_log,
            'id_inventario' => $id_inventario,
            'id_user' => $id_user,
            'id_area' => $id_area,
            'id_categoria' => $id_categoria,
            'estado' => 6,
            'tipo_reporte' => 2,
            'fechareg' => $fecha_final . ' ' . $hora_final,
            'descripcion' => $descripcion
        );

        $mantenimientos = ModeloInventario::registrarMantenimientosModel($datos);

        if (is_array($mantenimientos) && !empty($mantenimientos['guardar']) && $mantenimientos['guardar'] == true && isset($mantenimientos['id'])) {
            $registrados++;

            // Calcular la próxima fecha hábil (sumar 2 días y ajustar para evitar sábados/domingos)
            $fecha_proxima_ts = strtotime($fecha_final . ' + 2 days');
            while (date('w', $fecha_proxima_ts) == 0 || date('w', $fecha_proxima_ts) == 6) {
                $fecha_proxima_ts = strtotime('+1 day', $fecha_proxima_ts);
            }
            $fecha_proxima = date('Y-m-d', $fecha_proxima_ts);

            // Generar hora aleatoria para la solución (también entre 08:00 y 15:59)
            $hora_random_sol = mt_rand(8, 15);
            $minuto_random_sol = str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT);
            $hora_final_sol = str_pad($hora_random_sol, 2, '0', STR_PAD_LEFT) . ':' . $minuto_random_sol . ':00';

            $datos_solucion = array(
                'id_log' => $id_log,
                'id_inventario' => $id_inventario,
                'id_user' => $id_user,
                'id_area' => $id_area,
                'id_resp' => 90,
                'id_reporte' => $mantenimientos['id'],
                'estado' => 3,
                'tipo_reporte' => 2,
                'fecha_respuesta' => $fecha_proxima . ' ' . $hora_final_sol,
                'fechareg' => $fecha_proxima . ' ' . $hora_final_sol,
            );

            $solucion = ModeloInventario::registrarSolucionMantenimientoControl($datos_solucion);

            if ($solucion !== true) {
                // Si falla registrar la solución, lo contamos como error pero continuamos
                $errores++;
            }
        } else {
            $errores++;
        }
    }

    // Mensaje final
    if ($registrados > 0 && $errores === 0) {
        echo '
        <script>
            ohSnap("Mantenimientos Registrados Correctamente! ('.$registrados.')" , {color: "green", "duration": "2000"});
            setTimeout(function(){ window.location.replace("index"); }, 1050);
        </script>';
    } elseif ($registrados > 0) {
        echo '
        <script>
            ohSnap("Se registraron '.$registrados.' mantenimientos, pero hubo '.$errores.' errores.", {color: "orange", "duration": "3000"});
            setTimeout(function(){ window.location.replace("index"); }, 1500);
        </script>';
    } else {
        echo '
        <script>
            ohSnap("Error al registrar mantenimientos.", {color: "red", "duration": "2000"});
        </script>';
    }
}



    public function programarMantenimientosAires()
    {

        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&

            isset($_POST['id_log']) &&

            !empty($_POST['id_log'])

        ) {



            $fecha_desde = strtotime($_POST['fecha_desde']);

            $fecha_hasta = strtotime($_POST['fecha_hasta']);





            $fechas_array = [];



            for ($i = $fecha_desde; $i <= $fecha_hasta; $i += 86400) {



                $dias_no = date('w', $i);



                if ($dias_no != 0 && $dias_no != 6) {

                    $fechas_array[] = date('Y-m-d', $i);
                }
            }

            $conteo = count($fechas_array);

            $inventarios = $_POST['id_aires'];

            foreach ($inventarios as $idInventario) {



                $aires = ModeloInventario::getAiresMantenimientos($_POST['fecha_hasta'], $idInventario);


                foreach ($aires as $equipo) {


                    $id_inventario = $equipo['id'];

                    $id_user = $equipo['id_user'];

                    $id_area = $equipo['id_area'];



                    $fecha_aletorio = mt_rand(0, $conteo - 1);

                    $fecha_final = $fechas_array[$fecha_aletorio];



                    $datos = array(

                        'id_log' => $_POST['id_log'],

                        'id_inventario' => $id_inventario,

                        'id_user' => $id_user,

                        'id_area' => $id_area,

                        'estado' => 6,

                        'tipo_reporte' => 2,

                        'fechareg' => $fecha_final . ' 10:30:00',

                        'descripcion' => $_POST['descripcion']

                    );



                    $mantenimientos = ModeloInventario::registrarMantenimientosModel($datos);



                    if ($mantenimientos['guardar'] == true) {



                        $fecha_proxima = date("Y-m-d", strtotime($fecha_final . "+ 2 days"));

                        $fecha_proxima_dia = date('w', strtotime($fecha_proxima));

                        $fecha_proxima = ($fecha_proxima_dia == 0 && $fecha_proxima_dia == 6) ? date("Y-m-d", strtotime($fecha_proxima . "+ 2 days")) : $fecha_proxima;



                        $datos_solucion = array(

                            'id_log' => $_POST['id_log'],

                            'id_inventario' => $id_inventario,

                            'id_user' => $id_user,

                            'id_area' => $id_area,

                            'id_resp' => 13,

                            'id_reporte' => $mantenimientos['id'],

                            'estado' => 3,

                            'tipo_reporte' => 2,

                            'fecha_respuesta' => $fecha_proxima . ' 10:30:00',

                            'fechareg' => $fecha_proxima . ' 10:30:00',

                        );



                        $solucion = ModeloInventario::registrarSolucionMantenimientoControl($datos_solucion);
                    }
                }
            }



            if ($solucion == true) {

                echo '

                <script>

                ohSnap("Mantenimientos Registrados Correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,1050);



                function recargarPagina(){

                    window.location.replace("mantAires");

                }

                </script>';
            } else {

                echo '

                <script>

                ohSnap("Error de Mantenimientos!", {color: "red", "duration": "1000"});

                </script>';
            }
        }
    }

    public static function programarCopiaSeguridadOficina()
    {
        $data = array(
            'id_area' => 1,
            'id_user' => $_POST['id_log'],
            'observacion' => 'Copia de seguridad',
            'fecha' => $_POST['fecha_programacion'],
            'estado' => 0
        );
        $result = ModeloInventario::programarCopiaSeguridadOficina($data);
        if ($result == true) {
            echo '

                <script>

                ohSnap("Copia Programada Correctamente!", {color: "green", "duration": "1000"});

                </script>';
        }
    }

    public function actualizarDatosInventarioControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_inventario']) &&
            !empty($_POST['id_inventario'])
        ){
            $datos = array(
                'id_inventario' => $_POST['id_inventario'],
                'descripcion' => $_POST['descripcion'],
                'marca' => $_POST['marca'],
                'modelo' => $_POST['modelo'],
                'categoria' => $_POST['categoria'],
                'codigo' => $_POST['codigo_serial'],
                'frecuencia_mantenimiento' => $_POST['frecuencia_mantenimiento']
            );
            $actualizar = ModeloInventario::actualizarDatosInventarioModel($datos);
            if($actualizar){
                echo '
                <script>
                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);
                function recargarPagina(){
                    window.location.replace("'.BASE_URL.'listado/historial?inventario='.base64_encode($_POST['id_inventario']).'");
                }
                </script>';
            }else{
                echo '
                <script>
                ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});
                </script>';
            }
        }else{
            echo '
            <script>
            ohSnap("Ha ocurrido un error!", {color: "red", "duration": "1000"});
            </script>';
        }
    }

    public function obtenerReporteDeInventarioControl($id_inventario){
        $mostrar = ModeloInventario::obtenerReporteDeInventarioModel($id_inventario);
        return $mostrar;
    }
}
