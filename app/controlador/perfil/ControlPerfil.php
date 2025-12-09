<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once CONTROL_PATH . 'hash.php';

class ControlPerfil
{

	private static $instancia;

	public static function singleton_perfil()
	{
		if (!isset(self::$instancia)) {
			$miclase         = __CLASS__;
			self::$instancia = new $miclase;
		}
		return self::$instancia;
	}

	public function mostrarDatosPerfilControl($id)
	{
		$datos = ModeloPerfil::mostrarDatosPerfilModel($id);
		return $datos;
	}

	public function mostrarInformacionPerfilControl($id)
	{
		$datos = ModeloPerfil::mostrarInformacionPerfilModel($id);
		return $datos;
	}

	public function mostrarPerfilesControl()
	{
		$datos = ModeloPerfil::mostrarPerfilesModel();
		return $datos;
	}

	public function mostrarPerfilesTodosControl()
	{
		$datos = ModeloPerfil::mostrarPerfilesTodosModel();
		return $datos;
	}

	public function mostrarDatosSuperEmpresaControl($super_empresa, $tipo_img)
	{
		$datos = ModeloPerfil::mostrarDatosSuperEmpresaModel($super_empresa, $tipo_img);
		return $datos;
	}

	public function mostrarNivelesControl($super_empresa)
	{
		$mostrar = ModeloPerfil::mostrarNivelesModel($super_empresa);
		return $mostrar;
	}

	public function guardarPerfilControl()
	{
		if (
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['super_empresa']) &&
			!empty($_POST['super_empresa']) &&
			isset($_POST['id_log']) &&
			!empty($_POST['id_log']) &&
			isset($_POST['descripcion']) &&
			!empty($_POST['descripcion'])
		) {

			$fechareg = date('Y-m-d H:i:s');

			$datos = array(
				'nombre'        => $_POST['descripcion'],
				'fechareg'      => $fechareg,
				'user_log'      => $_POST['id_log'],
				'super_empresa' => $_POST['super_empresa'],
			);

			$guardar = ModeloPerfil::guardarPerfilModelo($datos);
			if ($guardar == true) {
				echo '
				<script>
				ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("index");
				}
				</script>
				';
			} else {
				echo '
				<script>
				ohSnap("Ha ocurrido un error", {color: "red"});
				</script>
				';
			}
		}
	}

	public function editarPerfilesControl()
	{
		if (
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_perfil']) &&
			!empty($_POST['id_perfil']) &&
			isset($_POST['nom_edit']) &&
			!empty($_POST['nom_edit'])
		) {
			$datos = array(
				'id_perfil' => $_POST['id_perfil'],
				'nombre'    => $_POST['nom_edit'],
			);

			$guardar = ModeloPerfil::editarPerfilesModel($datos);
			if ($guardar['guardar'] == true) {
				echo '
				<script>
				ohSnap("Editado correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("index");
				}
				</script>
				';
			} else {
				echo '
				<script>
				ohSnap("Ha ocurrido un error", {color: "red"});
				</script>
				';
			}
		}
	}

	public function editarPerfilControl()
	{
		if (
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_user']) &&
			!empty($_POST['id_user'])
		) {

			$pass      = $_POST['password'];
			$conf_pass = $_POST['conf_password'];

			$clavecifrada = ($pass == $conf_pass && $pass != "" && $conf_pass != "") ? Hash::hashpass($pass) : $_POST['pass_old'];

			$datos = array(
				'id_user'   => $_POST['id_user'],
				'documento' => $_POST['documento'],
				'nombre'    => $_POST['nombre'],
				'apellido'  => $_POST['apellido'],
				'telefono'  => $_POST['telefono'],
				'pass'      => $clavecifrada,
				'perfil'    => $_POST['perfil'],
				'nivel'     => $_POST['nivel'],
			);

			$guardar = ModeloPerfil::editarPerfilModel($datos);

			if ($guardar == true) {
                echo '
                <script>
                ohSnap("Modificado correctamente!", {color: "green", "duration": "1000"});
                setTimeout(recargarPagina,1050);

                function recargarPagina(){
                window.location.replace("index");
                }
                </script>
                ';

                if (!empty($_FILES['archivo']['name'])) {
                	$datos_foto = array(
                		'archivo' => $_FILES['archivo']['name'],
                		'id_user' => $_POST['id_user'],
                	);

                	$guardar_foto = $this->guardarFotoControl($datos_foto);
                }
            } else {
            	echo '
            	<script>
            	ohSnap("Ha ocurrido un error!", {color: "red"});
            	</script>
            	';
            }
        } else {
        }
    }

    public function guardarFotoControl($datos)
    {
        //obtener el nombre del archivo
    	$nom_arch = $datos['archivo'];
        //extraer la extencion del archivo de el archivo
    	$ext_arch   = pathinfo($nom_arch, PATHINFO_EXTENSION);
    	$fecha_arch = date('YmdHis');

    	$nombre_archivo = strtolower(md5($datos['id_user'] . '_' . $fecha_arch)) . '.' . $ext_arch;

    	$datos_temp = array(
    		'nombre'  => $nombre_archivo,
    		'id_user' => $datos['id_user'],
    	);

    	$guardar_evidencia = ModeloPerfil::guardarFotoModel($datos_temp);

    	if ($guardar_evidencia == true) {
            //ruta donde de alojamiento el archivo
    		$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
    		$ruta_img     = $carp_destino . $nombre_archivo;

            //verificar si subio el archivo y se mueve a su destino
    		if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
    			move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
    		}

    		return true;
    	}
    }

    public function eliminarPerfilControl()
    {
    	if (
    		$_SERVER['REQUEST_METHOD'] == 'POST' &&
    		isset($_POST['id_perfil']) &&
    		!empty($_POST['id_perfil'])
    	) {

    		$id_perfil = filter_input(INPUT_POST, 'id_perfil', FILTER_SANITIZE_NUMBER_INT);
    		$result    = ModeloPerfil::eliminarPerfilModelo($id_perfil);

    		if ($result == true) {
    			$r = "ok";
    		} else {
    			$r = "No";
    		}
    		return $r;
    	}
    }
}
