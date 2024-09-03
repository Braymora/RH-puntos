<?php

require_once("../../config/conexion.php"); // Reemplaza "conexion.php" con la ubicación correcta de tu archivo de conexión

try {
    $query_cargo = $conexion->prepare("SELECT  id_cargo, nombre_cargo FROM cargos");
    $query_cargo->execute();
    $result_cargo = $query_cargo->fetchAll(PDO::FETCH_ASSOC);
    $query_cargo->closeCursor(); // Añade esta línea
} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit();
}

?>

<!-- =======Modal Edit===== -->
<div id="myModalEdit" class="modalEdit">
    <div class="modal-contentEdit">
        <span class="close-btnEdit" onclick="closeModalEdit()">&times;</span>
        <div class="contEdit">
            <div class="contEdit_info">
                <img src="../../../assets/icons/edit-3.svg" alt="">
                <p>Lista de colaboradores</p>
            </div>
            <form class="contentInfo-inputs">
                <input type="hidden" name="id" id="id_colaborador">
                <div class="inputGroup">
                    <input type="number" id="cedula" required="" autocomplete="off" class="cedulaCol">
                    <label for="tipeContrat">Numero de documento</label>
                </div>
                <div class="inputGroup">
                    <input type="text" id="nombre_colaborador" required="" autocomplete="off" class="nameCol">
                    <label for="tipeContrat">Nombre colaborador</label>
                </div>
                <div class="inputGroup">
                    <input type="email" id="correo" required="" autocomplete="off" class="emailCol">
                    <label for="tipeContrat">Correo electrónico</label>
                </div>

                <div class="inputGroup">
                    <select name="cecoCol" id="id_ceco" class="cecoCol">
                        <option>Ceco</option>

                    </select>
                </div>
                <div class="inputGroup">
                    <select name="cargoCol" id="id_cargo" class="cargoCol">
                        <option>Cargo</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <select name="contrato" id="contratante" class="contrato">
                        <option>Tipo de contrato</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                        <option value="">contrato 1</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <select name="ciudad" id="id_ciudad" class="ciudad">
                        <option>Ciudad</option>
                        <option value="">Ciudad</option>
                        <option value="">Ciudad</option>
                        <option value="">Ciudad</option>
                        <option value="">Ciudad</option>
                        <option value="">Ciudad</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <input type="text" id="direccion" required="" autocomplete="off" class="direccionCol">
                    <label for="tipeContrat">Dirección</label>
                </div>
                <div class="inputGroup">
                    <select name="" id="" class="id_proyecto">
                        <option>Proyecto</option>
                        <option value="">Proyecto 1</option>
                        <option value="">Proyecto 1</option>
                        <option value="">Proyecto 1</option>
                        <option value="">Proyecto 1</option>
                        <option value="">Proyecto 1</option>
                    </select>
                </div>
                <div class="inputGroup">
                    <p>Fecha de ingreso</p>
                    <input type="date" id="fecha_ingreso" required="" autocomplete="off">
                </div>
                <div class="inputGroup">
                    <p>Estado</p>
                    <input type="text" id="Estado" required="" autocomplete="off">
                </div>
                <div class="inputGroup">
                    <textarea name="" id="Observacione" cols="30" rows="10"></textarea>
                </div>

                <button class="BtnCon" type="submit">
                    <span class="IconContainer">
                        <img src="../../../assets/icons/save.svg" alt="iconSearch">
                    </span>
                    <p class="text">Guardar cambios</p>
                </button>
            </form>
        </div>
    </div>
</div>