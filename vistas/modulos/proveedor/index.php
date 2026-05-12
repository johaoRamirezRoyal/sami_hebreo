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

if (isset($_POST['buscar'])) {
	$datos_proveedor = $instancia->buscarProveedorControl($_POST['buscar']);
} else {
	$datos_proveedor = $instancia->mostrarProveedoresControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
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
					<h4 class="m-0 font-weight-bold text-hebreo">
						<a href="<?=BASE_URL?>compras/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-hebreo"></i>
						</a>
						&nbsp;
						Proveedores
					</h4>
					<div class="btn-group">
						<a href="<?=BASE_URL?>proveedor/registro" class="btn btn-hebreo btn-sm">
							<i class="fa fa-plus"></i>
							&nbsp;
							Agregar Proveedor
						</a>
					</div>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-8 form-inline">
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
									<div class="input-group-append">
										<button class="btn btn-hebreo btn-sm" type="submit">
											<i class="fa fa-search"></i>
											&nbsp;
											Buscar
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-responsive mt-2">
						<table class="table table-hover table-sm border" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">Fecha ingreso</th>
									<th scope="col">Nombre o Razon social</th>
									<th scope="col">Identificacion</th>
									<th scope="col">Telefono</th>
									<th scope="col">Contacto</th>
									<th scope="col">Telefono contacto</th>
									<th scope="col">Detalle producto</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_proveedor as $proveedor) {
									$id_proveedor   = $proveedor['id_proveedor'];
									$nombre         = $proveedor['razon_social'];
									$identificacion = $proveedor['num_identificacion'];
									$telefono       = $proveedor['telefono'];
									$nom_contacto   = $proveedor['contacto'];
									$tel_contacto   = $proveedor['telefono_contacto'];
									$detalle        = $proveedor['detalle_producto'];
									$fecha_ingreso  = $proveedor['fecha_ingreso'];

									$documentos_proveedor = $instancia->validarDocumentosProveedorControl($id_proveedor);
									$evaluacion_proveedor = $instancia->validarEvaluacionProveedorControl($id_proveedor);

									$ver_documentos = ($documentos_proveedor['contar'] == 3) ? 'd-none' : '';

									$ver_evaluacion      = ($evaluacion_proveedor['id'] == '' && $documentos_proveedor['contar'] >= 3) ? '' : 'd-none';
									$ver_span_evaluacion = ($evaluacion_proveedor['id'] != '') ? '' : 'd-none';

									?>
									<tr class="text-center text-uppercase text-dark">
										<td><?=$fecha_ingreso?></td>
										<td>
											<a href="<?=BASE_URL?>proveedor/hojaRegistro?proveedor=<?=base64_encode($id_proveedor)?>"><?=$nombre?></a>
										</td>
										<td><?=$identificacion?></td>
										<td><?=$telefono?></td>
										<td><?=$nom_contacto?></td>
										<td><?=$tel_contacto?></td>
										<td><?=$detalle?></td>
										<td class="<?=$ver_span_evaluacion?>">
											<span class="badge badge-success">Evaluacion realizada</span>
										</td>
										<td>
											<div class="btn-group">
												<a href="<?=BASE_URL?>proveedor/hojaRegistro?proveedor=<?=base64_encode($id_proveedor)?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver hoja de registro" data-placement="bottom" data-trigger="hover">
													<i class="fa fa-eye"></i>
												</a>
												<a href="<?=BASE_URL?>proveedor/evaluacion?proveedor=<?=base64_encode($id_proveedor)?>" class="btn btn-success btn-sm <?=$ver_evaluacion?>" data-tooltip="tooltip" data-placement="bottom" title="Evaluacion (<?=date('Y')?>)">
													<i class="fas fa-clipboard-list"></i>
												</a>
												<a href="<?=BASE_URL?>proveedor/documentos?proveedor=<?=base64_encode($id_proveedor)?>" class="btn btn-hebreo btn-sm <?=$ver_documentos?>" data-tooltip="tooltip" data-placement="bottom" title="Subir Documentos">
													<i class="fas fa-upload"></i>
												</a>
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
?>