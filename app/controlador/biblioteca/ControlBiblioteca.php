<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'biblioteca' . DS . 'ModeloBiblioteca.php';
require_once CONTROL_PATH . 'numeros.php';

class ControlBiblioteca
{

    private static $instancia;

    public static function singleton_biblioteca()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarCategoriasBibliotecaControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarCategoriasBibliotecaModel();
        return $mostrar;
    }

    public function cantidadEjemplaresDisponiblesPrestadosControl()
    {
        $consulta    = ModeloBiblioteca::comandoSQL();
        $disponibles = ModeloBiblioteca::cantidadEjemplaresDisponiblesModel();
        $prestados   = ModeloBiblioteca::cantidadEjemplaresPrestadosModel();
        return array('prestados' => $prestados['cantidad_prestado'], 'disponibles' => $disponibles['cantidad_disponible']);
    }

    public function cantidadEjemplaresDisponiblesPrestadosLibroControl($id)
    {
        $consulta    = ModeloBiblioteca::comandoSQL();
        $disponibles = ModeloBiblioteca::cantidadEjemplaresDisponiblesLibroModel($id);
        $prestados   = ModeloBiblioteca::cantidadEjemplaresPrestadosLibroModel($id);
        return array('prestados' => $prestados['cantidad_prestado'], 'disponibles' => $disponibles['cantidad_disponible']);
    }

    public function mostrarLibrosControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarLibrosModel();
        return $mostrar;
    }

    public function mostrarLimitePaquetesControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarLimitePaquetesModel();
        return $mostrar;
    }

    public function mostrarUltimosDevolucionesControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarUltimosDevolucionesModel();
        return $mostrar;
    }

    public function mostrarTodosPaquetesControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarTodosPaquetesModel();
        return $mostrar;
    }

    public function mostrarTodosLibrosControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarTodosLibrosModel();
        return $mostrar;
    }

    public function utlimosPrestamosGeneralControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::utlimosPrestamosGeneralModel();
        return $mostrar;
    }

    public function ultimosPrestamosEjemplarControl($id_ejemplar)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarUltimosPrestamosEjemplarModel($id_ejemplar);
        return $mostrar;
    }

    public function moatrarDatosPaqueteControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::moatrarDatosPaqueteModel($id);
        return $mostrar;
    }

    public function moatrarDatosCodigoPaqueteControl($codigo)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::moatrarDatosCodigoPaqueteModel($codigo);
        return $mostrar;
    }

    public function mostrarDetallePaqueteControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarDetallePaqueteModel($id);
        return $mostrar;
    }

    public function mostrarDatosDetallePaqueteControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarDatosDetallePaqueteModel($id);
        return $mostrar;
    }

    public function reporteLibrosPrestadosControl($datos)
    {

        $nivel        = (empty($datos['nivel'])) ? '' : ' AND u.id_nivel = ' . $datos['nivel'];
        $curso        = (empty($datos['curso'])) ? '' : ' AND u.id_curso = ' . $datos['curso'];
        $fecha_inicio = (empty($datos['fecha_inicio'])) ? '' : ' AND bpr.fecha_prestamo >= "' . $datos['fecha_inicio'] . '"';
        $fecha_fin    = (empty($datos['fecha_fin'])) ? '' : ' AND bpr.fecha_prestamo <= "' . $datos['fecha_fin'] . '"';
        $codigo       = (empty($datos['codigo'])) ? '' : ' AND bj.codigo = "' . $datos['codigo'] . '"';

        $datos = array(
            'nivel'        => $nivel,
            'curso'        => $curso,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin,
            'codigo'       => $codigo,
        );

        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::reporteLibrosPrestadosModel($datos);
        return $mostrar;
    }

    public function prestamosUsuarioControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::prestamosUsuarioModel($id);
        return $mostrar;
    }

    public function utlimasDevolucionesGeneralControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::utlimasDevolucionesGeneralModel();
        return $mostrar;
    }

    public function ultimmosPrestamosEjemplarControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::ultimmosPrestamosEjemplarModel($id);
        return $mostrar;
    }

    public function ultimmosDevolucionesEjemplarControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::ultimmosDevolucionesEjemplarModel($id);
        return $mostrar;
    }

    public function ultimmosPrestamosUsuarioControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::ultimmosPrestamosUsuarioModel($id);
        return $mostrar;
    }

    public function mostrarInformacionLibroControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarInformacionLibroModel($id);
        return $mostrar;
    }

    public function cargarInformacionEjemplarControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::cargarInformacionEjemplarModel($id);
        return $mostrar;
    }

    public function informacionCodigoEjemplarControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::informacionCodigoEjemplarModel($id);
        return $mostrar;
    }

    public function mostrarEjemplaresControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarEjemplaresModel($id);
        return $mostrar;
    }

    public function devolucionLibroControl($codigo, $id_log)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::devolucionLibroModel($codigo, $id_log);
        return $mostrar;
    }

    public function devolucionPaqueteControl($codigo, $id_log)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::devolucionPaqueteModel($codigo, $id_log);
        return $mostrar;
    }

    public function buscarLibrosControl($datos)
    {
        $categoria    = (!empty($datos['categoria'])) ? ' AND bl.id_categoria = ' . $datos['categoria'] : '';
        $subcategoria = (!empty($datos['subcategoria'])) ? ' AND bl.id_subcategoria = ' . $datos['subcategoria'] : '';

        $datos = array('categoria' => $categoria, 'subcategoria' => $subcategoria, 'buscar' => $datos['buscar']);

        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::buscarLibrosModel($datos);
        return $mostrar;
    }

    public function buscarUsuariosBibliotecaNivelControl($datos)
    {

        $nivel = ($datos['nivel'] == '') ? '' : ' AND u.id_nivel = ' . $datos['nivel'];
        $curso = ($datos['curso'] == '') ? '' : ' AND u.id_curso = ' . $datos['curso'];

        $datos = array('nivel' => $nivel, 'curso' => $curso, 'buscar' => $datos['buscar']);

        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::buscarUsuariosBibliotecaNivelModel($datos);
        return $mostrar;
    }

    public function mostrarUsuariosBibliotecaControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarUsuariosBibliotecaModel();
        return $mostrar;
    }

    public function cargarSubcategoriasControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::cargarSubcategoriasModel($id);
        return $mostrar;
    }

    public function mostrarUltimosPrestamosControl()
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarUltimosPrestamosModel();
        return $mostrar;
    }

    public function historialPaquetesUsuariosControl($id)
    {
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::historialPaquetesUsuariosModel($id);
        return $mostrar;
    }

    public function mostrarListadoDePaquetesPendientesControl($id_user){
        $consulta = ModeloBiblioteca::comandoSQL();
        $mostrar  = ModeloBiblioteca::mostrarListadoDePaquetesPendientesModel($id_user);
        return $mostrar;
    }

    public function registrarLibroControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $subcategoria = (!empty($_POST['subcategoria'])) ? $_POST['subcategoria'] : 0;

            if (isset($_FILES['portada']['name']) && !empty($_FILES['portada']['name'])) {
                $nom_arch   = $_FILES['portada']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');
                $num_rand   = rand(1, 99999);

                $foto_portada = strtolower(md5($num_rand . '_' . $fecha_arch)) . '.' . $ext_arch;
            } else {
                $foto_portada = '';
            }

            $datos = array(
                'id_log'          => $_POST['id_log'],
                'titulo'          => $_POST['titulo'],
                'autor'           => $_POST['autor'],
                'editorial'       => $_POST['editorial'],
                'edicion'         => $_POST['edicion'],
                'id_categoria'    => $_POST['categoria'],
                'id_subcategoria' => $subcategoria,
                'observacion'     => $_POST['observacion'],
                'foto'            => $foto_portada,
            );

            $guardar = ModeloBiblioteca::registrarLibroModel($datos);

            if ($guardar['guardar'] == true) {

                for ($i = 0; $i < $_POST['ejemplares']; $i++) {

                    $datos_ejemplar = array(
                        'id_libro' => $guardar['id'],
                        'id_log'   => $_POST['id_log'],
                        'estado'   => 1,
                    );

                    $guardar_ejemplar = ModeloBiblioteca::registrarEjemplarModel($datos_ejemplar);

                    if ($guardar_ejemplar['guardar'] == true) {
                        $codigo            = $guardar['id'] . $_POST['categoria'] . $_POST['subcategoria'] . $guardar_ejemplar['id'];
                        $actualizar_codigo = ModeloBiblioteca::codigoEjemplarModel($guardar_ejemplar['id'], $codigo);
                    }
                }

                if ($actualizar_codigo == true) {
                    //ruta donde de alojamiento el archivo
                    $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                    $ruta_img     = $carp_destino . $foto_portada;

                    //verificar si subio el archivo y se mueve a su destino
                    if (is_uploaded_file($_FILES['portada']['tmp_name'])) {
                        move_uploaded_file($_FILES['portada']['tmp_name'], $ruta_img);
                    }

                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("agregar_libro");
                    }
                    </script>
                    ';

                } else {
                    echo '
                    <script>
                    ohSnap("Error al crear Ejemplares", {color: "red"});
                    </script>
                    ';
                }
            } else {
                echo '
                <script>
                ohSnap("Error al crear Libro", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function actualizarLibroControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $subcategoria = (!empty($_POST['subcategoria'])) ? $_POST['subcategoria'] : 0;

            if (isset($_FILES['portada']['name']) && !empty($_FILES['portada']['name'])) {
                $nom_arch   = $_FILES['portada']['name'];
                $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
                $fecha_arch = date('YmdHis');
                $num_rand   = rand(1, 99999);

                $foto_portada = strtolower(md5($num_rand . '_' . $fecha_arch)) . '.' . $ext_arch;
            } else {
                $foto_portada = $_POST['foto_portada_ant'];
            }

            $datos = array(
                'id_libro'        => $_POST['id_libro'],
                'id_log'          => $_POST['id_log'],
                'titulo'          => $_POST['titulo'],
                'autor'           => $_POST['autor'],
                'editorial'       => $_POST['editorial'],
                'edicion'         => $_POST['edicion'],
                'id_categoria'    => $_POST['categoria'],
                'id_subcategoria' => $subcategoria,
                'observacion'     => $_POST['observacion'],
                'foto'            => $foto_portada,
            );

            $guardar = ModeloBiblioteca::actualizarLibroModel($datos);

            if ($guardar == true) {
                //ruta donde de alojamiento el archivo
                $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
                $ruta_img     = $carp_destino . $foto_portada;

                //verificar si subio el archivo y se mueve a su destino
                if (is_uploaded_file($_FILES['portada']['tmp_name'])) {
                    move_uploaded_file($_FILES['portada']['tmp_name'], $ruta_img);
                }

                echo '
                <script>
                ohSnap("Editado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("informacion?libro=' . base64_encode($_POST['id_libro']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("ErroralModificarLibro", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function agregarEjemplarControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            for ($i = 0; $i < $_POST['cantidad']; $i++) {
                $datos_ejemplar = array(
                    'id_libro' => $_POST['id_libro'],
                    'id_log'   => $_POST['id_log'],
                    'estado'   => 1,
                );

                $guardar_ejemplar = ModeloBiblioteca::registrarEjemplarModel($datos_ejemplar);

                if ($guardar_ejemplar['guardar'] == true) {
                    $codigo            = $_POST['id_libro'] . $_POST['id_categoria'] . $_POST['id_subcategoria'] . $guardar_ejemplar['id'];
                    $actualizar_codigo = ModeloBiblioteca::codigoEjemplarModel($guardar_ejemplar['id'], $codigo);
                }
            }

            if ($guardar_ejemplar == true) {
                echo '
                <script>
                ohSnap("Agregado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("informacion?libro=' . base64_encode($_POST['id_libro']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al agregar ejemplar", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function prestarEjemplarControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['usuario']) &&
            !empty($_POST['usuario'])
        ) {

            $datos = array(
                'id_log'           => $_POST['id_log'],
                'id_ejemplar'      => $_POST['ejemplar'],
                'id_usuario'       => $_POST['usuario'],
                'fecha_prestamo'   => $_POST['fecha_prestamo'],
                'fecha_devolucion' => $_POST['fecha_devolucion'],
                'observacion'      => $_POST['observacion'],
            );

            $guardar = ModeloBiblioteca::prestarEjemplarModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Prestado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al realizar Prestamo", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function inactivarEjemplaresControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            foreach ($_POST['check_libro'] as $ejemplar) {
                $datos = array(
                    'id_log'      => $_POST['id_log'],
                    'id_ejemplar' => $ejemplar,
                );

                $inactivar = ModeloBiblioteca::inactivarEjemplaresModel($datos);

                if ($inactivar == true) {
                    echo '
                    <script>
                    ohSnap("Inactivado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("' . BASE_URL . 'biblioteca/libros/informacion?libro=' . base64_encode($_POST['id_libro']) . '");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Error al realizar Prestamo", {color: "red"});
                    </script>
                    ';
                }
            }
        }
    }

    public function guardarPaqueteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $codigo = $this->comprobarCodigoControl(generarCodigo(5));

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'nom_paquete' => $_POST['nom_paquete'],
                'codigo'      => $codigo,
            );

            $guardar = ModeloBiblioteca::guardarPaqueteModel($datos);

            if ($guardar['guardar'] == true) {

                $array_nom_contenido = array();
                $array_nom_contenido = $_POST['nom_contenido'];

                $it = new MultipleIterator();
                $it->attachIterator(new ArrayIterator($array_nom_contenido));

                $cont = 1;

                foreach ($it as $dato) {

                    $datos_detalle = array(
                        'id_log'     => $_POST['id_log'],
                        'nombre'     => $dato[0],
                        'id_paquete' => $guardar['id'],
                        'codigo'     => $codigo + $cont,
                    );

                    $guardar_detalle = ModeloBiblioteca::guardarDetallePaqueteModel($datos_detalle);

                    $cont++;
                }

                if ($guardar_detalle == true) {
                    echo '
                    <script>
                    ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                    setTimeout(recargarPagina,1050);

                    function recargarPagina(){
                        window.location.replace("agregar_paquete");
                    }
                    </script>
                    ';
                } else {
                    echo '
                    <script>
                    ohSnap("Error al guardar", {color: "red"});
                    </script>
                    ';
                }

            }
        }
    }

    public function inactivarContenidoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            foreach ($_POST['check_detalle'] as $dato) {

                $datos = array(
                    'id_paquete' => $_POST['id_paquete'],
                    'id_log'     => $_POST['id_log'],
                    'id_detalle' => $dato,
                );

                $guardar = ModeloBiblioteca::inactivarContenidoModel($datos);

            }

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Eliminados correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("detalle?paquete=' . base64_encode($_POST['id_paquete']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al eliminar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function agregarContenidoPaqueteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $codigo = ModeloBiblioteca::ultimoCodigoContenidoPaqueteModel($_POST['id_paquete']);

            $datos = array(
                'id_paquete' => $_POST['id_paquete'],
                'nombre'     => $_POST['nom_contenido'],
                'id_log'     => $_POST['id_log'],
                'codigo'     => $codigo['codigo'] + 1,
            );

            $guardar = ModeloBiblioteca::guardarDetallePaqueteModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("detalle?paquete=' . base64_encode($_POST['id_paquete']) . '");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al guardar", {color: "red"});
                </script>
                ';
            }
        }
    }

    public function prestarPaqueteControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'           => $_POST['id_log'],
                'id_paquete'       => $_POST['id_paquete'],
                'id_usuario'       => $_POST['usuario'],
                'fecha_prestamo'   => $_POST['fecha_prestamo'],
                'fecha_devolucion' => $_POST['fecha_devolucion'],
                'observacion'      => $_POST['observacion'],
            );

            $guardar = ModeloBiblioteca::prestarPaqueteModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Prestado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("prestamo");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al prestar", {color: "red"});
                </script>
                ';
            }

        }
    }

    public function comprobarCodigoControl($codigo)
    {
        $consulta_codigo_paquete = ModeloBiblioteca::codigoPaqueteModel($codigo);
        $result                  = ($consulta_codigo_paquete['id'] == '') ? $codigo : comprobarCodigoControl(generarCodigo(5));
        return $result;
    }

    public function prestarCodigoLibroControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos_ejemplar = ModeloBiblioteca::cargarInformacionEjemplarModel($_POST['id']);

            if ($datos_ejemplar['estado'] == 1) {

                $fecha_prestamo = date('Y-m-d');

                $datos = array(
                    'id_log'           => $_POST['id_log'],
                    'id_ejemplar'      => $datos_ejemplar['id_ejemplar'],
                    'id_usuario'       => $_POST['id_user'],
                    'fecha_prestamo'   => $fecha_prestamo,
                    'fecha_devolucion' => $_POST['fecha_devolucion'],
                    'observacion'      => '',
                );

                $guardar = ModeloBiblioteca::prestarEjemplarModel($datos);

                if ($guardar == true) {

                    $info = '
                    <tr class="text-center">
                    <td>' . $datos_ejemplar['titulo'] . '</td>
                    <td>' . $datos_ejemplar['codigo'] . '</td>
                    <td>' . $datos_ejemplar['nom_categoria'] . '</td>
                    <td>' . $datos_ejemplar['nom_subcategoria'] . '</td>
                    <td>' . $fecha_prestamo . '</td>
                    <td>' . $_POST['fecha_devolucion'] . '</td>
                    <td></td>
                    </tr>
                    ';
                }
            } else {
                $info = 'error';
            }

            return $info;

        }
    }

    public function extenderFechaPrestamoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'      => $_POST['id_log'],
                'id_prestamo' => $_POST['id_prestamo'],
                'fecha_nueva' => $_POST['fecha_nueva'],
            );

            $guardar = ModeloBiblioteca::extenderFechaPrestamoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Actualizado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("prestados");
                }
                </script>
                ';
            } else {
                echo '
                <script>
                ohSnap("Error al actualizar", {color: "red"});
                </script>
                ';
            }

        }
    }
    
    public static function eliminarLibro(){

        if(isset($_POST['modal_eliminar_libro'])) {

            $id_libro=$_POST['id_libro'];
            $activo=$_POST['id_activo'];

            $data=array(
                'id_libro' => $id_libro,
                'activo' => $activo
            );

            $result=ModeloBiblioteca::eliminarLibro($data);

            if ($result == true) {

                echo '

                <script>

                ohSnap("Eliminado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,2500);

                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>

                ';

            } else {

                echo '

                <script>

                ohSnap("Error al eliminar", {color: "red"});

                </script>

                ';

            }

        }

    }

    public function mostrarUsuariosPazYSalvoControl(){
        if(
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['curso']) &&
            !empty($_POST['curso'])
        ){
            $id_curso = $_POST['curso'];
            $array_paz_salvo_curso = ModeloBiblioteca::mostrarUsuariosPazYSalvoModel($id_curso);

            foreach($array_paz_salvo_curso as $usuario){
                $datos_correo = array(
                'asunto' => 'Paz y salvo',
                'mensaje' => '<p>El <span style="font-weight:bold;">Colegio Real Royal School</span> certifica el estado en el que se encuentra el alumnos en relación a paz y salvo por concepto de prestamo de libros.</p>
                <p>El certificado se encuentra en el siguiente enlace dirigido al pdf:' . BASE_URL . 'imprimir/biblioteca/paz_salvo?usuario=' . base64_encode($usuario['id_user']) . '</p>
                <p>Si tiene alguna duda, no dude en contactarnos</p>
                ',
                'correo' => array($usuario['correo_estudiante'], $usuario['correo_acudiente'], 'aprendiz.sistemas@royalschool.edu.co'),
                'archivo' => array()
                );
                
                $enviar = Correo::enviarCorreoModel($datos_correo);
                if($enviar){
                    echo '

                <script>

                ohSnap("Enviado correctamente!", {color: "green", "duration": "1000"});

                setTimeout(recargarPagina,2500);

                function recargarPagina(){

                    window.location.replace("index");

                }

                </script>

                ';
                }else{
                    echo 'Error al enviar correo, Contacte con el administrador del sistema';
                    die();
                }
            }       
               
        }
    }

}
