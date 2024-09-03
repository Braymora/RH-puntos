//Envio de PDF por correo al colaborador
$(document).ready(function () {
  $(".sendPdfButton").click(function () {
    var numeroOrden = $(this).data("orden");
    var correo = $(this).data("correo");

    var datos = {
      numero_orden: numeroOrden,
      correo: correo,
    };

    // Muestra el loader antes de la solicitud Ajax
    $("#loader").show();

    // Enviar datos a través de Ajax
    $.ajax({
      type: "POST",
      url: "../../includes/servicio/obtener_informacion_paraCorreo.php",
      data: datos,
      success: function (data) {
        // // Oculta el loader después de recibir la respuesta Ajax
        $("#loader").hide();

        var respuesta = JSON.parse(data);

        if (respuesta.exito) {
          $(".descripcion").text(respuesta.mensaje);
          $("#1").css("display", "flex");
          setTimeout(function () {
            $("#1").css("display", "none");
            // Esperar 1 segundo adicional antes de recargar la página
            setTimeout(function () {
              location.reload();
            }, 1000);
          }, 5000);
        } else {
          $(".descripcion").text(respuesta.mensaje);
          $("#2").css("display", "flex");
          setTimeout(function () {
            $("#2").css("display", "none");
            // Esperar 1 segundo adicional antes de recargar la página
            setTimeout(function () {
              location.reload();
            }, 1000);
          }, 5000);
        }
      },
    });
  });
});
