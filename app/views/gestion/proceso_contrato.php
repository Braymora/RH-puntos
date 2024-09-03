<?php
//id_contrato	numero_contrato	nombre_colaborador	id_tipo_contrato	id_colaborador 
session_start();
$_SESSION['alerta'] = ' ';
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $N_contrato = $_POST['contrato'];
    $N_colaborador = $_POST['identificacion'];
    $correo = $_POST['correo'];
    $tipo_contrato = $_POST['tipo_comtrato'];
    $colaborador = $_POST['nombre'];

    try {
        $sql = "SELECT * FROM colaboradores WHERE cedula = :N_colaborador";
        $stmtSelect = $conexion->prepare($sql);
        $stmtSelect->bindParam(':N_colaborador', $N_colaborador);
        $stmtSelect->execute();
        $dato = $stmtSelect->fetch();

        // numero_contrato	nombre_colaborador	id_tipo_contrato	id_colaborador
        $sqlInsert = "INSERT INTO contratos (numero_contrato, nombre_colaborador, id_tipo_contrato, id_colaborador, correo_col) VALUES (:numero_contrato, :nombre_colaborador, :id_tipo_contrato, :id_colaborador, :correo)";


        // Prepara y ejecuta la consulta de inserción
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->bindParam(':numero_contrato', $N_contrato);
        $stmtInsert->bindParam(':nombre_colaborador', $colaborador);
        $stmtInsert->bindParam(':id_tipo_contrato', $tipo_contrato);
        $stmtInsert->bindParam(':id_colaborador', $dato["id_colaborador"]);
        $stmtInsert->bindParam(':correo', $correo);

        if ($stmtInsert->execute()) {
            $_SESSION['alerta'] = 'exito';
            $_SESSION['titulo-alerta'] = 'exito';
            $_SESSION['des-alerta'] = ' Todo esta ok ;) .';
            header("Location: ./contrato.php");
            exit();
        } else {
            $_SESSION['alerta'] = 'error';
            $_SESSION['titulo-alerta'] = 'Error';
            $_SESSION['des-alerta'] = 'Todo lo que podria salir mal , salio mal comuniquese con soporte.';
        }
    } catch (Exception $e) {
        $_SESSION['alerta'] = 'error';
        $_SESSION['titulo-alerta'] = 'Error';
        $_SESSION['des-alerta'] = 'Todo lo que podria salir mal , salio mal comuniquese con soporte.';
        header("Location: ./contrato.php");
    }
}
