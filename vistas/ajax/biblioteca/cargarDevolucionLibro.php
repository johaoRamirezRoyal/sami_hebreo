<?php
date_default_timezone_set('America/Bogota');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', '..' . DS . '..' . DS . '..');
require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';
require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';

$instancia   = ControlBiblioteca::singleton_biblioteca();
$datos_libro = $instancia->cargarInformacionEjemplarControl($_POST['id']);
$devolucion  = $instancia->devolucionLibroControl($_POST['id'], $_POST['id_log']);

$portada                  = ($datos_libro['foto'] != '') ? 'upload/' . $datos_libro['foto'] : 'img/Portada.jpg';
$ultimas_devolucion_libro = $instancia->ultimmosDevolucionesEjemplarControl($_POST['id']);

if ($datos_libro['estado'] == 2) {
	?>
	<div class="row mt-2">
		<div class="col-lg-4 form-group text-center bg-light rounded">
			<img src="<?=PUBLIC_PATH . $portada?>" alt="" class="img-fluid" width="216">
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
	</div>

	<div class="table-responsive mt-2">
		<table class="table table-hover border table-sm" width="100%" cellspacing="0">
			<thead>
				<tr class="text-center font-weight-bold">
					<th colspan="10">Ultimas 30 Devoluciones</th>
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
					<th scope="col">Fecha Devuelto</th>
					<th scope="col">Observacion</th>
				</tr>
			</thead>
			<tbody class="buscar">
				<?php
				foreach ($ultimas_devolucion_libro as $devolucion) {
					$id_prestamo      = $devolucion['id_prestamo'];
					$nom_libro        = $devolucion['titulo'];
					$codigo_ejem      = $devolucion['codigo'];
					$nom_categoria    = $devolucion['nom_categoria'];
					$nom_subcategoria = $devolucion['nom_subcategoria'];
					$fecha_prestamo   = $devolucion['fecha_prestamo'];
					$fecha_devolucion = $devolucion['fecha_devolucion'];
					$fecha_devuelto   = $devolucion['fecha_devuelto'];
					$observacion      = $devolucion['observacion'];
					$nom_user         = $devolucion['nom_user'];
					?>
					<tr class="text-center">
						<td><?=$id_prestamo?></td>
						<td><?=$nom_user?></td>
						<td><?=$nom_libro?></td>
						<td><?=$codigo_ejem?></td>
						<td><?=$nom_categoria?></td>
						<td><?=$nom_subcategoria?></td>
						<td><?=$fecha_prestamo?></td>
						<td><?=$fecha_devolucion?></td>
						<td><?=$fecha_devuelto?></td>
						<td><?=$observacion?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<script>
		ohSnap("Devolucion correcta!", {
			color: "green",
			'duration': '1000'
		});
	</script>
	<?php
} else {
	?>
	<div class="row mt-2">
		<div class="col-lg-12 form-group text-center">
			<h5 class="text-gray">El ejemplar con codigo <span class="font-weight-bold"><?=$_POST['id']?></span> ya realizo devolucion</h5>
		</div>
	</div>
	<?php
}
?>