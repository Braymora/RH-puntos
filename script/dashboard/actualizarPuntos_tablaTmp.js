// Función para mostrar el modal
function showModalEditOrder() {
    document.getElementById('myModalEditOrder').classList.add('show');
}
// Función para cerrar el modal
function closeModalEditOrder() {
    document.getElementById('myModalEditOrder').classList.remove('show');
}

$(document).ready(function () {
    $('.edit-button').click(function () {
        var numeroOrden = $(this).data('orden');
        var nombreColaborador = $(this).data('nombre');
        var puntosAsignados = $(this).data('actuales');

        // Asigna los valores a los elementos <span> correspondientes en la modal
        $('#numeroOrdenSpan').text(numeroOrden);
        $('#nombreColaboradorSpan').text(nombreColaborador);
        $('#puntosAsignadosSpan').text(puntosAsignados);

        // Asigna los valores a los campos ocultos correspondientes en la modal
        $('#numeroOrden').val(numeroOrden);
        $('#nombreColaborador').val(nombreColaborador);
        $('#puntosAsignados').val(puntosAsignados);

        showModalEdit();
    });
});


//Actualizar datos en tabla Tmp
$(document).ready(function () {
    // Escucha el clic en el botón "Guardar"
    $('#guardarCambiosButton').click(function () {

        var idTmp = $('#id_tmp').val(); // Obtiene el valor del input hidden
        var puntosModificar = $('#puntosModificar').val(); // Obtiene los puntos a modificar
        var frecuencia = $('#frecuenciaTwo').val(); // Obtiene el valor del menú desplegable

        //datos al servidor usando AJAX:
        $.ajax({
            type: 'POST',
            url: '../../../app/includes/dashboard/actualizarPuntos_tablaTmp.php',
            data: {
                id_tmp: idTmp,
                puntosModificar: puntosModificar,
                frecuencia: frecuencia
            },
            success: function (response) {
                var respuesta = JSON.parse(response);

                if (respuesta.exito) { // Asegúrate de que 'exito' está en minúsculas aquí
                    $('.descripcion').text(respuesta.mensaje); // Establece el mensaje
                    $('#1').css('display', 'flex'); // Muestra la notificación en modal "1"
                    setTimeout(function () {
                        $('#1').css('display', 'none'); // Oculta la notificación después de un tiempo
                        // Esperar 1 segundo adicional antes de recargar la página
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }, 5000);

                } else {
                    // Si la respuesta no tiene error, muestra la notificación con el mensaje
                    $('.descripcion').text(respuesta.mensaje); // Establece el mensaje
                    $('#2').css('display', 'flex'); // Muestra la notificación
                    setTimeout(function () {
                        $('#2').css('display', 'none'); // Oculta la notificación después de un tiempo
                        // Esperar 1 segundo adicional antes de recargar la página
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }, 5000);
                }
            }
        });
    });
});