<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'perfil' . DS . 'ModeloPerfil.php';
require_once CONTROL_PATH . 'hash.php';
require_once CONTROL_PATH . 'numeros.php';
require_once CONTROL_PATH . 'archivos.php';

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

			if ($ext_arch == 'png' || $ext_arch == 'jpeg') {
				$compressed = compressImage($_FILES['archivo']['tmp_name'], $ruta_img, 70);
			} else {
				if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
					move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
				}
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

	public function mostrarTiposDocumentosControl(){
		$datos = ModeloPerfil::mostrarTiposDocumentosModel();
		return $datos;
	}

	public function agregarInformacionAdicionalControl(){
		if(
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_usuario']) &&
			!empty($_POST['id_usuario'])
		){
			$datos = array(
				'id_user' => $_POST['id_usuario'],
				'tipo_doc' => $_POST['tipo_doc'],
				'fecha_expedicion' => $_POST['fecha_expedicion'],
				'fecha_nacimiento' => $_POST['fecha_nacimiento'],
				'departamento_nacimiento' => $_POST['departamento_nacimiento'],
				'direccion' => $_POST['direccion'],
				'genero' => $_POST['genero'],
				'ultimo_nivel_educativo' => $_POST['ultimo_nivel_educativo'],
				'telefono' => $_POST['telefono'],
				'estrato' => $_POST['estrato'],
			); 

			$actualizar_telefono = ModeloPerfil::editarNumeroTelefonicoModel($datos);

			$guardar = ModeloPerfil::agregarInformacionAdicionalModel($datos);
			if($guardar['guardar'] == true){
				$informacion_guardada = ModeloPerfil::mostrarInformacionAdicionalModel($datos['id_user']);
				if($informacion_guardada != false){
					$borrar = ModeloPerfil::borrarInformacionAdicionalAntiguaModel($datos['id_user'], $guardar['id']);
				}
				echo '<script>
				ohSnap("Información adicional guardada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("informacion_personal");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Ha ocurrido un error!", {color: "red"});
				</script>';
			}
		}
	}

	public function agregarDocumentoIdentidadControl(){
		if(
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_log']) &&
			isset($_POST['id_datos'])
		){
			$nombre_archivo = $_FILES['documento_identidad']['name'];
			$extension_archivo = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
			$fecha_archivo = date('YmdHis');
	
			$nombre_final_archivo = strtolower(md5($fecha_archivo)) . '_identidad'. '.' . $extension_archivo;
	
			$datos_temp = array(
				'nombre' => $nombre_final_archivo,
				'id' => $_POST['id_datos'],
				'id_user' => $_POST['id_log'],
				'cedula_doc' => $nombre_final_archivo
			);

			$guardar_documento = ModeloPerfil::agregarDocumentoIdentidadModel($datos_temp);

			if($guardar_documento['guardar'] == true){
				//ruta donde de alojamiento el archivo
				$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
				$ruta_img = $carp_destino . $nombre_final_archivo;
	
				//verificar si subio el archivo y se mueve a su destino
				// if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
				// 	move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
				// }
				if (is_uploaded_file($_FILES['documento_identidad']['tmp_name'])) {
				if (move_uploaded_file($_FILES['documento_identidad']['tmp_name'], $ruta_img)) {
					if (file_exists($ruta_img)) {
						echo "✅ Archivo guardado correctamente ";
					} else {
						echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino.";
					}
					} else {
						echo "❌ Error al mover el archivo.";
					}
				} else {
					echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
					die();
				}
				echo '<script>
				ohSnap("Información adicional guardada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("informacion_personal");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Ha ocurrido un error!", {color: "red"});
				</script>';
			}
		}
	}

	public function guardarArchivoFormacionControl($datos_archivo){

		$nombre_archivo = $datos_archivo['archivo'];
		$extension_archivo = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
		$fecha_archivo = date('YmdHis');

		$nombre_final_archivo = strtolower(md5($fecha_archivo)) . '_formacion'. '.' . $extension_archivo;

		$datos_temp = array(
			'nombre' => $nombre_final_archivo,
			'id_formacion' => $datos_archivo['id_formacion'],
			'id_log' => $datos_archivo['id_log'],
		);


		$guardar_documento = ModeloPerfil::guardarArchivoFormacionModel($datos_temp);

		if($guardar_documento == true){
			//ruta donde de alojamiento el archivo
			$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
			$ruta_img = $carp_destino . $nombre_final_archivo;

			//verificar si subio el archivo y se mueve a su destino
			// if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
			// 	move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
			// }
			if (is_uploaded_file($_FILES['certificado_formacion']['tmp_name'])) {
			if (move_uploaded_file($_FILES['certificado_formacion']['tmp_name'], $ruta_img)) {
				if (file_exists($ruta_img)) {
					echo "✅ Archivo guardado correctamente ";
				} else {
					echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino.";
				}
			} else {
				echo "❌ Error al mover el archivo.";
			}
		} else {
			echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
			die();
		}
		}
	}

	public function agregarFormacionControl(){
		if(
			$_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_log']) &&
			!empty($_POST['id_log'])
		){

			$fecha_grado = (isset($_POST['fecha_grado'])) ? $_POST['fecha_grado'] : '';
			$fecha_expedicion_certi = (isset($_POST['fecha_expedicion'])) ? $_POST['fecha_expedicion'] : '';
			$duracion = (isset($_POST['duracion'])) ? $_POST['duracion'] : '';
			$archivo = $_FILES['certificado_formacion']['name'];

			$datos = array(
				'id_user' => $_POST['id_log'],
				'programa' => $_POST['programa_formacion'],
				'institucion' => $_POST['nombre_institucion'],
				'fecha_grado' => $fecha_grado,
				'fecha_expedicion_certi' => $fecha_expedicion_certi,
				'duracion' => $duracion,
				'tipo_formacion' => $_POST['tipo_formacion']
			);

			$guardar = ModeloPerfil::agregarFormacionPerfilModel($datos);

			if($guardar['guardar'] == true){

				$id_formacion = $guardar['id'];
				$datos_archivo = array(
					'archivo' => $archivo,
					'id_formacion' => $id_formacion,
					'id_log' => $_POST['id_log']
				);

				$guardar_certificado = $this->guardarArchivoFormacionControl($datos_archivo);

				echo '<script>
				ohSnap("Guardado correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("formacion?id=' . base64_encode($datos['id_user']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al agregar formación", {color: "red"});
				</script>';
			}
		}	
	}

	public function eliminarFormacionControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' && 
			isset($_POST['id']) &&
			!empty($_POST['id']))
		{
			$id = $_POST['id'];

			$result = ModeloPerfil::eliminarFormacionModel($id);

			$instancia_archivos = ControlArchivos::singleton_archivos();
			$eliminar_archivo = $instancia_archivos->eliminarArchivo($_POST['name_doc']);

			if($result == true){
				$eliminar_archivo = ModeloPerfil::eliminarArchivoFormacionModel($id);
				echo '<script>
				ohSnap("Formación eliminada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("formacion?id=' . base64_encode($_POST['id_log']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al eliminar formación", {color: "red"});
				</script>';
			}
		}
	}

	public function mostrarFormacionesFormalesUsuarioControl($id_user){
		$mostrar = ModeloPerfil::mostrarFormacionesFormalesUsuarioModel($id_user);
		return $mostrar;
	}

	public function mostrarFormacionesInformalesUsuarioControl($id_user){
		$mostrar = ModeloPerfil::mostrarFormacionesInformalesUsuarioModel($id_user);
		return $mostrar;
	}

	public function mostrarInformacionAdicionalControl($id_user){
		$datos = ModeloPerfil::mostrarInformacionAdicionalModel($id_user);
		return $datos;
	}

	public function mostrarNivelesAcademicosModel(){
		$datos = ModeloPerfil::mostrarNivelesAcademicosModel();
		return $datos;
	}

	public function mostrarNivelUsuarioModel($id_user){
		$datos = ModeloPerfil::mostrarNivelUsuarioModel($id_user);
		return $datos;
	}

	public function mostrarTodosPerfilesUsuariosControl(){
		$mostrar = ModeloPerfil::mostrarTodosPerfilesUsuariosModel();
		return $mostrar;
	}

	public function mostrarTodosLosCursosActivosControl(){
		$mostrar = ModeloPerfil::listarTodosLosCursosActivos();
		return $mostrar;
	}

	public function mostrarInformacionCertificadoFormacionControl($id_formacion){
		$datos = ModeloPerfil::mostrarInformacionCertificadoFormacionModel($id_formacion);
		return $datos;
	}

	public function insertarNuevaExperienciaLaboralControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_log']) &&
			!empty($_POST['id_log'])){

			$nombre_archivo = $_FILES['certificado_trabajo']['name'];
			$cargo = ($_POST['cargo'] === 'Otro:') ? $_POST['otro_cargo'] : $_POST['cargo'];

			$datos = array(
				'nombre_empresa' => $_POST['nombre_empresa'],
				'cargo' => $cargo,
				'fecha_ingreso' => $_POST['fecha_ingreso'],
				'fecha_retiro' => ($_POST['fecha_retiro'] != '') ? $_POST['fecha_retiro'] : '',
				'fecha_certificado' => ($_POST['fecha_certificado'] != '') ? $_POST['fecha_certificado'] : '',
				'id_user' => $_POST['id_log'],
				'nombre_doc' => $nombre_archivo
			);

			$guardar = ModeloPerfil::insertarNuevaExperienciaLaboralModel($datos);
			if($guardar){

				$id_experiencia = $guardar['id'];
				$datos_archivo = array(
					'nombre_doc' => $nombre_archivo,
					'id_experiencia' => $id_experiencia,
					'id_log' => $_POST['id_log']
				);

				$guardar_certificado = $this->agregarDocumentoExperienciaControl($datos_archivo);

				echo '<script>
				ohSnap("Experiencia Laboral guardada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("experienciaLaboral?id=' . base64_encode($_POST['id_log']) .'");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Ha ocurrido un error!", {color: "red"});
				</script>';
			}
		}
	}

	public function eliminarExperienciaLaboralControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' && 
			isset($_POST['id']) &&
			!empty($_POST['id']))
		{
			$id = $_POST['id'];
			$result = ModeloPerfil::eliminarExperienciaLaboralModel($id);

			$instancia_archivos = ControlArchivos::singleton_archivos();
			$eliminar_archivo = $instancia_archivos->eliminarArchivo($_POST['nombre_doc']);

			if($eliminar_archivo['estado'] == true){
				echo '<script>
				ohSnap("Archivo eliminado correctamente!", {color: "green", "duration": "1000"});
				</script>';
			}

			if($result == true){
				echo '<script>
				ohSnap("Experiencia laboral eliminada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("experienciaLaboral?id=' . base64_encode($_POST['redir']) .'");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al eliminar experiencia laboral", {color: "red"});
				</script>';
			}
		}
	}

	public function agregarDocumentoExperienciaControl($datos_archivo){

		$nombre_archivo = $datos_archivo['nombre_doc'];
		$extension_archivo = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
		$fecha_archivo = date('YmdHis');

		$nombre_final_archivo = strtolower(md5($fecha_archivo)) . '_experiencia'. '.' . $extension_archivo;

		$datos_temp = array(
			'nombre' => $nombre_final_archivo,
			'id_experiencia' => $datos_archivo['id_experiencia'],
			'id_log' => $datos_archivo['id_log'],
		);


		$guardar_documento = ModeloPerfil::agregarDocumentoExperienciaModel($nombre_final_archivo, $datos_temp['id_experiencia']);

		if($guardar_documento == true){
			//ruta donde de alojamiento el archivo
			$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
			$ruta_img = $carp_destino . $nombre_final_archivo;

			//verificar si subio el archivo y se mueve a su destino
			// if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
			// 	move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_img);
			// }
			if (is_uploaded_file($_FILES['certificado_trabajo']['tmp_name'])) {
			if (move_uploaded_file($_FILES['certificado_trabajo']['tmp_name'], $ruta_img)) {
				if (file_exists($ruta_img)) {
					echo "✅ Archivo guardado correctamente ";
				} else {
					echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino.";
				}
			} else {
				echo "❌ Error al mover el archivo.";
			}
			} else {
				echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
				die();
			}
		}
	}

	public function mostrarTodasLasExperienciasLaboralesUserControl($id_user){
		$datos = ModeloPerfil::mostrarTodasLasExperienciasLaboralesUserModel($id_user);
		return $datos;
	}

	public function agregarNuevoDocumentoVariadoControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['tipo_doc']) &&
			!empty($_POST['tipo_doc'])
		){
			$nombre_archivo = $_FILES['documento_variado']['name'];
			$extension_archivo = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
			$fecha_archivo = date('YmdHis');

			$nombre_final_archivo = strtolower(md5($fecha_archivo)) . '_experiencia'. '.' . $extension_archivo;

			$datos = array(
				'nombre_doc' => $nombre_final_archivo,
				'id_user' => $_POST['id_log'],
				'tipo_doc' => ($_POST['tipo_doc'] === 'Otro:') ? $_POST['otro_documento'] : $_POST['tipo_doc'] //$_POST['tipo_doc']
			);
			$guardar_documento = ModeloPerfil::agregarNuevoDocumentoVariadoModel($datos);

			if($guardar_documento['guardar'] == true){
				//ruta donde de alojamiento el archivo
				$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
				$ruta_img = $carp_destino . $nombre_final_archivo;

				//verificar si subio el archivo y se mueve a su destino
				if (is_uploaded_file($_FILES['documento_variado']['tmp_name'])) {
					if (move_uploaded_file($_FILES['documento_variado']['tmp_name'], $ruta_img)) {
						if (file_exists($ruta_img)) {
							echo "✅ Archivo guardado correctamente ";
						} else {
							echo "⚠️ El archivo se movió, pero no se encuentra en la ruta destino.";
						}
					} else{
						echo "❌ Error al mover el archivo. Contacte con el administrador. (Recargue la página)";
						die();
					}
				} else{
					echo "❌ El archivo no se subió correctamente al servidor contacte con el administrador. (Recargue la página)";
					die();
				}
				echo '<script>
				ohSnap("Documento guardado correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("otrosDocumentos?id=' . base64_encode($_POST['id_log']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Ha ocurrido un error!", {color: "red"});
				</script>';
			}
		}
	}

	public function eliminarDocumentoVariosControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' && 
			isset($_POST['id']) &&
			!empty($_POST['id']))
		{
			$id = $_POST['id'];
			$result = ModeloPerfil::eliminarDocumentoVariosModel($id);
			$instancia_archivos = ControlArchivos::singleton_archivos();
			$eliminar_archivo = $instancia_archivos->eliminarArchivo($_POST['nombre_doc']);

			if($result == true){
				echo '<script>
				ohSnap("Documento eliminado correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("otrosDocumentos?id=' . base64_encode($_POST['id_log']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al eliminar documento", {color: "red"});
				</script>';
			}
		}
	}

	public function mostrarDocumentosVariosUsuarioControl($id_user){
		$datos = ModeloPerfil::mostrarDocumentosVariosUsuarioModel($id_user);
		return $datos;
	}

	public function agregarProduccionIntelectualControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' &&
			isset($_POST['id_log']) &&
			!empty($_POST['id_log'])){

			$nombre_produccion = $_POST['nombre_produccion'];
			$tipo_produccion = $_POST['tipo_produccion'];
			$denominacion_produccion = $_POST['denominacion_produccion'];
			$objetivo_produccion = $_POST['objetivo_produccion'];
			$descipcion_produccion = $_POST['descipcion_produccion'];
			$duracion = $_POST['duracion'];
			$lugar = $_POST['lugar'];
			$observaciones = $_POST['observaciones'];
			$evidencia_produccion = $_FILES['evidencia_produccion']['name'];
			$extension_evidencia_produccion = pathinfo($evidencia_produccion, PATHINFO_EXTENSION);
			$fecha_evidencia_produccion = date('YmdHis');

			$nombre_archivo = strtolower(md5($fecha_evidencia_produccion)) . '_produccion'. '.' . $extension_evidencia_produccion;

			$datos = array(
				'id_user' => $_POST['id_log'],
				'nombre_produccion' => $nombre_produccion,
				'tipo_produccion' => $tipo_produccion,
				'denominacion_produccion' => $denominacion_produccion,
				'objetivo_produccion' => $objetivo_produccion,
				'descipcion_produccion' => $descipcion_produccion,
				'duracion' => $duracion,
				'lugar' => $lugar,
				'evidencia_produccion' => $nombre_archivo,
				'observaciones' => $observaciones
			);
			$guardar = ModeloPerfil::agregarProduccionIntelectualModel($datos);
			if($guardar['guardar'] == true){
				//ruta donde de alojamiento el archivo
				$carp_destino = PUBLIC_PATH_ARCH . 'upload' . DS;
				$ruta_img = $carp_destino . $nombre_archivo;

				//verificar si subio el archivo y se mueve a su destino
				if (is_uploaded_file($_FILES['evidencia_produccion']['tmp_name'])) {
					move_uploaded_file($_FILES['evidencia_produccion']['tmp_name'], $ruta_img);
				}

				echo '<script>
				ohSnap("Producción guardada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("produccionIntelectual?id=' . base64_encode($_POST['id_log']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al crear producción", {color: "red"});
				</script>';
			}

		}
	}

	public function eliminarProduccionIntelectualControl(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' && 
			isset($_POST['id']) &&
			!empty($_POST['id']))
		{
			$id = $_POST['id'];
			
			$instancia_archivos = ControlArchivos::singleton_archivos();
			$eliminar_archivo = $instancia_archivos->eliminarArchivo($_POST['name_doc']);

			$result = ModeloPerfil::eliminarProduccionIntelectualModel($id);

			if($result == true){
				echo '<script>
				ohSnap("Producción eliminada correctamente!", {color: "green", "duration": "1000"});
				setTimeout(recargarPagina,1050);

				function recargarPagina(){
					window.location.replace("produccionIntelectual?id=' . base64_encode($_POST['id_log']) . '");
				}
				</script>';
			}else{
				echo '<script>
				ohSnap("Error al eliminar producción", {color: "red"});
				</script>';
			}
		}
	}

	public function mostrarProduccionIntelectualUsuarioControl($id_user){
		$mostrar = ModeloPerfil::mostrarProduccionIntelectualUsuarioModel($id_user);
		return $mostrar;
	}
		
}
