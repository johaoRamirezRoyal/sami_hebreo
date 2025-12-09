$(document).ready(function() {
    var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
    /*------------------------------------------------------*/
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var output = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    cantidadReportes('', output);
    cantidadMantenimientos('', output);
    cantidadGeneral('', output);
    cantidadMantenimientosZona('', output);
    cantidadDanoZona('', output);
    /*----------------------------*/
    $("#fecha_inicio_mant").change(function() {
        let inicio = $(this).val();
        let fin = $("#fecha_fin_mant").val();
        cantidadMantenimientos(inicio, fin)
    });
    /*-----------------------------1*/
    $("#fecha_fin_mant").change(function() {
        let inicio = $("#fecha_inicio_mant").val();
        let fin = $(this).val();
        cantidadMantenimientos(inicio, fin)
    });
    /*----------------------------2*/
    $("#fecha_inicio_report").change(function() {
        let inicio = $(this).val();
        let fin = $("#fecha_fin_report").val();
        cantidadReportes(inicio, fin)
    });
    /*-----------------------------3*/
    $("#fecha_fin_report").change(function() {
        let inicio = $("#fecha_inicio_report").val();
        let fin = $(this).val();
        cantidadReportes(inicio, fin)
    });
    /*----------------------------4*/
    $("#fecha_inicio_general").change(function() {
        let inicio = $(this).val();
        let fin = $("#fecha_fin_general").val();
        cantidadGeneral(inicio, fin)
    });
    /*-----------------------------5*/
    $("#fecha_fin_general").change(function() {
        let inicio = $("#fecha_inicio_general").val();
        let fin = $(this).val();
        cantidadGeneral(inicio, fin)
    });
    /*-----------------------------6*/
    $("#fecha_fin_zona").change(function() {
        let inicio = $("#fecha_inicio_zona").val();
        let fin = $(this).val();
        cantidadMantenimientosZona(inicio, fin)
    });
    /*-----------------------------7*/
    $("#fecha_inicio_zona").change(function() {
        let fin = $("#fecha_fin_zona").val();
        let inicio = $(this).val();
        cantidadMantenimientosZona(inicio, fin)
    });
    /*-----------------------------8*/
    $("#fecha_fin_dano_zona").change(function() {
        let inicio = $("#fecha_inicio_zona_dano").val();
        let fin = $(this).val();
        cantidadDanoZona(inicio, fin)
    });
    /*-----------------------------9*/
    $("#fecha_inicio_zona_dano").change(function() {
        let fin = $("#fecha_fin_dano_zona").val();
        let inicio = $(this).val();
        cantidadDanoZona(inicio, fin)
    });

    function cantidadGeneral(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/graficas/cantidadesGeneral.php',
                method: "POST",
                data: {
                    'fecha_inicio': inicio,
                    'fecha_fin': fin,
                    'tipo': 2
                },
                dataType: 'json',
                success: function(r) {
                    var ctx = document.getElementById('danos_general_reporte').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'pie',
                        // The data for our dataset
                        data: {
                            datasets: [{
                                backgroundColor: ['rgba(4, 149, 121, 0.5)', 'rgba(243, 156, 18, 0.5)'],
                                data: [r.solucion, r.pendiente]
                            }],
                            labels: ['Solucionados (' + r.solucion + ')', 'Pendientes (' + r.pendiente + ')']
                        },
                        // Configuration options go here
                        options: {
                            tooltips: {
                                enabled: false
                            },
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function cantidadReportes(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/graficas/cantidades.php',
                method: "POST",
                data: {
                    'fecha_inicio': inicio,
                    'fecha_fin': fin,
                    'tipo': 2
                },
                dataType: 'json',
                success: function(r) {
                    var ctx = document.getElementById('danos').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'pie',
                        // The data for our dataset
                        data: {
                            datasets: [{
                                backgroundColor: ['rgba(4, 149, 121, 0.5)', 'rgba(243, 156, 18, 0.5)'],
                                data: [r.solucion, r.pendiente]
                            }],
                            labels: ['Solucionados (' + r.solucion + ')', 'Pendientes (' + r.pendiente + ')']
                        },
                        // Configuration options go here
                        options: {
                            tooltips: {
                                enabled: false
                            },
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function cantidadMantenimientos(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/graficas/cantidades.php',
                method: "POST",
                data: {
                    'fecha_inicio': inicio,
                    'fecha_fin': fin,
                    'tipo': 6
                },
                dataType: 'json',
                success: function(r) {
                    var ctx = document.getElementById('mantenimientos').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'pie',
                        // The data for our dataset
                        data: {
                            datasets: [{
                                backgroundColor: ['rgba(4, 149, 121, 0.5)', 'rgba(243, 156, 18, 0.5)'],
                                data: [r.solucion, r.pendiente]
                            }],
                            labels: ['Solucionados (' + r.solucion + ')', 'Pendientes (' + r.pendiente + ')']
                        },
                        // Configuration options go here
                        options: {
                            tooltips: {
                                enabled: false
                            },
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function cantidadMantenimientosZona(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/graficas/cantidadesMantZonas.php',
                method: "POST",
                data: {
                    'fecha_inicio': inicio,
                    'fecha_fin': fin,
                    'tipo': 6
                },
                dataType: 'json',
                success: function(r) {
                    var ctx = document.getElementById('mant_zona').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'pie',
                        // The data for our dataset
                        data: {
                            datasets: [{
                                backgroundColor: ['rgba(4, 149, 121, 0.5)', 'rgba(243, 156, 18, 0.5)'],
                                data: [r.solucion, r.pendiente]
                            }],
                            labels: ['Solucionados (' + r.solucion + ')', 'Pendientes (' + r.pendiente + ')']
                        },
                        // Configuration options go here
                        options: {
                            tooltips: {
                                enabled: false
                            },
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }

    function cantidadDanoZona(inicio, fin) {
        try {
            $.ajax({
                url: '../vistas/ajax/graficas/cantidadesZona.php',
                method: "POST",
                data: {
                    'fecha_inicio': inicio,
                    'fecha_fin': fin,
                    'tipo': 2
                },
                dataType: 'json',
                success: function(r) {
                    var ctx = document.getElementById('dano_zona').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'pie',
                        // The data for our dataset
                        data: {
                            datasets: [{
                                backgroundColor: ['rgba(4, 149, 121, 0.5)', 'rgba(243, 156, 18, 0.5)'],
                                data: [r.solucion, r.pendiente]
                            }],
                            labels: ['Solucionados (' + r.solucion + ')', 'Pendientes (' + r.pendiente + ')']
                        },
                        // Configuration options go here
                        options: {
                            tooltips: {
                                enabled: false
                            },
                        }
                    });
                }
            });
        } catch (evt) {
            alert(evt.message);
        }
    }
});