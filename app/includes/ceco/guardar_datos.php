<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $codigoCeco = $_POST['codigo_ceco'];
        $nombreCeco = $_POST['nombre_ceco'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO ceco (id_ceco, nombre_ceco) VALUES (:codigo_ceco, :nombre_ceco)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':codigo_ceco', $codigoCeco);
        $stmt->bindParam(':nombre_ceco', $nombreCeco);

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
