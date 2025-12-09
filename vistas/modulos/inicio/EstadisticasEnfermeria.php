<div class="col-lg-12">
	<div class="card shadow-sm mb-4">
		<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			<h4 class="m-0 font-weight-bold text-hebreo">
				Estadisticas Enfermeria
			</h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Fecha desde</label>
					<input type="date" class="form-control" id="fecha_desde_enfermeria">
				</div>
				<div class="col-lg-6 form-group">
					<label class="font-weight-bold">Fecha hasta</label>
					<input type="date" class="form-control" value="<?=date('Y-m-d')?>" id="fecha_hasta_enfermeria">
				</div>
				<div class="col-lg-3 mt-2">
				</div>
				<div class="col-lg-6 mt-2">
					<canvas id="Grafica_Enfermeria" width="800" height="800"></canvas>
				</div>
				<div class="col-lg-3 mt-2">
				</div>
			</div>
		</div>
	</div>
</div>