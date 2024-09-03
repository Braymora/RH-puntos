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

$sql_query = "SELECT * FROM ordenes_servicios";
$filas = $conexion->query($sql_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../style/general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop a través de tus datos de la base de datos y muestra cada fila
        foreach ($filas as $fila) {
            echo "<tr>";
            echo "<td class='nombre'>" . $fila['numero_contrato'] . "</td>";
            echo "<td class='edad'>" . $fila['cantidad_puntos'] . "</td>";
            echo "<td><button class='mostrar-info' data-id='{$fila['id_servicio']}'>Editar</button></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<!-- Modal -->
<div id="myModal" class="modalEdit">
    <div class="modal-contentEdit">
        <span class="close-btnEdit" id="closeModal">&times;</span>
        <div class="contEdit" >
            <div class="contEdit_info">
                <img src="../../../assets/icons/edit-3.svg" alt="">
                <p>Editar Servicio</p>
            </div>
            <form method="POST" class="contEdit_items">

                <div class="inputGroup">
                    <input type="text" id="nombreInput" name="nombre" required="" autocomplete="off" >
                    <label for="tipeContrat">nombre</label>
                </div>

                <div class="inputGroup">
                    <input type="text" id="edadInput" name="edad" required="" autocomplete="off" >
                    <label for="tipeContrat">nombre</label>
                </div>
                
                
                <input type="hidden" id="idInput" name="id">
                <button class="BtnConEdit" type="submit">
                    <span class="IconContainer_Edit">
                    <img src="../../../assets/icons/save.svg" alt="iconSearch_Edit">
                    </span>
                    <p class="text" id="guardarCambios" >Guardar Cambios</p>
                </button>
            </form>
        </div>
        
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    var botones = document.querySelectorAll(".mostrar-info");
    var modal = document.getElementById("myModal");
    var closeModal = document.getElementById("closeModal");

    // Ocultar el modal al principio
    modal.style.display = "none";

    botones.forEach(function(boton) {
        boton.addEventListener("click", function() {
            var fila = this.closest("tr"); // Encuentra la fila actual
            var nombre = fila.querySelector(".nombre").textContent;
            var edad = fila.querySelector(".edad").textContent;
            var id = this.getAttribute("data-id");

            // Llena los campos de entrada en el modal con los valores actuales
            document.getElementById("nombreInput").value = nombre;
            document.getElementById("edadInput").value = edad;
            document.getElementById("idInput").value = id;

            modal.style.display = "block";
        });
    });

    closeModal.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});
</script>


</body>
</html>