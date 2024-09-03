<?php
// Script para transferir datos después de 24 horas
$queryTransferirDatos = "INSERT INTO cantidad_puntos (cedula, nombre_colaborador, puntos_asignados, fecha_puntos, numero_orden, id_frecuencia)
                        SELECT cedula, nombre_colaborador, puntos_asignados, fecha_puntos, numero_orden, id_frecuencia
                        FROM temporal_servicios_puntos
                        WHERE TIMESTAMPDIFF(HOUR, fecha_puntos, NOW()) >= 24";

try {
    // Preparar y ejecutar la consulta de transferencia
    $stmt = $conexion->prepare($queryTransferirDatos);
    $stmt->execute();

    // Verificar si la transferencia fue exitosa
    if ($stmt->rowCount() > 0) {
        // Datos transferidos con éxito
        // Puedes también eliminar los registros de la tabla temporal después de la transferencia
        $queryEliminarRegistros = "DELETE FROM temporal_servicios_puntos WHERE TIMESTAMPDIFF(HOUR, fecha_puntos, NOW()) >= 24";
        $stmtEliminar = $conexion->prepare($queryEliminarRegistros);
        $stmtEliminar->execute();
    }
} catch (PDOException $e) {
    echo "Error al transferir datos: " . $e->getMessage();
}

// Cerrar la conexión
$conexion = null;
?>
