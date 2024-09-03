var editModal = document.getElementById("myModalEdit");
var closeModal = document.getElementById("closeModal");
var editForm = document.getElementById("register_user");

var editButtons = document.querySelectorAll(".edit-button");
editButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    var userId = this.getAttribute("data-id");
    var correo = this.getAttribute("data-correo");
    var usuario = this.getAttribute("data-user");

    // Rellena el formulario en el modal con los valores
    document.getElementById("cedula").value = userId;
    document.getElementById("emailUp").value = correo;
    document.getElementById("usuarioUp").value = usuario;

    // Abre el modal
    editModal.style.display = "block";
  });
});

closeModal.addEventListener("click", function () {
  editModal.style.display = "none";
});

window.addEventListener("click", function (event) {
  if (event.target == editModal) {
    editModal.style.display = "none";
  }
});

/* ==========
        Envía el formulario para actualizar los datos utilizando AJAX
==========*/

update_user.addEventListener("submit", function (event) {
  event.preventDefault();

  // Obtén los valores del formulario
  var id_user = $("#id_user").val();
  var email = $("#emailUp").val();
  var proyecto = $("#proyectoUp").val();
  var rol = $("#rolUp").val();
  var usuario = $("#usuarioUp").val();
  var contrasena = $("#contrasena").val();

  console.log("id: " + id_user);
  console.log("email: " + email);
  console.log("proyecto: " + proyecto);
  console.log("rol: " + rol);
  console.log("usuario: " + usuario);
  console.log("contraseña: " + contrasena);

  // Realiza la petición AJAX
  $.ajax({
    type: "POST",
    url: "../../../app/includes/crearUsuarios/actualizar.php",
    data: {
      id_user: id_user,
      email: email,
      proyecto: proyecto,
      rol: rol,
      usuario: usuario,
      contrasena: contrasena,
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

/*============================
        Eliminar usuario
        ============================*/
$(document).ready(function () {
  $(".buttonDelete").click(function () {
    var id = $(this).val();

    //console.log(id);

    // // Muestra el modal
    $("#confirmModal").show();

    // Cuando se hace clic en el botón de eliminar en el modal
    $("#deleteBtn").click(function () {
      $.ajax({
        url: "../../../app/includes/crearUsuarios/eliminar.php",
        type: "POST",
        data: {
          id: id,
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
