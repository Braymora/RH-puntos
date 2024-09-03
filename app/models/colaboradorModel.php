<?php
class ColaboradorModel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function insertarColaborador($cedula, $nombre, $email, $contrato, $direccion, $fechaIngreso, $observaciones, $ceco, $cargo, $ciudad, $proyecto) {
        $stmt = $this->conexion->prepare("INSERT INTO colaboradores (cedula, nombre_colaborador, correo, contratante, direccion, fecha_ingreso, observaciones, id_ceco, nombre_cargo, nombre_ciudad, nombre_proyecto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cedula, $nombre, $email, $contrato, $direccion, $fechaIngreso, $observaciones, $ceco, $cargo, $ciudad, $proyecto]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function colaboradorExiste($cedula) {
        $stmt = $this->conexion->prepare("SELECT cedula FROM colaboradores WHERE cedula = ?");
        $stmt->execute([$cedula]);
        return $stmt->rowCount() > 0;
    }
}
