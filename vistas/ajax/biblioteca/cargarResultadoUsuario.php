<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';
require_once CONTROL_PATH . 'perfil' . DS . 'ControlPerfil.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia          = ControlBiblioteca::singleton_biblioteca();
$instancia_perfil   = ControlPerfil::singleton_perfil();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$datos_usuario = $instancia_perfil->mostrarInformacionPerfilControl($_POST['id']);

if (!empty($datos_usuario['id_user'])) {

	$foto_perfil = ($datos_usuario['foto_carnet'] != '') ? 'upload/' . $datos_usuario['foto_carnet'] : 'img/user.svg';

	?>
	<input type="hidden" id="id_user" value="<?=$datos_usuario['id_user']?>">
	<input type="hidden" id="id_log" value="<?=$_POST['id_log']?>">
	<div class="row mt-2">
		<div class="circular--portrait">
			<img src="<?=PUBLIC_PATH . $foto_perfil?>">
		</div>
		<div class="col-lg-8 form-group">
			<div class="row p-2">
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Numero de Documento</label>
					<input type="text" class="form-control numeros" disabled value="<?=$datos_usuario['documento']?>">
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Nombre</label>
					<input type="text" class="form-control" value="<?=$datos_usuario['nombre']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Apellido</label>
					<input type="text" class="form-control" value="<?=$datos_usuario['apellido']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Correo</label>
					<input type="email" class="form-control" value="<?=$datos_usuario['correo']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Telefono</label>
					<input type="text" class="form-control" value="<?=$datos_usuario['telefono']?>" disabled >
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Fecha Devolucion <span class="text-danger">*</span></label>
					<input type="date" class="form-control" name="fecha_devolucion" required id="fecha_devolucion">
				</div>
			</div>
		</div>
		<div class="col-lg-4 form-group"></div>
		<div class="col-lg-4 form-group text-right">
			<a href="<?=BASE_URL?>biblioteca/prestamos/grupo" class="btn btn-danger btn-sm" style="margin-top: 1%;">
				<i class="fa fa-arrow-left"></i>
				&nbsp;
				Volver
			</a>
		</div>
		<div class="col-lg-4 form-group text-right">
			<div class="input-group">
				<input type="text" class="form-control filtro" placeholder="Codigo" aria-describedby="basic-addon2" name="buscar" id="codigo">
				<div class="input-group-append">
					<button class="btn btn-primary btn-sm codigo" type="button">
						<i class="fa fa-search"></i>
						&nbsp;
						Buscar
					</button>
				</div>
			</div>
		</div>
	</div>


	<div class="table-responsive mt-2">
		<table class="table table-hover border table-sm" width="100%" cellspacing="0">
			<thead>
				<tr class="text-center font-weight-bold">
					<th colspan="9">Libros a prestar</th>
				</tr>
				<tr class="text-center font-weight-bold">
					<th scope="col">Libro</th>
					<th scope="col">#Ejemplar</th>
					<th scope="col">Categoria</th>
					<th scope="col">Subcategoria</th>
					<th scope="col">Fecha Prestamo</th>
					<th scope="col">Fecha Devolucion</th>
					<th scope="col">Observacion</th>
				</tr>
			</thead>
			<tbody class="buscar informacion_prestamo">
			</tbody>
		</table>
	</div>
	<?php
} else {
	?>
	<div class="row mt-2">
		<div class="col-lg-12 form-group text-center">
			<h5 class="text-gray">No se encontraron resultados con el documento - <span class="font-weight-bold"><?=$_POST['id']?></span></h5>
		</div>
	</div>
	<?php
}
?>
<script>
	$("select").select2();
	$("table thead").addClass('text-uppercase');
	/*-------------------------------*/
	$("#codigo").focus();
	$("#codigo").keypress(function(e) {
		let code = (e.keyCode ? e.keyCode : e.which);
		if (code == 13) {
			$(".codigo").click();
		}
	});
	/*------------------------------------*/
	$(".codigo").click(function() {
		let id = $("#codigo").val();
		let id_log = $("#id_log").val();
		let id_user = $("#id_user").val();
		let fecha_devolucion = $("#fecha_devolucion").val();
		if (id != '' && fecha_devolucion != '') {
			prestarLibro(id, id_log, id_user, fecha_devolucion)
		}else{
			ohSnap("Falta Fecha Devolucion o Codigo!", {color: "red", "duration": "1000"});
		}
	});
	/*-------------------------------------*/
	function prestarLibro(id, id_log, id_user, fecha_devolucion) {
		try {
			$.ajax({
				url: '../../vistas/ajax/biblioteca/cargarResultadoLibroPrestado.php',
				method: 'POST',
				data: {
					'id': id,
					'id_log': id_log,
					'id_user': id_user,
					'fecha_devolucion': fecha_devolucion
				},
				cache: false,
				dataType: 'json',
				success: function(resultado) {
					if(resultado != 'error'){
						$("#codigo").focus();
						$("#codigo").val('');
						$(".informacion_prestamo").append(resultado);
					}else{
						ohSnap("Ejemplar ya prestado!", {color: "red", "duration": "1000"});
					}
				}
			});
		} catch (evt) {
			alert(evt.message);
		}
	}
</script>