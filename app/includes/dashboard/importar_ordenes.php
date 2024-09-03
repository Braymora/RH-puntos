<?php
require_once '../../config/conexion.php';
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$response = array(); // Inicializar la respuesta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES['excel_file']['tmp_name']) && !empty($_FILES['excel_file']['tmp_name'])) {
        $archivo = $_FILES['excel_file']['tmp_name'];
        $fileExtension = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);

        if ($fileExtension === 'xlsx') {
            $spreadsheet = IOFactory::load($archivo);
            $worksheet = $spreadsheet->getActiveSheet();
            $filas = $worksheet->getHighestRow();

            try {
                $conexion->beginTransaction();
                $stmtInsert = $conexion->prepare("INSERT INTO temporal_servicios_puntos (cedula, nombre_colaborador, puntos_asignados, fecha_puntos, numero_orden, id_frecuencia ) VALUES (:cedula, :nombre_colaborador, :puntos_asignados, :fecha_puntos, :numero_orden, :frecuencia)");

                for ($row = 2; $row <= $filas; $row++) {
                    $rowData = $worksheet->rangeToArray('A' . $row . ':F' . $row, NULL, TRUE, FALSE)[0];

                    list($cedula, $nombre_colaborador, $puntos_asignados, $fecha_puntos, $numero_orden, $frecuencia) = $rowData;

                    // Convertir la fecha de Excel a un formato reconocible por PHP
                    $fecha_puntos = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha_puntos);

                    // Formatear la fecha al formato 'Y-m-d' antes de insertarla en la base de datos
                    $fecha_formateada = $fecha_puntos->format('Y-m-d');

                    $stmtInsert->bindParam(':cedula', $cedula, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':nombre_colaborador', $nombre_colaborador, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':puntos_asignados', $puntos_asignados, PDO::PARAM_INT);
                    // Usar la variable en lugar del resultado de la función
                    $stmtInsert->bindParam(':fecha_puntos', $fecha_formateada);
                    $stmtInsert->bindParam(':numero_orden', $numero_orden, PDO::PARAM_STR);
                    $stmtInsert->bindParam(':frecuencia', $frecuencia, PDO::PARAM_STR);

                    $stmtInsert->execute();
                }

                $conexion->commit();
                $response = array("exito" => true, "mensaje" => "¡Datos del archivo Excel importados correctamente!");
            } catch (Exception $e) {
                // Capturar y mostrar el error detallado
                $conexion->rollBack();
                $response = array("error" => true, "mensaje" => "¡Error general al importar los datos del archivo Excel!", "errorDetallado" => $e->getMessage());
            }
        } else {
            $response = array("warning" => true, "mensaje" => "¡El archivo no es un archivo Excel válido (extensión .xlsx)!");
        }
    } else {
        $response = array("warning" => true, "mensaje" => "¡No se ha seleccionado un archivo o el archivo está vacío!");
    }
} else {
    $response = array("error" => true, "mensaje" => "Acceso denegado.");
}

header("Content-Type: application/json");
echo json_encode($response);
?>
