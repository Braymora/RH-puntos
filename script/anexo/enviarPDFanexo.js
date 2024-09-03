$(document).ready(function () {
  $(".sendPdfButton").click(function () {
    var numeroAnexo = $(this).data("anexo");
    var correo = $(this).data("correo");

    var datos = {
      numeroAnexo: numeroAnexo,
      correo: correo,
    };

    // Muestra el loader antes de la solicitud Ajax
    $("#loader").show();

    // Enviar datos a través de Ajax
    $.ajax({
      type: "POST",
      url: "../../includes/anexos/obtener_informacion_paraCorreoAnexos.php",
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
