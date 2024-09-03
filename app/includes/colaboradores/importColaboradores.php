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

                $insertData = [];
                for ($row = 2; $row <= $filas; $row++) {
                    $rowData = $worksheet->rangeToArray('A' . $row . ':L' . $row, NULL, TRUE, FALSE)[0];

                    // Verificar si la cédula no es nula o vacía
                    if (!empty($rowData[0])) {
                        list($cedula, $nombre_colaborador, $correo, $contratante, $direccion, $fechaIngreso, $observacion, $id_estado_colabora, $id_ceco, $nombre_cargo, $nombre_ciudad, $nombre_proyecto) = $rowData;

                        // Convertir la fecha de Excel a un formato reconocible por PHP
                        $fechaIn = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaIngreso);

                        // Formatear la fecha al formato 'Y-m-d' antes de insertarla en la base de datos
                        $fecha_formateada = $fechaIn->format('Y-m-d');

                        $insertData[] = [
                            'cedula' => $cedula,
                            'nombre_colaborador' => $nombre_colaborador,
                            'correo' => $correo,
                            'contratante' => $contratante,
                            'direccion' => $direccion,
                            'fechaIngreso' => $fecha_formateada,
                            'observacion' => $observacion,
                            'id_estado_colabora' => $id_estado_colabora,
                            'ceco' => $id_ceco,
                            'cargo' => $nombre_cargo,
                            'ciudad' => $nombre_ciudad,
                            'centrodeCostos' => $nombre_proyecto
                        ];
                    }
                }

                // Insertar todos los datos a la vez
                $stmtInsert = $conexion->prepare("INSERT INTO colaboradores (cedula, nombre_colaborador, correo, contratante, direccion, fecha_ingreso, observaciones, id_estado_colabora, id_ceco, nombre_cargo, nombre_ciudad, nombre_proyecto) VALUES (:cedula, :nombre_colaborador, :correo, :contratante, :direccion, :fechaIngreso, :observacion, :id_estado_colabora, :ceco, :cargo, :ciudad, :centrodeCostos)");
                foreach ($insertData as $data) {
                    $stmtInsert->execute($data);
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
