<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $codigo_ciudad = $_POST['codigo_ciudad'];
        $nombre_ciudad = $_POST['nombre_ciudad'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO ciudades (id_ciudad, nombre_ciudad) VALUES (:codigo_ciudad, :nombre_ciudad)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':codigo_ciudad', $codigo_ciudad);
        $stmt->bindParam(':nombre_ciudad', $nombre_ciudad);

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
