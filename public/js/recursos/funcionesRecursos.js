$(document).ready(function() {

    /*---------------------*/

    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

    /*---------------------*/

    $("#anio").hide();

    $("#file_doc").hide();

    $("#url_archivo").hide();

    $(".file_doc_edit").hide();

    $(".url_archivo_edit").hide();

    /*------------------------*/

    $(".tipo_doc").click(function() {

        var id = $(this).attr('id');

        if (id == 2) {

            $("#anio").show();

        } else {

            $("#anio").hide();

        }

    });

    /*--------------------*/

    $("#cate_doc").change(function() {

        var val = $(this).val();

        if (val == 1) {

            $("#url_archivo").show();

            $("#file_doc").hide();

            $("#url_archivo").attr('required', true);

        } else {

            $("#url_archivo").hide();

            $("#file_doc").show();

            $("#file_doc").attr('required', true);

        }

    });

    /*------------------------------*/

    $(".editar").on(tipoEvento, function() {

        var categoria = $(this).attr('data-categoria');

        var id = $(this).attr('id');

        if (categoria == 1) {

            $(".url_archivo_edit_" + id).show();

            $(".file_doc_edit_" + id).hide();

            $(".url_archivo_edit_" + id).attr('required', true);

        } else {

            $(".url_archivo_edit_" + id).hide();

            $(".file_doc_edit_" + id).show();

            $(".file_doc_edit_" + id).attr('required', true);

        }

    });

    /*--------------------*/

    $(".cate_doc_edit").change(function() {

        var val = $(this).val();

        var id = $(this).attr('id');

        if (val == 1) {

            $(".url_archivo_edit_" + id).show();

            $(".file_doc_edit_" + id).hide();

            $(".url_archivo_edit_" + id).attr('required', true);

        } else {

            $(".url_archivo_edit_" + id).hide();

            $(".file_doc_edit_" + id).show();

            $(".file_doc_edit_" + id).attr('required', true);

        }

    });

    /*----------------------*/

    $(".eliminar").on(tipoEvento, function() {

        var id = $(this).attr('id');

        var log = $(this).attr('data-log');

        eliminarDocumento(id, log)

    });

    /*----------------------*/

    $(".tipo_tramite").change(function() {

        var id = $(".tipo_tramite option:selected").val();

        var correo = $("#correo").val();

        $(".formulario_tipo").html('');

        mostrarFormularioTipoTramite(id, correo);

    });

    /*----------------------*/

    $(".tipo_permiso").change(function() {

        var id = $(".tipo_permiso option:selected").val();

        $(".formulario_permiso").html('');

        mostrarFormularioPermiso(id);

    });

    /*------------------*/

    function eliminarDocumento(id, log) {

        $.ajax({

            type: "POST",

            url: '../vistas/ajax/recursos/eliminarDocumento.php',

            data: {

                'id': id,

                'log': log

            },

            dataType: "JSON",

            success: function(r) {

                if (r == true) {

                    ohSnap("Eliminado Correctamente!", {

                        color: "yellow",

                        "duration": "1000"

                    });

                    $('.documento_' + id).fadeOut();

                } else {

                    ohSnap("Error al eliminar!", {

                        color: "red",

                        "duration": "1000"

                    });

                }

            }

        })

    }

    /*------------------*/

    function mostrarFormularioTipoTramite(id, correo, id_log) {

        $.ajax({

            type: "POST",

            url: '../../vistas/ajax/recursos/mostrarFormularioTipoTramite.php',

            data: {

                'id': id,

                'correo': correo,

                'id_log': id_log

            },

            success: function(r) {

                if (r != '' && id != '') {

                    $(".formulario_tipo").html(r);

                }

            }

        })

    }

    /*------------------*/

    function mostrarFormularioPermiso(id) {

        $.ajax({

            type: "POST",

            url: '../../vistas/ajax/recursos/mostrarFormularioTipoPermiso.php',

            data: {

                'id': id

            },

            success: function(r) {

                if (r != '' && id != '') {

                    $(".formulario_permiso").html(r);

                }

            }

        })

    }

});