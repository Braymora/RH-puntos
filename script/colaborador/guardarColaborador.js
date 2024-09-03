$(document).ready(function () {
  $("#formularioColaborador").on("submit", function (event) {
    event.preventDefault(); // Evitar el envío por defecto del formulario

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "../../controller/colaboradorC.php",
      data: formData,
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.exito) {
          showAlert("#1"); // Muestra la alerta de éxito
          $("#formularioColaborador")[0].reset(); // Restablecer el formulario

          // Ocultar el mensaje después de 5 segundos
          setTimeout(function () {
            $("#1").fadeOut("slow");

            // Esperar otro segundo y recargar la página
            setTimeout(function () {
              location.reload(true); // true para forzar la recarga desde el servidor
            }, 1000);
          }, 5000);
        } else {
          if (
            respuesta.mensaje ===
            "El colaborador con esta cédula ya existe en la base de datos."
          ) {
            showAlert("#4"); // Muestra la alerta de que el colaborador ya existe
          } else {
            showAlert("#3"); // Muestra la alerta de error
          }
        }
      },
      error: function (error) {
        if (error) {
          showAlert("#2"); // Muestra la alerta de error en caso de error AJAX
          $(".loader").css("display", "none");
        }
      },
    });
  });

  function showAlert(id) {
    $(id).show();
    setTimeout(function () {
      $(id).fadeOut();
    }, 5000);
  }

  // Agrega un controlador de eventos para cerrar todas las alertas
  $(".btn-cerrar").click(function () {
    $(".toast").hide();
  });
});
