<?php
session_start();


include '../../includes/dashboard/consulta_principal.php';
include '../../includes/dashboard/consultaTablaTemporal.php';

include '../../includes/servicio/alertas_serviciosfechaFin.php';


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
    <!-- =======Botoenes datateble========== -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <!-- ==========filtro fecha datatable============ -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">


    <title>Lista|Colaboradores</title>

    <style>
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

</head>

<body>

    <?php
    include '../nav/nav.php';
    ?>

    <main class="main">

        <?php
        include '../header/header.php';
        ?>
        <div class="main-dates">
            <a href="../colaboradores/listaColaboradores.php" class="main-dateItems">
                <span class="main_dateItems__info">Colaboradores</span>
                <div class="main-dateItems__content">
                    <?php
                    // Verifica si hay resultados antes de intentar acceder a ellos
                    if (!empty($resultCantColaborador)) {
                        $cantidadColaboradores = $resultCantColaborador[0]['count(*)'];
                    ?>
                        <p class="main_dateItems__date"><?php echo $cantidadColaboradores; ?></p>
                    <?php
                    } else {
                    ?>
                        <p class="main_dateItems__date">Sin resultados</p>
                    <?php
                    }
                    ?>
                    <img src="../../../assets/icons/user.svg" alt="" class="main_dateItems__icons">
                </div>
            </a>


            <a href="../gestion/contrato.php" class="main-dateItems">
                <span class="main_dateItems__info">Contratos</span>
                <div class="main-dateItems__content">
                    <?php
                    // Verifica si hay resultados antes de intentar acceder a ellos
                    if (!empty($resultCantContratos)) {
                        $cantidadContratos = $resultCantContratos[0]['total_registros']; // Corregido
                    ?>
                        <p class="main_dateItems__date"><?php echo $cantidadContratos; ?></p>
                    <?php
                    } else {
                    ?>
                        <p class="main_dateItems__date">Sin resultados</p>
                    <?php
                    }
                    ?>
                    <img src="../../../assets/icons/file-text.svg" alt="" class="main_dateItems__icons">
                </div>
            </a>
            <a href="../gestion/servicios.php" class="main-dateItems">
                <span class="main_dateItems__info">Ordenes</span>
                <div class="main-dateItems__content">
                    <?php
                    // Verifica si hay resultados antes de intentar acceder a ellos
                    if (!empty($resultCantOrdenesServicios)) {
                        $cantidadOrdenesServicios = $resultCantOrdenesServicios[0]['total_registrosOrdenes'];
                    ?>
                        <p class="main_dateItems__date"><?php echo $cantidadOrdenesServicios; ?></p>
                    <?php
                    } else {
                    ?>
                        <p class="main_dateItems__date">Sin resultados</p>
                    <?php
                    }
                    ?>
                    <div class="contentCheck">
                        <img src="../../../assets/icons/file-text.svg" alt="" class="main_dateItems__icons file">
                        <img src="../../../assets/icons/check.svg" alt="" class="main_dateItems__icons check">
                    </div>
                </div>
            </a>
        </div>

        <div class="main_table">
            <div class="cont-table">
                <div class="cont-table__options">
                    <div class="cont-table__optionsItems">

                    </div>
                    <div class="cont-table__optionsButtons">
                        <a href="#modalDetails" class="buttons optionsButtonsImporte"><img src="../../../assets/icons/download.svg" alt="">Importar ordenes</a>
                        <a href="../colaboradores/colaboradores.php" class="buttons optionsButtonsNewUser"><img src="../../../assets/icons/user-plus.svg" alt="">Agregar colaborador</a>
                    </div>
                </div>
                <div class="table-container">
                    <div class="cont-table__search">
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
                    </div>
                    <table class="responsive-table" id="table_colaborador">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Cedula</th>
                                <th>Colaborador</th>
                                <th>Centro costo</th>
                                <th>Cargo</th>
                                <th>Ciudad</th>
                                <th>Fecha ingreso</th>
                                <th>Estado</th>
                                <th>Proyecto</th>
                                <th>opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($result) > 0) {
                                foreach ($result as $data) {
                            ?>
                                    <tr>
                                        <td data-label="Id"><?php echo $data['id_colaborador'] ?></td>
                                        <td data-label="Cedula"><?php echo $data['cedula'] ?></td>
                                        <td data-label="Colaborador"><?php echo $data['nombre_colaborador'] ?></td>
                                        <td data-label="Centro costo"><?php echo $data['id_ceco'] ?></td>
                                        <td data-label="Cargo"><?php echo $data['nombre_cargo'] ?></td>
                                        <td data-label="Ciudad"><?php echo $data['nombre_ciudad'] ?></td>
                                        <td data-label="Fecha ingreso"><?php echo $data['fecha_ingreso'] ?></td>

                                        <td data-label="Estado">
                                            <span class="<?php echo ($data['nombre_estado_col'] == 'Activo') ? 'activo' : 'inactivo'; ?>">
                                                <?php echo $data['nombre_estado_col'] ?>
                                            </span>
                                        </td>


                                        <td data-label="Proyecto"><?php echo $data['nombre_proyecto'] ?></td>
                                        <td data-label="opciones">
                                            <button onclick="showModal()" data-cedula="<?php echo $data['cedula']; ?>" class="buttonOrdenes typeOrdenes"></button>

                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!--==============================================================
    /-----Modals----//
    ============================================================= -->

    <!-- ================Modal table detaills===============-->
    <div id="modalDetails" name="modalDetails" class="modalmasDetails">
        <div class="modalboxDetails movedowndetails">
            <a href="#close" title="Close" class="closeDetails">X</a>
            <div class="table-containerdetails">
                <p class="table-container__info">Los datos observados se encutran disponibles por 24
                    horas,
                    durante
                    este tiempo se podran modificar los puntos ingresados. Una vez transcurrido este
                    tiempo
                    el
                    sistema no permitira realizar cambios.</p>
                <div class="cont-table__optionsButtonsImporte">
                    <form method="POST" id="cargaExcelOrdenes" enctype="multipart/form-data" class="buttons optionsButtonsExporte formImport">
                        <label for="excel_file" class="custom-file-upload">
                            <img src="../../../assets/icons/download.svg" alt="">
                            <span class="custom-file-upload__text">Importar ordenes</span>
                            <input type="file" name="excel_file" id="excel_file" accept=".xlsx" style="display: none;">
                        </label>
                        <input type="submit" name="excel" id="submit_button" value="Importar" class="Btnimpot">
                    </form>
                    <div class="loader">Cargando
                        <span></span>
                    </div>
                </div>
                <!-- <div class="contDate">
                    <div class="contDate-date">
                        <label for="min">Fecha inicial:</label>
                        <input type="date" class="contDate-date__input" id="min" name="min">
                    </div>
                    <div class="contDate-date">
                        <label for="max">Fecha final:</label>
                        <input type="date" class="contDate-date__input" id="max" name="max">
                    </div>
                    <button id="resetDates" class="buttonReset"><img src="../../../assets/icons/refresh.svg" alt=""></button>
                </div> -->

                <table class="responsive-tableDetails" id="tablaTmp">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Número de orden</th>
                            <th>cedula</th>
                            <th>Nombre colaborador</th>
                            <th>Puntos asignados</th>
                            <th>Fecha de puntos</th>
                            <th>Tiempo</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($resultTmpPuntos) > 0) {
                            foreach ($resultTmpPuntos as $dataTmpPuntos) {
                        ?>
                                <tr>
                                    <td data-label="Id"><?php echo $dataTmpPuntos['id tabla temporal'] ?></td>
                                    <td data-label="numero_orden"><?php echo $dataTmpPuntos['Numero de orden'] ?></td>
                                    <td data-label="cedula"><?php echo $dataTmpPuntos['Cedula colaborador'] ?></td>
                                    <td data-label="nombre_colaborador"><?php echo $dataTmpPuntos['Nombre colaborador'] ?></td>
                                    <td data-label="puntos_asignados"><?php echo $dataTmpPuntos['Puntos asignados'] ?></td>
                                    <td data-label="fecha_puntos"><?php echo $dataTmpPuntos['Fecha de puntos'] ?></td>
                                    <td data-label="nombre_frecuencia"><?php echo $dataTmpPuntos['Nombre de Frecuencia'] ?></td>
                                    <td data-label="Opciones">
                                        <button onclick="showModalEdit()" class="button type2 edit-button" data-orden="<?php echo $dataTmpPuntos['Numero de orden']; ?>" data-cedula="<?php echo $dataTmpPuntos['Cedula colaborador']; ?>" data-nombre="<?php echo $dataTmpPuntos['Nombre colaborador']; ?>" data-actuales="<?php echo $dataTmpPuntos['Puntos asignados']; ?>" data-asignados="<?php echo $dataTmpPuntos['Puntos asignados']; ?>">
                                        </button>
                                        <button class="buttonDelete typeDelete" value="<?php echo $dataTmpPuntos['id tabla temporal'] ?>"></button>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <!-- ===========Modal alert eliminar orden=========== -->
                <div id="confirmModal" class="modal">
                    <div class="modal-content">
                        <input type="hidden" value="<?php echo $dataTmpPuntos['id tabla temporal']; ?>">
                        <h2>Confirmar eliminación</h2>
                        <p>¿Estás seguro de que quieres eliminar este dato?</p>
                        <div class="conbutton">
                            <button id="cancelBtn">Cancelar</button>
                            <button id="deleteBtn" value="<?php echo $dataTmpPuntos['id tabla temporal'] ?>">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================Modal table orders===============-->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="table-containerOrdenes">
                <div class="cont-table__search">
                    <div class="inputSearch-container">
                        <input type="text" name="text" class="inputSearch" placeholder="Search something...">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24" class="iconSearch">
                            <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                            <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
                            <g id="SVGRepo_iconCarrier">
                                <rect fill="white" height="24" width="24"></rect>
                                <path fill="" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM9 11.5C9 10.1193 10.1193 9 11.5 9C12.8807 9 14 10.1193 14 11.5C14 12.8807 12.8807 14 11.5 14C10.1193 14 9 12.8807 9 11.5ZM11.5 7C9.01472 7 7 9.01472 7 11.5C7 13.9853 9.01472 16 11.5 16C12.3805 16 13.202 15.7471 13.8957 15.31L15.2929 16.7071C15.6834 17.0976 16.3166 17.0976 16.7071 16.7071C17.0976 16.3166 17.0976 15.6834 16.7071 15.2929L15.31 13.8957C15.7471 13.202 16 12.3805 16 11.5C16 9.01472 13.9853 7 11.5 7Z" clip-rule="evenodd" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <table class="responsive-tableOrdenes" id="tableOrdenes" style="width:100%">
                    <thead>
                        <tr>
                            <th>Número de orden</th>
                            <th>Dias</th>
                            <th>Estado</th>
                            <th>Puntos asignados</th>
                            <th>Puntos Restantes</th>
                            <th>Fecha inicio</th>
                            <th>Fecha Fin</th>
                            <th>Ingresar puntos</th>
                            <!-- <th>Opciones</th> -->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================modal insert points===============-->
    <div id="myModalInsertPoints" class="modalInsertPoints">
        <div class="modal-contentInsertPoints">
            <span class="close-btnInsertPoints" onclick="closeModalInsertPoints()">&times;</span>
            <div class="contInsertPoints">
                <div class="contInsert_infoOrder">
                    <img src="../../../assets/icons/edit-3.svg" alt="">
                    <p>Ingresar Puntos</p>
                </div>
                <form class="contInsert_itemsOrder">
                    <input type="hidden" id="numeroOrden" name="numeroOrden" value="">
                    <input type="hidden" id="cedulaColaborador" name="cedulaColaborador" value="">
                    <input type="hidden" id="nombreColaborador" name="nombreColaborador" value="">
                    <ul>
                        <ul>
                            <li>
                                <p>Puntos:</p><input type="number" name="puntos" id="puntos" placeholder="Ingresar puntos"></input>
                            </li>
                            <li>
                                <p>Frecuencia:</p>
                                <select name="frecuencia" id="frecuencia">
                                    <?php
                                    if (count($resultFrecuencia) > 0) {
                                        foreach ($resultFrecuencia as $dataFrecuencia) {
                                    ?>
                                            <option value="<?php echo $dataFrecuencia['id_frecuencia']; ?>"><?php echo $dataFrecuencia['nombre_frecuencia']; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </li>
                            <div class="inputGroup">
                                <p>Fecha ingreso puntos</p>
                                <input type="date" name="fechaPuntos" id="fechaPuntos" required="" placeholder="Fecha ingreso de puntos">
                            </div>
                        </ul>
                        <button class="BtnConEditOrder" type="submit">
                            <span class="IconContainer_EditOrder">
                                <img src="../../../assets/icons/save.svg" alt="iconSearch_Edit">
                            </span>
                            <p class="text">Guardar</p>
                        </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ================modal to edit orders from the detail table===============-->
    <div id="myModalEdit" class="modalEdit">
        <div class="modal-contentEdit">
            <span class="close-btnEdit" onclick="closeModalEdit()">&times;</span>
            <div class="contEdit">
                <div class="contEdit_info">
                    <img src="../../../assets/icons/edit-3.svg" alt="">
                    <p>Modificar Puntos ingresados</p>
                </div>
                <form class="contEdit_items">
                    <input type="hidden" id="id_tmp" value="<?php echo $dataTmpPuntos['id tabla temporal'] ?>">
                    <ul>
                        <li>
                            <p>No. Orden:</p><span id="numeroOrdenSpan"></span>
                        </li>
                        <li>
                            <p>Colaborador:</p><span id="nombreColaboradorSpan"></span>
                        </li>
                        <li>
                            <p>Puntos asignados:</p><span id="puntosAsignadosSpan"></span>
                        </li>
                        <li>
                            <p>Puntos a modificar:</p><input type="number" id="puntosModificar" placeholder="Ingresar puntos"></input>
                        </li>
                        <li>
                            <p>Tiempo:</p>
                            <select name="frecuencia" id="frecuenciaTwo">
                                <?php
                                if (count($resultFrecuencia) > 0) {
                                    foreach ($resultFrecuencia as $dataFrecuencia) {
                                ?>
                                        <option value="<?php echo $dataFrecuencia['id_frecuencia']; ?>"><?php echo $dataFrecuencia['nombre_frecuencia']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </li>
                    </ul>
                    <button class="BtnConEdit" type="submit" id="guardarCambiosButton">
                        <span class="IconContainer_Edit">
                            <img src="../../../assets/icons/save.svg" alt="iconSearch_Edit">
                        </span>
                        <p class="text">Guardar</p>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- ================modal to edit orders from the orders table===============-->
    <div id="myModalEditOrder" class="modalEditOrder">
        <div class="modal-contentEditOrder">
            <span class="close-btnEditOrder" onclick="closeModalEditOrder()">&times;</span>
            <div class="contEditOrder">
                <div class="contEdit_infoOrder">
                    <img src="../../../assets/icons/edit-3.svg" alt="">
                    <p>Modificar Puntos</p>
                </div>
                <form class="contEdit_itemsOrder">
                    <ul>
                        <li>
                            <p>Colaborador:</p><span>xxxxxxxxxxxxx</span>
                        </li>
                        <li>
                            <p>No. Orden:</p><span>xxxxxxxxxxxxx</span>
                        </li>
                        <li>
                            <p>Puntos asignados:</p><span>xxxxxxxxxxxxx</span>
                        </li>
                        <li>
                            <p>Puntos a modificar:</p><input type="number" placeholder="Ingresar puntos"></input>
                        </li>
                        <li>
                            <p>Frecuencia:</p>
                            <select>
                                <option value="">Diario</option>
                                <option value="">Quicenal</option>
                                <option value="">Mensual</option>
                            </select>
                        </li>
                    </ul>
                    <button class="BtnConEditOrder" type="submit">
                        <span class="IconContainer_EditOrder">
                            <img src="../../../assets/icons/save.svg" alt="iconSearch_Edit">
                        </span>
                        <p class="text">Guardar</p>
                    </button>
                </form>
            </div>
        </div>
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

    <!-- ==========Dashboard============== -->
    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/dashboard/activarPrinicpalLateral.js"></script>
    <script src="../../../script/dashboard/modalsEditarTable.js"></script>
    <script src="../../../script/dashboard/tabla_principal.js"></script>
    <script src="../../../script/dashboard/modaListaOrdenes_colaborador.js"></script>
    <script src="../../../script/dashboard/actualizarPuntos_tablaTmp.js"></script>
    <script src="../../../script/dashboard/importar_ordenes.js"></script>
    <script src="../../../script/dashboard/deletePuntos.js"></script>


    <!-- ==================Modals============== -->
    <script src="../../../script/colaborador/modals.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>


    <!-- ==============filtro de fechas============= -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>



    <script src="https://cdn.tailwindcss.com"></script>

    <!-- =========botonesDataTABLE======= -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>



</body>

</html>