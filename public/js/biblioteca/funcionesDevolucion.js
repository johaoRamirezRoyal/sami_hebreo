$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*------------------------------------*/
    $("#buscar").focus();
    $("#buscar").keypress(function(e) {
        let code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            $(".buscar").click();
        }
    });
    /*------------------------------------*/
    $(".buscar").on(tipoEvento, function() {
        let id = $("#buscar").val();
        let id_log = $("#id_log").val();
        $(".informacion_libro").html('');
        if (id != '') {
            cargarDevolucionLibro(id, id_log)
        }
    });
    /*-------------------------------------*/
    function cargarDevolucionLibro(id, id_log) {
        try {
            $.ajax({
                url: '../../vistas/ajax/biblioteca/cargarDevolucionLibro.php',
                method: 'POST',
                data: {
                    'id': id,
                    'id_log': id_log
                },
                cache: false,
                success: function(resultado) {
                    $("#buscar").focus();
                    $("#buscar").val('');
                    $(".tabla_devolucion").hide();
                    $(".informacion_libro").html(resultado);
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*-------------------------------------*/
});