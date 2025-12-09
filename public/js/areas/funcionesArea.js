$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    $("#id_area").change(function() {
        var id = $(this).val();
        usuarioResponsable(id);
    });
    $(".inactivar_area").on(tipoEvento, function() {
        var id = $(this).attr('data-id');
        inactivarArea(id);
    });
    $(".activar_area").on(tipoEvento, function() {
        var id = $(this).attr('data-id');
        activarArea(id);
    });

    function usuarioResponsable(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/areas/usuarioResponsable.php',
                method: "POST",
                data: {
                    'id_area': id
                },
                cache: false,
                success: function(r) {
                    if (r != 'error') {
                        $("#usuario").val(r);
                    } else {
                        $("#usuario").val('No existe responsable');
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*------------------------------------------------*/
    function inactivarArea(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/areas/inactivarArea.php',
                method: 'POST',
                data: {
                    'id_area': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('#inactivar_' + id).addClass('d-none');
                        $('#activar_' + id).removeClass('d-none');
                        ohSnap("Inactivado correctamente!", {
                            color: "yellow",
                            'duration': '1000'
                        });
                    } else {
                        ohSnap("ha ocurrido un error!", {
                            color: "red",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function activarArea(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/areas/activarArea.php',
                method: 'POST',
                data: {
                    'id_area': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('#activar_' + id).addClass('d-none');
                        $('#inactivar_' + id).removeClass('d-none');
                        ohSnap("Activado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                    } else {
                        ohSnap("ha ocurrido un error!", {
                            color: "red",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function recargarPagina() {
        window.location.replace('index');
    }
});