<?php
include_once VISTA_PATH . 'cabeza.php';
?>
<style>

	#githubLink {
		position: absolute;
		right: 0;
		top: 12px;
		color: #2D99FF;
	}

	h1 {
		margin: 10px 0;
		font-size: 40px;
	}

	#loadingMessage {
		text-align: center;
		padding: 40px;
		background-color: #eee;
	}

	#canvas {
		width: 100%;
	}

	#output {
		margin-top: 20px;
		background: #eee;
		padding: 10px;
		padding-bottom: 0;
	}

	#output div {
		padding-bottom: 10px;
		word-wrap: break-word;
	}

	#noQRFound {
		text-align: center;
	}
</style>
<div class="container-fluid">
	<div class="row p-2">
		<div class="col-lg-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h4 class="m-0 font-weight-bold text-primary">
						Lector - codigo QR
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 form-group">
							<div id="loadingMessage">🎥 No se puede acceder a la transmisión de video (asegúrese de tener una c&aacute;mara web habilitada)</div>
							<canvas id="canvas" hidden></canvas>
							<div id="output" hidden>
								<div id="outputMessage">Codigo no detectado</div>
								<div hidden><b>Data:</b> <span id="outputData"></span></div>
							</div>
						</div>
						<div class="col-lg-12 form-group mt-2 text-center">
							<button type="button" class="btn btn-primary btn-sm activar">
								<i class="fas fa-camera"></i>
								&nbsp;
								Escanear Codigo
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';
?>
<script src="<?=PUBLIC_PATH?>js/asistencia/funcionesLector.js"></script>