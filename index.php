<?php
// Inicia la sesión para manejar la autenticación del usuario.
session_start();

// Cierra la sesión si el usuario ya está autenticado.
if (isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] === true) {
    // Limpia y destruye la sesión.
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración de metadatos y estilos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puntos</title>

    <!-- Incluye el archivo de fuentes -->
    <?php include 'app/views/font/font.php'; ?>
    
    <!-- Enlace al archivo de estilos general -->
    <link rel="stylesheet" href="style/general.css">
</head>

<body>
    <!-- Contenedor de partículas animadas -->
    <div id="particles-js"></div>

    <!-- Contenedor principal del formulario de login -->
    <div class="contLog">
        <form class="contLog-login" method="POST" action="" id="documento">
            <!-- Logo de la compañía -->
            <img src="assets/image/LogoColvatel.svg" alt="Logo compañia" class="contLog-login__logo">
            
            <!-- Título y descripción del formulario -->
            <h2 class="contLog-login__title">Bienvenido al sistema de puntos!</h2>
            <p class="contLog-login__descript">Antes de ingresar o iniciar sesión, por favor ingresa el siguiente dato</p>

            <!-- Campo de entrada para el número de documento -->
            <div class="input-container">
                <input type="text" id="input" name="doc" required="">
                <label for="input" class="label">Número de documento:</label>
                <div class="underline"></div>
            </div>

            <!-- Botón para validar el documento -->
            <button id="validarBtn" class="BtnCon">
                <span class="IconContainer">
                    <img src="assets/icons/search.svg" alt="iconSearch">
                </span>
                <p class="text">Validar</p>
            </button>
        </form>

        <!-- Contenedor de mensajes de alerta -->
        <div class="contenedor-toast" id="contenedor-toast">
            <!-- Toast de éxito -->
            <div class="toast exito" id="1" style="display: none;">
                <!-- Contenido del mensaje de éxito -->
                <div class="contenido">
                    <div class="icono">
                        <!-- Icono de éxito -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Éxito!</p>
                        <p class="descripcion" id="descripcion"></p>
                    </div>
                </div>
                <!-- Botón para cerrar el mensaje -->
                <button class="btn-cerrar">
                    <div class="icono">
                        <!-- Icono de cerrar -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>

            <!-- Toast de error -->
            <div class="toast success" id="2" style="display: none;">
                <!-- Contenido del mensaje de error -->
                <div class="contenido">
                    <div class="icono">
                        <!-- Icono de error -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Error!</p>
                        <p class="descripcion"></p>
                    </div>
                </div>
                <!-- Botón para cerrar el mensaje -->
                <button class="btn-cerrar">
                    <div class="icono">
                        <!-- Icono de cerrar -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>

            <!-- Toast de información -->
            <div class="toast info" id="3" style="display: none;">
                <!-- Contenido del mensaje de información -->
                <div class="contenido">
                    <div class="icono">
                        <!-- Icono de información -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Info</p>
                        <p class="descripcion"></p>
                    </div>
                </div>
                <!-- Botón para cerrar el mensaje -->
                <button class="btn-cerrar">
                    <div class ="icono">
                        <!-- Icono de cerrar -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>

            <!-- Toast de advertencia -->
            <div class="toast warning" id="4" style="display: none;">
                <!-- Contenido del mensaje de advertencia -->
                <div class="contenido">
                    <div class="icono">
                        <!-- Icono de advertencia -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Advertencia</p>
                        <p class="descripcion"></p>
                    </div>
                </div>
                <!-- Botón para cerrar el mensaje -->
                <button class="btn-cerrar">
                    <div class= "icono">
                        <!-- Icono de cerrar -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Script para las partículas animadas -->
    <script src="script/particles/particles.min.js"></script>
    <script src="script/particles/animacion.js"></script>

    <!-- Inclusión de jQuery y script de validación de documentos -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/validacionDocumento/validacionDocumento.js"></script>
</body>

</html>
