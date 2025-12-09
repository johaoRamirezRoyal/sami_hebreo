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

	header('Location:../../login?er=' . $error);

	exit();

}

include_once VISTA_PATH . 'cabeza.php';

include_once VISTA_PATH . 'navegacion.php';

require_once CONTROL_PATH . 'biblioteca' . DS . 'ControlBiblioteca.php';



$instancia = ControlBiblioteca::singleton_biblioteca();



$permisos = $instancia_permiso->permisosUsuarioControl(48, $perfil_log);

if (!$permisos) {

	include_once VISTA_PATH . 'modulos' . DS . '403.php';

	exit();

}



if (isset($_GET['libro'])) {



	$id_libro = base64_decode($_GET['libro']);



	$datos_libro         = $instancia->mostrarInformacionLibroControl($id_libro);

	$datos_categoria     = $instancia->mostrarCategoriasBibliotecaControl();

	$datos_ejemplares    = $instancia->mostrarEjemplaresControl($id_libro);

	$cantidad_ejemplares = $instancia->cantidadEjemplaresDisponiblesPrestadosLibroControl($id_libro);



	$portada = ($datos_libro['foto'] != '') ? 'upload/' . $datos_libro['foto'] : 'img/Portada.jpg';



	$fecha_utlima = ($datos_libro['fecha_actualizacion'] == '') ? $datos_libro['fechareg'] : $datos_libro['fecha_actualizacion'];

	?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-lg-12">

				<div class="card shadow-sm mb-4">

					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

						<h4 class="m-0 font-weight-bold text-primary">

							<a href="<?=BASE_URL?>biblioteca/libros/index" class="text-decoration-none">

								<i class="fa fa-arrow-left text-primary"></i>

							</a>

							&nbsp;

							Informacion del libro - <span class="text-uppercase">(<?=$datos_libro['titulo']?>)</span>

						</h4>

						<h6 class="text-primary font-weight-bold mt-2">

							Fecha de ultima actualizacion - <?=$fecha_utlima?>

						</h6>

					</div>

					<div class="card-body">

						<form method="POST" enctype="multipart/form-data">

							<input type="hidden" name="id_log" value="<?=$id_log?>">

							<input type="hidden" name="id_libro" value="<?=$id_libro?>">

							<input type="hidden" name="foto_portada_ant" value="<?=$datos_libro['foto']?>">

							<div class="row p-2">

								<div class="col-lg-4 form-group text-center bg-light rounded">

									<img src="<?=PUBLIC_PATH . $portada?>" alt="" class="img-fluid">

								</div>

								<div class="col-lg-8 form-group">

									<div class="row">

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Titulo <span class="text-danger">*</span></label>

											<input type="text" class="form-control" name="titulo" required value="<?=$datos_libro['titulo']?>">

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Autor</label>

											<input type="text" class="form-control" name="autor" value="<?=$datos_libro['autor']?>">

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Editorial</label>

											<input type="text" class="form-control" name="editorial" value="<?=$datos_libro['editorial']?>">

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Edicion o a&ntilde;o publicacion</label>

											<input type="text" class="form-control" name="edicion" value="<?=$datos_libro['edicion']?>">

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Categoria <span class="text-danger">*</span></label>

											<select class="form-control" name="categoria" id="categoria" required>

												<?php

												foreach ($datos_categoria as $categoria) {

													$id_categoria  = $categoria['id'];

													$nom_categoria = $categoria['nombre'];



													$select = ($datos_libro['id_categoria'] == $id_categoria) ? 'selected' : '';



													?>

													<option value="<?=$id_categoria?>" <?=$select?>><?=$nom_categoria?></option>

												<?php }?>

											</select>

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Sub-Categoria</label>

											<select class="form-control" name="subcategoria" id="subcategoria">

												<option value="<?=$datos_libro['id_subcategoria']?>"><?=$datos_libro['nom_subcategoria']?></option>

											</select>

										</div>

										<div class="col-lg-6 form-group">

											<label class="font-weight-bold">Foto de portada</label>

											<input type="file" class="form-control file" accept="image/*" id="file" name="portada">

										</div>

									</div>

								</div>

								<div class="col-lg-12 form-group mt-2">

									<label class="font-weight-bold">Observacion</label>

									<textarea name="observacion" class="form-control" rows="5"><?=$datos_libro['observacion']?></textarea>

								</div>

								<div class="col-lg-6 form-group text-left mt-2">

									<a href="<?=BASE_URL?>imprimir/biblioteca/codigosHtml?libro=<?=base64_encode($id_libro)?>" target="_blank" class="btn btn-secondary btn-sm">

										<i class="fa fa-print"></i>

										&nbsp;

										Imprimir Codigos

									</a>

								</div>

								<div class="col-lg-6 form-group text-right mt-2">

									<button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#agregar_ejemplar">

										<i class="fa fa-plus"></i>

										&nbsp;

										Agregar ejemplar

									</button>

									<button class="btn btn-primary btn-sm">

										<i class="fa fa-edit"></i>

										&nbsp;

										Actualzar informacion

									</button>

									<button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#eliminar_libro">
                                
                                        <i class="fa fa-times"></i>
                                
                                        &nbsp;
                                
                                        Dar de baja
                                
                                    </button>

								</div>

							</div>

						</form>

						<div class="table-responsive mt-3">

							<div class="btn-group mb-4">

								<button class="btn btn-danger btn-sm inactivar_ejemplar" type="button">

									<i class="fa fa-times"></i>

									&nbsp;

									Eliminar ejemplares

								</button>

							</div>

							<div class="row">

								<div class="col-lg-6 text-center">

									<h5 class="text-success font-weight-bold">Cantidad Ejemplares Disponibles: <?=$cantidad_ejemplares['disponibles']?></h5>

								</div>

								<div class="col-lg-6 text-center">

									<h5 class="text-danger font-weight-bold">Cantidad Ejemplares Prestados: <?=$cantidad_ejemplares['prestados']?></h5>

								</div>

							</div>

							<form method="POST" id="form_ejemplar">

								<table class="table table-hover border table-sm" cellspacing="0">

									<thead>

										<tr class="text-center font-weight-bold">

											<th colspan="7">listado de ejemplares</th>

										</tr>

										<tr class="text-center font-weight-bold">

											<th></th>

											<th>No. Ejemplar</th>

											<th>Codigo</th>

											<th>Categoria</th>

											<th>Sub-Categoria</th>

											<th>Estado</th>

										</tr>

									</thead>

									<tbody class="buscar">

										<?php

										$cont = 1;

										foreach ($datos_ejemplares as $ejem) {

											$id_ejem = $ejem['id'];

											$codigo  = $ejem['codigo'];

											$estado  = $ejem['estado'];



											if ($estado == 1) {

												$span = '<span class="badge badge-success">Disponible</span>';

											}

											if ($estado == 2) {

												$span = '<span class="badge badge-secondary">Prestado</span>';

											}

											if ($estado == 3) {

												$span = '<span class="badge badge-danger">Descartado</span>';

											}

											?>

											<tr class="text-center">

												<td>

													<input type="hidden" name="id_log" value="<?=$id_log?>">

													<input type="hidden" name="id_libro" value="<?=$id_libro?>">

													<input type="checkbox" value="<?=$id_ejem?>" name="check_libro[]" class="custom-checkbox check">

												</td>

												<td><?=$cont++?></td>

												<td><?=$codigo?></td>

												<td><?=$datos_libro['nom_categoria']?></td>

												<td><?=$datos_libro['nom_subcategoria']?></td>

												<td><?=$span?></td>

												<td>

													<a href="<?=BASE_URL?>imprimir/biblioteca/codigoEjemplar?libro=<?=base64_encode($id_libro)?>&ejemplar=<?=base64_encode($id_ejem)?>" class="btn btn-secondary btn-sm" target="_blank">

														<i class="fas fa-barcode"></i>

														&nbsp;

														Codigo

													</a>

												</td>

											</tr>

											<?php

										}

										?>

									</tbody>

								</table>

							</form>

							<div class="btn-group mb-4">

								<button class="btn btn-danger btn-sm inactivar_ejemplar" type="button">

									<i class="fa fa-times"></i>

									&nbsp;

									Eliminar ejemplares

								</button>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>



	<!-- Modal -->

	<div class="modal fade" id="agregar_ejemplar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar Ejemplar</h5>

				</div>

				<div class="modal-body">

					<form method="POST">

						<input type="hidden" name="id_libro" value="<?=$id_libro?>">

						<input type="hidden" name="id_log" value="<?=$id_log?>">

						<input type="hidden" name="id_categoria" value="<?=$datos_libro['id_categoria']?>">

						<input type="hidden" name="id_subcategoria" value="<?=($datos_libro['id_subcategoria'] == 0) ? '' : $datos_libro['id_subcategoria']?>">

						<div class="row p-2">

							<div class="col-lg-12 form-group">

								<label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>

								<input type="text" name="cantidad" class="form-control numeros">

							</div>

							<div class="col-lg-12 text-right form-group mt-2">

								<button class="btn btn-danger btn-sm" type="reset" data-dismiss="modal">

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


		<!-- Modal eliminar libro-->

	<div class="modal fade" id="eliminar_libro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Eliminar libro</h5>

				</div>

				<div class="modal-body">

					<form method="POST">

						<input type="hidden" name="id_libro" value="<?=$id_libro?>">

						<input type="hidden" name="id_activo" value="0">

						<div class="row p-2">

							<div class="col-lg-12 form-group">
										<center>
										<h1>¿Seguro que desea eliminar el libro?</h1>
										</center>
								
							</div>

							<div class="col-lg-12 text-center form-group mt-2">

								<button class="btn btn-danger btn-sm" type="reset" data-dismiss="modal">

									<i class="fa fa-times"></i>

									&nbsp;

									Cancelar

								</button>

								<button class="btn btn-primary btn-sm" type="submit" name="modal_eliminar_libro">

									<i class="fa fa-save"></i>

									&nbsp;

									Aceptar

								</button>

							</div>

						</div>

					</form>

				</div>

			</div>

		</div>

	</div>




	<?php

	include_once VISTA_PATH . 'script_and_final.php';



	if (isset($_POST['titulo'])) {

		$instancia->actualizarLibroControl();

	}



	if (isset($_POST['cantidad'])) {

		$instancia->agregarEjemplarControl();

	}



	if (isset($_POST['check_libro'])) {

		$instancia->inactivarEjemplaresControl();

	}

	if (isset($_POST['id_activo'])) {

		$instancia->eliminarLibro();

	}



}

?>

<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesBiblioteca.js"></script>