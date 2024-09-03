$(document).ready(function () {
    // Detectar cambios en el campo de selección
    $('#cedula').on('change', function () {
        // Obtener los datos asociados a la opción seleccionada
        var selectedOption = $(this).find(':selected');
        var nombre = selectedOption.data('nombre');
        var email = selectedOption.data('email');

        // Actualizar los campos de nombre y correo electrónico
        $('#colaborador').val(nombre);
        $('#email').val(email);
    });
});




/*===============
se activa y desactiva el toast de alertas
================*/

// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento del botón de cerrar
    var cerrarBtns = document.querySelectorAll(".btn-cerrar");

    // Itera sobre cada botón de cerrar
    cerrarBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            // Encuentra el elemento padre (toast) y ocúltalo
            var toast = this.closest(".toast");
            if (toast) {
                toast.style.display = "none";
            }
        });
    });

    // Oculta automáticamente el toast después de 5 segundos
    setTimeout(function () {
        var toasts = document.querySelectorAll(".toast");
        toasts.forEach(function (toast) {
            toast.style.display = "none";
        });
    }, 5000);
});