<?php
// actualizar.php

// Incluye tu archivo de conexión PDO
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id_ceco = $_POST['id_ceco'];
    $nombre_ceco = $_POST['nombre_ceco'];

    // Lógica para actualizar los datos en la base de datos
    // Utiliza una única consulta preparada para actualizar tanto el id_ceco como el nombre_ceco
    $sql = "UPDATE ceco SET id_ceco = :nuevo_id_ceco, nombre_ceco = :nombre_ceco WHERE id_ceco = :id_ceco";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nuevo_id_ceco', $id_ceco); // Aquí se actualiza el id_ceco
    $stmt->bindParam(':nombre_ceco', $nombre_ceco);
    $stmt->bindParam(':id_ceco', $id_ceco); // Este sería el valor actual del id_ceco

    // Ejecuta la consulta preparada
    if ($stmt->execute()) {
        // Envía una respuesta JSON con un mensaje de éxito
        echo json_encode(['exito' => true, 'mensaje' => 'Datos actualizados correctamente']);
    } else {
        // Envía una respuesta JSON con un mensaje de error
        echo json_encode(['error' => true, 'mensaje' => 'Error al actualizar los datos']);
    }
} else {
    // Envía una respuesta JSON con un mensaje de error si no es una solicitud POST válida
    echo json_encode(['error' => true, 'mensaje' => 'Solicitud no válida']);
    
}
