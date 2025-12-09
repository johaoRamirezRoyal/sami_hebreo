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
    /*------------------------*/
    $(".buscar").on(tipoEvento, function() {
        let id = $("#buscar").val();
        let id_log = $("#id_log").val();
        $(".informacion_paquete").html('');
        if (id != '') {
            cargarInformacionPaquete(id, id_log)
        }
    });
     /*-------------------------------*/
    function cargarInformacionPaquete(id, id_log) {
        try {
            $.ajax({
                url: '../../vistas/ajax/biblioteca/cargarResultadoPaqueteDevolucion.php',
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
                    $(".informacion_paquete").html(resultado);
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});