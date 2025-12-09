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

if (isset($_POST['buscar'])) {
	$datos_buscar     = array('tipo_buscar' => $_POST['tipo_buscar'], 'fecha' => $_POST['fecha'], 'buscar' => $_POST['buscar']);
	$datos_renovacion = $instancia->buscarRenovacionesDocumentosControl($datos_buscar);
} else {
	$datos_renovacion = $instancia->mostrarRenovacionesDocumentosControl();
}

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
						<a href="<?=BASE_URL?>recursos/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Listado Maestro de Documentos
					</h4>
					<?php
					$permisos = $instancia_permiso->permisosUsuarioControl(46, $perfil_log);
					if ($permisos) {
						?>
						<div class="btn-group">
							<a href="<?=BASE_URL?>recursos/cantidades" class="btn btn-secondary btn-sm">
								<i class="fa fa-eye"></i>
								&nbsp;
								Ver cantidades
							</a>
							<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#agregar_renovacion">
								<i class="fa fa-plus"></i>
								&nbsp;
								Agregar Documento
							</button>
						</div>
					<?php }?>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group">
								<select name="tipo_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Tipo Proceso" data-placement="top" data-trigger="hover">
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_tipo_gestion as $gestion) {
										$id_gestion = $gestion['id'];
										$nombre     = $gestion['nombre'];
										?>
										<option value="<?=$id_gestion?>"><?=$nombre?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<input type="date" class="form-control filtro_change" name="fecha" data-tooltip="tooltip" title="Fecha Vigencia" data-placement="top" data-trigger="hover">
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar"data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
									<div class="input-group-append">
										<button class="btn btn-primary btn-sm" type="submit">
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
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">Tipo de Proceso</th>
									<th scope="col">Nombre Documento</th>
									<th scope="col">Version Documento</th>
									<th scope="col">Fecha Vigencia</th>
									<th scope="col">Tipo Documento</th>
									<th scope="col">Gestion de Cambio</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_renovacion as $renovacion) {
									$id_documento   = $renovacion['id'];
									$tipo_proceso   = $renovacion['nom_tipo'];
									$nom_documento  = $renovacion['nom_documento'];
									$version_doc    = $renovacion['version_doc'];
									$fecha_vigencia = $renovacion['fecha_vigencia'];
									$fecha_revision = $renovacion['fecha_revision'];
									$categ_doc      = $renovacion['categ_doc'];
									$categ_doc_nom  = $renovacion['categ'];
									$gestion_cambio = $renovacion['gestion_cambio'];
									$id_user        = $renovacion['id_user'];

									$evidencia     = ($categ_doc == 1) ? $renovacion['url_archivo'] : PUBLIC_PATH . 'upload' . DS . $renovacion['evidencia'];
									$documento_old = $renovacion['evidencia'];

									if (empty($renovacion['url_archivo']) && $categ_doc == 1) {
										$evidencia_ver = 'd-none';
										$span          = '<span class="badge badge-danger">Falta archivo o url</span>';
									}

									if (empty($renovacion['evidencia']) && $categ_doc == 2) {
										$evidencia_ver = 'd-none';
										$span          = '<span class="badge badge-danger">Falta archivo o url</span>';
									}

									if (!empty($renovacion['url_archivo']) && $categ_doc == 1) {
										$evidencia_ver = '';
										$span          = '';
									}

									if (!empty($renovacion['evidencia']) && $categ_doc == 2) {
										$evidencia_ver = '';
										$span          = '';
									}

									?>
									<tr class="text-center documento_<?=$id_documento?>">
										<td><?=$tipo_proceso?></td>
										<td><?=$nom_documento?></td>
										<td><?=$version_doc?></td>
										<td><?=$fecha_vigencia?></td>
										<td><?=$categ_doc_nom?></td>
										<td><?=$gestion_cambio?></td>
										<td><?=$span?></td>
										<td>
											<div class="btn-group">
												<a href="<?=$evidencia?>" class="btn btn-secondary btn-sm <?=$evidencia_ver?>" target="_blank" data-tooltip="tooltip" title="Ver evidencia" data-placement="bottom" data-trigger="hover">
													<i class="fa fa-eye"></i>
												</a>
												<button class="btn btn-primary btn-sm editar" type="button" data-tooltip="tooltip" title="Editar" data-placement="bottom" data-trigger="hover" data-toggle="modal" data-target="#documento<?=$id_documento?>" id="<?=$id_documento?>" data-categoria="<?=$categ_doc?>">
													<i class="fa fa-edit"></i>
												</button>
												<button class="btn btn-danger btn-sm eliminar" id="<?=$id_documento?>" data-log="<?=$id_log?>" type="button" data-tooltip="tooltip" title="Eliminar Documento" data-placement="bottom">
													<i class="fa fa-trash"></i>
												</button>
											</div>
										</td>
									</tr>


									<div class="modal fade" id="documento<?=$id_documento?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Editar Documento</h5>
												</div>
												<div class="modal-body">
													<form method="POST" enctype="multipart/form-data">
														<input type="hidden" name="id_log" value="<?=$id_log?>">
														<input type="hidden" name="id_documento" value="<?=$id_documento?>">
														<input type="hidden" name="id_user" value="<?=$id_user?>">
														<input type="hidden" name="documento_old" value="<?=$documento_old?>">
														<div class="row p-2">
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Tipo de proceso <span class="text-danger">*</span></label>
																<select name="tipo_edit" class="form-control" required>
																	<?php
																	foreach ($datos_tipo_gestion as $gestion) {
																		$id_gestion = $gestion['id'];
																		$nombre     = $gestion['nombre'];

																		$select = ($renovacion['tipo_proceso'] == $id_gestion) ? 'selected' : '';
																		?>
																		<option value="<?=$id_gestion?>" <?=$select?>><?=$nombre?></option>
																	<?php }?>
																</select>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Nombre de documento <span class="text-danger">*</span></label>
																<input type="text" name="nombre_edit" class="form-control" value="<?=$nom_documento?>" required>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Version del documento <span class="text-danger">*</span></label>
																<input type="text" name="version_edit" class="form-control" value="<?=$version_doc?>" required>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha Vigencia <span class="text-danger">*</span></label>
																<input type="date" name="fecha_vigencia_edit" class="form-control" value="<?=$fecha_vigencia?>" required>
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Fecha Revision</label>
																<input type="date" name="fecha_revision_edit" class="form-control" value="<?=$fecha_revision?>">
															</div>
															<div class="col-lg-6 form-group">
																<label class="font-weight-bold">Categoria del documento <span class="text-danger">*</span></label>
																<select class="form-control cate_doc_edit" name="cate_doc_edit" id="<?=$id_documento?>" required>
																	<?php
																	$virtual_sel = ($categ_doc == 1) ? 'selected' : '';
																	$fisico_sel  = ($categ_doc == 2) ? 'selected' : '';
																	?>
																	<option value="1" <?=$virtual_sel?>>Virtual</option>
																	<option value="2" <?=$fisico_sel?>>Fisico</option>
																</select>
															</div>
															<div class="col-lg-12 form-group">
																<label class="font-weight-bold">Gestion del cambio</label>
																<textarea class="form-control" rows="5" name="gestion_edit"><?=$gestion_cambio?></textarea>
															</div>
															<div class="col-lg-12 form-group url_archivo_edit_<?=$id_documento?>">
																<label class="font-weight-bold">URL del archivo</label>
																<input type="text" name="url_archivo_edit" class="form-control" value="<?=$renovacion['url_archivo']?>">
															</div>
															<div class="form-group col-lg-12 file_doc_edit_<?=$id_documento?>">
																<label class="font-weight-bold">Evidencia</label>
																<input type="file" name="archivo_edit" class="form-control">
															</div>
															<div class="col-lg-12 form-group text-right mt-2">
																<button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
																	<i class="fa fa-times"></i>
																	&nbsp;
																	Cancelar
																</button>
																<button class="btn btn-primary btn-sm" type="submit">
																	<i class="fa fa-save"></i>
																	&nbsp;
																	Guardar
																</button>
															</div>
														</div>
													</form>
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
			</div>
		</div>
	</div>
</div>
<?php
require_once VISTA_PATH . 'script_and_final.php';
require_once VISTA_PATH . 'modulos' . DS . 'recursos' . DS . 'agregarRenovacion.php';

if (isset($_POST['nombre'])) {
	$instancia->agregarRenovacionDocumentoControl();
}

if (isset($_POST['id_documento'])) {
	$instancia->actualizarRenovacionDocumentoControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/recursos/funcionesRecursos.js"></script>