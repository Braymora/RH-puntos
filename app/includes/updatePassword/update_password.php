<?php
session_start();
include '../../config/conexion.php'; // Asegúrate de que tu archivo de conexión esté configurado correctamente

if (isset($_POST['iduser']) && isset($_POST['password']) && isset($_POST['newpassword']) && isset($_POST['confirmpassword'])) {
   
    $id = $_POST['iduser'];
    $password = $_POST['password'];
    $newPass = $_POST['newpassword'];
    $confirmPass = $_POST['confirmpassword'];

    // Verificar que la contraseña nueva y la confirmación sean iguales
    if ($newPass === $confirmPass) {
        try {
            // Preparar una consulta SQL para verificar la contraseña actual del usuario
            $query = "SELECT password FROM usuarios WHERE id = :id";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $currentPassword = $row['password'];

                // Verificar si la contraseña actual ingresada coincide con la contraseña almacenada en la base de datos
                if (password_verify($password, $currentPassword)) {
                    // Preparar una consulta SQL para actualizar la contraseña del usuario
                    $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE usuarios SET password = :hashedPass WHERE id = :id";
                    $stmt = $conexion->prepare($updateQuery);
                    $stmt->bindParam(':hashedPass', $hashedPass, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $_SESSION['success'] = "¡Contraseña actualizada correctamente!";
                    header("Location: ../../views/dashboard/dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "La contraseña actual ingresada no coincide, verifica e intenta nuevamente.";
                    header("Location: ../../views/dashboard/dashboard.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Error al consultar la contraseña actual. Por favor, intenta nuevamente.";
                header("Location: dashboard.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error en la base de datos: " . $e->getMessage();
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "La contraseña nueva y la confirmación no coinciden, verifica e intenta nuevamente.";
        header("Location: dashboard.php");
        exit();
    }
}

// Cerrar la conexión a la base de datos
$conexion = null;
