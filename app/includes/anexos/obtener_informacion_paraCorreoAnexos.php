<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;

require '../../config/conexion.php';
require '../../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numeroAnexo = isset($_POST["numeroAnexo"]) ? $_POST["numeroAnexo"] : '';
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : '';

    $consultarOrden = "SELECT 
        ax.id_anexo, 
        ax.numero_anexo, 
        ax.cedula_colabo, 
        ax.nombre_colaborador,
        cl.correo, 
        ax.idcolaborador, 
        ax.actividades, 
        ax.puntos, 
        ax.observaciones 
        FROM anexo ax
        JOIN colaboradores cl ON ax.idcolaborador = cl.id_colaborador
        WHERE ax.numero_anexo = :numero_anexo";

    // Preparar la consulta
    $stmt = $conexion->prepare($consultarOrden);
    $stmt->bindParam(':numero_anexo', $numeroAnexo, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*========================
    datos para la tabla en el formato
    ================================*/

    // Consulta parametrizada
    $queryActividades = "SELECT 
    ax.numero_anexo,
    ax.cedula_colabo, 
    ax.actividades, 
    ax.puntos
    FROM anexo ax
    JOIN colaboradores cl ON ax.idcolaborador = cl.id_colaborador
    WHERE ax.numero_anexo = :numero_anexo";


    // Asignar valores a variables
    foreach ($resultados as $resultado) {
        $numero_anexo = $resultado['numero_anexo'];
        $actividades = $resultado['actividades'];
        $observaciones = $resultado['observaciones'];
        $nombre_colaborador = $resultado['nombre_colaborador'];
        $cedula_colabo = $resultado['cedula_colabo'];

        // Preparar la consulta
        $stmt = $conexion->prepare($queryActividades);

        // Vincular el parámetro
        $stmt->bindParam(':numero_anexo', $numero_anexo, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener resultados
        $resultadosTabla = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include_once '../../views/Anexo/formatoAnexo.php';

        $mpdf = new \Mpdf\Mpdf();

        // Crear una instancia de Mpdf
        $plantillaAnexo = getPlantillaOrden($numero_anexo, $observaciones, $nombre_colaborador, $cedula_colabo, $resultadosTabla);

        $mpdf->WriteHTML($plantillaAnexo, \Mpdf\HTMLParserMode::HTML_BODY);

        $filename = "Anexo_{$numero_anexo}.pdf";

        // Obtener el contenido del PDF como cadena
        $pdfOutput = $mpdf->Output('', 'S');
    }

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
        $mail->addStringAttachment($pdfOutput, $filename);

        // Configurar el resto del correo
        $mail->setFrom('pruebasWebHj@outlook.com', 'Anexo');
        $mail->addAddress($correo);
        $mail->Subject = 'Se generó un nuevo Anexo';

        // Cuerpo del correo (puede estar vacío si solo estás enviando el PDF)
        $mail->Body = '
                    <h1>Anexo</h1>
                    <p>Estimado/a ' . $nombre_colaborador . ',</p>
                    <p>Se ha generado un nuevo anexo con el número: ' . $numeroAnexo . '.</p>
                  
                    <p>Adjunto a este correo encontrarás el PDF con los detalles completos del anexo.</p>
                    <p>Si tienes alguna pregunta o necesitas más información, no dudes en comunicarse a <a href="mailto:talentohumano@com.co" style="color: #005cbf;">talentohumano@com.co</a></p>
                    <p>Saludos cordiales,</p>
                    <p>Equipo de Gestión</p>
                ';


        // Enviar el correo
        $mail->send();

        echo json_encode(['exito' => true, 'mensaje' => '¡Correo enviado con éxito!']);
    } catch (Exception $e) {
        $mensajeErrorCorreo = isset($mail) ? $mail->ErrorInfo : '';
        echo json_encode(['error' => true, 'mensaje' => $th->getMessage(), 'errorCorreo' => $mensajeErrorCorreo]);
    }
}
