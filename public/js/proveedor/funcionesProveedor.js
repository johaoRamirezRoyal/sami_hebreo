$(document).ready(function() {

    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

    /*---------------------------------*/

    $("#valor_1").keyup(function() {

        var valor_1 = $("#valor_1").val();

        var valor_2 = $("#valor_2").val();

        var valor_3 = $("#valor_3").val();

        var valor_4 = $("#valor_4").val();

        var valor_5 = $("#valor_5").val();

        calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5);

    });

    /*-------------------------------------*/

    $("#valor_2").keyup(function() {

        var valor_1 = $("#valor_1").val();

        var valor_2 = $("#valor_2").val();

        var valor_3 = $("#valor_3").val();

        var valor_4 = $("#valor_4").val();

        var valor_5 = $("#valor_5").val();

        calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5);

    });

    /*-------------------------------------*/

    $("#valor_3").keyup(function() {

        var valor_1 = $("#valor_1").val();

        var valor_2 = $("#valor_2").val();

        var valor_3 = $("#valor_3").val();

        var valor_4 = $("#valor_4").val();

        var valor_5 = $("#valor_5").val();

        calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5);

    });

    /*-------------------------------------*/

    $("#valor_4").keyup(function() {

        var valor_1 = $("#valor_1").val();

        var valor_2 = $("#valor_2").val();

        var valor_3 = $("#valor_3").val();

        var valor_4 = $("#valor_4").val();

        var valor_5 = $("#valor_5").val();

        calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5);

    });

    /*-------------------------------------*/

    $("#valor_5").keyup(function() {

        var valor_1 = $("#valor_1").val();

        var valor_2 = $("#valor_2").val();

        var valor_3 = $("#valor_3").val();

        var valor_4 = $("#valor_4").val();

        var valor_5 = $("#valor_5").val();

        calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5);

    });

    /*------------------------------------------------*/

    function calcularCalificacion(valor_1, valor_2, valor_3, valor_4, valor_5) {

        $("#valor_total").html('');

        try {

            $.ajax({

                url: '../vistas/ajax/proveedor/calcularCalificacion.php',

                type: 'POST',

                data: {

                    'valor_1': valor_1,

                    'valor_2': valor_2,

                    'valor_3': valor_3,

                    'valor_4': valor_4,

                    'valor_5': valor_5,

                },

                cache: false,

                dataType: 'json',

                success: function(resultado) {

                    const total = resultado.total;
                    let html = '';

                    if (total > 5.0) {
                        html = '<h4><span class="badge badge-danger">Resultado no válido</span></h4>';
                    } else if (total === 5.0) {
                        html = '<h4><span class="badge badge-primary">' + total + '</span></h4>';
                    } else if (total >= 4.5 && total < 5.0) {
                        html = '<h4><span class="badge badge-info">' + total + '</span></h4>';
                    } else if (total >= 4.0 && total < 4.5) {
                        html = '<h4><span class="badge badge-success">' + total + '</span></h4>';
                    } else if (total > 3.5 && total < 4.0) {
                        html = '<h4><span class="badge badge-warning">' + total + '</span></h4>';
                    } else {
                        html = '<h4><span class="badge badge-danger">' + total + '</span></h4>';
                    }
                    $("#valor_total").html(html);


                    // if (resultado.total > 5.0) {

                    //     $("#valor_total").html('<h4><span class="badge badge-danger">Resultado no valido</span></h4>');

                    // }

                    // if (resultado.total == 5.0 && resultado.total >= 4.5) {

                    //     $("#valor_total").html('<h4><span class="badge badge-primary">' + resultado.total + '</span></h4>');

                    // }

                    // if (resultado.total <= 4.4 && resultado.total >= 4.0) {

                    //     $("#valor_total").html('<h4><span class="badge badge-success">' + resultado.total + '</span></h4>');

                    // }

                    // if (resultado.total <= 3.9 && resultado.total > 3.5) {

                    //     $("#valor_total").html('<h4><span class="badge badge-warning">' + resultado.total + '</span></h4>');

                    // }

                    // if (resultado.total <= 3.5) {

                    //     $("#valor_total").html('<h4><span class="badge badge-danger">' + resultado.total + '</span></h4>');

                    // }

                }

            });

        } catch (evt) {

            alert(evt.message);

        }

    }

    /*-----------------------------------------------------*/

    $(".eliminar_contacto").on(tipoEvento, function() {

        var id = $(this).attr('id');

        eliminarContacto(id);

    });

    /*------------------------------------------------*/

    function eliminarContacto(id) {

        try {

            $.ajax({

                url: '../vistas/ajax/proveedor/eliminarContacto.php',

                type: 'POST',

                data: {

                    'id': id,

                },

                cache: false,

                dataType: 'json',

                success: function(resultado) {

                    if (resultado.mensaje == 'ok') {

                        $(".contacto" + id).fadeOut();

                        ohSnap("Eliminado correctamente!", {

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

    /*-----------------------------------------------------*/

    $(".eliminar_banco").on(tipoEvento, function() {

        var id = $(this).attr('id');

        eliminarBanco(id);

    });

    /*------------------------------------------------*/

    function eliminarBanco(id) {

        try {

            $.ajax({

                url: '../vistas/ajax/proveedor/eliminarBanco.php',

                type: 'POST',

                data: {

                    'id': id,

                },

                cache: false,

                dataType: 'json',

                success: function(resultado) {

                    if (resultado.mensaje == 'ok') {

                        $(".banco" + id).fadeOut();

                        ohSnap("Eliminado correctamente!", {

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

});