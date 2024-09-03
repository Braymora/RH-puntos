<?php 
session_start();
$_SESSION['alerta']=' ';

include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){


    $idUser = $_POST['idUser'];
    $N_orden = $_POST['n_orden'];
    $N_contrato = $_POST['n_contrato'];
    $ceco = $_POST['ceco'];
    $n_anexo = $_POST['n_anexo'];
    $cantidad_puntos = $_POST['cantidad_puntos'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $correo_col = $_POST['correo'];
    $justificacion = $_POST['justificacion'];
    $observacion = $_POST['observacion'];

      
    try {
       
        $sql = "INSERT INTO ordenes_servicios (numero_orden,ceco,cantidad_puntos,fecha_inicio,fecha_fin,correo_col,justificacion, observaciones,numero_contrato, id_usuario, id_anexo) VALUES (:numero_orden,:ceco,:cantidad_puntos,:fecha_inicio,:fecha_fin,:correo_col,:justificacion, :observaciones,:numero_contrato, :idUser, :n_anexo)";

        // Prepara y ejecuta la consulta

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idUser', $idUser);
        $stmt->bindParam(':numero_orden', $N_orden);
        $stmt->bindParam(':numero_contrato', $N_contrato);
        $stmt->bindParam(':ceco', $ceco);
        $stmt->bindParam(':n_anexo', $n_anexo);
        $stmt->bindParam(':cantidad_puntos', $cantidad_puntos);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':correo_col', $correo_col);
        $stmt->bindParam(':observaciones', $observacion);
        $stmt->bindParam(':justificacion', $justificacion);

        if ($stmt->execute()) {
            $_SESSION['alerta'] = 'exito';
            $_SESSION['titulo-alerta'] = 'exito' ;
            $_SESSION['des-alerta'] = ' Todo esta ok ;) .';
            
            header("Location: ./servicios.php");
            exit();
        } else {
            $_SESSION['alerta'] = 'error';
            $_SESSION['titulo-alerta'] = 'ERROR' ;
            $_SESSION['des-alerta'] = 'Todo lo que podria salir mal , salio mal comuniquese con soporte.';
        }

    }catch (PDOException $e) {
        $_SESSION['alerta'] = 'error';
        $_SESSION['titulo-alerta'] = 'ERROR' ;
        $_SESSION['des-alerta'] = 'Error: ' . $e->getMessage();
        header("Location: ./servicios.php");
    }
    



}



?>