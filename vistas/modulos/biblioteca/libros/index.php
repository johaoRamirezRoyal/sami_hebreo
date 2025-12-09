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

$datos_categoria     = $instancia->mostrarCategoriasBibliotecaControl();
$cantidad_ejemplares = $instancia->cantidadEjemplaresDisponiblesPrestadosControl();

if (isset($_POST['buscar'])) {
	$subcategoria = (isset($_POST['subcategoria'])) ? $_POST['subcategoria'] : '';
	$datos        = array('categoria' => $_POST['categoria'], 'subcategoria' => $subcategoria, 'buscar' => $_POST['buscar']);
	$datos_libros = $instancia->buscarLibrosControl($datos);
} else {
	$datos_libros = $instancia->mostrarLibrosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(48, $perfil_log);
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
						<a href="<?=BASE_URL?>biblioteca/index" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Libros
					</h4>
					<a href="<?=BASE_URL?>biblioteca/libros/agregar_libro" class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i>
						&nbsp;
						Agregar nuevo libro
					</a>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 form-group">
								<select class="form-control" name="categoria" id="categoria" data-tooltip="tooltip" title="Categoria">
									<option value="" selected>Seleccione una categoria...</option>
									<?php
									foreach ($datos_categoria as $categoria) {
										$id_categoria  = $categoria['id'];
										$nom_categoria = $categoria['nombre'];
										?>
										<option value="<?=$id_categoria?>"><?=$nom_categoria?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<select class="form-control" name="subcategoria" id="subcategoria" data-tooltip="tooltip" title="Sub-ategoria">
								</select>
							</div>
							<div class="form-group col-lg-4">
								<div class="input-group">
									<input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">
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
					<div class="row">
						<div class="col-lg-6 text-center">
							<h5 class="text-success font-weight-bold">Cantidad Ejemplares Disponibles: <?=$cantidad_ejemplares['disponibles']?></h5>
						</div>
						<div class="col-lg-6 text-center">
							<h5 class="text-danger font-weight-bold">Cantidad Ejemplares Prestados: <?=$cantidad_ejemplares['prestados']?></h5>
						</div>
					</div>
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">No. Libro</th>
									<th scope="col">Titulo</th>
									<th scope="col">Autor</th>
									<th scope="col">Editorial</th>
									<th scope="col">Edicion</th>
									<th scope="col">Categoria</th>
									<th scope="col">Subcategoria</th>
									<th scope="col">Observacion</th>
									<th scope="col">Cantidad ejemplares</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_libros as $libro) {
									$id_libro         = $libro['id'];
									$titulo           = $libro['titulo'];
									$autor            = $libro['autor'];
									$editorial        = $libro['editorial'];
									$edicion          = $libro['edicion'];
									$nom_categoria    = $libro['nom_categoria'];
									$nom_subcategoria = $libro['nom_subcategoria'];
									$observacion      = $libro['observacion'];
									$cantidad_ejem    = $libro['cantidad_ejemplares'];

									if (!empty($id_libro)) {
										?>
										<tr class="text-center">
											<td><?=$id_libro?></td>
											<td>
												<a href="<?=BASE_URL?>biblioteca/libros/informacion?libro=<?=base64_encode($id_libro)?>">
													<?=strtoupper($titulo)?>
												</a>
											</td>
											<td><?=$autor?></td>
											<td><?=$editorial?></td>
											<td><?=$edicion?></td>
											<td><?=$nom_categoria?></td>
											<td><?=$nom_subcategoria?></td>
											<td><?=$observacion?></td>
											<td><?=$cantidad_ejem?></td>
											<td>
												<div class="btn-group">
													<a href="<?=BASE_URL?>biblioteca/libros/informacion?libro=<?=base64_encode($id_libro)?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver informacion" data-trigger="hover" data-placement="bottom">
														<i class="fa fa-eye"></i>
													</a>
												</div>
											</td>
										</tr>
										<?php
									} else {
										?>
										<tr class="text-center">
											<td colspan="8">No se encontraron resultados</td>
										</tr>
										<?php
									}
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
<script src="<?=PUBLIC_PATH?>js/biblioteca/funcionesBiblioteca.js"></script>