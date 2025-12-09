<?php
include_once VISTA_PATH . 'cabeza.php';
?>
<div class="container-fluid">
	<div class="row mt-2">
		<div class="col-lg-12 form-group">
			<div class="card shadow-sm">
				<div class="card-body">
					<div class="col-lg-12 form-group d-flex flex-row">
						<a href="<?=BASE_URL?>login">
							<i class="fa fa-arrow-left fa-2x"></i>
						</a>
						&nbsp;
						<h3 class="text-center text-primary font-weight-bold ml-4">¿Necesitas ayuda?</h3>
					</div>
					<div class="row p-2 mt-3">
						<div class="col-3">
							<div class="nav flex-column nav-tabs border-0" id="nav-tab" role="tablist">
								<button class="nav-link active btn btn-outline-primary font-weight-bold mb-1" id="nav-home-tab" data-toggle="tab" data-target="#nav-info" type="button" role="tab" aria-controls="nav-info" aria-selected="true">Sobre S.A.M.I</button>
								<button class="nav-link btn btn-outline-primary font-weight-bold mb-1" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Como reservar salones y subir firma digital</button>
								<button class="nav-link btn btn-outline-primary font-weight-bold mb-1" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Como reportar un articulo</button>
								<button class="nav-link btn btn-outline-primary font-weight-bold mb-1" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Como ver mi inventario</button>
							</div>
						</div>
						<div class="col-lg-9">
							<div class="tab-content" id="nav-tabContent">
								<div class="tab-pane fade show active" id="nav-info" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="card shadow-sm">
										<div class="card-body">
											<div class="row p-2">
												<div class="col-lg-12 form-group">
													<p class="h5"><span class="font-weight-bold">S.A.M.I</span> es un software en la nube que apoya la gestión administrativa y académica para instituciones educativas, permitiendo generar un impacto positivo en el crecimiento y desarrollo de sus procesos internos. </p>
													<p class="h5">Contamos con los siguientes módulos para instituciones educativas:</p>
													<br>
													<ul class="ml-4 h5">
														<li>Inventario</li>
														<li>Biblioteca</li>
														<li>Enfermería</li>
														<li>Reserva de salones</li>
														<li>Admisiones</li>
														<li>Gestion Humana</li>
														<li>Asistencia</li>
													</ul>
													<br>
													<p class="h5">El diseño de <span class="font-weight-bold">S.A.M.I</span> permite trabajar de manera fácil, ya que su diseño se encuentra estructurado a través de modulos que permite ubicar de manera rápida las funciones asignadas para cada usuario.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="card shadow-sm">
										<div class="card-body">
											<iframe style="width: 100%; height: 500px;" src="https://www.youtube.com/embed/aCGc4q-p7Ns" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
									<div class="card shadow-sm">
										<div class="card-body">
										<!--<iframe style="width: 100%; height: 500px;" src="https://youtube.com/whatch?v=zCLvC4oS0SA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>-->
											 <!--<iframe src="https://youtube.com/shorts/zCLvC4oS0SA" style="width: 100%; min-height: 550px;" frameborder="0"></iframe> -->
										<iframe width="100%" height="500px" src="https://www.youtube.com/embed/zCLvC4oS0SA" title="Tuto SAMI" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
									<div class="card shadow-sm">
										<div class="card-body">

										</div>
									</div>
								</div>
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