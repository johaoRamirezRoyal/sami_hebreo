$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    $("#enviar_datos").click(function(e) {
        e.preventDefault();
        var user = $("#user_name").val();
        var doc = $("#doc_user").val();
        var nombre = $("#nombre").val();
        var apellido = $("#apellido").val();
        var telefono = $("#telefono").val();
        var correo = $("#correo").val();
        var perfil = $("#perfil").val();
        var con = $("#password").val();
        var conf = $("#conf_password").val();
        var nivel = $("#nivel").val();
        if (user == "" || doc == "" || nombre == "" || correo == "" || perfil == "" || con == "" || conf == "" || nivel == "") {
            e.preventDefault();
            ohSnap("Favor completar todos los campos con un *", {
                color: "red",
                'duration': '1000'
            });
        } else {
            validarUsuario(user);
        }
    });
    $("#user_name").keyup(function() {
        $(".tooltip").hide();
        $("#user_name").removeClass('border border-danger');
    });
    $("#doc_user").keyup(function() {
        $(".tooltip").hide();
        $("#doc_user").removeClass('border border-danger');
    });
    $(".inactivar_user").on(tipoEvento, function() {
        var id = $(this).attr('data-id');
        inactivarUsuario(id);
    });
    $(".activar_user").on(tipoEvento, function() {
        var id = $(this).attr('data-id');
        activarUsuario(id);
    });
    /*     $(".eliminar_user").on(tipoEvento, function() {
            var id = $(this).attr('id');
            eliminarUsuario(id);
        });
        */
    $(".enviar_editar").on(tipoEvento, function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var pass = $(".pass_editar" + id).val();
        var conf_pass = $(".conf_pass_editar" + id).val();
        if (conf_pass != pass) {
            $(".tooltip").show();
            $(".conf_pass_editar" + id).focus();
            $(".conf_pass_editar" + id).attr('data-toggle', 'tooltip');
            $(".conf_pass_editar" + id).addClass('border border-danger');
            $(".conf_pass_editar" + id).tooltip({
                title: "La contraseña no coincide",
                trigger: "focus",
                placement: "right"
            });
            $(".conf_pass_editar" + id).tooltip('show');
        } else {
            $(".conf_pass_editar" + id).removeClass('border border-danger').addClass('');
            $(".tooltip").hide();
            $(".form_enviar_editar" + id).submit();
        }
    });

    function validarDocumento(doc, user) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/validarDocumento.php',
                method: 'POST',
                data: {
                    'documento': doc
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $(".tooltip").show();
                        $("#doc_user").focus();
                        $("#doc_user").attr('data-toggle', 'tooltip');
                        $("#doc_user").addClass('border border-danger');
                        $("#doc_user").tooltip({
                            title: "Documento ya existe",
                            trigger: "focus",
                            placement: "right"
                        });
                        $("#doc_user").tooltip('show');
                    } else {
                        validarUsuario(user);
                        //$("#form_enviar").submit();
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function validarUsuario(user) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/validarUsuario.php',
                method: 'POST',
                data: {
                    'usuario': user
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $(".tooltip").show();
                        $("#user_name").focus();
                        $("#user_name").attr('data-toggle', 'tooltip');
                        $("#user_name").addClass('border border-danger');
                        $("#user_name").tooltip({
                            title: "Usuario ya existe",
                            trigger: "focus",
                            placement: "right"
                        });
                        $("#user_name").tooltip('show');
                    } else {
                        $("#form_enviar").submit();
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function inactivarUsuario(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/inactivarUsuario.php',
                method: 'POST',
                data: {
                    'id_user': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('#inactivar_' + id + '').addClass('d-none');
                        $('#activar_' + id + '').removeClass('d-none');
                        ohSnap("Inactivado correctamente!", {
                            color: "red",
                            'duration': '1000'
                        });
                        /*setTimeout(recargarPagina, 1050);*/
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

    function activarUsuario(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/usuarios/activarUsuario.php',
                method: 'POST',
                data: {
                    'id_user': id
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $('#inactivar_' + id + '').removeClass('d-none');
                        $('#activar_' + id + '').addClass('d-none');
                        ohSnap("Activado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                        /*setTimeout(recargarPagina, 1050);*/
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
    /*     function eliminarUsuario(id) {
            try {
                $.ajax({
                    url: '../vistas/ajax/usuarios/eliminarUsuario.php',
                    method: 'POST',
                    data: { 'id_user': id },
                    cache: false,
                    success: function(resultado) {
                        if (resultado == 'ok') {
                            ohSnap("Eliminado correctamente!", { color: "green", 'duration': '1000' });
                            $('#usuario' + id).fadeOut();
                        } else {
                            ohSnap("ha ocurrido un error!", { color: "red", 'duration': '1000' });
                        }
                    }
                });
            } catch (evt) {
                alert(evt.message);
            }
        } */
    function recargarPagina() {
        window.location.replace('index');
    }
});