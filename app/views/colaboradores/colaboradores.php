<?php
session_start();

include '../../config/conexion.php';

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--Fonts-->
    <?php include '../font/font.php'; ?>

    <link rel="stylesheet" href="../../../style/general.css">

    <style>
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

    <title>Colaboradores</title>

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
                    <div class="contCreatecontract-title">
                        <img src="../../../assets/icons/user.svg" alt="Imagen para editar" class="contCreatecontract-info__image">
                        <h1 class="contCreatecontract-info__title">Crear nuevo Colaborador</h1>
                    </div>

                    <a href="listaColaboradores.php" class="BtnCon" type="submit">
                        <span class="IconContainer">
                            <img src="../../../assets/icons/eye.svg" alt="iconSearch">
                        </span>
                        <p class="text">Lista colaboradores</p>
                    </a>
                </div>

                <form class="contentInfo-inputs" id="formularioColaborador" method="post">
                    <div class="inputGroup">
                        <input type="number" name="cedula" id="cedula" autocomplete="off" class="cedulaCol">
                        <label for="tipeContrat">Numero de documento</label>
                    </div>
                    <div class="inputGroup">
                        <input type="text" name="name" id="name" autocomplete="off" class="nameCol">
                        <label for="tipeContrat">Nombre colaborador</label>
                    </div>
                    <div class="inputGroup">
                        <input type="email" name="email" id="email" autocomplete="off" class="emailCol">
                        <label for="tipeContrat">Correo electrónico</label>
                    </div>

                    <!-- Selecionar centro de costo -->
                    <div class="inputGroup">
                        <select name="ceco" id="ceco" class="cecoCol select2">
                            <option value="">Ceco</option>
                            <?php
                            $query_ceco = $conexion->query("SELECT * FROM ceco");
                            while ($ceco = $query_ceco->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <option value="<?php echo $ceco['id_ceco']; ?>"><?php echo $ceco['id_ceco']; ?> - <?php echo $ceco['nombre_ceco']; ?> </option>
                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>

                    <!--Selecionar cargos-->
                    <div class="inputGroup">
                        <select name="cargo" id="cargo" class="cargoCol select2">
                            <option value="">Cargo</option>
                            <?php
                            $query_cargo = $conexion->query("SELECT * FROM cargos");
                            while ($cargo = $query_cargo->fetch(PDO::FETCH_ASSOC)) :
                            ?>

                                <option value="<?php echo $cargo['nombre_cargo']; ?>"> <?php echo $cargo['nombre_cargo']; ?> </option>

                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                    <!--seleccionar contrato -->
                    <div class="inputGroup">
                        <select name="contrato" id="contrato" class="contrato select2">
                            <option value="">Tipo de contrato</option>
                            <?php
                            $query_contrato = $conexion->query("SELECT * FROM tipo_contrato");
                            while ($contrato = $query_contrato->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <option value="<?php echo $contrato['id_tipo_contrato']; ?>"><?php echo $contrato['nombre_tipo_contrato']; ?></option>
                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                    <!--seleccionar ciudad-->
                    <div class="inputGroup">
                        <select name="ciudad" id="ciudad" class="ciudad select2">
                            <option value="">Ciudad</option>
                            <?php
                            $query_ciudad = $conexion->query("SELECT * FROM ciudades");
                            while ($ciudad = $query_ciudad->fetch(PDO::FETCH_ASSOC)) :
                            ?>

                                <option value="<?php echo $ciudad['nombre_ciudad']; ?>"> <?php echo $ciudad['nombre_ciudad']; ?> </option>

                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <input type="text" name="direccion" id="direccion" autocomplete="off" class="direccionCol">
                        <label for="tipeContrat">Dirección</label>
                    </div>
                    <!--seleccionar proyecto-->
                    <div class="inputGroup">
                        <select name="proyecto" id="proyecto" class="proyectCol select2">
                            <option value="">Proyecto</option>
                            <?php
                            $query_proyecto = $conexion->query("SELECT * FROM proyectos");
                            while ($proyecto = $query_proyecto->fetch(PDO::FETCH_ASSOC)) :
                            ?>

                                <option value="<?php echo $proyecto['nombre_proyecto']; ?>"> <?php echo $proyecto['nombre_proyecto']; ?> </option>

                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <p>Fecha de ingreso</p>
                        <input type="date" name="fechaIngreso" id="fechaIngreso" autocomplete="off">
                    </div>
                    <div class="inputGroup">
                        <textarea name="observaciones" id="observaciones" cols="30" rows="10"></textarea>
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
       
        <!-- Alertas -->
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
                        <p class="descripcion">La operación fue exitosa.</p>
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
            <div class="toast error" id="2" style="display: none;">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Error!</p>
                        <p class="descripcion">Hubo un error al intentar procesar la operación.</p>
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

            <div class="toast warning" id="3" style="display: none;">
                <div class="contenido">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                    </div>
                    <div class="texto">
                        <p class="titulo">Advertencia</p>
                        <p class="descripcion">Alguno de los campos está vacio.</p>
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
                        <p class="descripcion">El colaborador ya se encuentra creado en el sistema</p>
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


    <script src="../../../script/dashboard/activarMenuLateral.js"></script>
    <script src="../../../script/dashboard/activarMenuHeader.js"></script>
    <script src="../../../script/colaborador/colaborador.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../../script/colaborador//guardarColaborador.js"></script>


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