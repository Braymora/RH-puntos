<?php
session_start();
// Incluye el archivo de conexión a la base de datos
require_once('../../config/conexion.php'); // Ajusta la ruta según tu estructura de archivos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroOrden = $_POST['numeroOrden'];
    $nombre = $_FILES['pdf-upload__anexo']['name'];
    $tmp_ruta = $_FILES['pdf-upload__anexo']['tmp_name'];
    $ubicacion = "../../files/anexos/" . $nombre;

    move_uploaded_file($tmp_ruta, $ubicacion);

    try {
        // Conecta a la base de datos utilizando PDO
        $pdo = new PDO("mysql:host=" . BD_HOST . ";dbname=" . BD_NAME, BD_USER, BD_PASSWORD);

        // Configura PDO para que lance excepciones en caso de errores
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Configura el juego de caracteres a UTF-8
        $pdo->exec("SET CHARACTER SET utf8");

        // Obtiene el ID de la orden a partir del número de orden
        $query = "SELECT id_servicio FROM ordenes_servicios WHERE numero_orden = :numeroOrden";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':numeroOrden', $numeroOrden);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $idServicio = $result['id_servicio'];

            // Verificar si ya existe un registro con el mismo id_orden
            $checkQuery = "SELECT id_orden FROM pdf_anexo_colaborador WHERE id_orden = :idServicio";
            $stmt = $pdo->prepare($checkQuery);
            $stmt->bindParam(':idServicio', $idServicio);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Ya existe un registro con el mismo id_orden
                $_SESSION['warning'] = "Este número de orden ya está relacionado a un documento.";
                header("Location: ../../views/colaboradores/listaColaboradores.php");
                exit; // Salir del script para evitar la inserción duplicada
            }

            // Inserta el PDF y el ID de la orden en la tabla pdf_colaborador
            $insertQuery = "INSERT INTO pdf_anexo_colaborador (id_orden, ruta) VALUES (:idServicio, :pdfFileName)";
            $stmt = $pdo->prepare($insertQuery);
            // Cambio aquí: Bind el nombre del archivo en lugar de la ubicación
            $stmt->bindParam(':idServicio', $idServicio);
            $stmt->bindParam(':pdfFileName', $nombre);
            $stmt->execute();

            //Actualiza el estado de la orden en la tabla servicio
            $updateQuery = "UPDATE ordenes_servicios SET id_estadoOrden_anexo = 1 WHERE id_servicio = :idServicio";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':idServicio', $idServicio);
            $stmt->execute();

            // Muestra un mensaje de éxito
            $_SESSION['success'] = "Se ha importado el PDF sin problema.";
            header("Location: ../../views/colaboradores/listaColaboradores.php"); // Redirige a la otra vista

        } else {
            $_SESSION['warning'] = "Número de orden no encontrado, por favor selecciona un número de orden.";
            header("Location: ../../views/colaboradores/listaColaboradores.php"); // Redirige a la otra vista
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    
}
?>
