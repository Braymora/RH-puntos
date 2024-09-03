<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $codigo_tipo_contrato = $_POST['codigo_tipo_contrato'];
        $nombre_tipo_contrato = $_POST['nombre_tipo_contrato'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO tipo_contrato (id_tipo_contrato, nombre_tipo_contrato) VALUES (:codigo_tipo_contrato, :nombre_tipo_contrato)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':codigo_tipo_contrato', $codigo_tipo_contrato);
        $stmt->bindParam(':nombre_tipo_contrato', $nombre_tipo_contrato);

        if ($stmt->execute()) {
            echo json_encode(['exito' => true, 'mensaje' => '¡Datos guardados correctamente!']);
        } else {
            echo json_encode(['error' => true, 'mensaje' => 'Error al guardar los datos']);
        }
    } catch (\Throwable $th) {
        // Captura cualquier excepción y devuelve un mensaje de error en formato JSON
        echo json_encode(['error' => true, 'mensaje' => $th->getMessage()]);
    }
}
