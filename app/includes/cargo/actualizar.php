<?php
// actualizar.php

// Incluye tu archivo de conexión PDO
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id_cargo = $_POST['id_cargo'];
    $nombre_cargo = $_POST['name_cargo']; // Debes usar 'name_cargo' en lugar de 'nombre_cargo'

    // Lógica para actualizar los datos en la base de datos
    // Utiliza una única consulta preparada para actualizar tanto el id_ceco como el nombre_ceco
    $sql = "UPDATE cargos SET nombre_cargo = :nombre_cargo WHERE id_cargo = :id_cargo";

    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':nombre_cargo', $nombre_cargo); // Corregido a 'name_cargo'
    $stmt->bindValue(':id_cargo', $id_cargo);

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
