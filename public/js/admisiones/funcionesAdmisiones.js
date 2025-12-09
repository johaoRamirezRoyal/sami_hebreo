$(document).ready(function() {

    $(".habilitar_form").click(function() {
        var id = $(this).attr('id');
        habilitarFormato(id);
    });


    function habilitarFormato(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/admisiones/habilitar.php',
                method: 'POST',
                data: { 'id': id },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        ohSnap("Habilitado correctamente!", { color: "green", "duration": "1000" });
                        setTimeout(recargarPagina, 1050);
                    } else {
                        ohSnap("Error!", { color: "red", "duration": "1000" });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function recargarPagina() {
        window.location.replace("index");
    }

});