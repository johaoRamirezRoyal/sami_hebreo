$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    cantidadGeneral('', output)
    /*-----------------------------1*/
    $("#fecha_desde_enfermeria").change(function() {
        let inicio = $(this).val();
        let fin = $("#fecha_hasta_enfermeria").val();
        cantidadGeneral(inicio, fin)
    });
    /*-----------------------------1*/
    $("#fecha_hasta_enfermeria").change(function() {
        let inicio = $("#fecha_desde_enfermeria").val();
        let fin = $(this).val();
        cantidadGeneral(inicio, fin)
    });

    function cantidadGeneral(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/enfermeria/graficasEnfermeria.php',
                method: "POST",
                data: {
                    'fecha_desde_enfermeria': inicio,
                    'fecha_hasta_enfermeria': fin,
                },
                dataType: 'json',
                success: function(resultado) {
                    var data = {
                        labels: resultado.nombre,
                        datasets: [{
                            data: resultado.cantidad,
                            backgroundColor: resultado.color,
                            hoverOffset: 4,
                        }],
                    };
                    var ctx = document.getElementById('Grafica_Enfermeria').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: data,
                        options: {
                            responsive: true,
                            tooltips: {
                                enabled: false
                            },
                            plugins: {
                                emptyDoughnut: {
                                    color: 'rgba(255, 128, 0, 0.5)',
                                    width: 2,
                                    radiusDecrease: 20
                                }
                            }
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});