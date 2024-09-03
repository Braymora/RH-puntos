<?php
// Inicia la sesión. Esto es necesario para trabajar con variables de sesión.
session_start();

// Verifica si no se proporciona el parámetro "token" en la URL.
if (!isset($_GET["token"])) {
    $_SESSION['error'] = "Token no proporcionado";
    exit();
}

// Obtiene el valor del parámetro "token" de la URL.
$token = $_GET["token"];

// Almacena el token en una variable de sesión
$_SESSION['token'] = $token;

// Requiere el archivo de configuración de conexión a la base de datos.
require __DIR__ . "/../../config/conexion.php";

// Prepara la consulta SQL para seleccionar todos los usuarios con un hash de token de reinicio no nulo.
$sql = "SELECT * FROM usuarios
        WHERE reset_token_hash IS NOT NULL";

// Prepara la consulta utilizando PDO (PHP Data Objects).
$stmt = $conexion->prepare($sql);

// Ejecuta la consulta SQL.
$stmt->execute();

// Inicializa la variable $user como nula.
$user = null;

// Recorre los resultados de la consulta.
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Verifica si el token proporcionado coincide con el hash almacenado en la base de datos.
    if (password_verify($token, $row['reset_token_hash'])) {
        // Asigna el usuario correspondiente al que tiene el token válido.
        $user = $row;
        // Sale del bucle ya que se encontró un usuario con el token válido.
        break;
    }
}

// Si no se encontró un usuario con el token válido, muestra un mensaje y termina el script.
if ($user === null) {
    $_SESSION['warning'] = "Token no encontrado o inválido";
    exit();
}


// Verifica si el token ha expirado comparando la fecha actual con la fecha de expiración del token.
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $_SESSION['warning'] = "El token ha expirado"; // Corregido el error tipográfico
    exit();
}


// Cierra el cursor para liberar los recursos asociados con la consulta.
$stmt->closeCursor();

// Cierra la conexión a la base de datos.
$conexion = null;
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
        <form action="../../includes/recoveryPassword/process-reset-password.php" class="contLog-login" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">


            <img src="../../../assets/image/LogoColvatel.svg" alt="Logo compañia" class="contLog-login__logo">
            <h2 class="contLog-login__title">Restablecer contraseña</h2>

            <div class="input-container container-password">
                <input type="password" id="input" name="password" required="">
                <label for="input" name="password" class="label password">Nueva contraseña:</label>
                <span class="icon" id="eyes">
                    <img src="../../../assets/icons/eye-off.svg" alt="ver password">
                </span>
                <span class="icon-two" id="eyes">
                    <img src="../../../assets/icons/eye.svg" alt="ver password">
                </span>
                <div class="underline"></div>
            </div>

            <div class="input-container container-password__confirm">
                <input type="password" id="inputTwo" name="confirm_password" required="">
                <label for="input" name="password" class="label password">Confirmar contraseña:</label>
                <span class="iconTwo" id="eyes">
                    <img src="../../../assets/icons/eye-off.svg" alt="ver password">
                </span>
                <span class="icon-two__confirm" id="eyes">
                    <img src="../../../assets/icons/eye.svg" alt="ver password">
                </span>
                <div class="underline"></div>
            </div>

            <button class="BtnCon" type="submit">
                <span class="IconContainer">
                    <img src="../../../assets/icons/lock.svg" alt="iconSearch">
                </span>
                <p class="text">Restablecer</p>
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


        <?php if (isset($_SESSION['warning'])) : ?>
            <div class="toast warning" id="4">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Advertencia</p>
                        <p class="descripcion"><?php echo $_SESSION['warning']; ?></p>
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
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>
    </div>

    <!--===== particles-js =====-->
    <script src="../../../script/particles/particles.min.js"></script>
    <script src="../../../script/particles/animacion.js"></script>
    <script src="../../../script/newPassword/newPassword.js"></script>


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