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
require_once CONTROL_PATH . 'reportes' . DS . 'ControlReportes.php';

$instancia = ControlReporte::singleton_reporte();

$datos_reporte = $instancia->mostrarReportesSolucionadosControl();

$permisos = $instancia_permiso->permisosUsuarioControl(29, $perfil_log);

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
						<a href="#" onclick="window.history.go(-1); return false;" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Visto bueno
					</h4>
					<form method="POST">
						<input type="hidden" name="id_log" value="<?=$id_log?>">
						<button class="btn btn-success btn-sm" data-popover="popover" title="Visto bueno general" data-content="Al hacer clic se marcaran todos los reportes con visto bueno." data-trigger="hover">
							<i class="fa fa-check"></i>
							&nbsp;
							Visto bueno general
						</button>
					</form>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-8 form-inline">
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<div class="input-group mb-3">
									<input type="text" class="form-control filtro" placeholder="Buscar">
									<div class="input-group-prepend">
										<span class="input-group-text rounded-right" id="basic-addon1">
											<i class="fa fa-search"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">#</th>
									<th scope="col">Usuario</th>
									<th scope="col">Area</th>
									<th scope="col">Descripcion</th>
									<th scope="col">Marca</th>
									<th scope="col">Modelo</th>
									<th scope="col">Codigo</th>
									<th scope="col">Fecha solucionado</th>
									<th scope="col">Observacion</th>
								</tr>
							</thead>
							<tbody class="buscar text-uppercase">
								<?php
								foreach ($datos_reporte as $reporte) {
									$descripcion     = $reporte['descripcion'];
									$marca           = $reporte['marca'];
									$modelo          = $reporte['modelo'];
									$fecha_respuesta = date('Y-m-d', strtotime($reporte['fecha_respuesta']));
									$observacion     = $reporte['observacion'];
									$codigo          = $reporte['codigo'];
									$id_user         = $reporte['id_user'];
									$id_reporte      = $reporte['id_reporte'];
									$id_area         = $reporte['id_area'];
									$area            = $reporte['area'];
									$usuario         = $reporte['usuario'];

									?>
									<tr class="text-center reporte<?=$id_reporte?>">
										<td><?=$id_reporte?></td>
										<td><?=$usuario?></td>
										<td><?=$area?></td>
										<td><?=$descripcion?></td>
										<td><?=$marca?></td>
										<td><?=$modelo?></td>
										<td><?=$codigo?></td>
										<td><?=$fecha_respuesta?></td>
										<td><?=$observacion?></td>
										<td>
											<div class="btn-group btn-group-sm" role="group">
												<a href="<?=BASE_URL?>imprimir/solucion?reporte=<?=base64_encode($id_reporte)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Descargar reporte">
													<i class="fa fa-download"></i>
												</a>
												<button class="btn btn-success btn-sm visto" id="<?=$id_reporte?>" data-tooltip="tooltip" data-placement="bottom" title="Conceder visto bueno">
													<i class="fa fa-check"></i>
												</button>
											</div>
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

if (isset($_POST['id_log'])) {
	$instancia->vistoBuenoGeneralControl();
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/reportes/funcionesReporte.js"></script>