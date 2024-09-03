<?php
session_start();

include '../../config/conexion.php';

$rolesPermitidos = [1, 2];

if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("location: ../../../index.php");
    exit();
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];

try {
    $sql_querry = "SELECT 
    os.id_servicio, 
    os.numero_orden, 
    os.numero_contrato, 
    cl.cedula,
    cl.nombre_colaborador, 
    cl.nombre_cargo,
    cl.correo,
    cl.nombre_proyecto,
    os.ceco, 
    cl.nombre_cargo,
    os.cantidad_puntos, 
    os.fecha_inicio, 
    os.fecha_fin, 
    sto.nombre_estado,
    os.observaciones 
    FROM ordenes_servicios os
    JOIN colaboradores cl ON os.numero_contrato = cl.cedula
    LEFT JOIN estado_ordenes_anex sto ON os.id_estadoOrden_anexo = sto.id_estadoOrden_anexo";

    if ($_SESSION['rol'] == 2) {
        $sql_querry .= " WHERE os.ceco = :id_proyecto_usuario";
        $stmt = $conexion->prepare($sql_querry);
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario);
        $stmt->execute();
        $servicos = $stmt->fetchAll();
    } else {
        $servicos = $conexion->query($sql_querry)->fetchAll();
    }

    $sql_querryCont = "SELECT ct.id_colaborador, ct.numero_contrato, ct.correo_col, cl.id_ceco
    FROM contratos ct
    JOIN colaboradores cl 
    ON cl.id_colaborador = ct.id_colaborador";

    if ($_SESSION['rol'] == 2) {
        $sql_querryCont .= " WHERE cl.id_ceco = :id_proyecto_usuario ";
        $stmt = $conexion->prepare($sql_querryCont);
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario);
        $stmt->execute();
        $contratos = $stmt->fetchAll();
    } else {
        $contratos = $conexion->query($sql_querryCont)->fetchAll();
    }


    $sql_querryAnexo = "SELECT ax.id_anexo ,ax.numero_anexo, ax.ceco, ax.nombre_colaborador, ax.idcolaborador FROM anexo ax";

    if ($_SESSION['rol'] == 2) {
        $sql_querryAnexo .= " WHERE ax.ceco = :id_proyecto_usuario GROUP BY ax.idcolaborador";
        $stmt = $conexion->prepare($sql_querryAnexo);
        $stmt->bindParam(':id_proyecto_usuario', $id_proyecto_usuario);
        $stmt->execute();
        $anexos = $stmt->fetchAll();
    } else {
        $sql_querryAnexo .= " GROUP BY ax.idcolaborador";
        $anexos = $conexion->query($sql_querryAnexo)->fetchAll();
    }


    $sql_ceco = "SELECT * FROM ceco";
    $ceco = $conexion->query($sql_ceco);


    // Obtener el último número de orden de la base de datos
    $sql_last_order = "SELECT MAX(numero_orden) AS last_order FROM ordenes_servicios";
    $stmt = $conexion->query($sql_last_order);
    $last_order = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_order = $last_order['last_order'] + 1;

    // Función para generar el siguiente número de orden único
    function generarNumeroOrdenUnico($conexion, $next_order)
    {
        $sql_check_order = "SELECT COUNT(*) AS count_order FROM ordenes_servicios WHERE numero_orden = :next_order";
        $stmt = $conexion->prepare($sql_check_order);
        $stmt->bindParam(':next_order', $next_order);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count_order'] > 0) {
            // Si el número de orden ya existe, se incrementa y verifica nuevamente
            $next_order++;
            return generarNumeroOrdenUnico($conexion, $next_order);
        } else {
            // Si el número de orden es único, se devuelve
            return $next_order;
        }
    }

    // Generar el número de orden único
    $numero_orden = generarNumeroOrdenUnico($conexion, $next_order);
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

        /* ========Bton ir a vistar anexos=========== */
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


    <title>servicio</title>
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
                        <a href="../Anexo/anexo.php" class="crearAnexo">Crear Anexo |-></a>
                        <h1 class="contCreatecontract-info__title">Nuevo servicio</h1>
                    </div>

                    <form class="contentInfo-inputs" action="proceso_servicios.php" method="post">
                        <input type="hidden" name="idUser" value="<?php echo $_SESSION['idUser'] ?>">

                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="n_orden" id="n_orden" value="<?php echo $numero_orden; ?>">
                            <label for="contrat">No.orden</label>
                        </div>


                        <div class="inputGroup">
                            <select name="n_contrato" id="n_contrato">
                                <option value="#">Seleccione número de contrato</option>
                                <?php foreach ($contratos as $data) : ?>
                                    <option value="<?php echo $data['numero_contrato']; ?>" data-correo="<?php echo $data['correo_col']; ?>" data-ceco="<?php echo $data['id_ceco']; ?>"><?php echo $data['numero_contrato']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="correo" id="correo">
                            <label for="correo">Correo colaborador</label>
                        </div>
                        <div class="inputGroup">
                            <select name="ceco" id="ceco">
                                <option value="#">Seleccione centro de costo</option>
                                <?php
                                while ($dataceco = $ceco->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <option value=<?php echo $dataceco['id_ceco']; ?>><?php echo $dataceco['nombre_ceco']; ?></option>

                                <?php
                                endwhile;
                                ?>
                            </select>
                        </div>

                        <div class="inputGroup">
                            <select name="n_anexo" id="n_anexo">
                                <option value="#">Seleccione número de Anexo</option>
                                <?php foreach ($anexos as $dataAnexo) : ?>
                                    <option value="<?php echo $dataAnexo['id_anexo']; ?>"><?php echo $dataAnexo['numero_anexo']; ?> - <?php echo $dataAnexo['nombre_colaborador']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="inputGroup">
                            <p>Fecha inico</p>
                            <input type="date" required="" placeholder="Fecha de inicio" name="fecha_inicio" id="fecha_inicio">
                        </div>

                        <div class="inputGroup">
                            <p>Fecha fin</p>
                            <input type="date" required="" name="fecha_fin" id="fecha_fin">
                        </div>

                        <div class="inputGroup">
                            <input type="text" required="" autocomplete="off" name="cantidad_puntos" id="cantidad_puntos">
                            <label for="tipeContrat">Cantidad de Puntos</label>
                        </div>
                        <div class="inputGroup">
                            <textarea name="justificacion" id="justificacion" cols="30" rows="2" placeholder="Ingrese su justificación"></textarea>
                        </div>
                        <div class="inputGroup">
                            <textarea name="observacion" id="observacion" cols="30" rows="2" placeholder="Ingrese su observación"></textarea>
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

            <!--[if IE]> la alerta va aqui <![endif]-->
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

            <!--[if IE]>fin alerta <![endif]-->

            <hr>
            <div class="main_table">
                <div class="cont-table">
                    <div class="table-container">
                        <table class="responsive-table" id="table_servicio">
                            <thead>
                                <tr>
                                    <th>No. orden</th>
                                    <th>No. contrato</th>
                                    <th>Nombre colaborador</th>
                                    <th>Centro de costo</th>
                                    <th>Cantidad de puntos</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Estado anexo</th>
                                    <th>Observaciones</th>
                                    <th>Eliminar</th>
                                    <th>Enviar PDF orden</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($servicos as $data) :
                                ?>
                                    <tr>
                                        <td data-label="No. orden"><?php echo $data['numero_orden']; ?></td>
                                        <td data-label="No. Contrato"><?php echo $data['numero_contrato']; ?></td>
                                        <td data-label="Nombre colaborador"><?php echo $data['nombre_colaborador']; ?></td>
                                        <td data-label="Centro de costo"><?php echo $data['ceco']; ?></td>
                                        <td data-label="Cantidad de puntos"><?php echo $data['cantidad_puntos']; ?></td>
                                        <td data-label="Fecha inicio"><?php echo $data['fecha_inicio']; ?></td>
                                        <td data-label="Fecha fin"><?php echo $data['fecha_fin']; ?></td>

                                        <?php
                                        echo ' <style>
                                                    .estadoSinFirmar {
                                                        color: white;
                                                        background-color: red;
                                                        padding: 5px;
                                                        border-radius: 5px;
                                                    }

                                                    .estadoFirmado {
                                                        color: white;
                                                        background-color: green;
                                                        padding: 5px;
                                                        border-radius: 5px;
                                                    }
                                                </style>';

                                        if ($data['nombre_estado'] == 'Sin Anexo') {
                                            echo '<td><span class="estadoSinFirmar">' . $data['nombre_estado'] . '</span></td>';
                                        } else if ($data['nombre_estado'] == 'Con Anexo') {
                                            echo '<td><span class="estadoFirmado">' . $data['nombre_estado'] . '</span></td>';
                                        }
                                        ?>

                                        <td data-label="Observaciones"><?php echo $data['observaciones']; ?></td>
                                        <td data-label="Eliminar">
                                            <button class="buttonDelete typeDelete" value="<?php echo $data['numero_orden'] ?>"></button>
                                        </td>
                                        <td data-label="Enviar PDF">
                                            <button id="fileButton" class="sendPdfButton" data-orden="<?php echo $data['numero_orden']; ?>" data-correo="<?php echo $data['correo']; ?>">
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

    <script src="../../../script/servicio/datatable.js"></script>
    <script src="../../../script/servicio/deleteServicio.js"></script>
    <script src="../../../script/servicio/enviarPDForden.js"></script>
    <script src="../../../script/servicio/busqueda_Select2.js"></script>

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