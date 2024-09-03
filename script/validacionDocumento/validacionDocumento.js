$(document).ready(function () {
  $("#documento").submit(function (e) {
    e.preventDefault();

    var cedula = $('input[name="doc"]').val();

    $.ajax({
      type: "POST",
      url: "app/includes/validarUsuario/validar_colaborador.php",
      data: {
        cedula: cedula,
      },
      success: function (response) {
        var respuesta = JSON.parse(response);

        if (respuesta.redirect) {
          window.location.href = respuesta.location;
        } else if (respuesta.exito) {
          $(".descripcion").text(respuesta.mensaje);
          $("#1").css("display", "flex");
          setTimeout(function () {
            $("#1").css("display", "none");
            setTimeout(function () {
              location.reload();
            }, 1000); // Recarga la página después de 6 segundos (5 segundos + 1 segundo)
          }, 5000);
        } else if (respuesta.warning) {
          $(".descripcion").text(respuesta.mensaje);
          $("#4").css("display", "flex");
          setTimeout(function () {
            $("#4").css("display", "none");
            setTimeout(function () {
              location.reload();
            }, 1000); // Recarga la página después de 6 segundos (5 segundos + 1 segundo)
          }, 5000);
        } else {
          $(".descripcion").text(respuesta.mensaje);
          $("#2").css("display", "flex");
          setTimeout(function () {
            $("#2").css("display", "none");
            setTimeout(function () {
              location.reload();
            }, 1000); // Recarga la página después de 6 segundos (5 segundos + 1 segundo)
          }, 5000);
        }
      },
    });
  });
});
