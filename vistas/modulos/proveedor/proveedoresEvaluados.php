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
require_once CONTROL_PATH . 'proveedor' . DS . 'ControlProveedor.php';

$instancia = ControlProveedor::singleton_proveedor();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

$evaluados_anio = $instancia->mostrarEvaluadosAnioControl();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-hebreo">
						<a href="<?=BASE_URL?>proveedor/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Proveedores evaluados <?=date('Y')?>
					</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive mt-2">
						<table class="table table-hover table-sm border" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">Nombre o Razon social</th>
									<th scope="col">Identificacion</th>
									<th scope="col">Fecha de evaluacion</th>
									<th scope="col">Calificacion total</th>
									<th scope="col">Criterio de confiabilidad</th>
									<th scope="col">Detalles</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($evaluados_anio as $evaluacion) {
									?>
									<tr class="text-center text-uppercase text-dark">
										<td>
											<a href="<?=BASE_URL?>proveedor/hojaRegistro?proveedor=<?=base64_encode($evaluacion['id_proveedor'])?>"><?=$evaluacion['nombre']?></a>
										</td>
										<td><?=$evaluacion['num_identificacion']?></td>
										<td><?=$evaluacion['fecha_evaluacion']?></td>
										<td><?=$evaluacion['total']?></td>
										<td><?=badgeCriterioEvaluacion($evaluacion['total'])?></td>
										<td>
											<a href="<?=BASE_URL?>imprimir/evaluacionProveedor?id=<?=base64_encode($evaluacion['id'])?>&tipo=evaluacion" target="_blank" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver PDF" data-placement="bottom" data-trigger="hover">
												<i class="fas fa-file-pdf"></i>
											</a>
										</td>
									</tr>
									<?php
								}
								?>
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
