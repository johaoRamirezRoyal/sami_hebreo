$(document).ready(function() {

    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

    /*------------------------------------*/

    var val = $("#documento").val();

    informacionUsuario(val)

    /*------------------------------------*/

    function informacionUsuario(id) {

        try {

            $.ajax({

                url: '../vistas/ajax/enfermeria/informacionUsuario.php',

                method: 'POST',

                data: {

                    'id': id

                },

                cache: false,

                dataType: 'json',

                success: function(resultado) {

                    if (resultado != false) {

                        $("#nombre").val(resultado.nombre + ' ' + resultado.apellido);

                        $("#nivel").val(resultado.nom_nivel);

                        $("#curso").val(resultado.nom_curso);

                        $("#id_user").val(resultado.id_user);

                    } else {

                        $("#nombre").val('');

                        $("#nivel").val('');

                        $("#curso").val('');

                        $("#id_user").val('');

                    }

                }

            });

        } catch (evt) {

            alert(evt.message);

        }

    }

    /*------------------------------------*/

    $("#motivo_consulta").change(function() {

        var id_categoria = $(this).val();

        if (id_categoria) {

            $.ajax({

                url: '../vistas/ajax/enfermeria/atencionRapida.php',

                method: 'POST',

                data: {

                    'id_categoria': id_categoria

                },

                cache: false,

                dataType: 'json',

                success: function(resultado) {

                    if (resultado != false) {

                        $("#tratamiento_atencion").val(resultado.atencion_rapida);

                    } else {

                        $("#tratamiento_atencion").val('');

                    }

                }

            });

        } else {

            $("#tratamiento_atencion").val('');

        }

    });

});
