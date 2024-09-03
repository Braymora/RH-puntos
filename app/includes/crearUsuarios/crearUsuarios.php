<?php
session_start();


require_once("../../config/conexion.php");

if (isset($_POST['cedula'], $_POST['nombre'], $_POST['proyecto'], $_POST['rol'], $_POST['usuario'], $_POST['correo'], $_POST['password'])) {

    $cedula = $_POST["cedula"];
    $colaborador = $_POST["nombre"];
    $proyecto = $_POST["proyecto"];
    $rol = $_POST["rol"];
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // echo $cedula . "Cedula:" . "\n"; // Imprime 'cedula'
    // echo $colaborador . "Nombre:" . "\n"; // Imprime 'nombre'
    // echo $proyecto . "Proeycto:" . "\n"; // Imprime 'proyecto'
    // echo $rol . "Rol:" . "\n"; // Imprime 'rol'
    // echo $usuario . "Uusrio:" . "\n"; // Imprime 'usuario'
    // echo $correo . "Corre:" . "\n"; // Imprime 'correo'
    // echo $password . "Contraseña: " . "\n"; // Imprime 'password'
    // exit(); // Detiene la ejecución del script

    // Validaciones de contraseña
    if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        echo json_encode(['error' => true, 'mensaje' => 'La contraseña debe tener al menos 8 caracteres, contener al menos una letra y al menos un número.']);
        exit;
    }

    $query = "SELECT cedula_colabo FROM usuarios WHERE usuario = :usuario OR correo = :correo";
    $stmt = $conexion->prepare($query);
    $stmt->execute(array(':usuario' => $usuario, ':correo' => $correo));

    $query = "SELECT cedula_colabo FROM usuarios WHERE usuario = :usuario OR correo = :correo";
    $stmt = $conexion->prepare($query);
    $stmt->execute(array(':usuario' => $usuario, ':correo' => $correo));

    if ($stmt->rowCount() > 0) {
        echo json_encode(['error' => true, 'mensaje' => 'El nombre de usuario o el correo electrónico ya están registrados.']);
    } else {
        $query = "INSERT INTO usuarios (cedula_colabo, nombre_colaborador, proyecto, usuario, correo, password, id_rol) VALUES (:cedula, :colaborador, :proyecto, :usuario, :correo, :password, :rol)";
        $stmt = $conexion->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute(array(':cedula' => $cedula, ':colaborador' => $colaborador, ':proyecto' => $proyecto, ':usuario' => $usuario, ':correo' => $correo, ':password' => $hashedPassword, ':rol' => $rol));

        if ($stmt->rowCount() > 0) {
            echo json_encode(['exito' => true, 'mensaje' => "¡Registro exitoso! ¡Bienvenido, $usuario!"]);
        } else {
            echo json_encode(['error' => true, 'mensaje' => 'Hubo un error al registrar el usuario. Por favor, inténtalo de nuevo.']);
        }
    }
}else{
    echo json_encode(['warning' => true, 'mensaje' => 'Los campos son obligatorios.']);
}

