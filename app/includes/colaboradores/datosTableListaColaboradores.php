<?php
require_once '../../config/conexion.php'; // Asegúrate de especificar la ruta correcta a tu archivo de conexión.

// Realizar una consulta para seleccionar todos los registros de la tabla "colaboradores"
$query = "SELECT * FROM colaboradores";
$resultado = $conexion->query($query);

if ($resultado) {
    $colaboradores = $resultado->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($colaboradores);
} else {
    echo json_encode(['error' => 'No se pudieron recuperar los datos']);
}
