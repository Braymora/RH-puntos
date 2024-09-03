<?php

include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id_user = $_POST['id_user'];
    $email = $_POST['email'];
    $proyecto = $_POST['proyecto'];
    $rol = $_POST['rol'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    if (!empty($id_user) && !empty($email) && !empty($proyecto) && !empty($rol) && !empty($usuario) && !empty($contrasena)) {
        // Actualizar los datos del usuario
        $query = "UPDATE usuarios SET proyecto = :proyecto, usuario = :usuario, correo = :correo, password = :password, id_rol = :rol WHERE id = :id";
        $stmt = $conexion->prepare($query);
        $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt->execute(array(':id' => $id_user, ':proyecto' => $proyecto, ':usuario' => $usuario, ':correo' => $email, ':password' => $hashedPassword, ':rol' => $rol));

        if ($stmt->rowCount() > 0) {
            echo json_encode(['exito' => true, 'mensaje' => '¡Actualización exitosa para el usuario: ' . $usuario . ' !']);
        } else {
            echo json_encode(['error' => true, 'mensaje' => 'Hubo un error al actualizar los datos del usuario. Por favor, inténtalo de nuevo.']);
        }
    } else {
        echo json_encode(['warning' => true, 'mensaje' => 'Los campos son obligatorios, incluyendo el ID del usuario.']);
    }
}
