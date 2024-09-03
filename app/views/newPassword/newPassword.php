<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos</title>

    <!--Fonts-->
    <?php include '../font/font.php'; ?>

    <link rel="stylesheet" href="../../../style/general.css">
</head>

<body>
    <div id="particles-js"></div>
    <div class="contLog">
        <form action="../../includes/recoveryPassword/send-password-reset.php" class="contLog-login" method="post">
            <img src="../../../assets/image/LogoColvatel.svg" alt="Logo compañia" class="contLog-login__logo">
            <h2 class="contLog-login__title">¿Has olvidado tu contraseña?</h2>
            <p class="contLog-login__descript">Ingrese su correo electrónico y le enviaremos un correo para restablecer su contraseña</p>

            <div class="input-container">
                <input type="text" name="email" required="">
                <label for="email" name="email" class="label">Ingrese su correo electrónico</label>
                <div class="underline"></div>
            </div>
            <button class="BtnCon" type="submit">
                <span class="IconContainer">
                    <img src="../../../assets/icons/key.svg" alt="iconSearch">
                </span>
                <p class="text">Enviar enlace</p>
            </button>
            <div class="backToToP">
                <img src="../../../assets/icons/chevron-left.svg" alt="Volver a inicio">
                <a href="../../views/login/login.php">Volver a inicio de sesión</a>
            </div>
        </form>
    </div>

    <!-- ===============
    Alert
=============== -->

    <div class="contenedor-toast" id="contenedor-toast">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="toast exito" id="1">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Exito!</p>
                        <p class="descripcion" id="descripcion"><?php echo $_SESSION['success']; ?></p>
                    </div>
                </div>
                <button class="btn-cerrar">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="toast success" id="2">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Error!</p>
                        <p class="descripcion"><?php echo $_SESSION['error']; ?></p>
                    </div>
                </div>
                <button class="btn-cerrar">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>


    <!--===== particles-js =====-->
    <script src="../../../script/particles/particles.min.js"></script>
    <script src="../../../script/particles/animacion.js"></script>

    <script>
        // Espera a que el DOM esté completamente cargado
        document.addEventListener("DOMContentLoaded", function() {
            // Obtén el elemento del botón de cerrar
            var cerrarBtns = document.querySelectorAll(".btn-cerrar");

            // Itera sobre cada botón de cerrar
            cerrarBtns.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    // Encuentra el elemento padre (toast) y ocúltalo
                    var toast = this.closest(".toast");
                    if (toast) {
                        toast.style.display = "none";
                    }
                });
            });

            // Oculta automáticamente el toast después de 5 segundos
            setTimeout(function() {
                var toasts = document.querySelectorAll(".toast");
                toasts.forEach(function(toast) {
                    toast.style.display = "none";
                });
            }, 5000);
        });
    </script>

</body>

</html>