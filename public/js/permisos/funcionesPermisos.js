$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    $(".lista_permiso").on(tipoEvento, function(e) {
        e.preventDefault();
        var opcion = $(this).attr('id');
        var user = $(this).attr('data-user');
        var perfil = $(this).attr('data-perfil');
        var nombre = $(this).attr('data-nombre');
        activarPermiso(opcion, perfil, user, nombre);
    });
    $(".lista_inactivar").on(tipoEvento, function(e) {
        e.preventDefault();
        var opcion = $(this).attr('id');
        var user = $(this).attr('data-user');
        var perfil = $(this).attr('data-perfil');
        var nombre = $(this).attr('data-nombre');
        inactivarPermiso(opcion, perfil, user, nombre);
    });

    function activarPermiso(opcion, perfil, user, nombre) {
        try {
            $.ajax({
                url: '../vistas/ajax/permisos/activarPermiso.php',
                type: 'POST',
                data: {
                    'opcion': opcion,
                    'perfil': perfil,
                    'user': user
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado.mensaje == 'ok') {
                        $(".opcion_" + opcion).addClass('active lista_inactivar disabled');
                        $(".opcion_" + opcion).removeClass('lista_permiso disabled');
                        $(".opcion_" + opcion).html(nombre + '<i class="fa fa-times float-right"></i>');
                        ohSnap("Permiso concedido", {
                            color: "green",
                            duration: "1000"
                        });
                    } else {
                        ohSnap("Error al conceder permiso", {
                            color: "red"
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*--------------------------*/
    $(".permisos_body").on(tipoEvento, '.lista_inactivar', function(e) {
        e.preventDefault();
        var opcion = $(this).attr('id');
        var user = $(this).attr('data-user');
        var perfil = $(this).attr('data-perfil');
        var nombre = $(this).attr('data-nombre');
        inactivarPermiso(opcion, perfil, user, nombre);
    });
    /*-------------------------------*/
    $(".permisos_body").on(tipoEvento, '.lista_permiso', function(e) {
        e.preventDefault();
        var opcion = $(this).attr('id');
        var user = $(this).attr('data-user');
        var perfil = $(this).attr('data-perfil');
        var nombre = $(this).attr('data-nombre');
        activarPermiso(opcion, perfil, user, nombre);
    });
    /*-------------------------------*/
    function inactivarPermiso(opcion, perfil, user, nombre) {
        try {
            $.ajax({
                url: '../vistas/ajax/permisos/inactivarPermiso.php',
                type: 'POST',
                data: {
                    'opcion': opcion,
                    'perfil': perfil,
                    'user': user
                },
                cache: false,
                dataType: 'json',
                success: function(resultado) {
                    if (resultado.mensaje == 'ok') {
                        $(".opcion_" + opcion).addClass('lista_permiso disabled');
                        $(".opcion_" + opcion).removeClass('active lista_inactivar disabled');
                        $(".opcion_" + opcion).html(nombre + '<i class="fa fa-check float-right"></i>');
                        ohSnap("Permiso removido", {
                            color: "red",
                            duration: "1000"
                        });
                    } else {
                        ohSnap("Error al remover permiso", {
                            color: "red"
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*------------------------*/
    function recargarPagina() {
        window.location.replace("index");
    }
});