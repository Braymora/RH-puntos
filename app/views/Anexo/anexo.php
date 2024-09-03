<?php
session_start();

include '../../config/conexion.php';

$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];

// Consulta para formulario
$query = "SELECT
    c.id_colaborador,
    c.cedula,
    c.nombre_colaborador,
    c.fecha_ingreso,
    c.id_estado_colabora,
    stc.nombre_estado_col,
    c.id_ceco,
    c.nombre_cargo,
    c.nombre_proyecto,
    c.correo,
    c.direccion,
    c.nombre_ciudad
    FROM colaboradores c
    LEFT JOIN ceco ce ON c.id_ceco = ce.id_ceco
    LEFT JOIN estado_colaborador stc ON c.id_estado_colabora = stc.id_estado_colabora";

if ($_SESSION['rol'] == 2) {
    $query .= " WHERE c.id_ceco = :id_proyecto_usuario AND c.id_estado_colabora = 1";
}

$query .= " ORDER BY c.nombre_colaborador;";

// Consulta para visualizar tabla
$queryTable = "SELECT ax.id_anexo, ax.numero_anexo, ax.cedula_colabo, ax.nombre_colaborador, cl.correo, ax.idcolaborador 
FROM anexo ax
JOIN colaboradores cl ON ax.idcolaborador = cl.id_colaborador
GROUP BY ax.cedula_colabo";

try {
    // Consulta para formulario
    $stmt = $conexion->prepare($query);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para visualizar tabla
    $stmtTable = $conexion->prepare($queryTable);
    $stmtTable->execute();
    $resultTable = $stmtTable->fetchAll(PDO::FETCH_ASSOC);

    // Resto del código (puedes agregar aquí el procesamiento de resultados, mostrar en la interfaz, etc.)

} catch (PDOException $e) {
    // Manejo de errores más detallado
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

    <link rel="stylesheet" href="../../../style/general.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <!-- ==========Datatable======= -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    <!-- =======Botones datateble========== -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        .cont-contract {
            height: auto !important;
        }

        #table_servicio_length {
            width: 6%;
        }

        .select2-container--default .select2-selection--single {
            border: 2px solid rgb(200, 200, 200) !important;
            padding: 20px 48px !important;
            display: flex !important;
            justify-content: left !important;
            align-items: center !important;
            /* Cambia el color del borde */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333;
            /* Cambia el color del texto seleccionado */
        }

        .select2-container--default .select2-results__option {
            background-color: #f5f5f5;
            /* Cambia el color de fondo del menú desplegable */
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #ddd;
            /* Cambia el color de fondo al pasar el ratón sobre las opciones */
        }

        /* ============boton agregar================= */
        .btnAgregar {
            border: 2px solid #24b4fb;
            background-color: rgb(18, 51, 77);
            border-radius: 0.9em;
            padding: 0.8em 1.2em 0.8em 1em;
            transition: all ease-in-out 0.2s;
            font-size: 16px;
        }

        .btnAgregar span {
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-weight: 600;
        }

        .btnAgregar:hover {
            background-color: #0071e2;
        }

        /* =========btn eliminar campos dinamicaos========== */
        .addedFields {
            display: grid;
            grid-template-columns: auto auto;
            padding: 0px 84px;
            position: relative;
        }

        .remove_field {
            background-color: red;
            padding: 8px;
            width: 36px;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: all 0.8s;
            position: absolute;
            right: 100px;
            top: 24px;
        }

        .remove_field:hover {
            background-color: rgb(18, 51, 77);
        }

        .contActividades {
            display: grid;
            grid-template-columns: auto auto;
            padding: 0px 84px;
        }


        /* ========Bton ir a vistar servicio=========== */
        .crearAnexo {
            padding: 8px 8px;
            background-color: rgb(18, 51, 77);
            border-radius: 4px;
            color: white;
            cursor: pointer;
            transition: all 0.8s;
        }

        .crearAnexo:hover {
            background-color: #71DD37;

        }

        /* =========Area textarea============ */
        .observaciones {
            align-items: center;
            display: none;
            flex-direction: column;
            justify-content: center;
            margin-top: 8px;
            text-align: center;
        }

        .observaciones h2 {
            margin: 0px;
            padding: 4px 12px;
        }

        .observaciones textarea {
            padding: 12px;
        }

        /* =================btn enviar PDF========= */
        @keyframes pulsate {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        #fileButton {
            padding: 14px 14px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            animation: pulsate 2s infinite;
            background-image: url("../../../assets/icons/pdf_icon.svg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }


        /* =======loader============== */
        .contLoader {
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
            display: none;
            height: 100vh;
            justify-content: center;
            position: fixed;
            width: 100%;
            z-index: 999999;
        }

        .loaderTwo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            background: transparent;
            border: 3px solid rgba(0, 102, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 150px;
            font-family: sans-serif;
            font-size: 20px;
            color: #0066ff;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 0 10px #0066ff;
            box-shadow: 0 0 20px rgba(0, 0, 0, .15);
        }

        .loaderTwo::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top: 3px solid #0066ff;
            border-right: 3px solid #0066ff;
            border-radius: 50%;
            animation: animateC 2s linear infinite;
        }

        .loaderTwo span {
            display: block;
            position: absolute;
            top: calc(50% - 2px);
            left: 50%;
            width: 50%;
            height: 4px;
            background: transparent;
            transform-origin: left;
            animation: animate 2s linear infinite;
        }

        .loaderTwo span::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #00aeff;
            top: -6px;
            right: -8px;
            box-shadow: 0 0 20px 5px #0066ff;
        }

        @keyframes animateC {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes animate {
            0% {
                transform: rotate(45deg);
            }

            100% {
                transform: rotate(405deg);
            }
        }
    </style>


    <title>Anexos</title>
</head>

<body>
    <!-- ==Loader== -->
    <div id="loader" class="contLoader">
        <div class="loaderTwo">Enviando
            <span></span>
        </div>
    </div>

    
    <div class="container">
        <!-- ==================currency vertical==================-->
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
                        <img src="../../../assets/icons/edit.svg" alt="Imagen para editar" class="contCreatecontract-info__image">
                        <a href="../gestion/servicios.php" class="crearAnexo"><-| Volver a servicios</a>
                                <h1 class="contCreatecontract-info__title">Nuevo Anexo</h1>
                    </div>

                    <form class="formInput" id="formAnexos" method="post">
                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="n_anexo" id="n_anexo">
                            <label for="contrat">No.Anexo</label>
                        </div>
                        <div class="inputGroup">
                            <input type="hidden" required="" autocomplete="off" name="idcolaborador" id="idcolaborador">
                        </div>
                        <div class="inputGroup">
                            <select name="cedula" id="cedula">
                                <option value="#">Seleccione número de cedula</option>
                                <?php foreach ($result as $data) : ?>
                                    <option value="<?php echo $data['cedula']; ?>" data-idcolaborador="<?php echo $data['id_colaborador']; ?>" data-cedula="<?php echo $data['cedula']; ?>" data-nombreColaborador="<?php echo $data['nombre_colaborador']; ?>" data-idceco="<?php echo $data['id_ceco']; ?>"><?php echo $data['cedula']; ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="name" id="name">
                            <label for="name">Nombre colaborador</label>
                        </div>

                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="namececo" id="namececo">
                            <label for="name">Centro de costo</label>
                        </div>

                        <div class="contActividades">
                            <div class="inputGroup">
                                <input type="number" required="" autocomplete="off" name="puntosPorMes" id="puntosPorMes">
                                <label for="name">Ingresar Puntos</label>
                            </div>
                            <div class="inputGroup">
                                <input type="text" required="" autocomplete="off" name="actividades" id="actividades">
                                <label for="name">Ingresar actividad</label>
                            </div>
                        </div>

                        <div class="additionalFieldsWrapper"></div>

                        <div class="observaciones">
                            <h2>Ingrese la información "calculo para el cumplimiento cuantitativo de las ordenes de servicio"</h2>
                            <textarea name="observaciones" id="observaciones" cols="30" rows="10">

                            </textarea>
                        </div>

                        <button class="btnAgregar" type="submit">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"></path>
                                </svg>
                            </span>
                        </button>

                        <button class="BtnCon" type="submit">
                            <span class="IconContainer">
                                <img src="../../../assets/icons/save.svg" alt="iconSearch">
                            </span>
                            <p class="text">Guardar</p>
                        </button>
                    </form>
                </div>
            </div>


            <div class="main_table">
                <div class="cont-table">
                    <div class="table-container">

                        
                        <table class="responsive-table" id="table_anexo">
                            <thead>
                                <tr>
                                    <th>No. anexo</th>
                                    <th>Cedula</th>
                                    <th>Nombre colaborador</th>
                                    <th>Correo</th>
                                    <th>Eliminar</th>
                                    <th>Enviar PDF Anexo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultTable as $dataTable) :
                                ?>
                                    <tr>
                                        <td data-label="No. anexo"><?php echo $dataTable['numero_anexo']; ?></td>
                                        <td data-label="Cedula"><?php echo $dataTable['cedula_colabo']; ?></td>
                                        <td data-label="Nombre colaborador"><?php echo $dataTable['nombre_colaborador']; ?></td>
                                        <td data-label="Correo"><?php echo $dataTable['correo']; ?></td>
                                        <td data-label="Eliminar">
                                            <button class="buttonDelete typeDelete" value="<?php echo $dataTable['numero_anexo'] ?>"></button>
                                        </td>
                                        <td data-label="Enviar PDF">
                                            <button id="fileButton" class="sendPdfButton" data-anexo="<?php echo $dataTable['numero_anexo']; ?>" data-correo="<?php echo $dataTable['correo']; ?>">
                                                <img src="../../../assets/icons/pdf_icon.svg" alt=""></img>
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- ===========Modal alert eliminar ceco=========== -->
            <div id="confirmModal" class="modal">
                <div class="modal-content">
                    <input type="hidden" value="<?php echo $dataTmpPuntos['id_ceco']; ?>">
                    <h2>Confirmar eliminación</h2>
                    <p>¿Estás seguro de que quieres eliminar este dato?</p>
                    <div class="conbutton">
                        <button id="cancelBtn">Cancelar</button>
                        <button id="deleteBtn" value="<?php echo $dataTmpPuntos['id_ceco'] ?>">Eliminar</button>
                    </div>
                </div>
            </div>
        </main>
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

    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/gestion/activarPrinicpalLateral_contratos.js"></script>

    
    <script src="../../../script/anexo/deleteAnexo.js"></script>
    <script src="../../../script/anexo/autocomplete.js"></script>
    <script src="../../../script/anexo/guardarDatos.js"></script>
    <script src="../../../script/anexo/agregarCampos_PuntosActividad.js"></script>
    <script src="../../../script/anexo/enviarPDFanexo.js"></script>
    <script src="../../../script/anexo/table.js"></script>



    <!-- ==============Datatable============= -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>


    <!-- =========botonesDataTABLE======= -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

</body>

</html>