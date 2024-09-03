$("#register_user").on("submit", function (e) {
  e.preventDefault();

  var cedula = $("#cedula").val();
  var nombre = $(".colaborador").val();
  var correo = $(".email").val();
  var proyecto = $("#proyecto").val();
  var rol = $("#rol").val();
  var usuario = $(".usuario").val();
  var password = $(".password").val();



  if (
    cedula === "" ||
    nombre === "" ||
    correo === "" ||
    proyecto === "" ||
    rol === "" ||
    usuario === "" ||
    password === ""
  ) {
    showAlert("#4");
  } else {
    $.ajax({
      type: "POST",
      url: "../../../app/includes/crearUsuarios/crearUsuarios.php",
      data: {
        cedula: cedula,
        nombre: nombre,
        proyecto: proyecto,
        rol: rol,
        usuario: usuario,
        correo: correo,
        password: password,
      },
      success: function (response) {
        //console.log(response);
        var respuesta = JSON.parse(response);

        if (respuesta.exito) {
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
          $("#3").css("display", "flex");
          setTimeout(function () {
            $("#3").css("display", "none");
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
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("Error en la solicitud AJAX: " + textStatus);
        // Aquí puedes manejar el error de la solicitud AJAX de manera adecuada.
      },
    });
  }
});
