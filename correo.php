<?php
require_once '../../config/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../../PHPMailer/Exception.php';
require '../../../PHPMailer/PHPMailer.php';
require '../../../PHPMailer/SMTP.php';
require '../../../vendor/autoload.php';

try {
    $sql = "SELECT os.id_servicio, 
    os.numero_orden, 
    os.ceco, 
    c.nombre_colaborador, 
    os.cantidad_puntos,
    (os.cantidad_puntos - COALESCE(SUM(cp.puntos_consumo), 0)) AS puntos_restantes, os.fecha_fin, 
    OS.fecha_envio_correo, 
    os.numero_contrato, 
    os.id_usuario, 
    us.correo, 
    us.nombre_colaborador AS 'nombre de usuario', 
    rl.nombre_rol, os.ultima_alerta	 
    FROM ordenes_servicios os
    JOIN contratos ctr ON os.numero_contrato = ctr.numero_contrato
    JOIN colaboradores c ON ctr.id_colaborador = c.id_colaborador
    JOIN cantidad_puntos cp ON os.numero_orden = cp.numero_orden
    LEFT JOIN usuarios us ON us.id=os.id_usuario
    LEFT JOIN rol rl ON us.id_rol = rl.id_rol
    GROUP BY os.id_servicio, 
    os.numero_orden, 
    os.ceco, 
    os.cantidad_puntos, 
    os.fecha_fin, 
    os.id_estadoOrden,
    os.numero_contrato, 
    os.id_usuario, 
    us.correo, 
    os.fecha_envio_correo, 
    os.ultima_alerta";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ordenes as $orden) {
        $fecha_actual = new DateTime();
        $fecha_fin = new DateTime($orden['fecha_fin']);
        $puntos_restantes = $orden['puntos_restantes'];
        $correo_coordinador = $orden['correo'];
        $ultima_alerta = new DateTime($orden['ultima_alerta']);

        $diferencia_horas = $fecha_actual->diff($ultima_alerta)->h;

        if ($diferencia_horas >= 24) {
            if ($fecha_actual < $fecha_fin && $fecha_actual < $fecha_fin->modify('-6 days') && $puntos_restantes < ($orden['cantidad_puntos'] * 0.3)) {
                enviarCorreo($correo_coordinador, "La orden {$orden['numero_orden']} asociada al colaborador {$orden['nombre_colaborador']}, aún tiene el 30% de {$orden['cantidad_puntos']} puntos sin consumir.");
            }

            // Validación 1
            if ($fecha_actual < $fecha_fin && $fecha_actual < $fecha_fin->modify('-6 days') && $puntos_restantes < ($orden['cantidad_puntos'] * 0.3)) {
                enviarCorreo($correo_coordinador, "La orden {$orden['numero_orden']} asociada al colaborador {$orden['nombre_colaborador']}, aún tiene el 30% de {$orden['cantidad_puntos']} puntos sin consumir.");
            }

            // Validación 2
            if ($fecha_actual < $fecha_fin && $puntos_restantes == 0) {
                enviarCorreo($correo_coordinador, "El colaborador {$orden['nombre_colaborador']} no tiene puntos disponibles para la orden {$orden['numero_orden']}.");
            }

            // Validación 3
            if ($fecha_actual == $fecha_fin && $puntos_restantes == 0) {
                enviarCorreo($correo_coordinador, "El colaborador {$orden['nombre_colaborador']} asociado a la orden {$orden['numero_orden']} no tiene puntos disponibles.");
            }

            // Validación 4
            if ($fecha_actual < $fecha_fin && $fecha_actual < $fecha_fin->modify('-5 days') && $puntos_restantes > 0) {
                enviarCorreo($correo_coordinador, "El colaborador {$orden['nombre_colaborador']} asociado a la orden {$orden['numero_orden']} se encuentra a 5 días de la fecha final de la orden y los puntos restantes son {$puntos_restantes}.");
            }

            // Validación 5
            if ($fecha_actual > $fecha_fin && $puntos_restantes == 0) {
                enviarCorreo($correo_coordinador, "El colaborador {$orden['nombre_colaborador']} asociado a la orden {$orden['numero_orden']}, la fecha fin de la orden caduca hoy y tiene {$puntos_restantes} puntos.");
            }

            // Actualizar la columna ultima_alerta con la fecha y hora actual
            $sqlUpdate = "UPDATE ordenes_servicios SET ultima_alerta = NOW() WHERE id_servicio = :id_servicio";
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':id_servicio', $orden['id_servicio']);
            $stmtUpdate->execute();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conexion = null;

function enviarCorreo($destinatario, $mensaje)
{
    $mail = new PHPMailer(true);

    try {
        // Configuraciones del servidor SMTP
        $mail->isSMTP();
        // ... (configuraciones de SMTP)

        // Configuraciones del mensaje
        $mail->setFrom('pruebasWebHj@outlook.com', 'APP RHpuntos');
        $mail->addAddress($destinatario);
        $mail->isHTML(true);
        $mail->Subject = 'Alerta RHpuntos ordenes';
        $mail->Body    = '
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,300&display=swap"
                rel="stylesheet">

            <style>
                body {
                    align-items: center;
                    display: flex;
                    font-family: \'Open Sans\', sans-serif;
                    justify-content: center;
                }

                .email {
                    align-items: center;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    height: 50%;
                    width: 50%;
                }

                .email .email_title {
                    font-size: 40px;
                    font-weight: bold;
                    text-align: center;
                }

                img {
                    width: 300px;
                }

                p {
                    font-size: 22px;
                    text-align: justify;
                }
            </style>
        </head>

        <body>
            <div class="email">
                <h2 class="email_title">Mensaje informativo emitido por RHpuntos</h2>
                <img src="https://illlustrations.co/static/74898b728451a18443001cffcfaf7834/ee604/119-working.png" alt="imagen de recuperar contraseña" width="400px">
                <p> ' . $mensaje . ' </p>
            </div>
        </body>

        </html>
        ';

        // Enviar correo
        $mail->send();
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
