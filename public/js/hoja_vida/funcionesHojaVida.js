$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

    $(".hardware").on(tipoEvento, function() {
        var id = $(this).attr('id');
        var id_hoja = $("#id_hoja").val();
        var id_log = $("#id_log").val();
        asignarComponenteHardware(id, id_log, id_hoja);
    });


    $("#desc_codigo").on(tipoEvento, function() {
        var codigo = $("#codigo").val();
        descargarCodigo(codigo);
    });


    function asignarComponenteHardware(id, id_log, id_hoja) {
        try {
            $.ajax({
                url: '../vistas/ajax/hoja_vida/asignarComponenteHardware.php',
                method: "POST",
                data: { 'id_inventario': id, 'id_log': id_log, 'id_hoja_vida': id_hoja },
                success: function(r) {
                    if (r == 'ok') {
                        $("#" + id).css('display', 'none');
                        $(".span-" + id).append('<span class="badge badge-success">Asignado</span>');
                        ohSnap("Asignado correctamente!", { color: "green", 'duration': '1000' });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }


    function descargarCodigo(codigo) {
        try {
            $.ajax({
                url: '../vistas/ajax/hoja_vida/codigo.php',
                method: "POST",
                data: { 'codigo': codigo },
                success: function(r) {
                    if (r) {
                        $("#desc_codigo").attr('download', r);
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

});