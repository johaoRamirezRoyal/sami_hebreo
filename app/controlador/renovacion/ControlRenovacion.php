<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'renovacion' . DS . 'ModeloRenovacion.php';

class ControlRenovacion
{

    private static $instancia;

    public static function singleton_renovacion()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function buscarRenovacionesControl($buscar, $anio)
    {
        $comando = ModeloRenovacion::comandoSQL();

        $consulta_bus  = ($anio == '') ? '' : 'LEFT JOIN anio_escolar an ON an.id = ' . $anio;
        $condicion_bus = ($anio == '') ? '' : 'AND YEAR(r.fecha_renovacion) >= an.anio_inicio AND YEAR(r.fecha_proxima) >= an.anio_fin';

        $mostrar = ModeloRenovacion::buscarRenovacionesModel($buscar, $consulta_bus, $condicion_bus);
        return $mostrar;
    }

    public function buscarRenovacionesDocumentosControl($datos)
    {
        $tipo  = (!empty($datos['tipo_buscar'])) ? ' AND r.tipo_proceso = ' . $datos['tipo_buscar'] : '';
        $fecha = (!empty($datos['fecha'])) ? ' AND r.fecha_vigencia = ' . $datos['fecha'] : '';

        $datos_buscar = array('tipo' => $tipo, 'fecha' => $fecha, 'buscar' => $datos['buscar']);

        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::buscarRenovacionesDocumentosModel($datos_buscar);
        return $mostrar;
    }

    public function mostrarRenovacionesControl()
    {
        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::mostrarRenovacionesModel();
        return $mostrar;
    }

    public function mostrarGestionControl()
    {
        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::mostrarGestionModel();
        return $mostrar;
    }

    public function mostrarTodasRenovacionesControl()
    {
        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::mostrarTodasRenovacionesModel();
        return $mostrar;
    }

    public function mostrarRenovacionesDocumentosControl()
    {
        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::mostrarRenovacionesDocumentosModel();
        return $mostrar;
    }

    public function eliminarDocumentoControl($id, $log)
    {
        $comando = ModeloRenovacion::comandoSQL();
        $mostrar = ModeloRenovacion::eliminarDocumentoModel($id, $log);
        return $mostrar;
    }

    public function agregarRenovacionControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $datos = array(
                'id_log'           => $_POST['id_log'],
                'nombre'           => $_POST['nombre'],
                'fecha_renovacion' => $_POST['fecha_renovacion'],
                'fecha_proxima'    => $_POST['fecha_proxima'],
            );

            $guardar = ModeloRenovacion::agregarRenovacionModel($datos);

            if ($guardar['guardar'] == true) {

                if (isset($_FILES['archivo']['name'])) {

                    $datos_archivo = array('id' => $guardar['id'], 'archivo' => $_FILES['archivo']);
                    $documento     = $this->guardarEvidenciaControl($datos_archivo);

                    $datos_temp = array(
                        'id_renovacion' => $guardar['id'],
                        'nombre'        => $documento,
                        'id_log'        => $_POST['id_log'],
                    );

                    $guardar_evidencia = ModeloRenovacion::guardarEvidenciaRenovacionModel($datos_temp);
                }

                echo '
                <script>
                ohSnap("Registrado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("index");
                }
                </script>';

            } else {
                echo '
                <script>
                ohSnap("Error al registrar!", {color: "red", "duration": "1000"});
                </script>';
            }
        }

    }

    public function agregarRenovacionDocumentoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {

            $documento = '';

            if (isset($_FILES['archivo']['name']) && !empty($_FILES['archivo']['name'])) {
                $datos_archivo = array('id' => rand(), 'archivo' => $_FILES['archivo']);
                $documento     = $this->guardarEvidenciaControl($datos_archivo);
            }

            $fecha_revision = (empty($_POST['fecha_revision'])) ? '0000-00-00' : $_POST['fecha_revision'];

            $datos = array(
                'id_log'         => $_POST['id_log'],
                'tipo_proceso'   => $_POST['tipo'],
                'nom_documento'  => $_POST['nombre'],
                'version_doc'    => $_POST['version'],
                'fecha_vigencia' => $_POST['fecha_vigencia'],
                'fecha_revision' => $fecha_revision,
                'categ_doc'      => $_POST['cate_doc'],
                'gestion_cambio' => $_POST['gestion'],
                'url_archivo'    => $_POST['url_archivo'],
                'evidencia'      => $documento,
            );

            $guardar = ModeloRenovacion::agregarRenovacionDocumentoModel($datos);

            if ($guardar['guardar'] == true) {
                echo '
                <script>
                ohSnap("Registrado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("renovacion_recursos");
                }
                </script>';
            } else {
                echo '
                <script>
                ohSnap("Error al registrar!", {color: "red", "duration": "1000"});
                </script>';
            }
        }
    }

    public function actualizarRenovacionDocumentoControl()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['id_log']) &&
            !empty($_POST['id_log'])
        ) {
            $documento = $_POST['documento_old'];

            if (isset($_FILES['archivo_edit']['name']) && !empty($_FILES['archivo_edit']['name'])) {
                $datos_archivo = array('id' => rand(), 'archivo' => $_FILES['archivo_edit']);
                $documento     = $this->guardarEvidenciaControl($datos_archivo);
            }

            $fecha_revision = (empty($_POST['fecha_revision_edit'])) ? '0000-00-00' : $_POST['fecha_revision_edit'];

            $datos = array(
                'id_log'         => $_POST['id_log'],
                'id_documento'   => $_POST['id_documento'],
                'id_user'        => $_POST['id_user'],
                'tipo_proceso'   => $_POST['tipo_edit'],
                'nom_documento'  => $_POST['nombre_edit'],
                'version_doc'    => $_POST['version_edit'],
                'fecha_vigencia' => $_POST['fecha_vigencia_edit'],
                'fecha_revision' => $fecha_revision,
                'categ_doc'      => $_POST['cate_doc_edit'],
                'gestion_cambio' => $_POST['gestion_edit'],
                'url_archivo'    => $_POST['url_archivo_edit'],
                'evidencia'      => $documento,
            );

            $guardar = ModeloRenovacion::actualizarRenovacionDocumentoModel($datos);

            if ($guardar == true) {
                echo '
                <script>
                ohSnap("Actualizado Correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                    window.location.replace("renovacion_recursos");
                }
                </script>';
            } else {
                echo '
                <script>
                ohSnap("Error al registrar!", {color: "red", "duration": "1000"});
                </script>';
            }
        }
    }

    private function guardarEvidenciaControl($datos)
    {
        $nom_arch   = $datos['archivo']['name'];
        $ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
        $fecha_arch = date('YmdHis');

        $nombre_archivo = strtolower(md5($datos['id'] . '_' . $fecha_arch)) . '.' . $ext_arch;

        //ruta donde de alojamiento el archivo
        $carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
        $ruta_img     = $carp_destino . $nombre_archivo;

        //verificar si subio el archivo y se mueve a su destino
        if (is_uploaded_file($datos['archivo']['tmp_name'])) {
            move_uploaded_file($datos['archivo']['tmp_name'], $ruta_img);
        }

        return $nombre_archivo;
    }

}
