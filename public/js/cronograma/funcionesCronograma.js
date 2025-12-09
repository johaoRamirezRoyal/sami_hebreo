document.addEventListener('DOMContentLoaded', function() {
    /*------------*/
    var d = new Date();
    var dias = (d.getDate() < 10) ? '0' + d.getDate() : d.getDate();
    var mes = ((d.getMonth() + 1) < 10) ? '0' + (d.getMonth() + 1) : (d.getMonth() + 1);
    var minutos = (d.getMinutes() < 10) ? '0' + d.getMinutes() : d.getMinutes();
    var horas = (d.getHours() < 10) ? '0' + d.getHours() : d.getHours();
    var fecha_actual = d.getFullYear() + "-" + mes + "-" + dias;
    /*---------------------------*/
    /*---------------------------*/
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialDate: fecha_actual,
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear'
        },
        height: 'auto',
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        selectable: true,
        selectMirror: true,
        nowIndicator: true,
        events: {
            url: '../vistas/ajax/cronograma/fechas.php',
        },
    });
    calendar.setOption('locale', 'es');
    calendar.render();
    //*-------*/
});