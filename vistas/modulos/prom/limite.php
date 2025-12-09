<?php
include_once VISTA_PATH . 'cabeza.php';
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-3"></div>
		<div class="col-lg-6">
			<div class="card shadow mt-5 bg-danger border-0">
				<div class="card-body">
					<center>
						<h3 class="font-weight-bold text-white">Codigo QR leido correctamente</h3>
						<h4 class="font-weight-bold text-white mt-5">Limite de cupos alcanzado</h4>
						<a class="btn btn-secondary btn-sm text-decoration-none mt-4" href="<?=BASE_URL?>prom/lector">
							<i class="fa fa-arrow-left"></i>
							&nbsp;
							Volver a leer codigo
						</a>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<script>
	$("#loader").hide();
</script>