<?php
session_start();
// Incluye el archivo de conexión a la base de datos
require '../../config/conexion.php';

// Recupera los datos enviados por AJAX
$numeroOrden = $_POST['numeroOrden'];
$puntos = $_POST['puntos'];
$cedulaColaborador = $_POST['cedulaColaborador'];
$nombreColaborador = $_POST['nombreColaborador'];
$fechaPuntos = date('Y-m-d', strtotime($_POST['fechaPuntos']));
$frecuencia = $_POST['frecuencia'];


try {
    // Consulta para obtener el estado de la orden en ordenes_servicios
    $query = "SELECT id_estadoOrden FROM ordenes_servicios WHERE numero_orden = :numeroOrden";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $estadoOrden = $row['id_estadoOrden'];

    // Comprueba si la orden está sin firmar
    if ($estadoOrden == 2) {
        $response = array("error" => false, "mensaje" => "La orden está sin firmar, no se pueden asignar puntos");
        echo json_encode($response);
    } else {
        // Consulta para obtener la cantidad de puntos en ordenes_servicios
        $query = "SELECT cantidad_puntos FROM ordenes_servicios WHERE numero_orden = :numeroOrden";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $cantidadPuntosOrden = $row['cantidad_puntos'];

        // Consulta para obtener la suma de puntos_asignados en cantidad_puntos
        $query = "SELECT SUM(puntos_asignados) as totalPuntos FROM cantidad_puntos WHERE numero_orden = :numeroOrden";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalPuntosAsignadosCantidadPuntos = $row['totalPuntos'];

        // Consulta para obtener la suma de puntos_asignados en temporal_servicios_puntos
        $query = "SELECT SUM(puntos_asignados) as totalPuntos FROM temporal_servicios_puntos WHERE numero_orden = :numeroOrden";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalPuntosAsignadosTemporalServiciosPuntos = $row['totalPuntos'];

        // Comprueba si los puntos que se van a insertar superan la cantidad de puntos en ordenes_servicios
        if ($totalPuntosAsignadosCantidadPuntos + $totalPuntosAsignadosTemporalServiciosPuntos + $puntos > $cantidadPuntosOrden) {
            $response = array("error" => true, "mensaje" => "La cantidad de puntos que se está intentando asignar supera la cantidad disponible en la orden");
            echo json_encode($response);
        } else {
            // Si los puntos son válidos, inserta los datos en la tabla correspondiente
            $query = "INSERT INTO temporal_servicios_puntos (cedula, nombre_colaborador, puntos_asignados, fecha_puntos, numero_orden, id_frecuencia) 
                      VALUES (:cedulaColaborador, :nombreColaborador, :puntos, :fechaPuntos, :numeroOrden, :frecuencia)";
            
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':numeroOrden', $numeroOrden, PDO::PARAM_STR);
            $stmt->bindParam(':puntos', $puntos, PDO::PARAM_INT);
            $stmt->bindParam(':cedulaColaborador', $cedulaColaborador, PDO::PARAM_STR);
            $stmt->bindParam(':nombreColaborador', $nombreColaborador, PDO::PARAM_STR);
            $stmt->bindParam(':fechaPuntos', $fechaPuntos, PDO::PARAM_STR);
            $stmt->bindParam(':frecuencia', $frecuencia, PDO::PARAM_STR);

            if($stmt->execute()){
                $response = array("exito" => true, "mensaje" => "¡Puntos guardados con éxito!");
                echo json_encode($response);

            } else {
                $response = array("error" => true, "mensaje" => "¡Error al guardar los puntos!");
                echo json_encode($response);
            }
        }
    }
} catch (PDOException $e) {
    $response = array("error" => true, "mensaje" => "Error: " . $e->getMessage());
    echo json_encode($response);
}
