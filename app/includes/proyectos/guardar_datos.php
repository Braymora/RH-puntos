<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $codigo_proyecto = $_POST['codigo_proyecto'];
        $nombre_proyecto = $_POST['nombre_proyecto'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO proyectos (id_proyecto, nombre_proyecto) VALUES (:codigo_proyecto, :nombre_proyecto)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':codigo_proyecto', $codigo_proyecto);
        $stmt->bindParam(':nombre_proyecto', $nombre_proyecto);

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
