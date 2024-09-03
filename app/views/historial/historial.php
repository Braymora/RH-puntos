<?php
session_start();

// Incluir el archivo de conexión
require '../../config/conexion.php';


if (isset($_SESSION['rol']) && $_SESSION['rol'] != 1 && $_SESSION['rol'] != 2) {
    header(("location: ../../../index.php"));
}

$id_proyecto_usuario = $_SESSION['id_proyecto'];

// Consulta para obtener los registros
$sql = "SELECT ct.id_cant_puntos, ct.numero_orden, os.cantidad_puntos, ct.puntos_asignados, ct.puntos_consumo 
        FROM cantidad_puntos ct
        JOIN ordenes_servicios os ON ct.numero_orden = os.numero_orden
        ORDER BY ct.id_cant_puntos";
$stmt = $conexion->query($sql);

$puntos_consumo_anterior = 0;
$numero_orden_anterior = null;

while ($row = $stmt->fetch()) {
    if ($row["numero_orden"] != $numero_orden_anterior) {
        // Si el numero_orden cambia, reinicia los puntos_consumo
        $puntos_consumo_anterior = $row["cantidad_puntos"] - $row["puntos_asignados"];
    } else {
        // Si el numero_orden es el mismo, realiza la operación
        $puntos_consumo_anterior = $puntos_consumo_anterior - $row["puntos_asignados"];
    }

    // Prepara la consulta de actualización
    $updateStmt = $conexion->prepare("UPDATE cantidad_puntos SET puntos_consumo = :puntos_consumo WHERE id_cant_puntos = :id_cant_puntos");

    // Ejecuta la consulta de actualización
    $updateStmt->execute(['puntos_consumo' => $puntos_consumo_anterior, 'id_cant_puntos' => $row["id_cant_puntos"]]);

    $numero_orden_anterior = $row["numero_orden"];
}

$sqlhistorial = "SELECT 
cp.id_cant_puntos,
cp.cedula AS 'Cedula colaborador', 
cp.nombre_colaborador AS 'Nombre colaborador',
cc.id_ceco AS 'Centro costo',
cc.nombre_ceco AS 'Cargo',
cl.nombre_ciudad AS 'Ciudad',
cl.fecha_ingreso AS 'Fecha de Ingreso',
ec.nombre_estado_col AS 'Estado colaborador',
cl.nombre_proyecto AS 'Nombre del proyecto',
os.numero_orden AS 'Número de orden',
eo.nombre_estado AS 'Estado de orden',
os.cantidad_puntos AS 'Cantidad de puntos',
cp.puntos_asignados AS 'Puntos asigandos',
cp.puntos_consumo AS 'Puntos actuales',
fr.nombre_frecuencia AS 'Frecuencia de Puntos',
os.fecha_inicio AS 'Fecha inicio',
os.fecha_fin AS 'Fecha fin'
FROM cantidad_puntos cp
JOIN colaboradores cl ON cp.cedula = cl.cedula
JOIN ceco cc ON cl.id_ceco = cc.id_ceco
JOIN estado_colaborador ec ON cl.id_estado_colabora = ec.id_estado_colabora
JOIN ordenes_servicios os ON cp.numero_orden = os.numero_orden
JOIN estado_ordenes eo ON os.id_estadoOrden = eo.id_estadoOrden
JOIN frecuencia_puntos fr ON cp.id_frecuencia = fr.id_frecuencia";

try {
    // Preparar y ejecutar la consulta en la tabla colaboradores
    $stmt = $conexion->prepare($sqlhistorial);
    $stmt->execute();
    // Obtener los resultados
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $e) {
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

    <!-- ===========datatable============ -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.semanticui.min.css">

    <!-- =======Botones datateble========== -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">


    <style>
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
    </style>

    <title>Historial</title>

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

                        <table class=" ui celled table responsive-table" id="example">
                            <thead>
                                <tr>
                                    <th>Cedula</th>
                                    <th>Colaborador</th>
                                    <th>Centro costo</th>
                                    <th>Cargo</th>
                                    <th>Ciudad</th>
                                    <th>Fecha ingreso</th>
                                    <th>Estado</th>
                                    <th>Nombre Proyecto</th>
                                    <th>Numero orden</th>
                                    <th>Estado Orden</th>
                                    <th>Cantidad de Puntos</th>
                                    <th>Puntos Asigandos</th>
                                    <th>Puntos Actuales</th>
                                    <th>Frecuencia</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($result) > 0) {
                                    foreach ($result as $data) {
                                ?>
                                        <tr>
                                            <td data-label="Cedula"><?php echo $data['Cedula colaborador'] ?></td>
                                            <td data-label="Colaborador"><?php echo $data['Nombre colaborador'] ?></td>
                                            <td data-label="Centro costo"><?php echo $data['Centro costo'] ?></td>
                                            <td data-label="Cargo"><?php echo $data['Cargo'] ?></td>
                                            <td data-label="Ciudad"><?php echo $data['Ciudad'] ?></td>
                                            <td data-label="Fecha ingreso"><?php echo $data['Fecha de Ingreso'] ?></td>
                                            <td data-label="Estado"><?php echo $data['Estado colaborador'] ?></td>
                                            <td data-label="Nombre Proyecto"><?php echo $data['Nombre del proyecto'] ?></td>
                                            <td data-label="Numero orden"><?php echo $data['Número de orden'] ?></td>
                                            <td data-label="Estado Orden"><?php echo $data['Estado de orden'] ?></td>
                                            <td data-label="Cantidad de Puntos"><?php echo $data['Cantidad de puntos'] ?></td>
                                            <td data-label="Puntos Asigandos"><?php echo $data['Puntos asigandos'] ?></td>
                                            <td data-label="Puntos Actuales"><?php echo $data['Puntos actuales'] ?></td>
                                            <td data-label="Frecuencia"><?php echo $data['Frecuencia de Puntos'] ?></td>
                                            <td data-label="Fecha inicio"><?php echo $data['Fecha inicio'] ?></td>
                                            <td data-label="Fecha fin"><?php echo $data['Fecha fin'] ?></td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Puntos asignados:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                </div>
            </div>
        </main>
    </div>


    <!-- ==========Dashboard============== -->
    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/dashboard/activarPrinicpalLateral_historial.js"></script>

    <!-- =====Datatable=== -->

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
    <script src="../../../script/historial/datatable.js"></script>

    <!-- =========botonesDataTABLE======= -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>





</body>

</html>