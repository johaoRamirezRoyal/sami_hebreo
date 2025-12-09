$(document).ready(function() {

    $("#cant_hermanos").keyup(function() {

        var valor = $(this).val();

        if (valor == '' || valor == 0) {
            $("#button-agregar").attr('disabled', true);
        } else {
            $("#button-agregar").attr('disabled', false);
        }

    });

    $(".enviar_datos").click(function() {
        var form = $("#form_hermanos");
        guardar(form);
    });


    function guardar(form) {
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/padres/guardar_hermano.php',
            data: form.serialize(),
            success: function(r) {
                $("#tabla_hermanos").append(r);
                $("#form_hermanos")[0].reset();
            }
        });
    }

});