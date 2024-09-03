<?php
// actualizar.php

// Incluye tu archivo de conexión PDO
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario enviados por POST
    $id = $_POST['id'];
    $cedula = $_POST['cedula'];
    $nombre_colaborador = $_POST['nombre_colaborador'];
    $correo = $_POST['correo'];
    $id_ceco = $_POST['id_ceco'];
    $nombre_cargo = $_POST['nombre_cargo'];
    $contratante = $_POST['contratante'];
    $nombre_ciudad = $_POST['nombre_ciudad'];
    $direccion = $_POST['direccion'];
    $nombre_proyecto = $_POST['nombre_proyecto'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $estado = $_POST['estado'];
    $Observacione = $_POST['Observacione'];


    // Lógica para actualizar los datos en la base de datos
    // Utiliza una única consulta preparada para actualizar tanto el id_ceco como el nombre_ceco
    // Update the database record
    $sql = "UPDATE colaboradores SET 
     cedula = :cedula,
     nombre_colaborador = :nombre_colaborador,
     correo = :correo,
     id_ceco = :id_ceco,
     nombre_cargo = :nombre_cargo,
     contratante = :contratante,
     nombre_ciudad = :nombre_ciudad,
     direccion = :direccion,
     nombre_proyecto = :nombre_proyecto,
     fecha_ingreso = :fecha_ingreso,
     id_estado_colabora = :estado,
     observaciones = :Observacione
     WHERE id_colaborador = :id";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_colaborador', $nombre_colaborador, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':id_ceco', $id_ceco, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_cargo', $nombre_cargo, PDO::PARAM_STR);
    $stmt->bindParam(':contratante', $contratante, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_ciudad', $nombre_ciudad, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':nombre_proyecto', $nombre_proyecto, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_ingreso', $fecha_ingreso, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->bindParam(':Observacione', $Observacione, PDO::PARAM_STR);

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
?>
