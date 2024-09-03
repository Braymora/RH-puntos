<?php
// actualizar.php

// Incluye tu archivo de conexión PDO
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id_ciudad = $_POST['id_ciudad'];
    $nombre_ciudad = $_POST['name_ciudad']; // Debes usar 'name_ciudad' en lugar de 'nombre_cargo'

    // Lógica para actualizar los datos en la base de datos
    // Utiliza una única consulta preparada para actualizar tanto el id_ceco como el nombre_ceco
    $sql = "UPDATE ciudades SET nombre_ciudad = :nombre_ciudad WHERE id_ciudad = :id_ciudad";

    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':nombre_ciudad', $nombre_ciudad); // Corregido a 'name_cargo'
    $stmt->bindValue(':id_ciudad', $id_ciudad);

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
