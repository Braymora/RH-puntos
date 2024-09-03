<?php
require '../../config/conexion.php'; // Incluye el archivo que contiene la conexión PDO

$id_cedula = $_POST['id_cedula']; // Recupera la cédula enviada por AJAX


try {
    $query = "SELECT 
    os.numero_orden AS 'Número de Orden',
    DATEDIFF(CURDATE(), os.fecha_inicio) AS 'Días de la Orden de Servicio',
    eto.nombre_estado AS 'Nombre estado orden',
    cl.cedula AS 'Cedula colaborador',
    cl.nombre_colaborador AS 'Nombre colaborador',
    os.cantidad_puntos AS 'Puntos Asignados orden',
    os.cantidad_puntos - COALESCE(SUM(cp.puntos_asignados), 0) AS puntos_restantes,
    os.fecha_inicio AS 'Fecha de Inicio',
    os.fecha_fin AS 'Fecha Fin',
    os.numero_contrato AS 'contrato',
    os.id_estadoOrden AS 'Estado de la Orden'
FROM 
    ordenes_servicios os
JOIN contratos ct ON os.numero_contrato = ct.numero_contrato
JOIN estado_ordenes eto ON os.id_estadoOrden = eto.id_estadoOrden
JOIN colaboradores cl ON cl.id_colaborador = ct.id_colaborador
LEFT JOIN cantidad_puntos cp ON os.numero_orden = cp.numero_orden
WHERE os.numero_contrato = :cedula
ORDER BY os.numero_orden;";

    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':cedula', $id_cedula, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['Número de Orden'] . '</td>';
            echo '<td>' . $row['Días de la Orden de Servicio'] . '</td>';

            echo '<style>
                    .estadoSinFirmar {
                        color: white;
                        background-color: red;
                        padding: 5px;
                        border-radius: 5px;
                    }
                    .estadoFirmado {
                        color: white;
                        background-color: green;
                        padding: 5px;
                        border-radius: 5px;
                    }
                </style>';

            if ($row['Nombre estado orden'] == 'Sin firmar') {
                echo '<td><span class="estadoSinFirmar">' . $row['Nombre estado orden'] . '</span></td>';
            } else if ($row['Nombre estado orden'] == 'Firmado') {
                echo '<td><span class="estadoFirmado">' . $row['Nombre estado orden'] . '</span></td>';
            }


            echo '<td>' . $row['Puntos Asignados orden'] . '</td>';
            echo '<td>' . $row['puntos_restantes'] . '</td>';
            echo '<td>' . $row['Fecha de Inicio'] . '</td>';
            echo '<td>' . $row['Fecha Fin'] . '</td>';
            // Aquí agregas las nuevas opciones
            // Dentro del bucle while para imprimir resultados
            echo '<td data-label="Ingresar puntos">
                    <button class="buttonInsert typeInsert" data-orden="' . $row['Número de Orden'] . '" data-cedula="' . $row['Cedula colaborador'] . '" data-nombre="' . $row['Nombre colaborador'] . '" onclick="showModalInsertPoints(this)"></button>
                    </td>';


            // echo '<td data-label="" class="buttonsOptions">
            // <button class="buttonEdit typeEdit" data-orden="' . $row['Número de Orden'] . '" data-nombre="' . $row['Nombre colaborador'] . '" data-puntosAsigandos="'. $row['Puntos Asignados orden'] .'" onclick="showModalEditOrder()"></button>
            // </td>';

            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No se encontraron datos para esta cédula.</td></tr>';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
