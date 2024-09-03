<?php

$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];

$queryTmpPuntos = "SELECT
tsp.id_tem_serv_punt AS 'id tabla temporal',
tsp.cedula AS 'Cedula colaborador',
tsp.nombre_colaborador AS 'Nombre colaborador',
tsp.puntos_asignados AS 'Puntos asignados',
tsp.fecha_puntos AS 'Fecha de puntos',
tsp.numero_orden AS 'Numero de orden',
fp.nombre_frecuencia AS 'Nombre de Frecuencia'
FROM
temporal_servicios_puntos tsp
JOIN
frecuencia_puntos fp ON tsp.id_frecuencia = fp.id_frecuencia
JOIN
colaboradores cl ON cl.cedula = tsp.cedula
WHERE cl.id_estado_colabora = 1";

if ($_SESSION['rol'] == 2) {
    $queryTmpPuntos .= " AND cl.id_ceco = :id_proyecto_usuario";
}

try {
    $stmt = $conexion->prepare($queryTmpPuntos);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario);
    }
    $stmt->execute();
    $resultTmpPuntos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit();
}
