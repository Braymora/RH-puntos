<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        // Obtiene los datos del formulario
        $numAnexo = $_POST['numAnexo'];
        $idcolaborador = $_POST['idcolaborador'];
        $cedula = $_POST['cedula'];
        $nombreColaborador = $_POST['nombreColaborador'];
        $namececo = $_POST['namececo'];
        $actividades = $_POST['actividades'];
        $puntosPorMes = $_POST['puntosPorMes'];
        $observaciones = $_POST['observaciones'];

        // Prepara y ejecuta una consulta SQL para insertar los datos en la tabla
        $sql = "INSERT INTO anexo (numero_anexo, idcolaborador, cedula_colabo, nombre_colaborador, ceco, actividades, puntos, observaciones) VALUES (:numAnexo, :idcolaborador, :cedula, :nombreColaborador, :namececo, :actividad, :puntosPorMes, :observaciones)";
        $stmt = $conexion->prepare($sql);

        // Vincula los valores que no cambian fuera del bucle
        $stmt->bindParam(':numAnexo', $numAnexo);
        $stmt->bindParam(':idcolaborador', $idcolaborador);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':nombreColaborador', $nombreColaborador);
        $stmt->bindParam(':namececo', $namececo);
        $stmt->bindParam(':observaciones', $observaciones);



        // Itera sobre los arrays de actividades y puntos
        for ($i = 0; $i < count($actividades); $i++) {
            // Nuevas variables para cada iteración
            $actividadActual = $actividades[$i];
            $puntosPorMesActual = $puntosPorMes[$i];

            // Asigna los valores de los parámetros que cambian en cada iteración
            $stmt->bindParam(':actividad', $actividadActual);
            $stmt->bindParam(':puntosPorMes', $puntosPorMesActual);

            // Ejecuta la consulta para cada par de actividades y puntos
            if (!$stmt->execute()) {
                echo json_encode(['error' => true, 'mensaje' => 'Error al guardar los datos']);
                exit(); // Sale del script en caso de error
            }
        }

        echo json_encode(['exito' => true, 'mensaje' => '¡Datos guardados correctamente!']);
    } catch (\Throwable $th) {
        // Captura cualquier excepción y devuelve un mensaje de error en formato JSON
        echo json_encode(['error' => true, 'mensaje' => $th->getMessage()]);
    } finally {
        // Cierra la conexión después de usarla
        $conexion = null;
    }

    exit(); // Asegura que el script se detenga después de enviar la respuesta JSON
}