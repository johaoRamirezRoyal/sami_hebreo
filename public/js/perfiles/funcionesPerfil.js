$(document).ready(function(){

	var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

	$(".eliminar_perfil").on(tipoEvento, function(){
		var id = $(this).attr('id');
		eliminarPerfil(id);
	});


	function eliminarPerfil(id){
		try {
			$.ajax({
				url: '../vistas/ajax/perfiles/eliminarPerfil.php',
				method: 'POST',
				data: {'id_perfil': id},
				cache: false,
				success: function (resultado) {
					if (resultado == 'ok') {
						ohSnap("Eliminado correctamente!", {color: "green", 'duration': '1000'});
						$('#perfil'+id).fadeOut();
					} else {
						ohSnap("ha ocurrido un error!", {color: "red", 'duration': '1000'});
					}
				}
			});
		} catch (evt) {
			alert(evt.message);
		}
	}

});