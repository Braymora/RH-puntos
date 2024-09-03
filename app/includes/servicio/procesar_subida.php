<?php

include '../../config/conexion.php';

if (isset($_POST['submit'])) {
    $uploadsDirectory = '../../files/plantillaOrden/'; // Directorio de almacenamiento de archivos

    $wordFile = $_FILES['wordFile'];
    $wordFileName = $uploadsDirectory . basename($wordFile['name']);

    // Mover el archivo al directorio de almacenamiento
    move_uploaded_file($wordFile['tmp_name'], $wordFileName);

    // Guardar la ruta del archivo en la base de datos (ajusta según tu esquema)
    $stmt = $conexion->prepare('INSERT INTO archivos (ruta) VALUES (?)');
    $stmt->execute([$wordFileName]);
}
