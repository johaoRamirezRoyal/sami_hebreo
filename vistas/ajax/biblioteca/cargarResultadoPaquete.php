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

$datos_paquete  = $instancia->moatrarDatosCodigoPaqueteControl($_POST['id']);
$datos_detalle  = $instancia->mostrarDetallePaqueteControl($datos_paquete['id']);
$datos_usuarios = $instancia_usuarios->mostrarTodosUsuariosControl();

if (!empty($datos_paquete['id']) && $datos_paquete['estado'] == 1) {
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
		<div class="col-lg-12 form-group text-right mt-2">
			<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#prestar_paquete">
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


	<div class="modal fade" id="prestar_paquete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Prestar Paquete</h5>
				</div>
				<div class="modal-body">
					<form method="POST">
						<input type="hidden" name="id_paquete" value="<?=$datos_paquete['id']?>">
						<input type="hidden" name="id_log" value="<?=$_POST['id_log']?>">
						<div class="row p-2">
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Titulo del paquete</label>
								<input type="text" class="form-control" value="<?=$datos_paquete['nombre']?>"disabled>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Codigo del paquete</label>
								<input type="text" class="form-control" value="<?=$datos_paquete['codigo']?>" disabled>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Usuario a prestar <span class="text-danger">*</span></label>
								<select name="usuario" class="form-control" required>
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_usuarios as $usuario) {
										$id_usuario   = $usuario['id_user'];
										$nom_completo = $usuario['nombre'] . ' ' . $usuario['apellido'];
										?>
										<option value="<?=$id_usuario?>"><?=$nom_completo?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Prestamo <span class="text-danger">*</span></label>
								<input type="date" name="fecha_prestamo" class="form-control" required value="<?=date('Y-m-d')?>">
							</div>
							<div class="col-lg-6 form-group">
								<label class="font-weight-bold">Fecha Devolucion <span class="text-danger">*</span></label>
								<input type="date" name="fecha_devolucion" class="form-control" required>
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Observacion</label>
								<textarea class="form-control" name="observacion" rows="5"></textarea>
							</div>
							<div class="col-lg-12 form-group text-right mt-2">
								<button class="btn btn-danger btn-sm" data-dismiss="modal" type="button">
									<i class="fa fa-times"></i>
									&nbsp;
									Cancelar
								</button>
								<button class="btn btn-primary btn-sm" type="submit">
									<i class="fas fa-hourglass-end"></i>
									&nbsp;
									Prestar Paquete
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
</script>

