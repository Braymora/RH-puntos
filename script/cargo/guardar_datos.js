//función para mostrar los datos en la tabla de ceco
$(document).ready(function () {
  var table = $("#table_cargo").DataTable({
    searching: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
    autoWidth: true,
    processing: true,
    dom: "lBfrtip", // Aquí se configuran los elementos que quieres en la tabla
    buttons: [
      {
        extend: "excel",
        footer: true,
        title: "Archivo",
        filename: "Export_File",
        text: '<button class="buttons optionsButtonsExporte" id="exportExcelButton"><img src="../../../assets/icons/share.svg" alt=""> Exportar Excel</button>',
      },
    ],
  });

  $("#customSearch").on("keyup", function () {
    var searchTerm = this.value.toLowerCase();
    $("#table_cargo tbody tr").hide();
    $("#table_cargo tbody tr")
      .filter(function () {
        var lineStr = $(this).text().toLowerCase();
        return lineStr.indexOf(searchTerm) !== -1;
      })
      .show();
  });
});

//función para crear y guardar los ceco
$(document).ready(function () {
  $("#register_user").submit(function (e) {
    e.preventDefault();

    //Obtener datos del formulario
    var codigo_cargo = $('input[name="codigo_cargo"]').val();
    var nombre_cargo = $('input[name="nombre_cargo"]').val();


    //Realizar solución AJAX
    $.ajax({
      type: "POST",
      url: "../../../app/includes/cargo/guardar_datos.php",
      data: {
        codigo_cargo: codigo_cargo,
        nombre_cargo: nombre_cargo
      },
      success: function (response) {
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
      },
    });
  });
});

