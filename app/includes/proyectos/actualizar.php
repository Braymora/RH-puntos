<?php
// actualizar.php

// Incluye tu archivo de conexión PDO
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id_proyecto = $_POST['id_proyecto'];
    $nombre_proyecto = $_POST['name_proyecto']; // Debes usar 'name_cargo' en lugar de 'nombre_cargo'

    // Lógica para actualizar los datos en la base de datos
    // Utiliza una única consulta preparada para actualizar tanto el id_ceco como el nombre_ceco
    $sql = "UPDATE proyectos SET nombre_proyecto = :nombre_proyecto WHERE id_proyecto = :id_proyecto";

    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':nombre_proyecto', $nombre_proyecto); // Corregido a 'name_cargo'
    $stmt->bindValue(':id_proyecto', $id_proyecto);

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
