<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        // Incluye el archivo de conexión a la base de datos
        require_once('../../config/conexion.php');

        $numero_anexo = $_POST['numero_anexo'];
        
        $query = "DELETE FROM anexo WHERE numero_anexo  = :numero_anexo";

        // Prepara la declaración
        $stmt = $conexion->prepare($query);

        // Vincula los parámetros
        $stmt->bindParam(':numero_anexo', $numero_anexo, PDO::PARAM_INT);

        // Ejecuta la declaración
        if ($stmt->execute()) {
            // La actualización se realizó con éxito, puedes enviar una respuesta si es necesario
            echo json_encode(['exito' => true, 'mensaje' => 'Registro eliminado con éxito.']);
        } else {
            echo json_encode(['error' => true, 'mensaje' => 'Hubo un error al eliminar el registro.']);
        }

    } catch (\Throwable $th) {
        // Captura cualquier excepción y devuelve un mensaje de error en formato JSON
        echo json_encode(['error' => true, 'mensaje' => $th->getMessage()]);
    }
}
