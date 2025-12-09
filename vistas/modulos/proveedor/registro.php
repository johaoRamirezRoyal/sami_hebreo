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
						Registro de proveedor
					</h4>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
							<div class="dropdown-header">Acciones:</div>
							<a class="dropdown-item" href="<?=BASE_URL?>proveedor/index">Listado proveedores</a>
						</div>
					</div>
				</div>
				<form method="POST">
					<input type="hidden" name="id_log" value="<?=$id_log?>">
					<div class="card-body">
						<div class="row">
							<div class="form-group col-lg-12 text-center">
								<h5 class="font-weight-bold text-hebreo">INFORMACION DEL PROVEEDOR EXTERNO</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Nombre o razon o social <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="nombre" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Identificacion <span class="text-danger">*</span></label>
								<select name="identificacion" class="form-control" required>
									<option value="" selected>Seleccione una opcion...</option>
									<option value="Nit">Nit</option>
									<option value="Cedula de ciudadania">Cedula de ciudadania</option>
									<option value="Cedula de extranjeria">Cedula de extranjeria</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Numero de identificacion <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="num_identificacion" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Direccion <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="direccion" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Ciudad <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="ciudad" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Departamento <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="departamento" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Pais <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="pais" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
								<input type="text" class="form-control numeros" name="telefono" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Correo electronico coorporativo <span class="text-danger">*</span></label>
								<input type="email" class="form-control" name="correo" required>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Fecha ingreso <span class="text-danger">*</span></label>
								<input type="date" class="form-control" name="fecha_ingreso" required>
							</div>
							<div class="form-group col-lg-12 text-center mt-5">
								<h5 class="font-weight-bold text-hebreo">INFORMACION DEL PROVEEDOR EXTERNO</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Tipo</label>
								<select class="form-control" name="tipo">
									<option value="" selected>Seleccione una opcion...</option>
									<option value="Bien">Bien</option>
									<option value="Servicio">Servicio</option>
									<option value="Bien y Servicio">Bien y Servicio</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Tiempo de entrega (Dias)</label>
								<input type="text" class="form-control numeros" name="tiempo_entrega">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Garantia (Dias/Meses)</label>
								<input type="text" class="form-control numeros" name="garantia">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Plazo de pago (Dias/Meses)</label>
								<input type="text" class="form-control numeros" name="plazo_pago">
							</div>
							<div class="form-group col-lg-12">
								<label class="font-weight-bold">Detalle del producto</label>
								<textarea class="form-control" cols="5" rows="5" name="detalle_producto"></textarea>
							</div>







							<div class="form-group col-lg-12 text-center mt-5">
								<h5 class="font-weight-bold text-hebreo">INFORMACION DEL REPRESENTANTE LEGAL</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Nombre completo</label>
								<input type="text" class="form-control" name="nom_representante">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Identificacion</label>
								<input type="text" class="form-control numeros" name="identificacion_representante">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Correo electronico</label>
								<input type="email" class="form-control" name="correo_representante">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Telefono</label>
								<input type="text" class="form-control" name="telefono_representante">
							</div>





							<div class="form-group col-lg-12 text-center mt-5">
								<h5 class="font-weight-bold text-hebreo">INFORMACIÓN DE CONTACTOS</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Nombre</label>
								<input type="text" class="form-control" name="nombre_contacto">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Telefono</label>
								<input type="text" class="form-control numeros" name="telefono_contacto">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Correo electronico</label>
								<input type="text" class="form-control" name="correo_contacto">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Cargo/Area</label>
								<select class="form-control" name="cargo_contacto">
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Asesor">Asesor</option>
									<option value="Contacto">Contacto</option>
									<option value="Compras/Finanzas">Compras/Finanzas</option>
								</select>
							</div>




							<div class="form-group col-lg-12 text-center mt-5">
								<h5 class="font-weight-bold text-hebreo">INFORMACIÓN TRIBUTARIA</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Regimen</label>
								<select class="form-control" name="regimen_proveedor">
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Comun">Comun</option>
									<option value="Simplificado">Simplificado</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Gran contribuyente</label>
								<select class="form-control" name="contribuyente_proveedor">
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Autoretenedor</label>
								<select class="form-control" name="autoretenedor_proveedor">
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Responsable industria y comercio</label>
								<select class="form-control" name="comercio_proveedor">
									<option value="" selected="">Seleccione una opcion...</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Actividad economica</label>
								<input type="text" class="form-control" name="actividad_proveedor">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Tarifa</label>
								<input type="text" class="form-control" name="tarifa_proveedor">
							</div>




							<div class="form-group col-lg-12 text-center mt-5">
								<h5 class="font-weight-bold text-hebreo">INFORMACIÓN BANCARIA PARA EFECTUAR PAGOS</h5>
								<hr>
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Nombre del banco</label>
								<input type="text" class="form-control" name="nom_banco">
							</div>
							<div class="form-group col-lg-4">
								<label class="font-weight-bold">Numero de la cuenta bancaria</label>
								<input type="text" class="form-control" name="num_banco">
							</div>
							<div class="col-lg-4 form-group">
								<label class="font-weight-bold">Tipo de cuenta</label>
								<select class="form-control" name="tipo_cuenta">
									<option value="" selected>Seleccione una opcion...</option>
									<option value="Ahorros">Ahorros</option>
									<option value="Corriente">Corriente</option>
								</select>
							</div>
						</div>

						<div class="col-lg-12 form-group text-right mt-4">
							<hr>
							<button type="submit" class="btn btn-hebreo btn-sm">
								Guardar y continuar
								&nbsp;
								<i class="fa fa-arrow-right"></i>
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

if (isset($_POST['id_log'])) {
	$instancia->registrarProveedorControl();
}

?>