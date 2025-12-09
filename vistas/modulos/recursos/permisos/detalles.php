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

require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';



$instancia = ControlRecursos::singleton_recursos();



$permisos = $instancia_permiso->permisosUsuarioControl(90, $perfil_log);

if (!$permisos) {

    include_once VISTA_PATH . 'modulos' . DS . '403.php';

    exit();

}



if (isset($_GET['id'])) {



    $id_permiso = base64_decode($_GET['id']);

    $url        = base64_decode($_GET['enlace']);



    $enlace = ($url == 0) ? 'listado' : 'misSolicitudes';



    $datos_permiso = $instancia->mostrarPermisoIdControl($id_permiso);

    if ($datos_permiso['estado'] == 0) {

        $bg  = 'bg-warning';

        $txt = 'Pendiente';

    }



    if ($datos_permiso['estado'] == 1) {

        $bg  = 'bg-success';

        $txt = 'Aprobado';

    }



    if ($datos_permiso['estado'] == 2) {

        $bg  = 'bg-danger';

        $txt = 'Rechazado';

    }

    //disable boton
    $disable = ($datos_permiso['estado'] != 0 
            && !in_array($perfil_log, [1, 3, 7, 8, 26]))
            ? 'disabled' 
            : '';

}


?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow-sm mb-4">

                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                    <h4 class="m-0 font-weight-bold text-primary">

                        <a href="<?=BASE_URL?>recursos/permisos/<?=$enlace?>" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Detalles del permiso No. <?=$id_permiso?>

                    </h4>

                </div>

                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">

                        <input type="hidden" name="id_log" value="<?=$id_log?>">

                        <input type="hidden" name="motivo" value="">

                        <input type="hidden" name="estado" value="1">

                        <div class="row p-2">

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['documento']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_user']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['telefono']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Motivo del permiso <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_motivo']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Tipo de permiso <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['nom_tipo']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Detalle del permiso: <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" disabled value="<?=$datos_permiso['tipo_permiso_detalle']?>">

                            </div>

                            <div class="col-lg-4 form-group">

                                <label class="font-weight-bold">Estado de la solicitud <span class="text-danger">*</span></label>

                                <input type="text" class="form-control text-white <?=$bg?>" disabled value="<?=$txt?>">

                            </div>

                            <?php

                            if ($datos_permiso['tipo_permiso'] == 1) {

                                ?>

                                <div class="col-lg-12 form-group mt-2">

                                    <h5 class="font-weight-bold text-primary text-center text-uppercase">Permiso Parcial</h5>

                                    <hr>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>

                                    <input type="date" class="form-control" value="<?=$datos_permiso['fecha_permiso']?>" disabled>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Hora de salida <span class="text-danger">*</span></label>

                                    <input type="time" class="form-control" value="<?=$datos_permiso['hora_salida']?>" disabled>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Tiempo aproximado del permiso <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" value="<?=$datos_permiso['tiempo_permiso']?>" disabled>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Detalle del permiso: <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" disabled value="<?=$datos_permiso['tipo_permiso_detalle']?>">

                                </div>

                                <div class="col-lg-6 form-group">

                                    <label class="font-weight-bold">Descargar evidencia del permiso (JPG, PNG, PDF, JPEG):</label>
                                    <br>

                                    <?php 

                                    $ruta_archivo = PUBLIC_PATH_ARCH . 'upload/' . $datos_permiso['evidencia_permiso'];
                                    if(!empty($datos_permiso['evidencia_permiso']) && file_exists($ruta_archivo)){

                                        ?>
                                    <a href="<?=PUBLIC_PATH?>upload/<?=$datos_permiso['evidencia_permiso']?>" class="btn btn-primary btn-sm" download>

                                        <i class="fa fa-download"></i>

                                        &nbsp;

                                        Descargar

                                    </a>

                                    <?php }?>

                                </div>

                                <div class="col-lg-12 form-group">

                                    <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>

                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['descripcion']?></textarea>

                                </div>


                                <?php

                            }

                            if ($datos_permiso['tipo_permiso'] == 2) {

                                ?>

                                <div class="col-lg-12 form-group mt-2">

                                    <h5 class="font-weight-bold text-primary text-center text-uppercase">Permiso Dia Completo</h5>

                                    <hr>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>

                                    <input type="date" class="form-control" id="fecha_permiso" value="<?=$datos_permiso['fecha_permiso']?>" disabled>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Fecha en que retorna a laborar <span class="text-danger">*</span></label>

                                    <input type="date" class="form-control" id="fecha_retorno" value="<?=$datos_permiso['fecha_retorno']?>" disabled>

                                </div>

                                <div class="col-lg-4 form-group">

                                    <label class="font-weight-bold">Cantidad de días de permiso <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" readonly value="<?=$datos_permiso['dias_permiso']?>" id="dias" disabled>

                                </div>

                                <div class="col-lg-6 form-group">

                                    <label class="font-weight-bold">Descargar evidencia del permiso (JPG, PNG, PDF, JPEG):</label>
                                    <br>
                                    <?php 
                                    
                                    $ruta_archivo = PUBLIC_PATH_ARCH . 'upload/' . $datos_permiso['evidencia_permiso'];
                                    if(!empty($datos_permiso['evidencia_permiso']) && file_exists($ruta_archivo)){

                                        ?>
                                    <a href="<?=PUBLIC_PATH?>upload/<?=$datos_permiso['evidencia_permiso']?>" class="btn btn-primary btn-sm" download>

                                        <i class="fa fa-download"></i>

                                        &nbsp;

                                        Descargar

                                    </a>

                                    <?php }?>

                                </div>

                                <div class="col-lg-12 form-group">

                                    <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>

                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['descripcion']?></textarea>

                                </div>


                                <?php

                            }

                            if ($datos_permiso['estado'] == 2) {

                                ?>

                                <div class="col-lg-12 form-group">

                                    <label class="font-weight-bold">Motivo de rechazo</label>

                                    <textarea class="form-control" rows="5" disabled><?=$datos_permiso['motivo_rechazo']?></textarea>

                                </div>

                                <?php

                            }

                            $permisos = $instancia_permiso->permisosUsuarioControl(75, $perfil_log);

                            if ($datos_permiso['estado'] == 0 && $permisos) {

                                ?>

                                <div class="col-lg-4 form-group mt-2">
                                    <label class="font-weight-bold">Permisos remunerado <span class="text-danger">*</span></label>
                                    <select name="remunerado" class="form-control" required <?=($datos_permiso['remunerado'] != '') ? 'disabled' : ''?>>
                                        <option value="" selected>Seleccione una opcion...</option>
                                        <option value="si" <?=($datos_permiso['remunerado'] == 'si') ? 'selected' : ''?>>Si</option>
                                        <option value="no" <?=($datos_permiso['remunerado'] == 'no') ? 'selected' : ''?>>No</option>
                                    </select>
                                </div>

                                <div class="col-lg-12 form-group mt-4 text-right">

                                    <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#rechazar">

                                        <i class="fa fa-times"></i>

                                        &nbsp;

                                        Rechazar

                                    </button>

                                    <button class="btn btn-primary btn-sm" type="submit" name="estado_permiso">

                                        <i class="fa fa-check"></i>

                                        &nbsp;

                                        Aprobar

                                    </button>

                                </div>

                            <?php }?>
                        </div>

                    </form>

                    <?php 
                    $permisos = $instancia_permiso->permisosUsuarioControl(90, $perfil_log);
                    if ($datos_permiso['estado'] == 0 && $permisos):
                    ?>
                    <!-- Botón para editar información del permiso -->
                            <div class="col-lg-12 form-group mt-2 text-right">

                                <button class="btn btn-primary btn-sm" type="button" name="editar_permiso" <?=$disable?> data-toggle="modal" data-target="#editar_permiso_<?=$datos_permiso['tipo_permiso']?>">

                                    <i class="fa fa-edit"></i>

                                    &nbsp;

                                    Editar

                                </button>

                                <?php
                                if($datos_permiso['tipo_permiso'] == 1){	
                                ?>
                                <div class="modal fade" id="editar_permiso_<?=$datos_permiso['tipo_permiso']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Editar permiso No. <?=$id_permiso?></h5>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">
                                                    <input type="hidden" name="id_log" value="<?=$id_log?>">
                                                    <input type="hidden" name="evidencia_permiso_guardada" value="<?=$datos_permiso['evidencia_permiso']?>">
                                                    <div class="row p-2">
                                                        <div class="col-lg-4 form-group">
                                                            <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" value="<?=$datos_permiso['documento']?>" disabled>
                                                        </div>
                                                        <div class="col-lg-4 form-group">
                                                            <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" value="<?=$datos_permiso['nom_user']?>" disabled>
                                                        </div>
                                                        <div class="col-lg-4 form-group">
                                                            <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" value="<?=$datos_permiso['telefono']?>" disabled>
                                                        </div>
                                                        <div class="col-lg-4 form-group">
                                                            <label class="font-weight-bold">Motivo del permiso <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" value="<?=$datos_permiso['nom_motivo']?>" disabled>
                                                        </div>
                                                        <div class="col-lg-4 form-group">
                                                            <label class="font-weight-bold">Tipo de permiso <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" value="<?=$datos_permiso['nom_tipo']?>" disabled>
                                                        </div>
                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="fecha_permiso_modal" value="<?=$datos_permiso['fecha_permiso']?>">
                                                        </div>
                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Hora de salida <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" name="hora_salida" value="<?=$datos_permiso['hora_salida']?>">
                                                        </div>
                                                        <div class="col-lg-6 form-group">
                                                            <label class="font-weight-bold">Tiempo aproximado del permiso en horas <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="tiempo_aproximado" value="<?=$datos_permiso['tiempo_permiso']?>">
                                                        </div>
                                                        <div class="col-lg-12 form-group">
                                                            <label class="font-weight-bold">Adjunte evidencia del permiso (JPG, PNG, PDF, JPEG): <span class="text-danger">*</span></label>
                                                            <div class="custom-file pmd-custom-file-filled">
                                                                <input type="file" class="custom-file-input file_input" id="evidencia_permiso" name="evidencia_permiso" accept=".png, .jpg, .jpeg, .pdf">
                                                                <label class="custom-file-label file_label_evidencia_permiso" for="customfilledFile" required></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 form-group">
                                                            <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="descripcion" rows="5"><?=$datos_permiso['descripcion']?></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary" name="editar_permiso">Guardar</button>
                                                        </div>
                                                </form>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <?php
                                if($datos_permiso['tipo_permiso'] == 2){	
                                ?>
                                    <div class="modal fade" id="editar_permiso_<?=$datos_permiso['tipo_permiso']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Editar permiso No. <?=$id_permiso?></h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">
                                                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                                                        <input type="hidden" name="evidencia_permiso_guardada" value="<?=$datos_permiso['evidencia_permiso']?>">
                                                        <div class="row p-2">
                                                            <div class="col-lg-4 form-group">
                                                                <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>    
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['documento']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-4 form-group">
                                                                <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['nom_user']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-4 form-group">
                                                                <label class="font-weight-bold">Telefono <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['telefono']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-4 form-group">
                                                                <label class="font-weight-bold">Motivo del permiso <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['nom_motivo']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-4 form-group">    
                                                                <label class="font-weight-bold">Tipo de permiso <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['nom_tipo']?>" disabled>
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" id="fecha_permiso_modal" name="fecha_permiso_modal" value="<?=$datos_permiso['fecha_permiso']?>">
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Fecha en que retorna a laborar <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" id="fecha_retorno_modal" name="fecha_retorno_modal" value="<?=$datos_permiso['fecha_retorno']?>" >
                                                            </div>
                                                            <div class="col-lg-6 form-group">
                                                                <label class="font-weight-bold">Cantidad de días de permiso <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" value="<?=$datos_permiso['dias_permiso']?>" id="dias_modal" name="dias_modal">
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Adjunte evidencia del permiso (JPG, PNG, PDF, JPEG): <span class="text-danger">*</span></label>
                                                                <div class="custom-file pmd-custom-file-filled">
                                                                    <input type="file" class="custom-file-input file_input" id="evidencia_permiso" name="evidencia_permiso" accept=".png, .jpg, .jpeg, .pdf">
                                                                    <label class="custom-file-label file_label_evidencia_permiso" for="customfilledFile" required></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" rows="5" name="descripcion"><?=$datos_permiso['descripcion']?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary" name="editar_permiso">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        // Esperar que el DOM esté completamente cargado
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const fechaPermisoInput = document.getElementById('fecha_permiso_modal');
                                            const fechaRetornoInput = document.getElementById('fecha_retorno_modal');
                                            const diasInput = document.getElementById('dias_modal');

                                            function calcularDiasPermiso() {
                                                const fechaInicio = fechaPermisoInput.value;
                                                const fechaFin = fechaRetornoInput.value;

                                                if (fechaInicio && fechaFin) {
                                                    const inicio = new Date(fechaInicio);
                                                    const fin = new Date(fechaFin);

                                                    // Verificar fechas válidas
                                                    if (!isNaN(inicio.getTime()) && !isNaN(fin.getTime())) {
                                                        const diferencia = (fin - inicio) / (1000 * 60 * 60 * 24);
                                                        diasInput.value = diferencia >= 0 ? Math.floor(diferencia) : '';
                                                    } else {
                                                        diasInput.value = '';
                                                    }
                                                } else {
                                                    diasInput.value = '';
                                                }
                                            }

                                            // Asociar eventos a los campos de fecha
                                            fechaPermisoInput.addEventListener('change', calcularDiasPermiso);
                                            fechaRetornoInput.addEventListener('change', calcularDiasPermiso);
                                        });
                                    </script>
                                <?php
                                }
                                ?>
                            </div>
                                <?php
                                endif;
                                ?>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="modal fade" id="rechazar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

          <div class="modal-header">

            <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Motivo de rechazo del permiso No. <?=$id_permiso?></h5>

        </div>

        <div class="modal-body">

            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id_permiso" value="<?=$id_permiso?>">

                <input type="hidden" name="id_log" value="<?=$id_log?>">

                <input type="hidden" name="estado" value="2">

                <div class="row p-2">

                    <div class="col-lg-12 form-group">

                        <label class="font-weight-bold">Motivo de rechazo</label>

                        <textarea class="form-control" rows="5" name="motivo"></textarea>

                    </div>

                    <div class="col-lg-12 form-group mt-2 text-right">

                        <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">

                            <i class="fa fa-times"></i>

                            &nbsp;

                            Cancelar

                        </button>

                        <button class="btn btn-primary btn-sm" type="submit" name="estado_permiso">

                            <i class="fas fa-paper-plane"></i>

                            &nbsp;

                            Enviar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>



</div>

<script>



	$('.clockpicker').clockpicker(

	{

		placement: 'bottom',

		donetext: 'Aceptar'

	});



	$("#fecha_permiso").change(function(){

		var fecha_permiso = $(this).val();

		var fecha_retorno = $("#fecha_retorno").val();

		$("#dias").val(calcularFecha(fecha_permiso, fecha_retorno));

	});



	$("#fecha_retorno").change(function(){

		var fecha_retorno = $(this).val();

		var fecha_permiso = $("#fecha_permiso").val();

		$("#dias").val(calcularFecha(fecha_permiso, fecha_retorno));

	});



	function calcularFecha(date1,date2){

		if (date1.indexOf("-") != -1) { date1 = date1.split("-"); } else if (date1.indexOf("/") != -1) { date1 = date1.split("/"); } else { return 0; }

		if (date2.indexOf("-") != -1) { date2 = date2.split("-"); } else if (date2.indexOf("/") != -1) { date2 = date2.split("/"); } else { return 0; }

		if (parseInt(date1[0], 10) >= 1000) {

			var sDate = new Date(date1[0]+"/"+date1[1]+"/"+date1[2]);

		} else if (parseInt(date1[2], 10) >= 1000) {

			var sDate = new Date(date1[2]+"/"+date1[0]+"/"+date1[1]);

		} else {

			return 0;

		}

		if (parseInt(date2[0], 10) >= 1000) {

			var eDate = new Date(date2[0]+"/"+date2[1]+"/"+date2[2]);

		} else if (parseInt(date2[2], 10) >= 1000) {

			var eDate = new Date(date2[2]+"/"+date2[0]+"/"+date2[1]);

		} else {

			return 0;

		}

		var one_day = 1000*60*60*24;

		var daysApart = Math.abs(Math.ceil((sDate.getTime()-eDate.getTime())/one_day));

		return daysApart;

	}

</script>

<?php

include_once VISTA_PATH . 'script_and_final.php';



if (isset($_POST['estado_permiso'])) {

    $instancia->estadoPermisoControl();

}

if(isset($_POST['editar_permiso'])){
    $instancia->actualizarDatosDelPermiso($_POST);
}