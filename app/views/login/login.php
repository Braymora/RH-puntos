<?php
$alert = '';
session_start();

// Verifica si el usuario no está autenticado y lo redirige a la página principal.
if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] !== true) {
    header('Location: ../../../index.php');
    exit();
}

// Si ya existe una sesión activa, redirige al panel de control.
if (!empty($_SESSION['active'])) {
    header('location: ../dashboard/dashboard.php');
} else {
    // Verifica si se ha enviado el formulario de inicio de sesión.
    if (!empty($_POST)) {
        // Verifica que los campos de usuario y contraseña no estén vacíos.
        if (empty($_POST['user']) || empty($_POST['password'])) {
            $alert = 'Ingrese su usuario y su clave';
        } else {
            // Incluye el archivo de conexión a la base de datos.
            require_once "../../config/conexion.php";

            $user = $_POST['user'];
            $pass = $_POST['password'];

            try {
                // Consulta la base de datos para obtener la información del usuario.
                $query = $conexion->prepare("SELECT u.id, u.cedula_colabo, u.nombre_colaborador, u.proyecto, u.usuario, u.estado_user, u.id_rol, r.id_rol, r.nombre_rol, password 
                FROM usuarios u 
                INNER JOIN rol r 
                ON u.id_rol = r.id_rol
                 WHERE u.usuario = :user");
                $query->bindParam(':user', $user);
                $query->execute();

                $data = $query->fetch(PDO::FETCH_ASSOC);

                // Verifica si se encontró un usuario con el nombre proporcionado.
                if ($data) {
                    $hashedPass = $data['password'];

                    // Verifica si la contraseña proporcionada coincide con la almacenada.
                    if (password_verify($pass, $hashedPass)) {
                        // Inicia la sesión y asigna valores a las variables de sesión.
                        $_SESSION['active'] = true;
                        $_SESSION['idUser'] = $data['id'];
                        $_SESSION['nombre'] = $data['nombre_colaborador'];
                        $_SESSION['email'] = $data['correo']; // Asegúrate de que este campo esté en la base de datos.
                        $_SESSION['user'] = $data['usuario'];
                        $_SESSION['rol'] = $data['id_rol'];
                        $_SESSION['rol_name'] = $data['nombre_rol'];
                        $_SESSION['id_proyecto'] = $data['proyecto'];

                        // Redirige al panel de control.
                        header('location: ../dashboard/dashboard.php');
                    } else {
                        $alert = 'El usuario o la contraseña son incorrectos';
                    }
                } else {
                    $alert = 'El usuario o la contraseña no existe';
                }
            } catch (PDOException $e) {
                $alert = 'Error en la consulta: ' . $e->getMessage();
            }
        }
    }
}
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
        <form class="contLog-login" method="post" id="login-form">
            <img src="../../../assets/image/LogoColvatel.svg" alt="Logo compañia" class="contLog-login__logo">
            <h2 class="contLog-login__title">Ingresar al sistema!</h2>
            <p class="contLog-login__descript">Antes de ingresar o iniciar sesión por favor ingresa los siguientes datos</p>

            <div class="input-container">
                <input type="text" name="user" required="">
                <label for="input" class="label">Usuario:</label>
                <div class="underline"></div>
            </div>
            <div class="input-container container-password">
                <a href="../newPassword/newPassword.php">¿Has olvidado tu contraseña?</a>
                <input type="password" id="input" name="password" required="">
                <label for="input" class="label password">Password:</label>
                <span class="icon" id="eyes">
                    <img src="../../../assets/icons/eye-off.svg" alt="ver password">
                </span>
                <span class="icon-two" id="eyes">
                    <img src="../../../assets/icons/eye.svg" alt="ver password">
                </span>
                <div class="underline"></div>
            </div>

            <button class="BtnCon" type="submit">
                <span class="IconContainer">
                    <img src="../../../assets/icons/lock.svg" alt="iconSearch">
                </span>
                <p class="text">Ingresar</p>
            </button>
        </form>
    </div>

    <!-- Muestra el mensaje de alerta si no se han llenado los campos -->
    <div class="contenedor-toast" id="contenedor-toast">
        <?php if ($alert) : ?>
            <div class="toast success" id="2">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Error!</p>
                        <p class="descripcion"><?php echo $alert; ?></p>
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
        <?php endif; ?>
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
    </div>

    <!--===== particles-js =====-->
    <script src="../../../script/particles/particles.min.js"></script>
    <script src="../../../script/particles/animacion.js"></script>
    <script src="../../../script/login/login.js"></script>
</body>

</html>
