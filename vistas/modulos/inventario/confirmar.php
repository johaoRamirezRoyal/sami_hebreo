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
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';

$instancia = ControlInventario::singleton_inventario();

$datos_confirmar = $instancia->mostrarCantidadesInventarioControl($id_log);

$permisos = $instancia_permiso->permisosUsuarioControl(16, $perfil_log);

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
						<a href="<?=BASE_URL?>inventario/listado" class="text-decoration-none">
							<i class="fa fa-arrow-left"></i>
						</a>
						&nbsp;
						Confirmar inventario
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-8 form-inline">
							<p class="text-danger"><b>Nota:</b> Este apartado es solo para confirmar las <b>CANTIDADES</b> del inventario, si las cantidades del sistema no son iguales favor diligenciar detalladamente en el campo <b>OBSERVACION</b> las cantidades con las que cuenta fisicamente y si desea elminar o agregar uno o mas articulos de ese mismo tipo.</p>
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
					<div class="table-responsive mt-4">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center text-uppercase bg-light">
									<th colspan="9">Listado inventario</th>
								</tr>
								<tr class="text-center font-weight-bold">
									<th scope="col">AREA</th>
									<th scope="col">DESCRIPCION</th>
									<th scope="col">CANTIDAD</th>
									<th scope="col">OBSERVACION</th>
								</tr>
							</thead>
							<tbody class="buscar text-uppercase">
								<?php
								foreach ($datos_confirmar as $confirmar) {
									$id_inventario = $confirmar['id'];
									$descripcion   = $confirmar['descripcion'];
									$cantidad      = $confirmar['cantidad'];
									$area          = $confirmar['area_nom'];
									$id_area       = $confirmar['id_area'];
									$id_user       = $confirmar['id_user'];

									$ver = ($confirmar['confirmado'] != 0) ? 'd-none' : '';

									?>
									<tr class="text-center fila<?=$id_inventario . ' ' . $ver?>">
										<td><?=$area?></td>
										<td><?=$descripcion?></td>
										<td><?=$cantidad?></td>
										<td>
											<textarea class="form-control observacion<?=$id_inventario?>"></textarea>
										</td>
										<td>
											<div class="btn-group" role="group" aria-label="Basic example">
												<button class="btn btn-danger btn-sm no_confirmar_inv" id="<?=$id_inventario?>" data-tooltip="tooltip" data-placement="bottom" title="No Confirmar" type="button" data-nombre="<?=$descripcion?>" data-log="<?=$id_log?>" data-area="<?=$id_area?>">
													<i class="fas fa-minus"></i>
												</button>
												<button class="btn btn-success btn-sm confirmar_inv" id="<?=$id_inventario?>" data-tooltip="tooltip" data-placement="bottom" title="Confirmar" data-user="<?=$id_user?>" type="button" data-session="0" data-nombre="<?=$descripcion?>" data-log="<?=$id_log?>" data-area="<?=$id_area?>">
													<i class="fas fa-check"></i>
												</button>
											</div>
										</td>
										<td>
											<button class="btn btn-success btn-sm agregar_inv" type="button" title="Advertencia!" data-popover="popover" data-placement="right" data-content="Al hacer clic se agregara una unidad del articulo <?=$descripcion?> en el area <?=$area?> y se marcara como PENDIENTE DE REVISION" data-trigger="hover" data-id="<?=$id_inventario?>">
												<i class="fa fa-plus"></i>
											</button>
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
<script src="<?=PUBLIC_PATH?>js/inventario/funcionesInventario.js"></script>