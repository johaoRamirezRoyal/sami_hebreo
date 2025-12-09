	<div class="col-lg-12">
		<div class="card shadow-sm mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h4 class="m-0 font-weight-bold text-primary">
					Estadisticas de cumplimiento (Sistemas)
				</h4>
			</div>
			<div class="card-body">
				<div class="row">

					<div class="col-lg-6">
						<div class="card shadow-sm mb-4">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h4 class="m-0 font-weight-bold text-primary">
									Mantenimientos preventivos
								</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Fecha desde</label>
										<input type="date" class="form-control" id="fecha_inicio_mant">
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Fecha hasta</label>
										<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_fin_mant">
									</div>
									<div class="col-lg-12 mt-2">
										<canvas id="mantenimientos"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card shadow-sm mb-4">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h4 class="m-0 font-weight-bold text-primary">
									Reportes de da&ntilde;os
								</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Fecha desde</label>
										<input type="date" class="form-control" id="fecha_inicio_report">
									</div>
									<div class="col-lg-6 form-group">
										<label class="font-weight-bold">Fecha hasta</label>
										<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_fin_report">
									</div>
									<div class="col-lg-12 mt-2">
										<canvas id="danos"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>