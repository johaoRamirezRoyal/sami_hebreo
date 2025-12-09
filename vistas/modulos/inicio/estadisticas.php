<div class="col-lg-12">
	<div class="card shadow-sm mb-4">
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			<h4 class="m-0 font-weight-bold text-primary">
				Estadisticas de cumplimiento
			</h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-lg-6">
					<div class="card shadow-sm mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h4 class="m-0 font-weight-bold text-primary">
								Reportes operativos (inventario general)
							</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha desde</label>
									<input type="date" class="form-control" id="fecha_inicio_general">
								</div>
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha hasta</label>
									<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_fin_general">
								</div>
								<div class="col-lg-12 mt-2">
									<canvas id="danos_general_reporte"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card shadow-sm mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h4 class="m-0 font-weight-bold text-primary">
								Reportes operativos (zonas comunes)
							</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha desde</label>
									<input type="date" class="form-control" id="fecha_inicio_zona_dano">
								</div>
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha hasta</label>
									<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_fin_dano_zona">
								</div>
								<div class="col-lg-12 mt-2">
									<canvas id="dano_zona"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card shadow-sm mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h4 class="m-0 font-weight-bold text-primary">
								Reportes de mantenimientos (zonas comunes)
							</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha desde</label>
									<input type="date" class="form-control" id="fecha_inicio_zona">
								</div>
								<div class="col-lg-6 form-group">
									<label class="font-weight-bold">Fecha hasta</label>
									<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_fin_zona">
								</div>
								<div class="col-lg-12 mt-2">
									<canvas id="mant_zona"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>