var tipoEvento = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
// Set up the canvas2
var canvas2 = document.getElementById("sig-canvas2");
var ctx2 = canvas2.getContext("2d");
ctx2.strokeStyle = "#222222";
ctx2.lineWith = 2;
// Set up mouse events for drawing2
var drawing2 = false;
var mousePos2 = {
    x: 0,
    y: 0
};
var lastPos2 = mousePos2;
canvas2.addEventListener("mousedown", function(e) {
    drawing2 = true;
    lastPos2 = getMousePos2(canvas2, e);
}, false);
canvas2.addEventListener("mouseup", function(e) {
    drawing2 = false;
}, false);
canvas2.addEventListener("mousemove", function(e) {
    mousePos2 = getMousePos2(canvas2, e);
}, false);
// Get the position of the mouse relative to the canvas2
function getMousePos2(canvasDom, mouseEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
        x: mouseEvent.clientX - rect.left,
        y: mouseEvent.clientY - rect.top
    };
}
// Get a regular interval for drawing2 to the screen
window.requestAnimFrame2 = (function(callback) {
    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimaitonFrame || function(callback) {
        window.setTimeout(callback, 1000 / 60);
    };
})();
// Draw to the canvas2
function renderCanvas2() {
    if (drawing2) {
        ctx2.moveTo(lastPos2.x, lastPos2.y);
        ctx2.lineTo(mousePos2.x, mousePos2.y);
        ctx2.stroke();
        lastPos2 = mousePos2;
    }
}
// Allow for animation
(function drawLoop2() {
    requestAnimFrame2(drawLoop2);
    renderCanvas2();
})();
// Set up touch events for mobile, etc
canvas2.addEventListener("touchstart", function(e) {
    mousePos2 = getTouchPos(canvas2, e);
    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousedown", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas2.dispatchEvent(mouseEvent);
}, false);
canvas2.addEventListener("touchend", function(e) {
    var mouseEvent = new MouseEvent("mouseup", {});
    canvas2.dispatchEvent(mouseEvent);
}, false);
canvas2.addEventListener("touchmove", function(e) {
    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas2.dispatchEvent(mouseEvent);
}, false);
// Get the position of a touch relative to the canvas2
function getTouchPos(canvasDom, touchEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
        x: touchEvent.touches[0].clientX - rect.left,
        y: touchEvent.touches[0].clientY - rect.top
    };
}
// Prevent scrolling when touching the canvas2
document.body.addEventListener("touchstart", function(e) {
    if (e.target == canvas2) {
        e.preventDefault();
    }
}, false);
document.body.addEventListener("touchend", function(e) {
    if (e.target == canvas2) {
        e.preventDefault();
    }
}, false);
document.body.addEventListener("touchmove", function(e) {
    if (e.target == canvas2) {
        e.preventDefault();
    }
}, false);

function clearCanvas() {
    canvas2.width = canvas2.width;
}
var firma2 = canvas2.toDataURL();
var img2 = document.getElementById("laimagen2");
//var solucionar = document.getElementById("solucionar");
/*$(".solucionar").click(tipoEvento, function() {
    img2.src = canvas2.toDataURL("image/png");
    let imagen = $("#laimagen2").attr('src');
    let user = $("#id_user").val();
    let resp = $("#id_log").val();
    let inventario = $("#id_inventario").val();
    let observacion = $("#observacion").val();
    let super_empresa = $("#super_empresa").val();
    let tipo_reporte = $("#tipo_reporte").val();
    $.ajax({
        type: "POST",
        url: '../vistas/ajax/reportes/solucion.php',
        data: {
            'valor': imagen,
            'user': user,
            'resp': resp,
            'inventario': inventario,
            'op': 2,
            'observacion': observacion,
            'super_empresa': super_empresa,
            'tipo_reporte': tipo_reporte
        },
        dataType: 'json',
        success: function(response) {
            if (response.mensaje == 'ok') {
                ohSnap("Firma solucionado guardada!", {
                    color: "green",
                    'duration': '1000'
                });
                setTimeout(limpiarFormulario, 1010);
            }
            //img2.src = response.replace(/"([^"]+(?="))"/g, '$1');
        }
    });
}, false);*/
var limpiar = document.getElementById("clean2");
limpiar.addEventListener("click", function() {
    canvas2.width = canvas.width;
    $("#sig-canvas2").show();
}, false);

function limpiarFormulario() {
    canvas2.width = canvas.width;
    canvas.width = canvas.width;
    $("#observacion").val('');
    window.location.replace("index");
}