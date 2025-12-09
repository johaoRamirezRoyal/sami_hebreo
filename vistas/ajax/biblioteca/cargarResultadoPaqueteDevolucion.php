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

$datos_paquete = $instancia->moatrarDatosCodigoPaqueteControl($_POST['id']);
$datos_detalle = $instancia->mostrarDetallePaqueteControl($datos_paquete['id']);

$devolucion = $instancia->devolucionPaqueteControl($_POST['id'], $_POST['id_log']);

if ($datos_paquete['estado'] == 2) {
	?>
	<div class="row p-3">
		<div class="col-lg-4 form-group">
			<label class="font-weight-bold">Titulo del paquete</label>
			<input type="text" class="form-control" disabled value="<?=$datos_paquete['nombre']?>">
		</div>
		<div class="col-lg-4 form-group">
			<label class="font-weight-bold">Codigo del paquete</label>
			<input type="text" class="form-control" disabled value="<?=$datos_paquete['codigo']?>">
		</div>
	</div>
	<div class="table-responsive mt-2">
		<table class="table table-hover border table-sm" width="100%" cellspacing="0">
			<thead>
				<tr class="text-center font-weight-bold">
					<th scope="col" colspan="4">Contenido del paquete</th>
				</tr>
				<tr class="text-center font-weight-bold">
					<th scope="col">Titulo</th>
					<th scope="col">Codigo</th>
				</tr>
			</thead>
			<tbody class="buscar">
				<?php
				foreach ($datos_detalle as $detalle) {
					$id_detalle  = $detalle['id'];
					$nom_detalle = $detalle['titulo'];
					$codigo      = $detalle['codigo'];
					?>
					<tr class="text-center">
						<td><?=$nom_detalle?></td>
						<td><?=$codigo?></td>
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
		$("table thead").addClass('text-uppercase');
	</script>
	<?php
} else {
	?>
	<div class="row mt-2">
		<div class="col-lg-12 form-group text-center">
			<h5 class="text-gray">El paquete con codigo  <span class="font-weight-bold"><?=$_POST['id']?></span> ya realizo devolucion</h5>
		</div>
	</div>
	<?php
}
?>