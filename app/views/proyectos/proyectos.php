<?php
require_once("../../config/conexion.php"); // Reemplaza "conexion.php" con la ubicación correcta de tu archivo de conexión
session_start();

$query = "SELECT pr.id_proyecto, pr.nombre_proyecto FROM proyectos pr";

try {
    // Preparar y ejecutar la consulta en la tabla colaboradores
    $stmt = $conexion->prepare($query);
    $stmt->execute();
    // Obtener los resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit(); // Detener el script en caso de error en la consulta
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Fonts-->
    <?php include '../font/font.php'; ?>

    <!-- ==========Datatable======= -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../../style/general.css">
    <link rel="stylesheet" href="../../../style/proyectos/proyecto.css">

    <style>
        .cont-contract {
            height: auto !important;
        }


        div#table_ceco_length {
            width: 20%;
        }


        /**modal editar ceco */
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


    <title>Proyectos</title>

</head>

<body>

    <?php
    include '../nav/nav.php';
    ?>

    <main class="main">
        <?php
        include '../header/header.php';
        ?>

        <div class="contCreatecontract">
            <div class="cont-contract">
                <div class="contCreatecontract-info">
                    <img src="../../../assets/icons/folder.svg" alt="Imagen para editar" class="contCreatecontract-info__image">
                    <h1 class="contCreatecontract-info__title">Proyectos</h1>
                </div>
                <form class="contentInfo-inputs" id="register_proyecto">
                    <div class="inputGroup">
                        <input type="text" name="codigo_proyecto" required="" autocomplete="off" class="proyecto">
                        <label for="tipeContrat">Codigo Proyecto</label>
                    </div>

                    <div class="inputGroup">
                        <input type="text" name="nombre_proyecto" required="" autocomplete="off" class="proyecto">
                        <label for="tipeContrat">Nombre Proyecto</label>
                    </div>

                    <button class="BtnCon" type="submit">
                        <span class="IconContainer">
                            <img src="../../../assets/icons/save.svg" alt="iconSearch">
                        </span>
                        <p class="text">Guardar</p>
                    </button>
                </form>
            </div>
        </div>
        <hr>


        <div class="main_table">
            <div class="cont-table">
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
                    <table class="responsive-table" id="table_proyecto">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Proyecto</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($result) > 0) {
                                foreach ($result as $data) {
                            ?>
                                    <tr>
                                        <td data-label="Codigo"><?php echo $data['id_proyecto'] ?></td>
                                        <td data-label="Nombre proyecto"><?php echo $data['nombre_proyecto'] ?></td>
                                        <td data-label="opciones">
                                            <button class="button type2 edit-button" data-proyecto="<?php echo $data['id_proyecto']; ?>" data-name="<?php echo $data['nombre_proyecto']; ?>">
                                                Editar
                                            </button>
                                            <button class="buttonDelete typeDelete" value="<?php echo $data['id_proyecto'] ?>"></button>
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


        <!-- =============Modal para editar ceco======== -->
        <!-- Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2>Editar Datos</h2>
                <form class="contentInfo-inputs" id="editForm">
                    <div class="inputGroup">
                        <input type="text" name="codigo_proyecto" id="codigo_proyectoUp" required="" autocomplete="off" class="usuario">
                        <label for="tipeContrat">Código proyecto</label>
                    </div>
                    <div class="inputGroup">
                        <input type="text" name="nombre_proyecto" id="name_proyectoUp" required="" autocomplete="off" class="usuario">
                        <label for="tipeContrat">Nombre proyecto</label>
                    </div>
                    <button class="BtnCon" type="submit">
                        <span class="IconContainer">
                            <img src="../../../assets/icons/save.svg" alt="iconSearch">
                        </span>
                        <p class="text">Guardar</p>
                    </button>
                </form>
            </div>
        </div>

        <!-- ===========Modal alert eliminar ceco=========== -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <input type="hidden" value="<?php echo $dataTmpPuntos['id_proyecto']; ?>">
                <h2>Confirmar eliminación</h2>
                <p>¿Estás seguro de que quieres eliminar este dato?</p>
                <div class="conbutton">
                    <button id="cancelBtn">Cancelar</button>
                    <button id="deleteBtn" value="<?php echo $dataTmpPuntos['id_proyecto'] ?>">Eliminar</button>
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


    </main>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/dashboard/activarPrinicpalLateral_historial.js"></script>

    <script src="../../../script/proyectos/guardar_datos.js"></script>
    <script src="../../../script/proyectos/update_delete_momdal.js"></script>

    <!-- ==============Datatable============= -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>



</body>

</html>