$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*-----------------------*/

    $("#sonido").attr('disabled', true);
    $("#portatil").attr('disabled', true);
    $(".hora").html('');

    //$(".hora").selectpicker();

    /*---------------------*/

    $("#salon").change(function() {
        var id = $(this).val();
        var fecha = $("#fecha").val();
        $(".hora").html('');
        mostrarOpcionesSalon(id);
        mostrarHoras(id, fecha);
        contarPortatil(fecha);
    });

    $("#fecha").change(function() {
        var id = $("#salon").val();
        var fecha = $(this).val();
        $(".hora").html('');
        validarFecha(fecha, id);
    });

    /*--------------------------------------*/

    $(".aprobar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        aprobarReserva(id);
    });

    /*----------------------------------------*/

    $(".rechazar").on(tipoEvento, function() {
        var id = $(this).attr('id');
        rechazarReserva(id)
    });

    /*--------------------------------------*/

    /*$("#reservar").on(tipoEvento, function(e) {

        e.preventDefault();

        var salon = $("#salon").val();

        var fecha = $("#fecha").val();

        var hora_inicio = $("#hora_inicio").val();

        var hora_fin = $("#hora_fin").val();

        validarFechaReserva(salon, fecha, hora_inicio, hora_fin)

    });*/

    /*--------------------------------------*/

    function mostrarOpcionesSalon(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/salon/opcionesSalon.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    $("#sonido").attr('disabled', false);
                    $("#portatil").attr('disabled', false);
                    if (resultado.sonido == 'si' && resultado.portatil == 'si') {
                        $(".sonido").attr('checked', true);
                        $(".portatil").attr('checked', true);
                    }
                    if (resultado.sonido == 'no' && resultado.portatil == 'si') {
                        $(".sonido").attr('checked', false);
                        $(".portatil").attr('checked', true);
                    }
                    if (resultado.sonido == 'si' && resultado.portatil == 'no') {
                        $(".sonido").attr('checked', true);
                        $(".portatil").attr('checked', false);
                    }
                    if (resultado.sonido == 'no' && resultado.portatil == 'no') {
                        $(".sonido").attr('checked', false);
                        $(".portatil").attr('checked', false);
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*-----------------------------------*/

    function mostrarHoras(salon, fecha) {
        try {
            $.ajax({
                url: '../vistas/ajax/salon/validar.php',
                method: 'POST',
                data: {
                    'salon': salon,
                    'fecha': fecha,
                },
                cache: false,
                dataType: 'json',
                success: function(r) {
                    var array = r.horas_activas;
                    array.forEach(function(datos) {
                        $(".hora").append('<div class="custom-control custom-switch ml-4">' + '<input type="checkbox" class="custom-control-input" id="' + datos.id_hora + '" value="' + datos.id_hora + '" name="horas[]">' + '<label class="custom-control-label" for="' + datos.id_hora + '">' + datos.horas + '</label>' + '</div>');
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    /*-------------------------------------*/
function validarFecha(fecha, id) {
    var d = new Date();
    var dias = (d.getDate() < 10) ? '0' + d.getDate() : d.getDate();
    var mes = ((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1);
    var minutos = (d.getMinutes() < 10) ? '0' + d.getMinutes() : d.getMinutes();
    var horas = (d.getHours() < 10) ? '0' + d.getHours() : d.getHours();
    var fecha_actual = d.getFullYear() + "-" + mes + "-" + dias;
    var dias_manana = ((d.getDate() + 1) < 10) ? '0' + (d.getDate() + 1) : (d.getDate() + 1);
    var fecha_manana = d.getFullYear() + "-" + mes + "-" + dias_manana;
    var hora = horas + ':' + minutos;

    if (hora >= '12:00' && fecha == fecha_manana) {
        ohSnap("No puedes realizar una reserva despues de 12 del medio dia!", {
            color: "red",
            "duration": "1500"
        });
        $("#fecha").val('');
        $("#fecha").focus();
        return;
    }

    if (fecha == fecha_actual) {
        ohSnap("No puedes reservar para el mismo Dia!", {
            color: "red",
            "duration": "1500"
        });
        $("#fecha").val('');
        $("#fecha").focus();
        return;
    }

    if (fecha < fecha_actual) {
        ohSnap("No puedes reservar para el dia anterior!", {
            color: "red",
            "duration": "1500"
        });
        $("#fecha").val('');
        $("#fecha").focus();
        return;
    }

    // ---- Validación principal ----
    var day = new Date(fecha + "T00:00:00").getDay(); // parse seguro
    var day_actual = new Date(fecha_actual + "T00:00:00").getDay();

    if (day == 0) {
        ohSnap("No puedes realizar una reserva los fines de semana!", {
            color: "red",
            "duration": "1500"
        });
        $("#fecha").val('');
        $("#fecha").focus();
        return;
    }

    // 🔹 Validación viernes-sábado-domingo → bloquear semana siguiente
 var hoy = new Date();
var diaHoy = hoy.getDay(); // 0=domingo ... 6=sábado

// calcular lunes de la semana próxima (no saltar dos semanas)
var lunesProx = new Date(hoy);
lunesProx.setDate(hoy.getDate() + ((8 - diaHoy) % 7));  // lunes siguiente

// sábado de esa semana
var sabadoProx = new Date(lunesProx);
sabadoProx.setDate(lunesProx.getDate() + 5);

// fecha de la reserva
var fechaRes = new Date(fecha + "T00:00:00");

// si hoy es viernes, sábado o domingo → bloquear esa semana completa
if ((diaHoy == 5 || diaHoy == 6 || diaHoy == 0) && 
    fechaRes >= lunesProx && fechaRes <= sabadoProx) {

    ohSnap("No puedes reservar la siguiente semana si hoy es viernes, sábado o domingo!", {
        color: "red",
        "duration": "2000"
    });
    $("#fecha").val('');
    $("#fecha").focus();
    return;
}

    if (day_actual == 6 && day == 1) {
        ohSnap("No puedes realizar una reserva de sabado a lunes!", {
            color: "red",
            "duration": "1500"
        });
        $("#fecha").val('');
        $("#fecha").focus();
        return;
    }

    // si pasó todas las validaciones
    contarPortatil(fecha);
    mostrarHoras(id, fecha);
}

    /*-----------------------------------*/

    function contarPortatil(fecha) {
        try {
            $.ajax({
                url: '../vistas/ajax/salon/contarPortatil.php',
                method: 'POST',
                data: {
                    'fecha': fecha
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado.cantidad == 0) {
                        $(".portatil").attr('disabled', true);
                        $(".portatil").attr('checked', false);
                        $("#portatil_label").text('Portatil disponibles para la fecha ' + fecha + " (" + resultado.cantidad + ')');
                    } else {
                        $(".portatil").attr('disabled', false);
                        $(".portatil").attr('checked', true);
                        $("#portatil_label").text('Portatil disponibles para la fecha ' + fecha + " (" + resultado.cantidad + ')');
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    /*-----------------------*/

    /*--------------------------------------*/

    function aprobarReserva(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/salon/aprobar.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $(".botones_" + id).hide();
                        $(".span_" + id).html('<span class="badge badge-success">Aprobada</span>');
                        ohSnap("Aprobado correctamente!", {
                            color: "green",
                            "duration": "1000"
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    /*-----------------------------------*/

    function rechazarReserva(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/salon/rechazar.php',
                method: 'POST',
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $(".botones_" + id).hide();
                        $(".span_" + id).html('<span class="badge badge-danger">Rechazado</span>');
                        ohSnap("Rechazado correctamente!", {
                            color: "yellow",
                            "duration": "1000"
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    /*setInterval(function() {

        var d = new Date();

        var dias = (d.getDate() < 10) ? '0' + (d.getDate() + 1) : (d.getDate() + 1);

        var mes = ((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1);

        var strDate = d.getFullYear() + "-" + mes + "-" + dias;

        contarPortatil(strDate);

    }, 900);*/
    var d = new Date();
    var dias = ((d.getDate() + 1) < 10) ? '0' + (d.getDate() + 1) : (d.getDate() + 1);
    var mes = ((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1);
    var strDate = d.getFullYear() + "-" + mes + "-" + dias;
    contarPortatil(strDate);
});