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

$datos_libro    = $instancia->cargarInformacionEjemplarControl($_POST['id']);
$datos_usuarios = $instancia_usuarios->mostrarTodosUsuariosControl();

$datos_usuario = $instancia_perfil->mostrarInformacionPerfilControl($_POST['id']);
$datos_libros  = $instancia->mostrarTodosLibrosControl();

if (!empty($datos_libro['id']) && $datos_libro['estado'] == 1) {

	$portada = ($datos_libro['foto'] != '') ? 'upload/' . $datos_libro['foto'] : 'img/Portada.jpg';

	$ultimos_prestamos_libro = $instancia->ultimmosPrestamosEjemplarControl($_POST['id']);
	?>
	<div class="row mt-2">
		<div class="col-lg-4 form-group text-center bg-light rounded">
			<img src="<?=PUBLIC_PATH . $portada?>" alt="" class="img-fluid">
		</div>
		<div class="col-lg-8 form-group">
			<div class="row">
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Titulo</label>
					<input type="text" class="form-control" value="<?=$datos_libro['titulo']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Autor</label>
					<input type="text" class="form-control" value="<?=$datos_libro['autor']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Editorial</label>
					<input type="text" class="form-control"  value="<?=$datos_libro['editorial']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Edicion o a&ntilde;o publicacion</label>
					<input type="text" class="form-control" value="<?=$datos_libro['edicion']?>" disabled>
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Categoria</label>
					<input type="text" value="<?=$datos_libro['nom_categoria']?>" disabled class="form-control">
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Sub-Categoria</label>
					<input type="text" value="<?=$datos_libro['nom_subcategoria']?>" disabled class="form-control">
				</div>
				<div class="col-lg-12 form-group mt-2">
					<label class="font-weight-bold">Observacion</label>
					<textarea disabled class="form-control" rows="5"><?=$datos_libro['observacion']?></textarea>
				</div>
			</div>
		</div>
		<div class="col-lg-12 form-group text-right">
			<a href="<?=BASE_URL?>biblioteca/prestamos/index" class="btn btn-danger btn-sm">
				<i class="fa fa-arrow-left"></i>
				&nbsp;
				Volver
			</a>
			<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#prestar_libro">
				<i class="fas fa-hourglass-end"></i>
				&nbsp;
				Realizar Prestamo
			</button>
		</div>
	</div>

	<div class="table-responsive mt-2">
		<table class="table table-hover border table-sm" width="100%" cellspacing="0">
			<thead>
				<tr class="text-center font-weight-bold">
					<th colspan="9">Ultimos 30 prestamos realizados</th>
				</tr>
				<tr class="text-center font-weight-bold">
					<th scope="col">No. Prestamo</th>
					<th scope="col">Usuario</th>
					<th scope="col">Libro</th>
					<th scope="col">#Ejemplar</th>
					<th scope="col">Categoria</th>
					<th scope="col">Subcategoria</th>
					<th scope="col">Fecha Prestamo</th>
					<th scope="col">Fecha Devolucion</th>
					<th scope="col">Observacion</th>
				</tr>
			</thead>
			<tbody class="buscar">
				<?php
				foreach ($ultimos_prestamos_libro as $prestamo) {
					$id_prestamo      = $prestamo['id_prestamo'];
					$nom_libro        = $prestamo['titulo'];
					$codigo_ejem      = $prestamo['codigo'];
					$nom_categoria    = $prestamo['nom_categoria'];
					$nom_subcategoria = $prestamo['nom_subcategoria'];
					$fecha_prestamo   = $prestamo['fecha_prestamo'];
					$fecha_devolucion = $prestamo['fecha_devolucion'];
					$observacion      = $prestamo['observacion'];
					$nom_user         = $prestamo['nom_user'];
					?>
					<tr class="text-center d-table-row">
						<td><?=$id_prestamo?></td>
						<td><?=$nom_user?></td>
						<td><?=$nom_libro?></td>
						<td><?=$codigo_ejem?></td>
						<td><?=$nom_categoria?></td>
						<td><?=$nom_subcategoria?></td>
						<td><?=$fecha_prestamo?></td>
						<td><?=$fecha_devolucion?></td>
						<td><?=$observacion?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="modal fade" id="prestar_libro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Realizar Prestamo</h5>
				</div>
				<div class="modal-body">
					<form method="POST">
						<input type="hidden" name="id_log" value="<?=$_POST['id_log']?>">
						<input type="hidden" name="ejemplar" value="<?=$datos_libro['id_ejemplar']?>">
						<div class="row p-2">
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Libro</label>
								<input type="text" disabled value="<?=$datos_libro['titulo']?>" class="form-control">
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Libro - # Ejemplar</label>
								<input type="text" disabled value="<?=$datos_libro['codigo']?>" class="form-control">
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
								<select name="usuario" class="form-control" required>
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_usuarios as $usuario) {
										$id_user      = $usuario['id_user'];
										$nom_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
										?>
										<option value="<?=$id_user?>"><?=$nom_completo?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Prestamo <span class="text-danger">*</span></label>
								<input type="date" name="fecha_prestamo" value="<?=date('Y-m-d')?>" class="form-control" required>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Devolucion <span class="text-danger">*</span></label>
								<input type="date" name="fecha_devolucion" class="form-control" required>
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Observacion</label>
								<textarea class="form-control" rows="5" name="observacion"></textarea>
							</div>
							<div class="col-lg-12 form-group text-right mt-2">
								<button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
									<i class="fa fa-times"></i>
									&nbsp;
									Cancelar
								</button>
								<button class="btn btn-primary btn-sm" type="submit">
									<i class="fas fa-hourglass-end"></i>
									&nbsp;
									Prestar Libro
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
} else if (!empty($datos_usuario['id_user'])) {
	$foto_perfil = ($datos_usuario['foto_carnet'] != '') ? 'upload/' . $datos_usuario['foto_carnet'] : 'img/user.svg';

	$ultimos_prestamos_user = $instancia->ultimmosPrestamosUsuarioControl($_POST['id']);
	?>
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
			</div>
		</div>
		<div class="col-lg-12 form-group text-right">
			<a href="<?=BASE_URL?>biblioteca/prestamos/index" class="btn btn-danger btn-sm">
				<i class="fa fa-arrow-left"></i>
				&nbsp;
				Volver
			</a>
			<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#prestar_libro">
				<i class="fas fa-hourglass-end"></i>
				&nbsp;
				Realizar Prestamo
			</button>
		</div>
	</div>


	<div class="table-responsive mt-2">
		<table class="table table-hover border table-sm" width="100%" cellspacing="0">
			<thead>
				<tr class="text-center font-weight-bold">
					<th colspan="9">Ultimos 30 prestamos realizados</th>
				</tr>
				<tr class="text-center font-weight-bold">
					<th scope="col">No. Prestamo</th>
					<th scope="col">Usuario</th>
					<th scope="col">Libro</th>
					<th scope="col">#Ejemplar</th>
					<th scope="col">Categoria</th>
					<th scope="col">Subcategoria</th>
					<th scope="col">Fecha Prestamo</th>
					<th scope="col">Fecha Devolucion</th>
					<th scope="col">Observacion</th>
				</tr>
			</thead>
			<tbody class="buscar">
				<?php
				foreach ($ultimos_prestamos_user as $prestamo) {
					$id_prestamo      = $prestamo['id_prestamo'];
					$nom_libro        = $prestamo['titulo'];
					$codigo_ejem      = $prestamo['codigo'];
					$nom_categoria    = $prestamo['nom_categoria'];
					$nom_subcategoria = $prestamo['nom_subcategoria'];
					$fecha_prestamo   = $prestamo['fecha_prestamo'];
					$fecha_devolucion = $prestamo['fecha_devolucion'];
					$observacion      = $prestamo['observacion'];
					$nom_user         = $prestamo['nom_user'];
					?>
					<tr class="text-center d-table-row">
						<td><?=$id_prestamo?></td>
						<td><?=$nom_user?></td>
						<td><?=$nom_libro?></td>
						<td><?=$codigo_ejem?></td>
						<td><?=$nom_categoria?></td>
						<td><?=$nom_subcategoria?></td>
						<td><?=$fecha_prestamo?></td>
						<td><?=$fecha_devolucion?></td>
						<td><?=$observacion?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>


	<div class="modal fade" id="prestar_libro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Realizar Prestamo</h5>
				</div>
				<div class="modal-body">
					<form method="POST">
						<input type="hidden" name="id_log" value="<?=$_POST['id_log']?>">
						<input type="hidden" name="usuario" value="<?=$datos_usuario['id_user']?>">
						<div class="row p-2">
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Libro <span class="text-danger">*</span></label>
								<select class="form-control" id="libro_cargar" required>
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_libros as $libro) {
										$id_libro  = $libro['id'];
										$nom_libro = $libro['titulo'];
										?>
										<option value="<?=$id_libro?>"><?=$nom_libro?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Libro - # Ejemplar <span class="text-danger">*</span></label>
								<select name="ejemplar" class="form-control" id="ejemplar" required></select>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
								<input type="text" class="form-control" value="<?=$datos_usuario['nombre'] . ' ' . $datos_usuario['apellido']?>" disabled>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Prestamo <span class="text-danger">*</span></label>
								<input type="date" name="fecha_prestamo" value="<?=date('Y-m-d')?>" class="form-control" required>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Devolucion <span class="text-danger">*</span></label>
								<input type="date" name="fecha_devolucion" class="form-control" required>
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Observacion</label>
								<textarea class="form-control" rows="5" name="observacion"></textarea>
							</div>
							<div class="col-lg-12 form-group text-right mt-2">
								<button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
									<i class="fa fa-times"></i>
									&nbsp;
									Cancelar
								</button>
								<button class="btn btn-primary btn-sm" type="submit">
									<i class="fas fa-hourglass-end"></i>
									&nbsp;
									Prestar Libro
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
} else {
	?>
	<div class="row mt-2">
		<div class="col-lg-12 form-group text-center">
			<h5 class="text-gray">No se encontraron resultados con el codigo - <span class="font-weight-bold"><?=$_POST['id']?></span></h5>
		</div>
	</div>
	<?php
}
?>
<script>
	$("select").select2();
	$("table thead").addClass('text-uppercase');
	/*---------------------------------------*/
	$("#libro_cargar").change(function() {
		let id = $(this).val();
		if (id == '') {} else {
			cargarEjemplaresLibro(id);
		}
	});
	/*---------------------------------------*/
	function cargarEjemplaresLibro(id) {
		$('#ejemplar').html('');
		try {
			$.ajax({
				url: '../../vistas/ajax/biblioteca/cargarEjemplaresLibro.php',
				method: 'POST',
				data: {
					'id': id
				},
				cache: false,
				dataType: 'json',
				success: function(resultado) {
					$('#ejemplar').append($('<option />', {
						text: 'Seleccione una sub-categoria...',
						value: '',
					}));
					$.each(resultado, function(key, valor) {
						if(valor.estado == 1){
							$('#ejemplar').append($('<option />', {
								text: valor.codigo,
								value: valor.id,
							}));
						}
					});
				}
			});
		} catch (evt) {
			alert(evt.message);
		}
	}
</script>