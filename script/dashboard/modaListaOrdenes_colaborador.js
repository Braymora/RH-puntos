//tabla para importar ordenes
$(document).ready(function () {
  var table = $("#tablaTmp").DataTable({
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
    processing: true,
  });

});

//Tabla para visualizar litado de ordenes asiganadas al usuario

$(document).ready(function () {
  // Inicializa el DataTable en la tabla de órdenes
  $("#tableOrdenes").DataTable({
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
    processing: true,
  });
});

$(".buttonOrdenes").click(function () {
  var id_cedula = $(this).data("cedula");

  $.ajax({
    url: "../../../app/includes/dashboard/consultarOrdenes_colaborador.php",
    type: "POST",
    data: {
      id_cedula: id_cedula,
    },
    success: function (data) {
      $("#tableOrdenes tbody").html(data);
    },
    error: function (error) {
      console.error("Error: " + error);
    },
  });
});

// Agrega esta función para mostrar el modal y pasar el número de orden
function showModalInsertPoints(button) {
  var numeroOrden = button.getAttribute("data-orden");
  var cedulaColaborador = button.getAttribute("data-cedula");
  var nombreColaborador = button.getAttribute("data-nombre");

  // Obtén los valores directamente del formulario
  var fechaPuntos = $("#fechaPuntos").val();
  // Captura el valor seleccionado del campo select (frecuencia)
  var frecuencia = $('select[name="frecuencia"]').val();

  $("#numeroOrden").val(numeroOrden);
  $("#cedulaColaborador").val(cedulaColaborador);
  $("#nombreColaborador").val(nombreColaborador);

  // Asigna los valores de fechaPuntos y frecuencia a los campos ocultos
  $("#fechaPuntos").val(fechaPuntos);
  $("#frecuencia").val(frecuencia);

  $("#myModalInsertPoints").show();

  document
    .querySelector(".close-btnInsertPoints")
    .addEventListener("click", function () {
      document.getElementById("myModalInsertPoints").style.display = "none";
    });
}

// función para manejar el envío del formulario mediante AJAX
$("form.contInsert_itemsOrder").submit(function (e) {
  e.preventDefault();

  var puntos = $("#puntos").val();
  var numeroOrden = $("#numeroOrden").val();
  var cedulaColaborador = $("#cedulaColaborador").val();
  var nombreColaborador = $("#nombreColaborador").val();
  var fechaPuntos = $("#fechaPuntos").val();
  var frecuencia = $("#frecuencia").val();

  $.ajax({
    type: "POST",
    url: "../../includes/dashboard/IngresarPuntos.php",
    data: {
      puntos: puntos,
      numeroOrden: numeroOrden,
      cedulaColaborador: cedulaColaborador,
      nombreColaborador: nombreColaborador,
      fechaPuntos: fechaPuntos,
      frecuencia: frecuencia,
    },
    success: function (response) {
      var respuesta = JSON.parse(response);

      if (respuesta.exito) {
        // Asegúrate de que 'exito' está en minúsculas aquí
        $(".descripcion").text(respuesta.mensaje); // Establece el mensaje
        $("#1").css("display", "flex"); // Muestra la notificación en modal "1"
        setTimeout(function () {
          $("#1").css("display", "none"); // Oculta la notificación después de un tiempo
          // Esperar 1 segundo adicional antes de recargar la página
          setTimeout(function () {
            location.reload();
          }, 1000);
        }, 5000);
        location.reload();
      } else {
        // Si la respuesta no tiene error, muestra la notificación con el mensaje
        $(".descripcion").text(respuesta.mensaje); // Establece el mensaje
        $("#2").css("display", "flex"); // Muestra la notificación
        setTimeout(function () {
          $("#2").css("display", "none"); // Oculta la notificación después de un tiempo
          // Esperar 1 segundo adicional antes de recargar la página
          setTimeout(function () {
            location.reload();
          }, 1000);
        }, 5000);
        location.reload();
      }
    },
    error: function () {
      // Maneja errores en la solicitud AJAX
    },
  });
});
