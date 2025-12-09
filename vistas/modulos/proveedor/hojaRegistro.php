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

$datos_proveedor = $instancia->mostrarProveedoresControl();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
	include_once VISTA_PATH . 'modulos' . DS . '403.php';
	exit();
}

if (isset($_GET['proveedor'])) {

	$id_proveedor = base64_decode($_GET['proveedor']);

	$datos_proveedor        = $instancia->mostrarDatosProveedorIdControl($id_proveedor);
	$contactos_proveedor    = $instancia->mostrarContactosProveedorControl($id_proveedor);
	$banco_proveedor        = $instancia->mostrarBancoProveedorControl($id_proveedor);
	$documentos_proveedor   = $instancia->mostrarDocumentosProveedorControl($id_proveedor);
	$calificacion_proveedor = $instancia->mostrarCalificacionProveedorControl($id_proveedor);
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card shadow-sm mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h4 class="m-0 font-weight-bold text-hebreo">
							<a href="<?=BASE_URL?>proveedor/index"  class="text-decoration-none">
								<i class="fa fa-arrow-left text-hebreo"></i>
							</a>
							&nbsp;
							Hoja de registro (<?=$datos_proveedor['nombre']?>)
						</h4>
						<h6 class="text-right mt-2 font-weight-bold  text-hebreo">Fecha de ultima actualizacion: <?=$datos_proveedor['fechareg']?></h6>
					</div>
					<div class="card-body">
						<form method="POST">
							<input type="hidden" value="<?=$id_log?>" name="id_log" id="id_log">
							<input type="hidden" value="<?=$id_proveedor?>" name="id_proveedor" id="id_proveedor">
							<div class="row">

								<div class="form-group col-lg-12 text-center">
									<h5 class="font-weight-bold">INFORMACION DEL PROVEEDOR EXTERNO</h5>
									<hr>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Nombre o razon social <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="nombre" value="<?=$datos_proveedor['nombre']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Identificacion <span class="text-danger">*</span></label>
									<select class="form-control" name="identificacion" required>
										<option value="<?=$datos_proveedor['identificacion']?>" class="d-none" selected><?=$datos_proveedor['identificacion']?></option>
										<option value="Nit">Nit</option>
										<option value="Cedula de ciudadania">Cedula de ciudadania</option>
										<option value="Cedula de extranjeria">Cedula de extranjeria</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Numero de identificacion <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="num_identificacion" value="<?=$datos_proveedor['num_identificacion']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Direccion <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="direccion" value="<?=$datos_proveedor['direccion']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Ciudad <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="ciudad" value="<?=$datos_proveedor['ciudad']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Departamento <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="departamento" value="<?=$datos_proveedor['departamento']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Pais <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="pais" value="<?=$datos_proveedor['pais']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="telefono" value="<?=$datos_proveedor['telefono']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
									<input type="text" class="form-control" maxlength="50" required name="correo" value="<?=$datos_proveedor['correo']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Fecha ingreso <span class="text-danger">*</span></label>
									<input type="date" class="form-control" maxlength="50" required name="fecha_ingreso" value="<?=$datos_proveedor['fecha_ingreso']?>">
								</div>


								<div class="form-group col-lg-12 text-center mt-5">
									<h5 class="font-weight-bold">INFORMACION DEL PRODUCTO</h5>
									<hr>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Tipo</label>
									<select class="form-control" name="tipo">
										<option value="<?=$datos_proveedor['tipo']?>" class="d-none"><?=$datos_proveedor['tipo']?></option>
										<option value="Bien">Bien</option>
										<option value="Servicio">Servicio</option>
										<option value="Bien y Servicio">Bien y Servicio</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Tiempo de entrega (Dias)</label>
									<input type="text" class="form-control numeros" maxlength="50" name="tiempo_entrega" value="<?=$datos_proveedor['tiempo_entrega']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Garantia (Dias/Meses)</label>
									<input type="text" class="form-control numeros" maxlength="50" name="garantia" value="<?=$datos_proveedor['garantia']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Plazo de pago (Dias/Meses)</label>
									<input type="text" class="form-control numeros" maxlength="50" name="plazo_pago" value="<?=$datos_proveedor['plazo_pago']?>">
								</div>
								<div class="form-group col-lg-12">
									<label class="font-weight-bold">Detalle del producto</label>
									<textarea class="form-control" rows="5" name="detalle_producto"><?=$datos_proveedor['detalle_producto']?></textarea>
								</div>



								<div class="form-group col-lg-12 text-center mt-5">
									<h5 class="font-weight-bold">INFORMACION DEL REPRESENTANTE LEGAL</h5>
									<hr>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Nombre completo</label>
									<input type="text" class="form-control" name="nom_representante" value="<?=$datos_proveedor['nom_representante']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Identificacion</label>
									<input type="text" class="form-control numeros" name="identificacion_representante" value="<?=$datos_proveedor['identificacion_representante']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Correo electronico</label>
									<input type="email" class="form-control" name="correo_representante" value="<?=$datos_proveedor['correo_representante']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Telefono</label>
									<input type="text" class="form-control" name="telefono_representante" value="<?=$datos_proveedor['telefono_representante']?>">
								</div>


								<div class="form-group col-lg-12 text-center mt-5">
									<h5 class="font-weight-bold">INFORMACIÓN TRIBUTARIA</h5>
									<hr>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Regimen</label>
									<select class="form-control" name="regimen_proveedor">
										<option value="<?=$datos_proveedor['regimen_proveedor']?>" selected class="d-none"><?=$datos_proveedor['regimen_proveedor']?></option>
										<option value="Comun">Comun</option>
										<option value="Simplificado">Simplificado</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Gran contribuyente</label>
									<select class="form-control" name="contribuyente_proveedor">
										<option value="<?=$datos_proveedor['contribuyente_proveedor']?>" selected class="d-none"><?=$datos_proveedor['contribuyente_proveedor']?></option>
										<option value="Si">Si</option>
										<option value="No">No</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Autoretenedor</label>
									<select class="form-control" name="autoretenedor_proveedor">
										<option value="<?=$datos_proveedor['autoretenedor_proveedor']?>" selected class="d-none"><?=$datos_proveedor['autoretenedor_proveedor']?></option>
										<option value="Si">Si</option>
										<option value="No">No</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Responsable industria y comercio</label>
									<select class="form-control" name="comercio_proveedor">
										<option value="<?=$datos_proveedor['comercio_proveedor']?>" selected class="d-none"><?=$datos_proveedor['comercio_proveedor']?></option>
										<option value="Si">Si</option>
										<option value="No">No</option>
									</select>
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Actividad economica</label>
									<input type="text" class="form-control" name="actividad_proveedor" value="<?=$datos_proveedor['actividad_proveedor']?>">
								</div>
								<div class="form-group col-lg-4">
									<label class="font-weight-bold">Tarifa</label>
									<input type="text" class="form-control" name="tarifa_proveedor" value="<?=$datos_proveedor['tarifa_proveedor']?>">
								</div>


							<div class="col-lg-12 form-group mt-4">
								<div class="table-responsive">
									<table class="table table-hover border" width="100%" cellspacing="0">
										<thead>
											<tr class="text-center font-weight-bold">
												<th colspan="5">
													INFORMACIÓN DE CONTACTOS
												</th>
												<th>
													<button type="button" class="btn btn-hebreo btn-sm float-right" data-tooltip="tooltip" data-placement="left" title="Agregar contacto" data-toggle="modal" data-target="#agregar_contacto">
														<i class="fa fa-plus"></i>
													</button>
												</th>
											</tr>
											<tr class="text-center font-weight-bold">
												<th>Nombre</th>
												<th>Telefono</th>
												<th>Correo</th>
												<th>Cargo</th>
											</tr>
										</thead>
										<tbody class="buscar text-uppercase">
											<?php
											foreach ($contactos_proveedor as $contacto) {
												$id_contacto = $contacto['id'];
												$nombre      = $contacto['nombre_contacto'];
												$telefono    = $contacto['telefono_contacto'];
												$correo      = $contacto['correo_contacto'];
												$cargo       = $contacto['cargo_contacto'];
												$activo      = $contacto['activo'];

												$ver = ($activo == 1) ? '' : 'd-none';
												?>
												<tr class="text-center text-dark <?=$ver?> contacto<?=$id_contacto?>">
													<td><?=$nombre?></td>
													<td><?=$telefono?></td>
													<td><?=$correo?></td>
													<td><?=$cargo?></td>
													<td>
														<button type="button" class="btn btn-danger btn-sm eliminar_contacto" data-tooltip="tooltip" data-placement="bottom" title="Eliminar" id="<?=$id_contacto?>">
															<i class="fa fa-times"></i>
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



							<div class="col-lg-12 form-group mt-4">
								<div class="table-responsive">
									<table class="table table-hover border" width="100%" cellspacing="0">
										<thead>
											<tr class="text-center font-weight-bold">
												<th colspan="4">
													INFORMACIÓN BANCARIA PARA EFECTUAR PAGOS
												</th>
												<th>
													<button type="button" class="btn btn-hebreo btn-sm float-right" data-tooltip="tooltip" data-placement="left" title="Agregar banco" data-toggle="modal" data-target="#agregar_banco">
														<i class="fa fa-plus"></i>
													</button>
												</th>
											</tr>
											<tr class="text-center font-weight-bold">
												<th>Nombre</th>
												<th>Numero de cuenta</th>
												<th>Tipo de cuenta</th>
											</tr>
										</thead>
										<tbody class="buscar text-uppercase">
											<?php
											foreach ($banco_proveedor as $banco) {
												$id_banco = $banco['id'];
												$nombre   = $banco['nom_banco'];
												$numero   = $banco['num_banco'];
												$tipo     = $banco['tipo_cuenta'];
												$activo   = $banco['activo'];

												$ver = ($activo == 1) ? '' : 'd-none';
												?>
												<tr class="text-center text-dark <?=$ver?> banco<?=$id_banco?>">
													<td><?=$nombre?></td>
													<td><?=$numero?></td>
													<td><?=$tipo?></td>
													<td>
														<button type="button" class="btn btn-danger btn-sm eliminar_banco" data-tooltip="tooltip" data-placement="bottom" title="Eliminar" id="<?=$id_banco?>">
															<i class="fa fa-times"></i>
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


							<div class="col-lg-12 form-group mt-4">
								<div class="table-responsive">
									<table class="table table-hover border" width="100%" cellspacing="0">
										<thead>
											<tr class="text-center font-weight-bold">
												<th colspan="2">
													DOCUMENTACION LEGAL
												</th>
											</tr>
											<tr class="text-center font-weight-bold">
												<th>Tipo de documento</th>
											</tr>
										</thead>
										<tbody class="buscar text-uppercase">
											<?php
											foreach ($documentos_proveedor as $documento) {
												$id_documento = $documento['id'];
												$nombre       = $documento['nom_documento'];
												$activo       = $documento['activo'];
												$descargar    = $documento['nombre'];

												$ver = ($activo == 1) ? '' : 'd-none';
												?>
												<tr class="text-center text-dark <?=$ver?>">
													<td><?=$nombre?></td>
													<td>
														<div class="btn-group">
															<a class="btn btn-hebreo btn-sm" href="<?=BASE_URL?>proveedor/actualizarDocumento?proveedor=<?=base64_encode($id_proveedor)?>&documento=<?=base64_encode($id_documento)?>" data-tooltip="tooltip" data-placement="bottom" title="Cambiar archivo">
																<i class="fa fa-file"></i>
															</a>
														</div>
														<div class="btn-group">
															<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-tooltip="tooltip" title="Ver documento" data-trigger="hover" data-target="#rut_<?=$id_documento?>">
																<i class="fa fa-eye"></i>
															</button>
														</div>
														<div class="btn-group">
															<a class="btn btn-hebreo btn-sm" href="<?=PUBLIC_PATH?>upload/<?=$descargar?>" download="<?=$nombre?>_<?=$datos_proveedor['nombre']?>" target="_blank" data-tooltip="tooltip" data-placement="bottom" title="Descargar archivo">
																<i class="fa fa-download"></i>
															</a>
														</div>
													</td>
													<!-- <td>
														<button type="button" class="btn btn-hebreo btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Subir">
															<i class="fa fa-upload"></i>
														</button>
													</td> -->
												</tr>


												<div class="modal fade" id="rut_<?=$id_documento?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-lg" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title font-weight-bold text-hebreo" id="exampleModalLabel"><?=$nombre?> - (<?=$datos_proveedor['nombre']?>)</h5>
																<button type="button" class="btn" data-dismiss="modal" aria-label="Close">
																	<i class="fa fa-times"></i>
																</button>
															</div>
															<div class="modal-body">
																<div class="row">
																	<div class="col-lg-12 text-center form-group">
																		<?php
																		$ext = pathinfo($descargar, PATHINFO_EXTENSION);
																		if ($ext == 'pdf') {
																			?>
																			<iframe src="<?=PUBLIC_PATH?>upload/<?=$descargar?>" style="width:100%; height:550px;" frameborder="0" class="img-fluid text-center"></iframe>
																			<?php
																		} else {
																			?>
																			<img src="<?=PUBLIC_PATH?>upload/<?=$descargar?>" alt="" width="550">
																			<?php
																		}
																		?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>


							<div class="col-lg-12 form-group mt-4">
								<div class="table-responsive">
									<table class="table table-hover border" width="100%" cellspacing="0">
										<thead>
											<tr class="text-center font-weight-bold">
												<th colspan="3">
													EVALUACIONES REALIZADAS
												</th>
											</tr>
											<tr class="text-center font-weight-bold">
												<th>Año evaluado</th>
												<th>Calificacion total</th>
												<th>Criterio de confiabilidad</th>
												<th>Detalles</th>
											</tr>
										</thead>
										<tbody class="buscar text-uppercase">
											<?php
											foreach ($calificacion_proveedor as $calificacion) {
												$id_calificacion = $calificacion['id'];
												$anio_evaluado   = date('Y', strtotime($calificacion['fecha_evaluacion']));
												$total           = $calificacion['total'];

												$badge = '';

												if ($total >= 4.5) {
													$badge = '<span class="badge badge-primary p-2">Muy confiable</span>';
												} elseif ($total >= 4.0) {
													$badge = '<span class="badge badge-success p-2">Confiable</span>';
												} elseif ($total >= 3.6) {
													$badge = '<span class="badge badge-warning p-2">Aceptable</span>';
												} else {
													$badge = '<span class="badge badge-danger p-2">No confiable</span>';
												}

												?>
												<tr class="text-center text-dark">
													<td><?=$anio_evaluado?></td>
													<td><?=$total?></td>
													<td><?=$badge?></td>
													<td>
														<div class="btn-group">
															<a href="<?=BASE_URL?>proveedor/detallesEvaluacion?proveedor=<?=base64_encode($id_proveedor)?>&id_calificacion=<?=base64_encode($id_calificacion)?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver detalles" data-placement="bottom" data-trigger="hover">
																<i class="fa fa-eye"></i>
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


							<div class="form-group col-lg-12">
								<a href="<?=BASE_URL?>imprimir/hoja_registro?proveedor=<?=base64_encode($id_proveedor)?>" target="_blank" class="btn btn-secondary btn-sm float-left">
									<i class="fa fa-print"></i>
									&nbsp;
									Imprimir
								</a>
								<button type="submit" class="btn btn-hebreo btn-sm float-right">
									<i class="fa fa-save"></i>
									&nbsp;
									Guardar
								</button>
							</div>


						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="agregar_contacto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-success" id="exampleModalLabel">Agregar contacto</h5>
				</div>
				<form method="POST">
					<input type="hidden" name="id_log" value="<?=$id_log?>">
					<input type="hidden" name="id_proveedor" value="<?=$id_proveedor?>">
					<div class="modal-body border-0">
						<div class="row p-2">
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="nombre_contacto" required>
							</div>
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Telefono</label>
								<input type="text" class="form-control" name="telefono_contacto">
							</div>
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Correo electronico <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="correo_contacto" required>
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Cargo/Area <span class="text-danger">*</span></label>
								<select class="form-control" name="cargo_contacto" required>
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Asesor">Asesor</option>
									<option value="Contacto">Contacto</option>
									<option value="Compras/Finanzas">Compras/Finanzas</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
							<i class="fa fa-times"></i>
							&nbsp;
							Cerrar
						</button>
						<button type="submit" class="btn btn-hebreo btn-sm">
							<i class="fa fa-save"></i>
							&nbsp;
							Guardar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>



	<!-- Modal -->
	<div class="modal fade" id="agregar_banco" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-success" id="exampleModalLabel">Agregar banco</h5>
				</div>
				<form method="POST">
					<input type="hidden" name="id_log" value="<?=$id_log?>">
					<input type="hidden" name="id_proveedor" value="<?=$id_proveedor?>">
					<div class="modal-body border-0">
						<div class="row p-2">
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Nombre del banco <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="nom_banco" required>
							</div>
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Numero de la cuenta bancaria <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="num_banco" required>
							</div>
							<div class="col-lg-12 form-group">
								<label class="font-weight-bold">Tipo de cuenta <span class="text-danger">*</span></label>
								<select class="form-control" name="tipo_cuenta" required>
									<option value="" selected>Seleccione una opcion...</option>
									<option value="Ahorros">Ahorros</option>
									<option value="Corriente">Corriente</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
							<i class="fa fa-times"></i>
							&nbsp;
							Cerrar
						</button>
						<button type="submit" class="btn btn-hebreo btn-sm">
							<i class="fa fa-save"></i>
							&nbsp;
							Guardar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php
	include_once VISTA_PATH . 'script_and_final.php';

	if (isset($_POST['nombre'])) {
		$instancia->actualizarProveedorControl();
	}

	if (isset($_POST['nombre_contacto'])) {
		$instancia->agregarContactoControl();
	}

	if (isset($_POST['nom_banco'])) {
		$instancia->agregarBancoControl();
	}
}
?>
<script type="text/javascript" src="<?=PUBLIC_PATH?>js/proveedor/funcionesProveedor.js"></script>