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
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia          = ControlInventario::singleton_inventario();
$instancia_areas    = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$datos_areas   = $instancia_areas->mostrarAreasControl($id_super_empresa);
$datos_usuario = $instancia_usuarios->mostrarTodosUsuariosControl();

if (isset($_POST['area_buscar'])) {
	$datos  = array('area' => $_POST['area_buscar'], 'usuario' => $_POST['usuario_buscar'], 'buscar' => $_POST['buscar']);
	$buscar = $instancia->buscarInventarioNoConfirmadoControl($datos);
} else {
	$buscar = $instancia->mostrarInventarioNoConfirmadoControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(16, $perfil_log);

if (!$permisos) {
	include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
	exit();
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-primary">
						<a href="<?=BASE_URL?>inicio" class="text-decoration-none">
							<i class="fa fa-arrow-left text-primary"></i>
						</a>
						&nbsp;
						Confirmacion de inventario
					</h4>
				</div>
				<div class="card-body">
					<form method="POST">
						<div class="row p-2">
							<div class="form-group col-lg-4">
								<select name="area_buscar" id="" class="form-control filtro_change select2" data-tooltip="tooltip" title="Area" data-trigger="hover" data-placement="top">
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_areas as $areas) {
										$id_area     = $areas['id'];
										$nombre_area = $areas['nombre'];
										?>
										<option value="<?=$id_area?>"><?=$nombre_area?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<select name="usuario_buscar" class="form-control filtro_change select2" data-tooltip="tooltip" title="Usuario" data-trigger="hover" data-placement="top" >
									<option value="" selected>Seleccione una opcion...</option>
									<?php
									foreach ($datos_usuario as $usuarios) {
										$id_usuario  = $usuarios['id_user'];
										$nombre_user = $usuarios['nom_user'];

										$ver = ($areas['estado'] == 'inactivo') ? 'd-none' : '';
										?>
										<option value="<?=$id_usuario?>" class="<?=$ver?>"><?=$nombre_user?></option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="col-lg-4 form-group">
								<div class="input-group">
									<input type="text" class="form-control" name="buscar" placeholder="Buscar...">
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
						<div class="table-responsive mt-4">
							<table class="table table-hover border table-sm" width="100%" cellspacing="0">
								<thead>
									<tr class="text-center font-weight-bold">
										<th scope="col">USUARIO</th>
										<th scope="col">AREA</th>
										<th scope="col">DESCRIPCION</th>
										<th scope="col">MARCA</th>
										<th scope="col">CANTIDAD</th>
										<th scope="col">ESTADO</th>
										<th scope="col">OBSERVACION A VERIFICAR</th>
									</tr>
								</thead>
								<tbody class="buscar">
									<?php
									foreach ($buscar as $inventario) {
										$id_inventario = $inventario['id'];
										$nombre        = $inventario['descripcion'];
										$cantidad      = $inventario['cantidad'];
										$usuario       = $inventario['usuario'];
										$marca         = $inventario['marca'];
										$estado        = $inventario['estado_nombre'];
										$observacion   = $inventario['observacion'];
										$id_area       = $inventario['id_area'];
										$area          = $inventario['area'];
										$id_user       = $inventario['id_user'];
										?>
										<tr class="text-center fila<?=$id_inventario?>">
											<td class="text-uppercase"><?=$usuario?></td>
											<td class="text-uppercase"><?=$area?></td>
											<td><?=$nombre?></td>
											<td><?=$marca?></td>
											<td><?=$cantidad?></td>
											<td><span class="badge badge-danger">No confirmado</span></td>
											<td><?=$observacion?></td>
											<td>
												<button type="button" class="btn btn-success btn-sm confirmar_inv" title="Advertencia!" data-popover="popover" data-placement="right" data-content="Al hacer clic se marcaran todos los articulos <?=$nombre?> del area <?=$area?> como confirmados, no se realizara la peticion o solicitud diligenciada en el campo observacion."  data-session="1" data-trigger="hover" data-user="<?=$id_user?>" id="<?=$id_inventario?>" data-nombre="<?=$nombre?>" data-log="<?=$id_log?>" data-area="<?=$id_area?>">
													<i class="fa fa-check"></i>
													&nbsp;
													Confirmar
												</button>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<script src="<?=PUBLIC_PATH?>js/inventario/funcionesInventario.js"></script>