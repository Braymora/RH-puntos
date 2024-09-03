$(document).ready(function() {
    // Seleccionar el elemento select
    var selectCedula = $("#cedula");

    // Realizar una solicitud AJAX para obtener las opciones desde PHP
    $.ajax({
        type: "GET", // Puedes usar GET o POST según tu preferencia
        url: 'app/includes/consultaCedula/consultarColaboradores.php', 
        dataType: "json", // Esperamos una respuesta en formato JSON
        success: function(response) {
            // Limpiar las opciones actuales en el select
            selectCedula.empty();

            // Agregar las opciones obtenidas desde PHP
            $.each(response.data, function(key, value) {
                selectCedula.append($('<option>', {
                    value: value.id,
                    text: value.nombre
                }));
            });
        },
        error: function() {
            console.error("Error en la solicitud AJAX");
        }
    });
});