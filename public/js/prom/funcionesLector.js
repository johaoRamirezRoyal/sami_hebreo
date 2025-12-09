var video = document.createElement("video");
var canvasElement = document.getElementById("canvas");
var canvas = canvasElement.getContext("2d");
var loadingMessage = document.getElementById("loadingMessage");
var outputContainer = document.getElementById("output");
var outputMessage = document.getElementById("outputMessage");
var outputData = document.getElementById("outputData");

function drawLine(begin, end, color) {
    canvas.beginPath();
    canvas.moveTo(begin.x, begin.y);
    canvas.lineTo(end.x, end.y);
    canvas.lineWidth = 4;
    canvas.strokeStyle = color;
    canvas.stroke();
}
// Use facingMode: environment to attemt to get the front camera on phones
navigator.mediaDevices.getUserMedia({
    video: {
        facingMode: "environment"
    }
}).then(function(stream) {
    video.srcObject = stream;
    video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
    video.play();
    requestAnimationFrame(tick);
});

function tick() {
    loadingMessage.innerText = "⌛ Cargando video..."
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        loadingMessage.hidden = true;
        canvasElement.hidden = false;
        outputContainer.hidden = false;
        canvasElement.height = video.videoHeight;
        canvasElement.width = video.videoWidth;
        canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
        });
        if (code) {
            drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
            drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
            drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
            drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
            disminiurCupo(code.data);
            return;
        } else {
            outputMessage.hidden = false;
            outputData.parentElement.hidden = true;
        }
    }
    requestAnimationFrame(tick);
}

/*------------------------------------------------*/
function disminiurCupo(id) {
    try {
        $.ajax({
            url: '../vistas/ajax/prom/cupo.php',
            type: 'POST',
            data: {
                'id': id,
            },
            cache: false,
            success: function(resultado) {
                if (resultado == 'ok') {
                    myRedirect("alerta", "id", 0);
                } else if (resultado == 'limite') {
                    myRedirect("limite", "id", 0);
                } else {
                    ohSnap("Error al leer!", {
                        color: "red",
                        "duration": "1000"
                    });
                }
            }
        });
    } catch (evt) {
        alert(evt.message);
    }
}
var myRedirect = function(redirectUrl, arg, value) {
    var form = $('<form action="' + redirectUrl + '" method="post">' + '<input type="hidden" name="' + arg + '" value="' + value + '"></input>' + '</form>');
    $('body').append(form);
    $(form).submit();
};