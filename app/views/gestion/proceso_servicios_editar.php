<?php 
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la solicitud de edición
    if (isset($_POST['id'])) {
        $nombreEditado = $_POST['nombre'];
        $edadEditada = $_POST['edad'];
        $id = $_POST['id'];

        // Realizar la actualización en la base de datos utilizando una consulta UPDATE
        $sql_update = "UPDATE ordenes_servicios SET numero_contrato = '$nombreEditado', cantidad_puntos = '$edadEditada' WHERE id_servicio = $id";

        if ($conexion->query($sql_update) === TRUE) {
            // Éxito en la actualización
            echo 'todo esta bien ';
        } else {
            // Manejar error en la actualización
        }
    }
}

?>