<?php
require_once '../../config/conexion.php';
session_start();

$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'] ?? null;

$query = "SELECT cl.id_colaborador, cl.cedula, cl.nombre_colaborador, cl.correo, cl.contratante, cl.direccion, cl.fecha_ingreso, cl.observaciones, cl.id_estado_colabora, stc.nombre_estado_col, cl.id_ceco, cl.nombre_cargo, cl.nombre_ciudad, pr.id_proyecto, cl.nombre_proyecto
FROM colaboradores cl
INNER JOIN estado_colaborador stc ON cl.id_estado_colabora = stc.id_estado_colabora
INNER JOIN proyectos pr ON cl.nombre_proyecto = pr.nombre_proyecto
WHERE cl.id_estado_colabora = 1";

if ($_SESSION['rol'] == 2) {
    $query .= " AND cl.id_ceco = :id_proyecto_usuario";
}

$query .= " ORDER BY cl.nombre_colaborador;";

$queryOrdenes = "SELECT `id_servicio`, `numero_orden`, `cantidad_puntos`, `fecha_inicio`, `fecha_fin`, `observaciones`, `id_estadoOrden`, `numero_contrato` FROM `ordenes_servicios` WHERE id_estadoOrden = 2 ORDER BY numero_orden";

$queryAnexos = "SELECT `id_servicio`, `numero_orden`, `cantidad_puntos`, `fecha_inicio`, `fecha_fin`, `observaciones`, `id_estadoOrden`, `numero_contrato` FROM `ordenes_servicios` WHERE id_estadoOrden_anexo = 2 ORDER BY numero_orden";

$queryCeco = "SELECT `id_ceco`, `nombre_ceco` FROM ceco";

$queryCargo = "SELECT `id_cargo`, `nombre_cargo` FROM cargos";

$queryContrato = "SELECT `id_tipo_contrato`, `nombre_tipo_contrato` FROM tipo_contrato";

$queryCiudad = "SELECT `id_ciudad`, `nombre_ciudad` FROM ciudades";

$queryProyecto = "SELECT `id_proyecto`, `nombre_proyecto` FROM proyectos";

$queryEstadoCol = "SELECT `id_estado_colabora`, `nombre_estado_col` FROM estado_colaborador";



try {
    $stmt = $conexion->prepare($query);

    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario);
    }

    $stmt->execute();

    $stmtOrdenes = $conexion->prepare($queryOrdenes);
    $stmtOrdenes->execute();

    $stmtAnexos = $conexion->prepare($queryAnexos);
    $stmtAnexos->execute();

    $stmtCeco = $conexion->prepare($queryCeco);
    $stmtCeco->execute();

    $stmtCargo = $conexion->prepare($queryCargo);
    $stmtCargo->execute();

    $stmtContrato = $conexion->prepare($queryContrato);
    $stmtContrato->execute();

    $stmtCiudades = $conexion->prepare($queryCiudad);
    $stmtCiudades->execute();

    $stmtProyecto = $conexion->prepare($queryProyecto);
    $stmtProyecto->execute();

    $stmtEstado = $conexion->prepare($queryEstadoCol);
    $stmtEstado->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resultOrdenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);
    $resultAnexos = $stmtAnexos->fetchAll(PDO::FETCH_ASSOC);
    $resultCeco = $stmtCeco->fetchAll(PDO::FETCH_ASSOC);
    $resultCargo = $stmtCargo->fetchAll(PDO::FETCH_ASSOC);
    $resultContrato = $stmtContrato->fetchAll(PDO::FETCH_ASSOC);
    $resultProyecto = $stmtProyecto->fetchAll(PDO::FETCH_ASSOC);
    $resultEstado = $stmtEstado->fetchAll(PDO::FETCH_ASSOC);
    $resultCiudad = $stmtCiudades->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit();
}

$conexion = null;
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Fonts-->
    <?php include '../font/font.php'; ?>

    <link rel="stylesheet" href="../../../style/general.css">
    <!-- ==========Datatable======= -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- ==select libreria= -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container {
            width: 80% !important;
            z-index: 99999 !important;
            /* Ajusta el valor según sea necesario para asegurarte de que el desplegable aparezca encima del modal */
        }

        .ui.selection.dropdown>.dropdown.icon {
            cursor: pointer;
            position: absolute;
            width: auto;
            height: auto;
            line-height: 1.21428571em;
            top: 28px;
            right: 1em;
            z-index: 3;
            margin: -.78571429em;
            padding: .91666667em;
            opacity: .8;
            transition: opacity .1s ease;
        }

        .ui.dropdown>.text {
            display: inline-block;
            transition: none;
            color: rgb(18, 51, 77);
        }


        /**modal eliminar ceco */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            align-items: center;
            background-color: #fefefe;
            border: 1px solid #888;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 15% auto;
            padding: 20px;
            width: 50%;
        }

        .modal-content h2 {
            font-size: 30px;
            padding: 10px;
        }

        .modal-content p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .modal-content .conbutton {
            align-items: center;
            display: flex;
            justify-content: center;
            width: 80%;
        }

        .conbutton button {
            border-radius: 4px;
            margin: 0px 12px;
            padding: 12px;
            width: 100px;
        }

        #deleteBtn {
            color: white;
            background-color: #ff0000;
            transition: all 0.8s;
        }

        #deleteBtn:hover {
            background-color: #737679;
        }

        #cancelBtn {
            color: white;
            background-color: #555555;
            transition: all 0.8s;
        }

        #cancelBtn:hover {
            color: white;
            background-color: rgb(18, 51, 77);
        }
    </style>


    <title>Lista|Colaboradores</title>

</head>

<body>

    <?php
    include '../nav/nav.php';
    ?>

    <main class="main">

        <?php
        include '../header/header.php';
        ?>

        <div class="main_table">
            <div class="cont-table">
                <div class="cont-table__options">
                    <div class="cont-table__optionsItems">

                    </div>
                    <div class="cont-table__optionsButtons">
                        <form method="POST" id="cargaExcel" enctype="multipart/form-data" class="buttons optionsButtonsExporte formImport">
                            <label for="excel_file" class="custom-file-upload">
                                <img src="../../../assets/icons/download.svg" alt="">
                                <span class="custom-file-upload__text">Seleccionar archivo</span>
                                <input type="file" name="excel_file" id="excel_file" accept=".xlsx" style="display: none;">
                            </label>
                            <input type="submit" name="excel" id="submit_button" value="Importar" class="Btnimpot">
                        </form>

                        <div class="loader">Cargando
                            <span></span>
                        </div>
                    </div>
                </div>

                <h2>Listado de colaboradores</h2>

                <div class="table-container">
                    <!-- <div class="cont-table__search">
                        <div class="inputSearch-container">
                            <input type="text" id="customSearch" name="text" class="inputSearch" placeholder="Search something...">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24" class="iconSearch">
                                <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <rect fill="white" height="24" width="24"></rect>
                                    <path fill="" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM9 11.5C9 10.1193 10.1193 9 11.5 9C12.8807 9 14 10.1193 14 11.5C14 12.8807 12.8807 14 11.5 14C10.1193 14 9 12.8807 9 11.5ZM11.5 7C9.01472 7 7 9.01472 7 11.5C7 13.9853 9.01472 16 11.5 16C12.3805 16 13.202 15.7471 13.8957 15.31L15.2929 16.7071C15.6834 17.0976 16.3166 17.0976 16.7071 16.7071C17.0976 16.3166 17.0976 15.6834 16.7071 15.2929L15.31 13.8957C15.7471 13.202 16 12.3805 16 11.5C16 9.01472 13.9853 7 11.5 7Z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </div>
                    </div> -->
                    <table class="responsive-table" id="table_colaborador">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>No. Documento</th>
                                <th>Nombre colaborador</th>
                                <th>Correo</th>
                                <th>Ceco</th>
                                <th>Cargo</th>
                                <th>Contratante</th>
                                <th>Ciudad</th>
                                <th>Dirección</th>
                                <th>Proyecto</th>
                                <th>Fecha ingreso</th>
                                <th>Estado</th>
                                <th>Observacione</th>
                                <th>Editar</th>
                                <th>Subir PDF Orden</th>
                                <th>Subir PDF Anexo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($result) > 0) {
                                foreach ($result as $data) {
                            ?>
                                    <tr>
                                        <td data-label="Id"><?php echo $data['id_colaborador']; ?></td>
                                        <td data-label="No. Documento"><?php echo $data['cedula']; ?></td>
                                        <td data-label="Nombre colaborador"><?php echo $data['nombre_colaborador']; ?></td>
                                        <td data-label="Correo"><?php echo $data['correo']; ?></td>
                                        <td data-label="Ceco"><?php echo $data['id_ceco']; ?></td>
                                        <td data-label="Cargo"><?php echo $data['nombre_cargo']; ?></td>
                                        <td data-label="Tipo de contrato"><?php echo $data['contratante']; ?></td>
                                        <td data-label="Ciudad"><?php echo $data['nombre_ciudad']; ?></td>
                                        <td data-label="Dirección"><?php echo $data['direccion']; ?></td>
                                        <td data-label="Proyecto"><?php echo $data['nombre_proyecto']; ?></td>
                                        <td data-label="Fecha ingreso"><?php echo $data['fecha_ingreso']; ?></td>

                                        <td data-label="Estado">
                                            <span class="<?php echo ($data['nombre_estado_col'] == 'Activo') ? 'activo' : 'inactivo'; ?>">
                                                <?php echo $data['nombre_estado_col'] ?>
                                            </span>
                                        </td>
                                        <td data-label="Observacione"><?php echo $data['observaciones']; ?></td>
                                        <td data-label="Editar">
                                            <button class="buttonEdit typeEditar" onclick="showModalEdit()" id="modalEdit"></button>
                                        </td>
                                        <td data-label="Subir PDF Orden">
                                            <button class="buttonPdf typePdf" onclick="showModalPdf()" id="modalPdf"></button>
                                        </td>
                                        <td data-label="Subir PDF Anexo">
                                            <button class="buttonPdf typePdf" onclick="showModalPdfAnexo()" id="modalPdfAnexo"></button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- =======Modal Edit===== -->
                    <div id="myModalEdit" class="modalEdit">
                        <div class="modal-contentEdit">
                            <span class="close-btnEdit" onclick="closeModalEdit()">&times;</span>
                            <div class="contEdit">
                                <div class="contEdit_info">
                                    <img src="../../../assets/icons/edit-3.svg" alt="">
                                    <p>Lista de colaboradores</p>
                                </div>
                                <form class="contentInfo-inputs" id="idColaborador">
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
                                        <select name="cecoCol" id="id_ceco" class="cecoCol select2">
                                            <option value="">Ceco</option>
                                            <?php
                                            foreach ($resultCeco as $ceco) {
                                                $id = $ceco['id_ceco'];
                                                $nombre = $ceco['nombre_ceco'];
                                                echo "<option value=\"$id\">$id - $nombre</option>";
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <div class="inputGroup">
                                        <select name="cargoCol" id="nombre_cargo" class="cargoCol select2">
                                            <option value="">Cargo</option>
                                            <?php
                                            foreach ($resultCargo as $cargo) {
                                                $id_cargo = $cargo['id_cargo'];
                                                $nombre_cargo = $cargo['nombre_cargo'];
                                                echo "<option value=\"$nombre_cargo\">$nombre_cargo</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="inputGroup">
                                        <div class="inputGroup">
                                            <input type="text" id="contratante" required="" autocomplete="off" class="contratante">
                                            <label for="tipeContrat">Contratante</label>
                                        </div>
                                    </div>
                                    <div class="inputGroup">
                                        <select name="ciudad" id="nombre_ciudad" class="ciudad select2">
                                            <option value="">Selecione Ciudad</option>
                                            <?php
                                            foreach ($resultCiudad as $ciudades) {
                                                $id_ciudad = $ciudades['id_ciudad'];
                                                $nombre_ciudad = $ciudades['nombre_ciudad'];
                                                echo "<option value=\"$nombre_ciudad\">$nombre_ciudad</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="inputGroup">
                                        <input type="text" id="direccion" required="" autocomplete="off" class="direccionCol">
                                        <label for="tipeContrat">Dirección</label>
                                    </div>
                                    <div class="inputGroup">
                                        <select name="proyecto" id="nombre_proyecto" class="id_proyecto select2">
                                            <option value="">seleccione Proyecto</option>
                                            <?php
                                            foreach ($resultProyecto as $proyecto) {
                                                $id_proyecto = $proyecto['id_proyecto'];
                                                $nombre_proyecto = $proyecto['nombre_proyecto'];
                                                echo "<option value=\"$nombre_proyecto\">$nombre_proyecto</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="inputGroup">
                                        <p>Fecha de ingreso</p>
                                        <input type="date" id="fecha_ingreso" required="" autocomplete="off">
                                    </div>
                                    <div class="inputGroup">
                                        <select name="estado" id="estado" class="id_proyecto select2">
                                            <option value="">seleccione estado</option>
                                            <?php
                                            foreach ($resultEstado as $estado) {
                                                $id_estado = $estado['id_estado_colabora'];
                                                $nombre_estado = $estado['nombre_estado_col'];
                                                echo "<option value=\"$id_estado\">$nombre_estado</option>";
                                            }
                                            ?>
                                        </select>
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
                    <!-- =======Modal import pdf===== -->
                    <div id="myModalPdf" class="modalPdf">
                        <div class="modal-contentPdf">
                            <span class="close-btnPdf" onclick="closeModalPdf()">&times;</span>
                            <div class="contPdf">
                                <div class="contPdf_info">
                                    <img src="../../../assets/icons/edit-3.svg" alt="">
                                    <p>Seleccione el número de orden</p>
                                </div>
                                <form class="contentPdf-inputs" id="pdf-upload-form" enctype="multipart/form-data" method="post" action="../../includes//colaboradores/importarPdf.php">
                                    <div class="inputGroup">
                                        <select name="numeroOrden" class="numeroOrden">
                                            <option>Seleccione número de orden</option>
                                            <?php
                                            if (count($resultOrdenes) > 0) {
                                                foreach ($resultOrdenes as $dataOrdenes) {
                                            ?>
                                                    <option value="<?php echo $dataOrdenes['numero_orden']; ?>"><?php echo $dataOrdenes['numero_orden']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="file-upload">
                                        <label for="pdf-upload" class="file-label">
                                            <i class="fas fa-cloud-upload-alt"></i> Seleccionar archivo PDF
                                        </label>
                                        <input type="file" name="pdf-upload" id="pdf-upload" accept=".pdf" class="file-input">
                                    </div>
                                    <div id="pdf-preview" class="pdf-preview">
                                        <!-- Vista previa del PDF seleccionado -->
                                    </div>
                                    <button class="BtnCon" type="submit" value="subir">
                                        <span class="IconContainer">
                                            <img src="../../../assets/icons/save.svg" alt="iconSearch">
                                        </span>
                                        <p class="text">Guardar</p>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- =======Modal import pdf anexo===== -->
                    <div id="myModalPdf_anexo" class="modalPdfAnexo">
                        <div class="modal-contentPdf__Anexo">
                            <span class="close-btnPdf__anexo" onclick="closeModalPdf()">&times;</span>
                            <div class="contPdf__anexo">
                                <div class="contPdf_info__anexo">
                                    <img src="../../../assets/icons/edit-3.svg" alt="">
                                    <p>Seleccione el número de orden</p>
                                </div>
                                <form class="contentPdf-inputs__anexo" id="pdf-upload-form__anexo" enctype="multipart/form-data" method="post" action="../../includes/anexos/importarPdfAnexos.php">
                                    <div class="inputGroup">
                                        <select name="numeroOrden" class="numeroOrden">
                                            <option>Seleccione número de orden</option>
                                            <?php
                                            if (count($resultAnexos) > 0) {
                                                foreach ($resultAnexos as $dataAnexos) {
                                            ?>
                                                    <option value="<?php echo $dataAnexos['numero_orden']; ?>"><?php echo $dataAnexos['numero_orden']; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="file-upload">
                                        <label for="pdf-upload__anexo" class="file-label">
                                            <i class="fas fa-cloud-upload-alt"></i> Seleccionar archivo PDF
                                        </label>
                                        <input type="file" name="pdf-upload__anexo" id="pdf-upload__anexo" accept=".pdf" class="file-input">
                                    </div>
                                    <div id="pdf-preview__anexo" class="pdf-preview__anexo">
                                        <!-- Vista previa del PDF seleccionado -->
                                    </div>
                                    <button class="BtnCon" type="submit" value="subir">
                                        <span class="IconContainer">
                                            <img src="../../../assets/icons/save.svg" alt="iconSearch">
                                        </span>
                                        <p class="text">Guardar</p>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- =========Alerta============ -->
    <div class="contenedor-toast" id="contenedor-toast">

        <!-- ==========Importe PDF======== -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="toast exito" id="1">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Exito!</p>
                        <p class="descripcion"><?php echo $_SESSION['success']; ?></p>
                    </div>
                </div>
                <button class="btn-cerrar">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="toast error" id="2" style="display: none;">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Error!</p>
                        <p class="descripcion"><?php echo $_SESSION['error']; ?></p>
                    </div>
                </div>
                <button class="btn-cerrar">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])) : ?>
            <div class="toast warning" id="4">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Advertencia</p>
                        <p class="descripcion"><?php echo $_SESSION['warning']; ?></p>
                    </div>
                </div>
                <button class="btn-cerrar">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>
    </div>



    <!-- ===============
    Alert
=============== -->

    <div class="contenedor-toast" id="contenedor-toast">
        <div class="toast exito" id="1" style="display: none;">
            <div class="contenido">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
                    </svg>
                </div>
                <div class="texto">
                    <p class="titulo">Exito!</p>
                    <p class="descripcion" id="descripcion"></p>
                </div>
            </div>
            <button class="btn-cerrar">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </div>
            </button>
        </div>
        <div class="toast success" id="2" style="display: none;">
            <div class="contenido">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                </div>
                <div class="texto">
                    <p class="titulo">Error!</p>
                    <p class="descripcion"></p>
                </div>
            </div>
            <button class="btn-cerrar">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </div>
            </button>
        </div>
        <div class="toast info" id="3" style="display: none;">
            <div class="contenido">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg>
                </div>
                <div class="texto">
                    <p class="titulo">Info</p>
                    <p class="descripcion"></p>
                </div>
            </div>
            <button class="btn-cerrar">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </div>
            </button>
        </div>
        <div class="toast warning" id="4" style="display: none;">
            <div class="contenido">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                </div>
                <div class="texto">
                    <p class="titulo">Advertencia</p>
                    <p class="descripcion"></p>
                </div>
            </div>
            <button class="btn-cerrar">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </div>
            </button>
        </div>
    </div>


    <!-- ========Lista de colaboradores========== -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="../../../script/colaborador/importPdf.js"></script>
    <script src="../../../script/colaborador/importPdf-anexos.js"></script>
    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/colaborador/colaborador.js"></script>

    <script src="../../../script/colaborador/listaColaboradores.js"></script>
    <script src="../../../script/colaborador/importColaboradores.js"></script>

    <script src="../../../script/colaborador/actualizarColaborador.js"></script>



    <!-- ==================Modals============== -->
    <script src="../../../script/colaborador/modals.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- =========DataTABLE======= -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>


    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2 en el elemento con la clase "select2"
            $(".select2").select2();
        });
    </script>


</body>

</html>