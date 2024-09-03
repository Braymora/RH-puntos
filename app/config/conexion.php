<?php
define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASSWORD', '');
define('BD_NAME', 'puntos');

try {
    $conexion = new PDO("mysql:host=".BD_HOST.";dbname=".BD_NAME, BD_USER, BD_PASSWORD);

    // Configurar PDO para que lance excepciones en caso de errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configurar el juego de caracteres a UTF-8
    $conexion->exec("SET CHARACTER SET utf8");
    

} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit(); // Detener el script en caso de error de conexión
}

return $conexion;

?>
