<?php

date_default_timezone_set('America/Bogota');

define('DS', DIRECTORY_SEPARATOR);

define('ROOT', '..' . DS . '..' . DS . '..');

require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';

require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

require_once CONTROL_PATH . 'matricula' . DS . 'ControlMatricula.php';



$instancia           = ControlRecursos::singleton_recursos();

$instancia_matricula = ControlMatricula::singleton_matricula();



$datos_eps   = $instancia_matricula->mostrarDatosEpsControl();

$datos_grupo = $instancia->mostrarDatosGrupoFamiliarControl();



$tipo_tramite   = $_POST['id'];

$correo_usuario = $_POST['correo'];



if ($tipo_tramite == 1) {

	?>

	<div class="col-lg-12 text-center form-group">

		<h5 class="text-primary font-weight-bold text-uppercase">Certificado Laboral</h5>

		<hr>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">¿Cuál es el motivo por el cual solicita el certificado laboral? <span class="text-danger">*</span></label>

		<input type="text" name="motivo" class="form-control" required>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Desea que su certificado laboral, mencione <span class="text-danger">*</span></label>

		<select name="mencione" class="form-control">

			<option value="" selected>Selccione una opcion...</option>

			<option value="Salario y tiempo de servicio">Salario y tiempo de servicio</option>

			<option value="Objetivo del cargo">Objetivo del cargo</option>

			<option value="Otra">Otra</option>

		</select>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Nombre de la persona o entidad que desea aparezca en el certificado <span class="text-danger">*</span></label>

		<input type="text" class="form-control" name="nombre_dirige" required placeholder="">

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Menciona cual (Otra)</label>

		<input type="text" class="form-control" name="otra">

	</div>

	<div class="col-lg-12 text-left form-group mt-4">

		<h5 class="text-primary font-weight-bold text-uppercase">Modo de entrega del certificado Laboral</h5>

		<hr>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Correo <span class="text-danger">*</span></label>

		<input type="text" class="form-control" name="correo" value="<?=$correo_usuario?>" required placeholder="">

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Modo de entrega del Certificado Laboral <span class="text-danger">*</span></label>

		<select name="modo_entrega" class="form-control" required>

			<option value="" selected>Seleccione una opcion...</option>

			<option value="Recoger personalmente en la oficina de Gestión Humana">Recoger personalmente en la oficina de Gestión Humana</option>

			<option value="Correo electronico">Correo electr&oacute;nico</option>

		</select>

	</div>

	<?php

}



if ($tipo_tramite == 2) {

	?>

	<div class="col-lg-12 text-center form-group">

		<h5 class="text-primary font-weight-bold text-uppercase">CERTIFICADO DE INGRESOS Y RETENCIONES</h5>

		<hr>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">A&ntilde;o Grabable <span class="text-danger">*</span></label>

		<select name="anio_grabable" class="form-control" required>

			<option value="" selected>Seleccione una opcion...</option>

			<option value="Ultimo a&ntilde;o grabable">Ultimo a&ntilde;o grabable</option>

			<option value="Otra">Otra</option>

		</select>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Especifique cual (Otra)</label>

		<input type="text" name="otra" class="form-control">

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Modo de entrega del Certificado Laboral <span class="text-danger">*</span></label>

		<select name="modo_entrega" class="form-control" required>

			<option value="Recoger personalmente en la oficina de Gestión Humana" selected>Recoger personalmente en la oficina de Gestión Humana</option>

		</select>

	</div>

	<?php

}



if ($tipo_tramite == 3) {

	?>

	<div class="col-lg-12 text-center form-group">

		<h5 class="text-primary font-weight-bold text-uppercase">TRASLADO DE EPS</h5>

		<hr>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">En cual EPS se encuentra actualmente <span class="text-danger">*</span></label>

		<select name="eps_actual" class="form-control" required>

			<option value="" selected>Seleccione una opcion...</option>

			<?php

			foreach ($datos_eps as $eps) {

				$id_eps  = $eps['id'];

				$nom_eps = $eps['nombre'];

				?>

				<option value="<?=$id_eps?>"><?=$nom_eps?></option>

			<?php }?>

		</select>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Especifique a cual EPS desea trasladarse <span class="text-danger">*</span></label>

		<select name="eps_traslado" class="form-control" required>

			<option value="" selected>Seleccione una opcion...</option>

			<?php

			foreach ($datos_eps as $eps) {

				$id_eps  = $eps['id'];

				$nom_eps = $eps['nombre'];

				?>

				<option value="<?=$id_eps?>"><?=$nom_eps?></option>

			<?php }?>

		</select>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Adjunte: escaner cedula del titular <span class="text-danger">*</span></label>

		<div class="custom-file pmd-custom-file-filled">

			<input type="file" class="custom-file-input file_input" id="cedula_titular" name="cedula_titular" required accept=".png, .jpg, .jpeg, .pdf">

			<label class="custom-file-label file_label_cedula_titular" for="customfilledFile"></label>

		</div>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Grupo familiar <span class="text-danger">*</span></label>

		<select name="grupo_familiar" class="form-control grupo_preg" required>

			<option value="" selected>Seleccione una opcion...</option>

			<option value="1">No tengo grupo familiar asociado a mi EPS</option>

			<option value="2">Si tengo grupo familiar en mi EPS</option>

		</select>

	</div>

	<div class="col-lg-4 form-group grupo_sel">

		<label class="font-weight-bold">Describa grupo familiar vinculado a su EPS actual <span class="text-danger">*</span></label>

		<select name="grupo_familiar_familia[]" class="form-control"  multiple="multiple" required>

			<?php

			foreach ($datos_grupo as $grupo) {

				$id_grupo  = $grupo['id'];

				$nom_grupo = $grupo['nombre'];

				?>

				<option value="<?=$id_grupo?>"><?=$nom_grupo?></option>

			<?php }?>

		</select>

	</div>

	<div class="col-lg-4 form-group grupo_sel">

		<label class="font-weight-bold">Adjuntar documentos necesarios <span class="text-danger">*</span></label>

		<div class="custom-file pmd-custom-file-filled">

			<input type="file" class="custom-file-input file_input" id="documento_necesario" name="documento_necesario[]" required accept=".png, .jpg, .jpeg, .pdf" multiple>

			<label class="custom-file-label file_label_documento_necesario" for="customfilledFile"></label>

		</div>

	</div>

	<div class="col-lg-12 form-group mt-2 grupo_sel">

		<h6 class="text-danger"><strong>Nota:</strong> si no sabe cuales son los documentos necesarios para cada grupo familiar, <strong><a href="<?=PUBLIC_PATH?>img/pdfs/GRUPO_FAMILIAR.pdf" target="_blank">CLIC AQUI</a></strong></h6>

	</div>

	<?php

}

if ($tipo_tramite == 4) {

	?>

	<div class="col-lg-12 text-center form-group">

		<h5 class="text-primary font-weight-bold text-uppercase">INCLUSION DE BENEFICIARIO</h5>

		<hr>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Seleccione beneficiario <span class="text-danger">*</span></label>

		<select name="beneficiario[]" class="form-control" multiple="multiple" required>

			<?php

			foreach ($datos_grupo as $grupo) {

				$id_grupo  = $grupo['id'];

				$nom_grupo = $grupo['nombre'];

				?>

				<option value="<?=$id_grupo?>"><?=$nom_grupo?></option>

			<?php }?>

		</select>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Adjuntar documentos necesarios del beneficiario <span class="text-danger">*</span></label>

		<div class="custom-file pmd-custom-file-filled">

			<input type="file" class="custom-file-input file_input" id="documento_necesario" name="documento_necesario[]" required accept=".png, .jpg, .jpeg, .pdf" multiple>

			<label class="custom-file-label file_label_documento_necesario" for="customfilledFile"></label>

		</div>

	</div>

	<div class="col-lg-12 form-group mt-2">

		<h6 class="text-danger"><strong>Nota:</strong> si no sabe cuales son los documentos necesarios para cada grupo familiar, <strong><a href="<?=PUBLIC_PATH?>img/pdfs/GRUPO_FAMILIAR.pdf" target="_blank">CLIC AQUI</a></strong></h6>

	</div>

	<?php

}

if ($tipo_tramite == 5) {

	?>

	<div class="col-lg-12 text-center form-group">

		<h5 class="text-primary font-weight-bold text-uppercase">TRASLADO FONDO DE PENSIONES</h5>

		<hr>

	</div>

	<div class="col-lg-12 form-group">

		<p>En relación a su solicitud de traslado entre régimen de pensión, deberá realizar la doble asesoría en su fondo de pensiones actual  y al que quiere acceder (Colpensiones), a continuación adjunto links:</p>

		<label class="font-weight-bold mt-2">Doble asesoría Colpensiones</label>

		<br>

		<a target="_blank" href="https://www.colpensiones.gov.co/pensiones/publicaciones/3100/doble-asesoria-entre-regimenes/" class="btn btn-info btn-sm">

			<i class="fas fa-link"></i>

			&nbsp;

			Clic Aqui

		</a>

		<br>

		<label class="font-weight-bold mt-2">Doble asesoría Protección</label>

		<br>

		<a target="_blank" href="https://www.proteccion.com/wps/portal/proteccion/web/doble-asesoria/" class="btn btn-info btn-sm">

			<i class="fas fa-link"></i>

			&nbsp;

			Clic Aqui

		</a>

		<br>

		<label class="font-weight-bold mt-2">Doble asesoría Porvenir</label>

		<br>

		<a target="_blank" href="https://www.porvenir.com.co/web/como-mejorar-tu-pension/todo-sobre-la-doble-asesoria" class="btn btn-info btn-sm">

			<i class="fas fa-link"></i>

			&nbsp;

			Clic Aqui

		</a>

		<br>

		<label class="font-weight-bold mt-2">Doble asesoría Colfondos</label>

		<br>

		<a target="_blank" href="https://www.colfondos.com.co/dxp/personas/pensiones-obligatorias/doble-asesoria/" class="btn btn-info btn-sm">

			<i class="fas fa-link"></i>

			&nbsp;

			Clic Aqui

		</a>

		<p class="mt-4">Recuerde que para hacer efectivo el traslado debe cumplir con lo siguiente:</p>

		<ul class="ml-4">

			<li> Haber cumplido cinco (5) años de afiliación en el Régimen de Ahorro Individual.</li>

		</ul>

		<p class="text-danger"><strong>Nota:</strong> En caso de tener los dos certificados necesarios adjuntar y realizar el tramite.</p>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Fondo Privado <span class="text-danger">*</span></label>

		<div class="custom-file pmd-custom-file-filled">

			<input type="file" class="custom-file-input file_input" id="fondo_privado" name="certificado_fondo[]" required accept=".png, .jpg, .jpeg, .pdf">

			<label class="custom-file-label file_label_fondo_privado" for="customfilledFile"></label>

		</div>

	</div>

	<div class="col-lg-6 form-group">

		<label class="font-weight-bold">Fondo Publico <span class="text-danger">*</span></label>

		<div class="custom-file pmd-custom-file-filled">

			<input type="file" class="custom-file-input file_input" id="fondo_publico" name="certificado_fondo[]" required accept=".png, .jpg, .jpeg, .pdf">

			<label class="custom-file-label file_label_fondo_publico" for="customfilledFile"></label>

		</div>

	</div>

	<?php

}

?>

<div class="col-lg-12 form-group text-right mt-4">

	<button class="btn btn-primary btn-sm">

		<i class="fa fa-save"></i>

		&nbsp;

		Guardar

	</button>

</div>



<script>

	$(".grupo_sel").addClass('d-none');

	$(".grupo_preg").change(function(){

		var id = $(".grupo_preg option:selected").val();

		if(id == 1){

			$(".grupo_sel").addClass('d-none');

		}

		if(id  == 2){

			$(".grupo_sel").removeClass('d-none');

		}

	});

	/*-------------------*/

	$("select").select2();

    /*-------------------*/

	$(".file_input").change(function() {

		let id = $(this).attr('id');

		let valor = $(this).val().split('\\').pop();

		if (id == '') {

			$(".file_label").text(valor);

		} else {

			$(".file_label_" + id).text(valor);

		}

		if (valor == '' && id == '') {

			$(".file_label").text('Falta archivo');

			$(this).val('')

		} else {

			$(".file_label").text(valor);

		}

		if (valor == '' && id != '') {

			$(".file_label_" + id).text('Falta archivo');

			$('#' + id).val('')

		} else {

			$(".file_label_" + id).text(valor);

		}

	});

</script>