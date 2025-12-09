$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    $("#buscar").click(function() {
        var documento = $("#documento").val();
        if (documento == '') {
            alert("El documento no puede estar vacio");
        } else {
            ValidarDocumento(documento);
        }
    })
    /*----------------------*/
    $(".alerta_tomada").addClass('d-none');
    $(".alerta_ya_tomada").addClass('d-none');
    $(".alerta_documento").addClass('d-none');
    /*----------------------*/
    $("#documento").keypress(function(e) {
        let code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            $("#buscar").click();
        }
    });
    /*----------------------*/
    function ValidarDocumento(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/asistencia/ValidarCedula.php',
                type: 'POST',
                data: {
                    'id': id,
                },
                cache: false,
                success: function(resultado) {
                    if (resultado == 'ok') {
                        $(".alerta_tomada").removeClass('d-none');
                        $(".alerta_ya_tomada").addClass('d-none');
                        $(".alerta_documento").addClass('d-none');
                    } else if (resultado == 'tomada') {
                        $(".alerta_ya_tomada").removeClass('d-none');
                        $(".alerta_tomada").addClass('d-none');
                        $(".alerta_documento").addClass('d-none');
                    } else {
                        $(".alerta_documento").removeClass('d-none');
                        $(".alerta_tomada").addClass('d-none');
                        $(".alerta_ya_tomada").addClass('d-none');
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function recargarPagina() {
        window.location.replace("../login");
    }
});