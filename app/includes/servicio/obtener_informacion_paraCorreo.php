<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;

require '../../config/conexion.php';
require '../../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : '';
    $numeroOrden = isset($_POST["numero_orden"]) ? $_POST["numero_orden"] : '';

    $consultarOrden = "SELECT 
    os.id_servicio, 
    os.numero_orden, 
    os.numero_contrato, 
    cl.cedula,
    cl.nombre_colaborador, 
    cl.nombre_cargo,
    cl.correo,
    cl.nombre_proyecto,
    os.ceco, 
    cl.nombre_cargo,
    os.cantidad_puntos, 
    os.fecha_inicio, 
    os.fecha_fin, 
    os.justificacion,
    os.observaciones,
    FROM ordenes_servicios os
    JOIN colaboradores cl ON os.numero_contrato = cl.cedula WHERE os.numero_orden = :numeroOrden";

    // Preparar la consulta
    $stmt = $conexion->prepare($consultarOrden);
    $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    // Asignar valores a variables
    $nombre_proyecto = $resultado['nombre_proyecto'];
    $cedula = $resultado['cedula'];
    $cantidad_puntos = $resultado['cantidad_puntos'];
    $ceco = $resultado['ceco'];
    $nombre_colaborador = $resultado['nombre_colaborador'];
    $nombre_cargo = $resultado['nombre_cargo'];
    $fecha_inicio = $resultado['fecha_inicio'];
    $fecha_fin = $resultado['fecha_fin'];
    $justificacion = $resultado['justificacion'];
    $observaciones = $resultado['observaciones'];


    include '../../views/gestion/formatoOrden.php';

    $mpdf = new \Mpdf\Mpdf();

    // Crear una instancia de Mpdf

    $plantillaOrden = getPlantillaOrden($numeroOrden, $nombre_proyecto, $cedula, $cantidad_puntos, $ceco, $nombre_colaborador, $nombre_cargo, $fecha_inicio, $fecha_fin, $justificacion, $observaciones);

    $mpdf->WriteHTML($plantillaOrden, \Mpdf\HTMLParserMode::HTML_BODY);

    $filename = "orden_servicio_{$numeroOrden}.pdf";

    // Obtener el contenido del PDF como cadena
    $pdfOutput = $mpdf->Output('', 'S');

    // Crear instancia de PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        // Configurar el correo
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Host = "smtp-mail.outlook.com";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->Username = "pruebasWebHj@outlook.com";
        $mail->Password = "mcf2023**";

        // Adjuntar el PDF desde la variable
        $mail->addStringAttachment($pdfOutput, 'orden_servicio_' . $numeroOrden . '.pdf');

        // Configurar el resto del correo
        $mail->setFrom('pruebasWebHj@outlook.com', 'Orden de servicio');
        $mail->addAddress($correo); // Agrega la dirección de correo destino
        $mail->Subject = 'Se generó una nueva orden';

        // Cuerpo del correo (puede estar vacío si solo estás enviando el PDF)
        $mail->Body = '
                    <div style="font-family: Arial, sans-serif; color: #333;">
                        <h1 style="text-align: center; color: #005cbf;">Orden de Servicio</h1>
                        <p>Estimado/a ' . $nombre_colaborador . ',</p>
                        <p>Se ha generado una nueva orden de servicio con el número: <strong>' . $numeroOrden . '</strong>.</p>
                        <p>Los detalles de la orden son los siguientes:</p>
                        <ul style="list-style-type: none;">
                            <li><strong>Proyecto:</strong> ' . $nombre_proyecto . '</li>
                            <li><strong>Cantidad de puntos:</strong> ' . $cantidad_puntos . '</li>
                            <li><strong>CECO:</strong> ' . $ceco . '</li>
                            <li><strong>Cargo:</strong> ' . $nombre_cargo . '</li>
                            <li><strong>Fecha de inicio:</strong> ' . $fecha_inicio . '</li>
                        </ul>
                        <p>Adjunto a este correo encontrarás el PDF con los detalles completos de la orden.</p>
                        <p>Si estás de acuerdo con los términos de la orden, por favor firma y envía al correo <a href="mailto:talentohumano@com.co" style="color: #005cbf;">talentohumano@com.co</a> o, en su defecto, acércate a nuestras oficinas ubicadas en Diagonal 23K No. 96F -62.</p>
                        <p>Si tienes alguna pregunta o necesitas más información, no dudes en responder al correo <a href="mailto:talentohumano@com.co" style="color: #005cbf;">talentohumano@com.co</a> o al número xxxxxxxx</p>
                        <p style="margin-top: 50px;">Saludos cordiales,</p>
                        <p>Equipo de Gestión</p>
                    </div>
';



        // Enviar el correo
        $mail->send();

        echo json_encode(['exito' => true, 'mensaje' => '¡Correo enviado con éxito!']);
    } catch (Exception $e) {
        $mensajeErrorCorreo = isset($mail) ? $mail->ErrorInfo : '';
        echo json_encode(['error' => true, 'mensaje' => $th->getMessage(), 'errorCorreo' => $mensajeErrorCorreo]);
    }
}
