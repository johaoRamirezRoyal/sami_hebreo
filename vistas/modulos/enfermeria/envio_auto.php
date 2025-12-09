<?php
include_once VISTA_PATH . 'cabeza.php';
require_once CONTROL_PATH . 'enfermeria' . DS . 'ControlEnfermeria.php';

$instancia = ControlEnfermeria::singleton_enfermeria();

$datos_atencion = $instancia->mostrarListadoAtencionMedicaProgramadasControl();
?>
<div class="container-fluid mt-4">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-primary">
						Automatizacion de correos - Atenciones Medicas
					</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive mt-2">
						<table class="table table-hover border table-sm" width="100%" cellspacing="0">
							<thead>
								<tr class="text-center font-weight-bold">
									<th scope="col">No. Atencion Medica</th>
									<th scope="col">Documento</th>
									<th scope="col">Nombre</th>
									<th scope="col">Nivel</th>
									<th scope="col">Curso</th>
									<th scope="col">Motivo</th>
									<th scope="col">Tratamiento</th>
									<th scope="col">Fecha de atencion</th>
								</tr>
							</thead>
							<tbody class="buscar">
								<?php
								foreach ($datos_atencion as $atencion) {
									$id_atencion = $atencion['id'];
									$nom_user    = $atencion['nom_user'];
									$documento   = $atencion['documento'];
									$curso       = $atencion['nom_curso'];
									$nivel       = $atencion['nom_nivel'];
									$motivo      = $atencion['motivo_cons'];
									$tratamiento = $atencion['tratamiento'];

									if ($atencion['envio'] == 1) {
										$span_envio = '<span class="badge badge-success">Correo Enviado</span>';
									}

									if ($atencion['envio'] == 2) {
										$span_envio = '<span class="badge badge-secondary">Envio de correo programado pendiente</span>';
									}

									if ($atencion['envio'] == 2 && $atencion['enviado'] == 1) {
										$span_envio = '<span class="badge badge-success">Correo Programado Enviado</span>';
									}

									$hora = date('H:i');
									//$hora = '16:01';

									if($hora >= '13:00'){
										$instancia->correosAutomaticosAtencionMedicaControl($id_atencion);
									}

									//$instancia->correosAutomaticosAtencionMedicaControl($id_atencion);

									?>
									<tr class="text-center">
										<td><?=$id_atencion?></td>
										<td><?=$documento?></td>
										<td><?=$nom_user?></td>
										<td><?=$nivel?></td>
										<td><?=$curso?></td>
										<td><?=$motivo?></td>
										<td><?=$tratamiento?></td>
										<td><?=date('Y-m-d', strtotime($atencion['fechareg']))?></td>
										<td><?=$span_envio?></td>
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
<script>
	$(".loader").hide();
</script>