<?php
session_start();

require_once '../../config/conexion.php';

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];

    // Realizar la consulta en la base de datos para verificar si el usuario existe y está activo
    $stmt = $conexion->prepare("SELECT COUNT(*) AS count, id_estado_colabora FROM colaboradores WHERE cedula = :cedula");
    $stmt->bindParam(':cedula', $cedula);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comprobar si el usuario existe y si está activo
    $exists = ($result['count'] > 0);
    $id_estado_colabora = $result['id_estado_colabora'];

    if ($exists) {
        if ($id_estado_colabora == 2) {
            // El usuario está inactivo, mostrar un mensaje
            echo json_encode(['warning' => true, 'mensaje' => '¡Usuario existe. El usuario está inactivo']);
        } else {
            // El usuario existe y está activo, establecer la variable de sesión y redirigir
            $_SESSION['usuario_validado'] = true;
            echo json_encode(['redirect' => true, 'location' => 'app/views/login/login.php']);
        }
    } else {
        // El usuario no existe en la base de datos
        echo json_encode(['error' => true, 'mensaje' => 'El usuario no existe en la base de datos']);
    }
} else {
    // No se proporcionó la cédula
    echo json_encode(['error' => true, 'mensaje' => 'No se proporcionó la cédula']);
}
?>
