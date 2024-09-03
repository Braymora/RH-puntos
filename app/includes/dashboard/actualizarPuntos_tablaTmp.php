<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos enviados por AJAX
        $id_tmp = $_POST['id_tmp'];
        $puntosModificar = $_POST['puntosModificar'];
        $frecuencia = $_POST['frecuencia'];

        // Prepara y ejecuta la consulta SQL para actualizar los datos en la tabla temporal
        $query = "UPDATE temporal_servicios_puntos SET puntos_asignados = :puntosModificar, id_frecuencia = :frecuencia WHERE id_tem_serv_punt = :id_tmp";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':puntosModificar', $puntosModificar, PDO::PARAM_INT);
        $stmt->bindParam(':frecuencia', $frecuencia, PDO::PARAM_INT);
        $stmt->bindParam(':id_tmp', $id_tmp, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // La actualización se realizó con éxito, puedes enviar una respuesta si es necesario
            echo json_encode(['exito' => true, 'mensaje' => 'Datos actualizados con éxito']);
        } else {
            // Hubo un error en la actualización
            echo json_encode(['error' => false, 'mensaje' => 'Error al actualizar los datos']);
        }
    } catch (PDOException $e) {
        // Maneja cualquier error de la base de datos
        echo json_encode(['error' => false, 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Si no es una solicitud POST, no hagas nada o maneja el caso en consecuencia
    echo json_encode(['error' => false, 'mensaje' => 'Solicitud no válida']);
}
?>
