$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*--------------------------*/
    $(".visto").on(tipoEvento, function() {
        var id = $(this).attr('id');
        vistoBuenoReporte(id);
    });
    /*--------------------------------------*/
    function vistoBuenoReporte(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/reportes/vistoBueno.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado.mensaje == 'ok') {
                        $(".reporte" + id).fadeOut();
                        ohSnap("Firmado correctamente!", {
                            color: "green",
                            "duration": "1000"
                        });
                        //setTimeout(recargarPagina, 1050);
                    } else {
                        ohSnap("Error al firmar!", {
                            color: "red",
                            "duration": "1000"
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*--------------------------*/
    function recargarPagina() {
        window.location.replace("visto");
    }
});