$(document).ready(function() {
    /*----------------------*/
    /*---------------------*/
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /**-----------------------------------------**/
    $('.agregar_producto').on(tipoEvento, function() {
        var rand = Math.floor((Math.random() * 9999999) + 1);
        $(".buscar").append('<tr id="fila' + rand + '"><td><input class="form-control" name="producto[]" type="text" required></td>' + '<td><input class="form-control numeros text-center" name="cantidad[]" type="text" required></td><td class="text-center"><button class="btn btn-danger btn-sm remover_input" type="button" data-tooltip="tooltip" title="Eliminar" data-trigger="hover" id="' + rand + '"><i class="fa fa-minus"></i></button></td></tr>');
    });
    $(".buscar").on("click", ".remover_input", function() {
        var id = $(this).attr('id');
        $("#fila" + id).html("");
        $("#fila" + id).fadeOut();
    });
    /*------------------------*/
    $(".precio").on({
        "focus": function(event) {
            $(event.target).select();
        },
        "keyup": function(event) {
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "").replace(/([0-9])([0-9]{2})$/, '$1$2').replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        }
    });
    /*------------------------------------------*/
    $(".buscar").on("click", ".iva_no", function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', false);
        }
    });
    $(".buscar").on("click", ".iva_si", function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', false);
        }
    });
    $(".buscar").on("click", ".iva_incluido", function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', true);
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', false);
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', false);
        }
    });
    $('.iva_no').change(function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', true);
            $('input[data-log=' + id + '][id="iva_incluido"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', false);
            $('input[data-log=' + id + '][id="iva_incluido"]').attr('disabled', false);
        }
    });
    $('.iva_si').change(function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', true);
            $('input[data-log=' + id + '][id="iva_incluido"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', false);
            $('input[data-log=' + id + '][id="iva_incluido"]').attr('disabled', false);
        }
    });
    $('.iva_incluido').change(function() {
        var id = $(this).attr('data-log');
        if ($(this).is(':checked')) {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', true);
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', true);
            var form = $('#prefactura');
            calcular(form);
        } else {
            $('input[data-log=' + id + '][id="iva_si"]').attr('disabled', false);
            $('input[data-log=' + id + '][id="iva_no"]').attr('disabled', false);
        }
    });
    /*--------------------------------------*/
    $("#estado").change(function() {
        var val = $(this).val();
        if (val == 3 || val == 4) {
            $("#fecha_aplazado").attr('required', true);
        }
    });
    $(".valor_unt").keyup(function() {
        var form = $('#prefactura');
        calcular(form);
    });
    /* $("#iva_no").change(function() {
         var form = $('#prefactura');
         calcular(form);
     });
     $("#iva_si").change(function() {
         var form = $('#prefactura');
         calcular(form);
     });*/
    $(".cantidad").keyup(function() {
        var form = $('#prefactura');
        calcular(form);
    });

    function calcular(form) {
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/prefactura/calcular.php',
            data: form.serialize(),
            dataType: "JSON",
            success: function(r) {
                var array = r.totales;
                array.forEach(function(datos) {
                    $("#" + datos.id_producto).val(number_format_js(datos.total_unidad, 0, '.', ','));
                });
                $("#subtotal").val(number_format_js(r.subtotal, 0, '.', ','));
                $("#iva").val(number_format_js(r.iva, 0, '.', ','));
                $("#total_final").val(number_format_js(r.total_final, 0, '.', ','));
            }
        })
    }
    /*-----------------------------------------------*/
    function number_format_js(number, decimals, dec_point, thousands_point) {
        if (number == null || !isFinite(number)) {
            throw new TypeError("number is not valid");
        }
        if (!decimals) {
            var len = number.toString().split('.').length;
            decimals = len > 1 ? len : 0;
        }
        if (!dec_point) {
            dec_point = '.';
        }
        if (!thousands_point) {
            thousands_point = ',';
        }
        number = parseFloat(number).toFixed(decimals);
        number = number.replace(".", dec_point);
        var splitNum = number.split(dec_point);
        splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
        number = splitNum.join(dec_point);
        return number;
    }
    /*-------------------------------------------------------*/
    $(".remover_articulo").on(tipoEvento, function() {
        var id = $(this).attr('id');
        removerArticulo(id);
    });

    function removerArticulo(id) {
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/solicitud/removerArticulo.php',
            data: {
                'id': id
            },
            dataType: "JSON",
            success: function(r) {
                if (r.mensaje == 'ok') {
                    $(".art" + id).fadeOut();
                }
            }
        })
    }
    /*---------------------------------------------------*/
    $("#agregar_articulo").on(tipoEvento, function() {
        var id = $(this).attr('data-id');
        var log = $(this).attr('data-log');
        agregarArticulo(id, log);
    });

    function agregarArticulo(id, log) {
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/solicitud/agregarArticulo.php',
            data: {
                'id': id,
                'id_log': log
            },
            dataType: "JSON",
            success: function(r) {
                if (r.mensaje == 'ok') {
                    $(".buscar").append(r.html);
                }
            }
        })
    }
    $(".buscar").on("click", ".remover_articulo", function() {
        var id = $(this).attr('id');
        removerArticulo(id);
    });
    $(".buscar").on("click", ".remover_articulo", function() {
        var id = $(this).attr('id');
        removerArticulo(id);
    });
    $(".buscar").on("keyup", ".cantidad", function() {
        var form = $('#prefactura');
        calcular(form);
    });
    $(".buscar").on("keyup", ".valor_unt", function() {
        var form = $('#prefactura');
        calcular(form);
    });
    $(".buscar").on("keyup", ".precio", function() {
        $(event.target).val(function(index, value) {
            return value.replace(/\D/g, "").replace(/([0-9])([0-9]{2})$/, '$1$2').replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });
    });
});