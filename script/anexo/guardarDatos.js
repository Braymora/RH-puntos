// Función para crear y guardar el anexo
$(document).ready(function () {
  $("#formAnexos").submit(function (e) {
    e.preventDefault();

    // Obtener datos del formulario
    var numAnexo = $('input[name="n_anexo"]').val();
    var idcolaborador = $('input[name="idcolaborador"]').val();
    var cedula = $('select[name="cedula"]').val();
    var nombreColaborador = $('input[name="name"]').val();
    var namececo = $('input[name="namececo"]').val();



    // Obtener datos de los campos dinámicos
    var actividadesContActividades = $(
      '.contActividades input[name="actividades"]'
    )
      .map(function () {
        return $(this).val();
      })
      .get();

    var puntosPorMesContActividades = $(
      '.contActividades input[name="puntosPorMes"]'
    )
      .map(function () {
        return $(this).val();
      })
      .get();

    var actividadesAdditionalFields = $(
      '.additionalFieldsWrapper input[name="actividades[]"]'
    )
      .map(function () {
        return $(this).val();
      })
      .get();

    var puntosPorMesAdditionalFields = $(
      '.additionalFieldsWrapper input[name="puntosPorMes[]"]'
    )
      .map(function () {
        return $(this).val();
      })
      .get();

    // Combina los datos de ambos contenedores
    var todasLasActividades = actividadesContActividades.concat(
      actividadesAdditionalFields
    );
    var todosLosPuntosPorMes = puntosPorMesContActividades.concat(
      puntosPorMesAdditionalFields
    );

    var observaciones = $('textarea[name="observaciones"]').val();



    // Realizar solución AJAX
    $.ajax({
      type: "POST",
      url: "../../../app/includes/anexos/proceso_anexos.php",
      data: {
        numAnexo: numAnexo,
        idcolaborador: idcolaborador,
        cedula: cedula,
        nombreColaborador: nombreColaborador,
        namececo: namececo,
        actividades: todasLasActividades,
        puntosPorMes: todosLosPuntosPorMes,
        observaciones: observaciones
      },
      success: function (response) {
        ;
        var respuesta = JSON.parse(response);

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