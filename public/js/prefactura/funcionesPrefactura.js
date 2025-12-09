$(document).ready(function() {

    $(".valor_unt").keyup(function() {
        var form = $('#prefactura');
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/prefactura/calcular.php',
            data: form.serialize(),
            dataType: "JSON",
            success: function(r) {
                var array = r.totales;
                array.forEach(function(datos) {
                    $("#" + datos.id_residuo).val(number_format_js(datos.total_unidad, 0, '.', ','));
                });
                $("#subtotal").val(number_format_js(r.subtotal, 0, '.', ','));
                $("#total_final").val(number_format_js(r.total_final, 0, '.', ','));
            }
        })
    });



    $(".descuento").keyup(function() {
        var form = $('#prefactura');
        $.ajax({
            type: "POST",
            url: '../vistas/ajax/prefactura/calcular.php',
            data: form.serialize(),
            dataType: "JSON",
            success: function(r) {
                var array = r.totales;
                array.forEach(function(datos) {
                    $("#" + datos.id_residuo).val(number_format_js(datos.total_unidad, 0, '.', ','));
                });
                $("#subtotal").val(number_format_js(r.subtotal, 0, '.', ','));
                $("#total_final").val(number_format_js(r.total_final, 0, '.', ','));
            }
        })
    });




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

});