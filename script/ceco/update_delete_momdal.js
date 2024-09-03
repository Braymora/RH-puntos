/*==========================
        Obtén una referencia al modal y al botón de cerrar
==========================*/

var editModal = document.getElementById("editModal");
var closeModal = document.getElementById("closeModal");

// Obtén una referencia al formulario en el modal
var editForm = document.getElementById("editForm");

// Agrega un evento click a cada botón "Editar" en la tabla
var editButtons = document.querySelectorAll(".edit-button");
editButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    // Obtén los valores de data-ceco y data-name del botón
    var cecoId = this.getAttribute("data-ceco");
    var nombreCeco = this.getAttribute("data-name");

    // Rellena el formulario en el modal con los valores
    editForm.elements.codigo_ceco.value = cecoId;
    editForm.elements.nombre_ceco.value = nombreCeco;

    // Abre el modal
    editModal.style.display = "block";
  });
});

// Cierra el modal cuando se hace clic en la "x" de cerrar
closeModal.addEventListener("click", function () {
  editModal.style.display = "none";
});

// Cierra el modal cuando se hace clic fuera del modal
window.addEventListener("click", function (event) {
  if (event.target == editModal) {
    editModal.style.display = "none";
  }
});

/* ==========
        Envía el formulario para actualizar los datos utilizando AJAX
==========*/

editForm.addEventListener("submit", function (event) {
  event.preventDefault();

  // Obtén los valores del formulario
  var id_ceco = $("#codigo_cecoUp").val();
  var name_ceco = $("#name_cecoUp").val();

  // Realiza la petición AJAX
  $.ajax({
    type: "POST",
    url: "../../../app/includes/ceco/actualizar.php",
    data: {
      id_ceco: id_ceco,
      nombre_ceco: name_ceco,
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

      // Actualiza la tabla si es necesario
      // Puedes recargar la tabla o hacer otras operaciones según tus necesidades
    },
    error: function (error) {
      // Maneja los errores
      console.error(error);
    },
  });
});

/*============================
        Eliminar ceco
        ============================*/
$(document).ready(function () {
  $(".buttonDelete").click(function () {
    var idceco = $(this).val();

    // Muestra el modal
    $("#confirmModal").show();

    // Cuando se hace clic en el botón de eliminar en el modal
    $("#deleteBtn").click(function () {
      $.ajax({
        url: "../../../app/includes/ceco/eliminar.php",
        type: "POST",
        data: {
          idceco: idceco,
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
