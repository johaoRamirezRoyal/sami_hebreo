$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*------------------------------------*/
    $("#categoria").change(function() {
        $('#subcategoria').html('');
        let id = $(this).val();
        if (id == '') {} else {
            cargarSubcategorias(id);
        }
    });
    /*--------------------------*/
    $(".inactivar_ejemplar").attr('disabled', true);
    $('.check').change(function() {
        if ($('.check').is(':checked')) {
            $(".inactivar_ejemplar").attr('disabled', false);
        } else {
            $(".inactivar_ejemplar").attr('disabled', true);
        }
    });
    $(".inactivar_ejemplar").click(function() {
        $("#form_ejemplar").submit();
    });
    /*-------------------------*/
    function cargarSubcategorias(id) {
        try {
            $.ajax({
                url: '../../vistas/ajax/biblioteca/cargarSubcategorias.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: "json",
                success: function(resultado) {
                    $('#subcategoria').append($('<option />', {
                        text: 'Seleccione una sub-categoria...',
                        value: '',
                    }));
                    $.each(resultado, function(key, valor) {
                        $('#subcategoria').append($('<option />', {
                            text: valor.nombre,
                            value: valor.id,
                        }));
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});