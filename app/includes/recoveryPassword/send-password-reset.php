<?php

session_start();

if (isset($_POST["email"])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $_SESSION['error'] = "Por favor, introduce una dirección de correo electrónico válida.";
        header("Location: ../../views/newPassword/newPassword.php");
        exit();
    }

    $token = bin2hex(random_bytes(16));
    $token_hash = password_hash($token, PASSWORD_BCRYPT);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    require __DIR__ . "/../../config/conexion.php";

    try {
        $sql = "UPDATE usuarios
                SET reset_token_hash = :token_hash,
                    reset_token_expires_at = :expiry
                WHERE correo = :email";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':token_hash', $token_hash, PDO::PARAM_STR);
        $stmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $mail = require __DIR__ . "/mailer.php";
            $mail->CharSet = 'UTF-8';
            $mail->setFrom("pruebasWebHj@outlook.com");
            $mail->addAddress($email);
            $mail->Subject = "Se te olvidó la contraseña?";
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                    }
                    .container {
                        width: 80%;
                        margin: auto;
                        overflow: hidden;
                    }
                    .main-content {
                        float: left;
                        width: 70%;
                        padding: 30px;
                        background-color: #fff;
                        color: #333;
                    }
                    .main-content h1 {
                        color: #333;
                    }
                    .button {
                        display: inline-block;
                        color: #fff;
                        background-color: #333;
                        padding: 10px 20px;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="main-content">
                        <h1>Recuperación de Contraseña</h1>
                        <p>Hola,</p>
                        <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el botón de abajo para cambiar tu contraseña.</p>
                        <a href="http://127.0.0.1/RHpuntos/app/views/recoveryPassword/recoveryPassword.php?token=' . $token . '" class="button">Restablecer Contraseña</a>
                        <p>Si no solicitaste un cambio de contraseña, ignora este correo electrónico o ponte en contacto con el soporte si tienes alguna pregunta.</p>
                        <p>Gracias,</p>
                        <p>El equipo de Soporte</p>
                    </div>
                </div>
            </body>
            </html>            
            ';
            try {
                $mail->send();
                $_SESSION['success'] = "Correo enviado, por favor revisa tu bandeja de entrada.";
                header("Location: ../../views/newPassword/newPassword.php");
                //$alert1 = 'Correo enviado, por favor revisa tu bandeja de entrada.';
                //echo "Correo enviado, por favor revisa tu bandeja de entrada.";
            } catch (Exception $e) {
                echo "El mensaje no pudo ser enviado. Error del servidor de correo: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "No se encontró el usuario o no se realizó ninguna actualización.";
            header("Location: ../../views/newPassword/newPassword.php");
            exit();
            //$alert = 'No se encontró el usuario o no se realizó ninguna actualización.';
            //echo "No se encontró el usuario o no se realizó ninguna actualización.";
        }
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    } finally {
        $conexion = null;
    }
} else {
    echo "La clave 'email' no está presente en la matriz \$_POST.";
}
