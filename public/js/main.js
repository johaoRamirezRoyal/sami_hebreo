$(document).ready(function() {
    //$('[data-toggle="tooltip"]').tooltip();
    $('[data-tooltip="tooltip"]').tooltip();
    $('[data-popover="popover"]').popover();
    $('.clockpicker').clockpicker();
    $(".loader").fadeOut("slow");
    $("table thead").addClass('text-uppercase');
    $("select").select2();
    /*-------------------*/
    $(".file_input").change(function() {
        let id = $(this).attr('id');
        let valor = $(this).val().split('\\').pop();
        if (id == '') {
            $(".file_label").text(valor);
        } else {
            $(".file_label_" + id).text(valor);
        }
        if (valor == '' && id == '') {
            $(".file_label").text('Falta archivo');
            $(this).val('')
        } else {
            $(".file_label").text(valor);
        }
        if (valor == '' && id != '') {
            $(".file_label_" + id).text('Falta archivo');
            $('#' + id).val('')
        } else {
            $(".file_label_" + id).text(valor);
        }
    });
    /*--------------------*/
    $(".user").focus();
    $('.filtro').keyup(function() {
        var rex = new RegExp($(this).val(), 'i');
        $('.buscar tr').hide();
        $('.buscar tr').filter(function() {
            return rex.test($(this).text());
        }).show();
    });
    $(".user").keyup(function() {
        minus(this);
    });
    $('.filtro_change').change(function() {
        var rex = new RegExp($(this).val(), 'i');
        $('.buscar tr').hide();
        $('.buscar tr').filter(function() {
            return rex.test($(this).text());
        }).show();
    });
    $(".numeros").keypress(function(e) {
        soloNumeros(e);
    });
    $(".letras").keypress(function(e) {
        return soloLetras(e)
    });

    function soloNumeros(e) {
        var key = window.event ? e.which : e.keyCode;
        if (key < 48 || key > 57) {
            e.preventDefault();
        }
    }

    function minus(e) {
        e.value = e.value.toLowerCase();
    }

    function soloLetras(e) {
        key = e.keyCode || e.which;
        tecla = String.fromCharCode(key).toLowerCase();
        letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
        especiales = "8-37-39-46";
        tecla_especial = false
        for (var i in especiales) {
            if (key == especiales[i]) {
                tecla_especial = true;
                break;
            }
        }
        if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            return false;
        }
    }
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(".clock").removeClass('clock');
    }

    function goBack() {
        window.history.back();
    }
    /*-------------------------------------------*/
    var id = $("#id_log_inicio").val();
    validarFirmaSubida(id);

    function validarFirmaSubida(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/validarFirma.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado.mensaje == 'ok') {
                        $("#subir_firma").modal('hide');
                    } else {
                        $("#subir_firma").modal('show');
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});