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
    $('.agregar').on(tipoEvento, function() {
        var rand = Math.floor((Math.random() * 9999999) + 1);
        $(".buscar").append('<tr id="fila' + rand + '"><td><input class="form-control" name="nom_contenido[]" type="text" required></td>' + '<td class="text-center"><button class="btn btn-danger btn-sm remover_input" type="button" data-tooltip="tooltip" title="Eliminar" data-trigger="hover" id="' + rand + '"><i class="fa fa-minus"></i></button></td></tr>');
    });
    $(".buscar").on("click", ".remover_input", function() {
        var id = $(this).attr('id');
        $("#fila" + id).html("");
        $("#fila" + id).fadeOut();
    });
    /*-----------------------------*/
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
                url: '../../vistas/ajax/biblioteca/cargarResultadoPaquete.php',
                method: 'POST',
                data: {
                    'id': id,
                    'id_log': id_log
                },
                cache: false,
                success: function(resultado) {
                    $("#buscar").focus();
                    $("#buscar").val('');
                    $(".tabla_prestamos").hide();
                    $(".informacion_paquete").html(resultado);
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*-------------------------------------*/
});