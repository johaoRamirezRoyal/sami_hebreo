<?php
require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
	$er    = '2';
	$error = base64_encode($er);
	$salir = new Session;
	$salir->iniciar();
	$salir->outsession();
	header('Location:../login?er=' . $error);
	exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'renovacion' . DS . 'ControlRenovacion.php';

$instancia = ControlRenovacion::singleton_renovacion();

$datos_tipo_gestion = $instancia->mostrarGestionControl();

$permisos = $instancia_permiso->permisosUsuarioControl(45, $perfil_log);

if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-primary">
						<a href="<?=BASE_URL?>recursos/renovacion_recursos" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Cantidades por tipo de proceso
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-8 form-group">
						</div>
						<div class="col-lg-4 form-group">
							<div class="input-group">
								<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
								<div class="input-group-append">
									<button class="btn btn-primary btn-sm" type="button">
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
									<th scope="col">No. gestion</th>
									<th scope="col">Tipo de proceso</th>
									<th scope="col">Cantidad de documentos</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								$total = 0;
								foreach ($datos_tipo_gestion as $tipo) {
									$id_tipo  = $tipo['id'];
									$nombre   = $tipo['nombre'];
									$cantidad = (!empty($tipo['cantidad'])) ? $tipo['cantidad'] : 0;

									$total += $cantidad;
									?>
									<tr class="text-center">
										<td><?=$id_tipo?></td>
										<td><?=$nombre?></td>
										<td><?=$cantidad?></td>
									</tr>
									<?php
								}
								?>

								<tr class="text-center text-uppercase font-weight-bold">
									<td></td>
									<td>Total: </td>
									<td><?=$total?></td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>