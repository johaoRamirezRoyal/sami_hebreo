$(document).ready(function() {

    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');

    $("#enviar_perfil").on(tipoEvento, function(e) {
        e.preventDefault();
        var pass_new = $("#password").val();
        var conf_password = $("#conf_password").val();

        if (conf_password != pass_new) {
            $(".tooltip").show();
            $("#conf_password").focus();
            $("#conf_password").attr('data-toggle', 'tooltip');
            $("#conf_password").addClass('border border-danger');
            $("#conf_password").tooltip({ title: "La contraseña no coincide", trigger: "focus", placement: "right" });
            $("#conf_password").tooltip('show');
        } else {
            $("#conf_password").removeClass('border border-danger').addClass('');
            $(".tooltip").hide();
            $("#form_enviar").submit();
        }

    });


    $("#conf_password").keyup(function() {
        var pass_new = $("#password").val();
        var conf_password = $("#conf_password").val();

        if (conf_password != pass_new) {
            $(".tooltip").show();
            $("#conf_password").focus();
            $("#conf_password").attr('data-toggle', 'tooltip');
            $("#conf_password").addClass('border border-danger');
            $("#conf_password").tooltip({ title: "La contraseña no coincide", trigger: "focus", placement: "right" });
            $("#conf_password").tooltip('show');
        } else {
            ('');
            $("#conf_password").removeClass('border border-danger').addClass('border border-success');
            $(".tooltip").hide();

        }
    });

});