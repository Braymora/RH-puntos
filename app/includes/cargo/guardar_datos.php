<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $codigo_cargo = $_POST['codigo_cargo'];
        $nombre_cargo = $_POST['nombre_cargo'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO cargos (id_cargo, nombre_cargo) VALUES (:codigo_cargo, :nombre_cargo)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':codigo_cargo', $codigo_cargo);
        $stmt->bindParam(':nombre_cargo', $nombre_cargo);

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
