idColaborador.addEventListener("submit", function (event) {
  event.preventDefault();

  var id = $("#id_colaborador").val();
  var cedula = $("#cedula").val();
  var nombre_colaborador = $("#nombre_colaborador").val();
  var correo = $("#correo").val();
  var id_ceco = $("#id_ceco").val();
  var nombre_cargo = $("#nombre_cargo").val();
  var contratante = $("#contratante").val();
  var nombre_ciudad = $("#nombre_ciudad").val();
  var direccion = $("#direccion").val();
  var nombre_proyecto = $("#nombre_proyecto").val();
  var fecha_ingreso = $("#fecha_ingreso").val();
  var estado = $("#estado").val();
  var Observacione = $("#Observacione").val();

  // Realiza la petición AJAX
  $.ajax({
    type: "POST",
    url: "../../../app/includes/colaboradores/actualizarColaborador.php",
    data: {
      id: id,
      cedula: cedula,
      nombre_colaborador: nombre_colaborador,
      correo: correo,
      id_ceco: id_ceco,
      nombre_cargo: nombre_cargo,
      contratante: contratante,
      nombre_ciudad: nombre_ciudad,
      direccion: direccion,
      nombre_proyecto: nombre_proyecto,
      fecha_ingreso: fecha_ingreso,
      estado: estado,
      Observacione: Observacione,
    },
    success: function (response) {
      // Maneja la respuesta del servidor
      var respuesta = JSON.parse(response);

      if (respuesta.exito) {
        // Asegúrate de que 'exito' está en minúsculas aquí
        $(".descripcion").text(respuesta.mensaje); // Establece el mensaje
        $("#1").css("display", "flex"); // Muestra la notificación en modal "1"
        setTimeout(function () {
          $("#1").css("display", "none"); // Oculta la notificación después de un tiempo
        }, 5000);
        location.reload();
      } else {
        // Si la respuesta no tiene error, muestra la notificación con el mensaje
        $(".descripcion").text(respuesta.mensaje); // Establece el mensaje
        $("#2").css("display", "flex"); // Muestra la notificación
        setTimeout(function () {
          $("#2").css("display", "none"); // Oculta la notificación después de un tiempo
        }, 5000);
        location.reload();
      }

      // Cierra el modal
      editModal.style.display = "none";
    },
    error: function (error) {
      // Maneja los errores
      console.error(error);
    },
  });
});
