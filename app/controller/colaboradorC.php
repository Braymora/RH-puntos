<?php

session_start();

include '../config/conexion.php';
include '../models/colaboradorModel.php';

if (isset($_POST['cedula'], $_POST['name'], $_POST['email'], $_POST['direccion'], $_POST['fechaIngreso'], $_POST['observaciones'], $_POST['ceco'], $_POST['cargo'], $_POST['ciudad'], $_POST['proyecto'])) {
    if (!empty($_POST['cedula']) && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['direccion']) && !empty($_POST['fechaIngreso']) && !empty($_POST['observaciones']) && !empty($_POST['ceco']) && !empty($_POST['cargo']) && !empty($_POST['ciudad']) && !empty($_POST['proyecto'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['name'];
        $email = $_POST['email'];
        $contrato = $_POST['contrato'];
        $direccion = $_POST['direccion'];
        $fechaIngreso = $_POST['fechaIngreso'];
        $observaciones = $_POST['observaciones'];
        $ceco = $_POST['ceco'];
        $cargo = $_POST['cargo'];
        $ciudad = $_POST['ciudad'];
        $proyecto = $_POST['proyecto'];

        // echo 'cedula: ' . $cedula . "\n";
        // echo 'nombre: ' . $nombre . "\n";
        // echo 'email: ' . $email . "\n";
        // echo 'contrato: ' . $contrato . "\n";
        // echo 'direccion: ' . $direccion . "\n";
        // echo 'fechaIngreso: ' . $fechaIngreso . "\n";
        // echo 'observaciones:  ' . $observaciones . "\n";
        // echo 'ceco: ' . $ceco . "\n";
        // echo 'cargo:  ' . $cargo . "\n";
        // echo 'ciudad:  ' . $ciudad . "\n";
        // echo 'proyecto: ' . $proyecto . "\n";
        // exit();

        $colaboradorModel = new ColaboradorModel($conexion);

        // Verificar si el colaborador ya existe
        if ($colaboradorModel->colaboradorExiste($cedula)) {
            $response = array("exito" => false, "mensaje" => "El colaborador con esta cédula ya existe en la base de datos.");
            echo json_encode($response);
        } else {
            // Insertar el colaborador si no existe
            $exito = $colaboradorModel->insertarColaborador($cedula, $nombre, $email, $contrato, $direccion, $fechaIngreso, $observaciones, $ceco, $cargo, $ciudad, $proyecto);

            if ($exito) {
                $response = array("exito" => true);
                echo json_encode($response);
            } else {
                $response = array("exito" => false, "mensaje" => "Hubo un error al guardar los datos del colaborador.");
                echo json_encode($response);
            }
        }
    } else {
        $response = array("exito" => false, "mensaje" => "Uno o más campos están vacíos.");
        echo json_encode($response);
    }
} else {
    $response = array("exito" => false, "mensaje" => "Los datos no se enviaron correctamente.");
    echo json_encode($response);
}
