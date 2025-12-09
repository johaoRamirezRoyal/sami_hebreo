<?php

date_default_timezone_set('America/Bogota');

define('DS', DIRECTORY_SEPARATOR);

define('ROOT', '..' . DS . '..' . DS . '..');

require_once '..' . DS . '..' . DS . '..' . DS . 'confi' . DS . 'Config.php';

require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';



$instancia = ControlRecursos::singleton_recursos();



$id_tipo = $_POST['id'];



if ($id_tipo == 1) {

	?>

	<div class="col-lg-12 form-group mt-2">

		<h5 class="text-primary font-weight-bold text-center">PERMISO PARCIAL</h5>

		<hr>

	</div>

	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<i class="fa fa-exclamation-triangle"></i>

		&nbsp;

		<strong>¡Atención!</strong>

		&nbsp;

		Tener en cuenta que en caso de incapacidad o cita médica es necesario contar con un certificado que valide la incapacidad, en caso de no adjuntar la evidencia el permiso quedará pendiente. Se le hará un recordatorio para hacer entrega de este documento.

		&nbsp;

		<strong>¡Consideración!</strong>

		&nbsp;

		La solicitud puede estar sujeta a cambios en caso de que el coordinador o su jefe inmediato decida ajustar el permiso.


		<button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>

		<input type="date" class="form-control" name="fecha_permiso" required>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Hora de salida <span class="text-danger">*</span></label>

		<div class="input-group clockpicker">

			<input type="time" class="form-control" name="hora_salida" required>

		</div>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Tiempo aproximado del permiso (En minutos)<span class="text-danger">*</span></label>

		<input type="number" class="form-control" name="tiempo_aproximado" required>

	</div>

	<div class="form-group col-lg-12 mt-2 text-right">
		<label class="font-weight-bold">Adjunte evidencia del permiso (JPG, PNG, PDF, JPEG):</label>
		<div class="custom-file pmd-custom-file-filled">
			<input type="file" class="custom-file-input file_input" id="evidencia_permiso" name="evidencia_permiso" accept=".png, .jpg, .jpeg, .pdf">
			<label class="custom-file-label file_label_evidencia_permiso" for="customfilledFile"></label>
		</div>
	</div>

	<div class="col-lg-12 form-group">

		<label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>

		<textarea class="form-control" name="descripcion" rows="5"></textarea>

	</div>

	<div style="position:relative;width:300px;">
		<iframe src="https://convertlive.com/es/w/convertir/horas/a/minutos" frameBorder="0" width="300px" height="280px" style="border:medium none;overflow-x:hidden;overflow-y:hidden;margin-bottom:-5px;"><p>Su navegador no soporta iframes. <a href="https://convertlive.com/es/convertir">convertlive</a>.</p></iframe><a target="_blank" rel="noopener" style="position:absolute;bottom:7px;right:15px;font-family:monospace;color:#68808F;font-size:12px;font-weight:700;" href="https://convertlive.com/es/convertir">convertlive</a>
	</div>

	<?php

}



if ($id_tipo == 2) {

	?>

	<div class="col-lg-12 form-group mt-2">

		<h5 class="text-primary font-weight-bold text-center">DIA COMPLETO</h5>

		<hr>

	</div>

	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		<i class="fa fa-exclamation-triangle"></i>

		&nbsp;

		<strong>¡Atención!</strong>

		&nbsp;

		Tener en cuenta que en caso de incapacidad o cita médica es necesario contar con un certificado que valide la incapacidad, en caso de no adjuntar la evidencia el permiso quedará pendiente. Se le hará un recordatorio para hacer entrega de este documento.

		&nbsp;

		<strong>¡Consideración!</strong>

		&nbsp;

		La solicitud puede estar sujeta a cambios en caso de que el coordinador o su jefe inmediato decida ajustar el permiso.


		<button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Fecha para la que solicita el permiso <span class="text-danger">*</span></label>

		<input type="date" class="form-control fecha_permiso" name="fecha_permiso" required>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Fecha en que retorna a laborar <span class="text-danger">*</span></label>

		<input type="date" class="form-control fecha_retorno" name="fecha_retorno" required>

	</div>

	<div class="col-lg-4 form-group">

		<label class="font-weight-bold">Cantidad de días de permiso <span class="text-danger">*</span></label>

		<input type="text" class="form-control dias" readonly name="dias_permiso" required>

	</div>

	<div class="form-group col-lg-12 mt-2 text-right">
		<label class="font-weight-bold">Adjunte evidencia del permiso (JPG, PNG, PDF, JPEG):</label>
		<div class="custom-file pmd-custom-file-filled">
			<input type="file" class="custom-file-input file_input" id="evidencia_permiso" name="evidencia_permiso" accept=".png, .jpg, .jpeg, .pdf">
			<label class="custom-file-label file_label_evidencia_permiso" for="customfilledFile"></label>
		</div>
	</div>

	<div class="col-lg-12 form-group">

		<label class="font-weight-bold">Breve descripcion del permiso <span class="text-danger">*</span></label>

		<textarea class="form-control" name="descripcion" rows="5"></textarea>

	</div>

	<?php

}

if (!empty($id_tipo)) {

	?>

	<div class="col-lg-12 form-group text-right mt-2">

		<button class="btn btn-primary btn-sm" type="submit">

			<i class="fa fa-save"></i>

			&nbsp;

			Guardar

		</button>

	</div>

	<?php

}

?>

<script>
$(document).on('change', '.custom-file-input', function() {
  var fileName = $(this).val().split('\\').pop();
  $(this).next('.custom-file-label').text(fileName);
});


	$('.clockpicker').clockpicker(

	{

		placement: 'bottom',

		donetext: 'Aceptar'

	});



	$(".fecha_permiso").change(function(){

		var fecha_permiso = $(this).val();

		var fecha_retorno = $(".fecha_retorno").val();

		$(".dias").val(calcularFecha(fecha_permiso, fecha_retorno));

	});



	$(".fecha_retorno").change(function(){

		var fecha_retorno = $(this).val();

		var fecha_permiso = $(".fecha_permiso").val();

		$(".dias").val(calcularFecha(fecha_permiso, fecha_retorno));

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