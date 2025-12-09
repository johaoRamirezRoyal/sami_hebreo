$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*------------------------------------------------*/
    $(".confirmar_inv").on(tipoEvento, function() {
        let id = $(this).attr('id');
        let descripcion = $(this).attr('data-nombre');
        let id_log = $(this).attr('data-log');
        let id_area = $(this).attr('data-area');
        let session = $(this).attr('data-session');
        let user = $(this).attr('data-user');
        confirmarInventario(id, descripcion, id_log, id_area, session, user);
    });

    function confirmarInventario(id, descripcion, id_log, id_area, session, user) {
        try {
            $.ajax({
                url: '../vistas/ajax/inventario/confirmarInventario.php',
                method: "POST",
                data: {
                    'id': id,
                    'descripcion': descripcion,
                    'id_log': id_log,
                    'id_area': id_area,
                    'session': session,
                    'user': user,
                },
                cache: false,
                dataType: 'json',
                success: function(r) {
                    if (r.mensaje == 'ok') {
                        $(".fila" + id).fadeOut();
                        ohSnap("Confirmado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*---------------------------------------------------*/
    /*------------------------------------------------*/
    $(".no_confirmar_inv").on(tipoEvento, function() {
        let id = $(this).attr('id');
        let descripcion = $(this).attr('data-nombre');
        let id_log = $(this).attr('data-log');
        let id_area = $(this).attr('data-area');
        let observacion = $(".observacion" + id).val();
        if (observacion == '') {
            $(".observacion" + id).focus();
            ohSnap("Campo obligatorio!", {
                color: "red",
                'duration': '1000'
            });
        } else {
            noConfirmarInventario(id, descripcion, id_log, id_area, observacion);
        }
    });

    function noConfirmarInventario(id, descripcion, id_log, id_area, observacion) {
        try {
            $.ajax({
                url: '../vistas/ajax/inventario/noConfirmarInventario.php',
                method: "POST",
                data: {
                    'id': id,
                    'descripcion': descripcion,
                    'id_log': id_log,
                    'id_area': id_area,
                    'observacion': observacion
                },
                cache: false,
                dataType: 'json',
                success: function(r) {
                    if (r.mensaje == 'ok') {
                        $(".fila" + id).fadeOut();
                        ohSnap("Guardado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*------------------------------------------------*/
    $(".agregar_inv").on(tipoEvento, function() {
        let id = $(this).attr('data-id');
        agregarInventario(id);
    });

    function agregarInventario(id) {
        try {
            $.ajax({
                url: '../vistas/ajax/inventario/agregarInventario.php',
                method: "POST",
                data: {
                    'id': id
                },
                cache: false,
                dataType: 'json',
                success: function(r) {
                    if (r.mensaje == 'ok') {
                        ohSnap("Agregado correctamente!", {
                            color: "green",
                            'duration': '1000'
                        });
                        setTimeout(recargarPagina, 1050);
                    }
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
    /*---------------------------------------------------*/
    /*---------------------------------------------------*/
    $("#enviar_inventario").on(tipoEvento, function(e) {
        e.preventDefault();
        let formulario = new FormData();
        formulario.append('archivo', $("#file")[0].files[0]);
        formulario.append('descripcion', $('#descripcion').prop('value'));
        formulario.append('marca', $('#marca').prop('value'));
        formulario.append('modelo', $('#modelo').prop('value'));
        formulario.append('precio', $('#precio').prop('value'));
        formulario.append('fecha', $('#fecha').prop('value'));
        formulario.append('usuario', $('#usuario').prop('value'));
        formulario.append('area', $('#area').prop('value'));
        formulario.append('cantidad', $('#cantidad').prop('value'));
        formulario.append('super_empresa', $('#super_empresa').prop('value'));
        formulario.append('id_log', $('#id_log').prop('value'));
        formulario.append('categoria', $('#categoria').prop('value'));
        formulario.append('fecha_ingreso', $('#fecha_ingreso').prop('value'));
        if ($('#cantidad').val() == '' || $('#area').val() == '' || $('#usuario').val() == '' || $("#descripcion").val() == '' || $('#categoria').val() == '' || $('#fecha_ingreso').val() == '') {
            $('#cantidad').focus();
            $('#area').focus();
            $('#usuario').focus();
            $('#descripcion').focus();
            $('#categoria').focus();
            ohSnap("Campos obligatorios vacios!", {
                color: "red",
                'duration': '1000'
            });
        } else {
            $.ajax({
                type: "POST",
                url: '../vistas/ajax/inventario/inventarioTemp.php',
                data: formulario,
                processData: false,
                cache: false,
                dataType: "text",
                contentType: false,
                success: function(r) {
                    $("#tabla_inventario").prepend(r);
                    $("#carta_entrega").removeClass('disabled');
                    $("#agr_inventario")[0].reset();
                    $("#carta_entrega").removeClass('disabled');
                    ohSnap("Registrados Correctamente!", {
                        color: "green",
                        "duration": "1000"
                    });
                    $('#descripcion').val('');
                    $('#cantidad').val('');
                    $('#area').val('');
                    $('#usuario').val('');
                    $('#categoria').val('');
                    $('#select2-area-container').text('');
                    $('#select2-usuario-container').text('');
                    $('#select2-categoria-container').text('');
                }
            });
        }
    });
    /*--------------------------------------------*/
    $("#file").change(function() {
        let id = $(this).attr('id');
        cambiarFile(id);
    });

    function cambiarFile(id) {
        const input = document.getElementById(id);
        let fileSize = input.files[0]['size'];
        let siezekiloByte = parseInt(fileSize / 1024);
        if (input.files && input.files[0] && siezekiloByte < 3072) {
            $("#archivo_" + id).text(input.files[0]['name']);
            $(".tooltip").hide();
        } else {
            $(".tooltip").hide();
            $(".tooltip").show();
            $("#" + id).focus();
            $("#" + id).attr('data-toggle', 'tooltip');
            $("#" + id).addClass('border border-danger');
            $("#" + id).tooltip({
                title: "Limite de 3mb excedido",
                trigger: "focus",
                placement: "bottom"
            });
            $("#" + id).tooltip('show');
            $("#" + id).val('');
            $("#archivo_" + id).text('');
        }
    }

    function fileValidation() {
        let fileInput = document.getElementById('file');
        let filePath = fileInput.value;
        let allowedExtensions = /(.jpg|.jpeg|.png)$/i;
        if (!allowedExtensions.exec(filePath)) {
            fileInput.value = '';
            return false;
        } else {
            return true;
        }
    }
    /*--------------------------------------------*/
    function recargarPagina() {
        window.location.replace('confirmar');
    }
});