const iconEyes = document.querySelector(".icon");
const iconEyesTwo = document.querySelector(".icon-two");
const input = document.getElementById("input");

iconEyes.addEventListener("click", function () {
  if (input.type === "password") {
    input.type = "text";
    iconEyes.style.display = "none";
    iconEyesTwo.style.display = "block";
  } else {
    input.type = "password";
    iconEyes.style.display = "none";
    iconEyesTwo.style.display = "block";
  }
});

iconEyesTwo.addEventListener("click", function () {
  if (input.type === "text") {
    input.type = "password";
    iconEyes.style.display = "block";
    iconEyesTwo.style.display = "none";
  }
});


// Función para ocultar el mensaje de alerta
function hideAlert() {
  const alert = document.querySelector(".toast.success");
  if (alert) {
    alert.style.display = "none";
  }
}

// Ocultar el mensaje de alerta después de 3 segundos
setTimeout(hideAlert, 3000);

// Agregar un controlador de eventos al botón de cerrar
const closeButton = document.querySelector(".btn-cerrar");
if (closeButton) {
  closeButton.addEventListener("click", hideAlert);
}
