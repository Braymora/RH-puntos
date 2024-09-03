<?php

session_start();

if (!isset($_POST["token"])) {
    $_SESSION['error'] = "Token no encontrado";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

$token = $_POST["token"];
$conexion = require __DIR__ . "/../../config/conexion.php";

$sql = "SELECT * FROM usuarios
        WHERE reset_token_hash IS NOT NULL";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$user = null;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (password_verify($token, $row['reset_token_hash'])) {
        $user = $row;
        break;
    }
}

if ($user === null) {
    $_SESSION['error'] = "Token no encontrado";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $_SESSION['error'] = "El token ha expirado";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

if (strlen($_POST["password"]) < 8) {
    $_SESSION['warning'] = "La contraseña debe tener al menos 8 caracteres";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

if (!preg_match("/[a-zA-Z]/", $_POST["password"])) {
    $_SESSION['warning'] = "La contraseña debe contener al menos una letra";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    $_SESSION['warning'] = "La contraseña debe contener al menos un número";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

if ($_POST["password"] !== $_POST["confirm_password"]) {
    $_SESSION['error'] = "Las contraseñas no coinciden";
    header("Location: ../../views/recoveryPassword/recoveryPassword.php?token=" . urlencode($token));
    exit();
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql_update = "UPDATE usuarios
               SET password = :password_hash,
                   reset_token_hash = NULL,
                   reset_token_expires_at = NULL
               WHERE id = :id";

$stmt_update = $conexion->prepare($sql_update);
$stmt_update->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
$stmt_update->bindParam(':id', $user["id"], PDO::PARAM_INT);

$stmt_update->execute();

$_SESSION['success'] = "Contraseña actualizada. Ahora puedes iniciar sesión";
header("Location: ../../views/login/login.php");
exit();
