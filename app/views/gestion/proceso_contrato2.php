<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Recibe los datos JSON enviados en la solicitud
    $jsonData = file_get_contents("php://input");

    // Decodifica los datos JSON en un arreglo asociativo
    $data = json_decode($jsonData, true);

    if (isset($data['cedula'])) {
        // Realiza la lógica necesaria aquí para procesar la 'cedula' recibida
        $cedula = $data['cedula'];
        $query = "SELECT nombre_colaborador, correo FROM colaboradores WHERE cedula = :cedula";
        $statement = $conexion->prepare($query);
        $statement->bindParam(':cedula', $cedula, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        

        // Por ejemplo, puedes devolver el resultado de la consulta como una respuesta JSON
        $response = array('name' => $result['nombre_colaborador'], 'correo' =>$result['correo']);
        echo json_encode($response);
        
    } else {
        // En caso de que no se haya recibido 'cedula' en los datos POST
        echo "No se proporcionó la cedula en la solicitud POST.";
    }
    
}
?>