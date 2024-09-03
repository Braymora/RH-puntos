/*============================
        Eliminar anexo
        ============================*/
$(document).ready(function () {
  $(".buttonDelete").click(function () {
    var numero_anexo = $(this).val();

    // Muestra el modal
    $("#confirmModal").show();

    // Cuando se hace clic en el botón de eliminar en el modal
    $("#deleteBtn").click(function () {
      $.ajax({
        url: "../../../app/includes/anexos/eliminar.php",
        type: "POST",
        data: {
          numero_anexo: numero_anexo,
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
          }
        },
      });

      // Cierra el modal
      $("#confirmModal").hide();
    });

    // Cuando se hace clic en el botón de cancelar en el modal
    $("#cancelBtn").click(function () {
      // Cierra el modal
      $("#confirmModal").hide();
    });
  });
});
