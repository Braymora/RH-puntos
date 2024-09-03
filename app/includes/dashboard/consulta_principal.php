<?php

include '../../config/conexion.php';

$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];

$query = "SELECT
    c.id_colaborador,
    c.cedula,
    c.nombre_colaborador,
    c.fecha_ingreso,
    c.id_estado_colabora,
    stc.nombre_estado_col,
    c.id_ceco,
    c.nombre_cargo,
    c.nombre_proyecto,
    c.correo,
    c.direccion,
    c.nombre_ciudad,
    ct.numero_contrato
    FROM colaboradores c
    LEFT JOIN ceco ce ON c.id_ceco = ce.id_ceco
    LEFT JOIN contratos ct ON ct.id_colaborador = c.id_colaborador
    LEFT JOIN ciudades cd ON c.nombre_ciudad = cd.nombre_ciudad
    LEFT JOIN proyectos pr ON c.nombre_proyecto = pr.nombre_proyecto
    LEFT JOIN estado_colaborador stc ON c.id_estado_colabora = stc.id_estado_colabora
    WHERE c.id_estado_colabora = 1 AND ct.id_colaborador = c.id_colaborador";

if ($_SESSION['rol'] == 2) {
    $query .= " AND c.id_ceco = :id_proyecto_usuario";
}

$query .= " ORDER BY c.nombre_colaborador;";






$queryFrecuencia = "SELECT id_frecuencia, nombre_frecuencia FROM frecuencia_puntos";

//validar cantidad de colaboradores segun el ceco del usuario coordinador
$cantColaboradores = "SELECT count(*) FROM colaboradores cl WHERE cl.id_estado_colabora = 1";
if ($_SESSION['rol'] == 2) {
    $cantColaboradores .= " AND cl.id_ceco = :id_proyecto_usuario";
}



// Validar cantidad de contratos según el ceco del usuario coordinador
$cantContratos = "SELECT COUNT(*) AS total_registros
                  FROM contratos cnt 
                  JOIN colaboradores cl ON cnt.id_colaborador = cl.id_colaborador
                  WHERE cl.id_estado_colabora = 1";

if ($_SESSION['rol'] == 2) {
    $cantContratos .= " AND cl.id_ceco = :id_proyecto_usuario";
}

// Validar cantidad de órdenes de servicios según el ceco del usuario coordinador
$cantOrdenesServicios = "SELECT COUNT(*) AS total_registrosOrdenes
                        FROM ordenes_servicios os
                        JOIN colaboradores cl ON os.numero_contrato = cl.cedula
                        WHERE cl.id_estado_colabora = 1";

if ($_SESSION['rol'] == 2) {
    $cantOrdenesServicios .= " AND cl.id_ceco = :id_proyecto_usuario";
}

try {
    // Consulta principal
    $stmt = $conexion->prepare($query);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Consulta para datos de la tabla frecuencia_puntos
    $stmt = $conexion->prepare($queryFrecuencia);
    $stmt->execute();
    $resultFrecuencia = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Consulta cantidad de colaboradores
    $stmt = $conexion->prepare($cantColaboradores);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultCantColaborador = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Consulta cantidad de contratos
    $stmt = $conexion->prepare($cantContratos);
    if ($_SESSION['rol'] == 2) {
        // Asegúrate de que $id_proyecto_usuario esté definido
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultCantContratos = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Consulta cantidad de SERVICIOS
    $stmt = $conexion->prepare($cantOrdenesServicios);
    if ($_SESSION['rol'] == 2) {
        // Asegúrate de que $id_proyecto_usuario esté definido
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultCantOrdenesServicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit(); // Detener el script en caso de error en la consulta
}