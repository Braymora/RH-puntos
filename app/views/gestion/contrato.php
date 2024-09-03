<?php
//id_contrato	numero_contrato	nombre_colaborador	id_tipo_contrato	id_colaborador 
include '../../config/conexion.php';

session_start();
$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];



//consulta para tabla
$sql_querry = "SELECT cnt.id_contrato, 
cnt.numero_contrato, 
cnt.nombre_colaborador, 
cnt.id_tipo_contrato, 
cnt.id_colaborador, 
cl.id_ceco
    FROM contratos cnt 
    JOIN colaboradores cl ON cnt.id_colaborador = cl.id_colaborador
    WHERE cl.id_estado_colabora = 1";

if ($_SESSION['rol'] == 2) {
    $sql_querry .= " AND cl.id_ceco = :id_proyecto_usuario";
}

// Consulta para obtener colaboradores
$sql_colaborador = "SELECT cl.cedula, cl.nombre_colaborador, cl.correo, cl.id_ceco, cl.id_estado_colabora FROM colaboradores cl WHERE id_estado_colabora = 1";
if ($_SESSION['rol'] == 2) {
    $sql_colaborador .= " AND id_ceco = :id_proyecto_usuario";
}




try {
    //consulta para lista de tipo de contrato
    $sql_querrycon = "SELECT * FROM tipo_contrato ";
    $tipocontratos = $conexion->query($sql_querrycon);

    //consulta tabla contratos
    $stmt = $conexion->prepare($sql_querry);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultContratos = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Preparar la consulta
    $stmt = $conexion->prepare($sql_colaborador);
    if ($_SESSION['rol'] == 2) {
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario, PDO::PARAM_INT);
    }
    $stmt->execute();
    $resultColaborador = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Manejo de excepciones
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
    </style>

    <title>Dashboard</title>

</head>

<body>

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
                        <h1 class="contCreatecontract-info__title">Nuevo contrato</h1>
                    </div>
                    <form class="contentInfo-inputs" action="proceso_contrato.php" method="post">
                        <div class="inputGroup">
                            <input type="text" required="" name="contrato" id="contrato" autocomplete="off" readonly>
                            <input type="hidden" id="contrato_hidden" name="contrato_hidden">
                            <label for="contrat">No.contrato</label>
                        </div>
                        <div class="inputGroup">
                            <select class="mi-select2" name="identificacion" id="identificacion" onchange="autollenarDatos()">
                                <option value="">Seleccione número de cedula</option>
                                <?php
                                foreach ($resultColaborador as $data) :
                                ?>
                                    <option value="<?php echo $data['cedula']; ?>"><?php echo $data['cedula']; ?> - <?php echo $data['id_ceco']; ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="inputGroup">
                            <input type="text" required="" name="nombre" id="nombre" value="">
                            <label for="nombre">Nombre colaborador</label>
                        </div>
                        <div class="inputGroup">
                            <input type="text" required="" name="correo" id="correo" value="">
                            <label for="correo">Correo colaborador</label>
                        </div>

                        <div class="inputGroup">
                            <select name="tipo_comtrato" id="tipo_comtrato">
                                <option value="#">Seleccione tipo de contrato</option>
                                <?php
                                while ($datacon = $tipocontratos->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <option value=<?php echo $datacon['id_tipo_contrato']; ?>><?php echo $datacon['nombre_tipo_contrato']; ?></option>

                                <?php
                                endwhile;
                                ?>
                            </select>
                        </div>
                        <button class="BtnCon" type="submit">
                            <span class="IconContainer">
                                <img src="../../../assets/icons/save.svg" alt="iconSearch">
                            </span>
                            <p class="text">Crear</p>
                        </button>
                    </form>
                </div>
            </div>
            <hr>

            <div class="main_table">
                <div class="cont-table">
                    <div class="table-container">
                        <table class="responsive-table" id="table_contrato">
                            <thead>
                                <tr>
                                    <th>No. Contrato</th>
                                    <th>Nombre colaborador</th>
                                    <th>Tipo Contrato</th>
                                    <th>opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (count($resultContratos) > 0) {
                                    foreach ($resultContratos as $dataTmpContratos) {
                                ?>
                                        <tr>
                                            <td data-label="No. Contrato"><?php echo $dataTmpContratos['numero_contrato']; ?></td>
                                            <td data-label="Nombre colaborador"><?php echo $dataTmpContratos['nombre_colaborador']; ?></td>
                                            <td data-label="Tipo Contrato"><?php echo $dataTmpContratos['id_tipo_contrato']; ?></td>
                                            <td data-label="opciones">
                                                <button class="buttonDelete typeDelete" value="<?php echo $dataTmpContratos['numero_contrato'] ?>"></button>
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

                <!-- ===========Modal alert eliminar ceco=========== -->
                <div id="confirmModal" class="modal">
                    <div class="modal-content">
                        <input type="hidden" value="<?php echo $dataTmpPuntos['numero_contrato']; ?>">
                        <h2>Confirmar eliminación</h2>
                        <p>¿Estás seguro de que quieres eliminar este dato?</p>
                        <div class="conbutton">
                            <button id="cancelBtn">Cancelar</button>
                            <button id="deleteBtn" value="<?php echo $dataTmpPuntos['numero_contrato'] ?>">Eliminar</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ===============
    Alert validación Manu
    =============== -->
    <?php if (isset($_SESSION['alerta'])) : ?>
        <div class="contenedor-toast" id="contenedor-toast">
            <div class="toast <?php echo ($_SESSION['alerta'] === 'exito') ? 'exito' : (($_SESSION['alerta'] === 'error') ? 'error' : 'warning'); ?>" id="1">
                <div class="contenido">
                    <div class="icono">
                        <?php if ($_SESSION['alerta'] === 'exito') {
                            echo '<svg xmlns="http://www.w4.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z" />
                            </svg>';
                        } elseif ($_SESSION['alerta'] === 'error') {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>';
                        } else {
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                            </svg>';
                        }  ?>

                    </div>

                    <div class="texto">
                        <p class="titulo"><?php echo $_SESSION['titulo-alerta'] ?></p>
                        <p class="descripcion"><?php echo $_SESSION['des-alerta'] ?></p>
                    </div>
                </div>

                <button class="btn-cerrar" onclick="closeAlertBar1()">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
        <script>
            // Oculta la barra de alerta
            function closeAlertBar1() {
                $('#1').slideUp();
            }
        </script>
        <script>
            // Muestra la barra de alerta
            $(document).ready(function() {
                $('#1').slideDown();

                // Oculta la barra de alerta después de 5 segundos (puedes ajustar el tiempo)
                setTimeout(function() {
                    closeAlertBar1();
                }, 5000);
            });

            // Oculta la barra de alerta
            function closeAlertBar1() {
                $('#1').slideUp();
            }
        </script>


        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>


    <!-- ===============
    Alert Johan
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

    <script src="../../../script/contrato/datatable.js"></script>
    <script src="../../../script/contrato/deleteContrato.js"></script>

    <!-- ==============Datatable============= -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>


    <!-- =========botonesDataTABLE======= -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>







    <!--[if IE]> contrat <![endif]-->
    <script>
        // Inicializa Select2 en el elemento select
        $(document).ready(function() {
            $('#identificacion').select2({
                placeholder: 'Selecciona una opción',
                tags: true,
                width: '80%',
                backgroundcolor: '#FFFFFF'
            });
        });


        function autollenarDatos() {
            var select = document.getElementById('identificacion');
            var nombreInput = document.getElementById('nombre');
            var correoInput = document.getElementById('correo');
            var contratoInput = document.getElementById('contrato');
            var selectedValue = select.value;

            if (selectedValue === "") {
                nombreInput.value = "hola esto es una prueba que funcionas bien ";
                contratoInput.value = 'selectedValue'
            } else {
                // Realiza una solicitud POST al servidor para buscar el nombre en la base de datos
                // utilizando el valor seleccionado como parámetro.
                fetch('proceso_contrato2.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            cedula: selectedValue
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                        nombreInput.value = response.name; // Establece el valor del campo 'nombre' con el resultado de la consulta.
                        correoInput.value = response.correo;
                        contratoInput.value = selectedValue;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
            // Realiza una solicitud AJAX para obtener los datos de la persona
        }
    </script>

</body>

</html>