$.ajax({
    type: 'POST',
    url: '../../includes/dashboard/IngresarPuntos.php',
    data: {
        puntos: puntos,
        numeroOrden: numeroOrden,
        cedulaColaborador: cedulaColaborador,
        nombreColaborador: nombreColaborador,
        fechaPuntos: fechaPuntos,
        frecuencia: frecuencia
    },
    success: function (response) {

        var respuesta = JSON.parse(response);


        if (respuesta.exito) { // Asegúrate de que 'exito' está en minúsculas aquí
            $('.descripcion').text(respuesta.mensaje); // Establece el mensaje
            $('#1').css('display', 'flex'); // Muestra la notificación en modal "1"
            setTimeout(function () {
                $('#1').css('display', 'none'); // Oculta la notificación después de un tiempo
            }, 5000);

        } else {
            // Si la respuesta no tiene error, muestra la notificación con el mensaje
            $('.descripcion').text(respuesta.mensaje); // Establece el mensaje
            $('#2').css('display', 'flex'); // Muestra la notificación
            setTimeout(function () {
                $('#2').css('display', 'none'); // Oculta la notificación después de un tiempo
            }, 5000);
        }

    },
    error: function () {
        // Maneja errores en la solicitud AJAX
    }

});