<?php
require_once("../../config/conexion.php"); // Reemplaza "conexion.php" con la ubicación correcta de tu archivo de conexión
session_start();

if (isset($_SESSION['rol']) && $_SESSION['rol'] != 1) {
    header(("location: ../dashboard/dashboard.php"));
}

try {
    $query_cedula = $conexion->prepare("SELECT cedula, nombre_colaborador, correo FROM colaboradores WHERE id_estado_colabora = 1");
    $query_cedula->execute();
    $result_cedula = $query_cedula->fetchAll(PDO::FETCH_ASSOC);


    $query_ceco = $conexion->prepare("SELECT id_ceco, nombre_ceco FROM ceco");
    $query_ceco->execute();
    $result_ceco = $query_ceco->fetchAll(PDO::FETCH_ASSOC);


    $query_rol = $conexion->prepare("SELECT * FROM rol");
    $query_rol->execute();
    $result_rol = $query_rol->fetchAll(PDO::FETCH_ASSOC);


    $query_user = $conexion->prepare("SELECT us.id, us.nombre_colaborador, ce.id_ceco, rl.nombre_rol, us.correo, us.usuario 
    FROM usuarios us
    JOIN rol rl ON us.id_rol = rl.id_rol
    JOIN ceco ce ON us.proyecto = ce.id_ceco");
    $query_user->execute();
    $result_user = $query_user->fetchAll(PDO::FETCH_ASSOC); // Corregir $query_rol por $query_user


} catch (PDOException $e) {
    echo "Error al ejecutar la consulta: " . $e->getMessage();
    exit();
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


    <title>usuario</title>

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
                    <img src="../../../assets/icons/user.svg" alt="Imagen para editar" class="contCreatecontract-info__image">
                    <h1 class="contCreatecontract-info__title">Crear nuevo usuario</h1>
                </div>
                <form class="contentInfo-inputs" id="register_user">
                    <div class="inputGroup">
                        <select name="cedula" id="cedula" class="cedula select2">
                            <option value="">Identificación del colaborador</option>
                            <?php
                            foreach ($result_cedula as $colaborador) {
                            ?>
                                <option value="<?php echo $colaborador['cedula'];?>" data-nombre="<?php echo $colaborador['nombre_colaborador']; ?>" data-email="<?php echo $colaborador['correo']; ?>"><?php echo $colaborador['cedula'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="inputGroup">
                        <div class="inputGroup">
                            <input type="text" id="colaborador" name="colaborador" required="" autocomplete="off" class="colaborador" disabled>
                        </div>
                    </div>

                    <div class="inputGroup">
                        <div class="inputGroup">
                            <input type="text" id="email" name="email" required="" autocomplete="off" class="email" disabled>
                        </div>
                    </div>

                    <div class="inputGroup">
                        <select name="proyecto" id="proyecto" class="proyecto select2">
                            <option value="">Seleccionar ceco</option>
                            <?php
                            foreach ($result_ceco as $ceco) {
                            ?>
                                <option value="<?php echo $ceco['id_ceco']; ?>"><?php echo $ceco['nombre_ceco'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="inputGroup">
                        <select name="rol" id="rol" class="rol select2">
                            <option value="">Seleccionar rol</option>
                            <?php
                            foreach ($result_rol as $rol) {
                            ?>
                                <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre_rol'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <input type="text" name="usuario" required="" autocomplete="off" class="usuario">
                        <label for="tipeContrat">Ingresar usuario</label>
                    </div>
                    <div class="inputGroup">
                        <input type="password" required="" autocomplete="off" class="password">
                        <label for="tipeContrat">*********</label>
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
                    <table class=" ui celled table responsive-table" id="example">
                        <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th>Ceco</th>
                                <th>Rol</th>
                                <th>Correo</th>
                                <th>Usuario</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($result_user) > 0) {
                                foreach ($result_user as $data) {
                            ?>
                                    <tr>
                                        <td data-label="Colaborador"><?php echo $data['nombre_colaborador']; ?></td>
                                        <td data-label="Proyecto"><?php echo $data['id_ceco']; ?></td>
                                        <td data-label="Rol"><?php echo $data['nombre_rol']; ?></td>
                                        <td data-label="Correo"><?php echo $data['correo']; ?></td>
                                        <td data-label="Usuario"><?php echo $data['usuario']; ?></td>
                                        <td data-label="opciones">
                                            <button class="button type2 edit-button" data-id="<?php echo $data['id']; ?>" data-correo="<?php echo $data['correo']; ?>" data-user="<?php echo $data['usuario']; ?>">
                                                Editar
                                            </button>

                                            <button class="buttonDelete typeDelete" value="<?php echo $data['id'] ?>"></button>
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

            <!-- =======Modal Edit===== -->
            <div id="myModalEdit" class="modalEdit">
                <div class="modal-contentEdit">
                    <span class="close" id="closeModal">&times;</span>
                    <div class="contEdit">
                        <div class="contEdit_info">
                            <img src="../../../assets/icons/edit-3.svg" alt="">
                            <p>Editar Usuario</p>
                        </div>
                        <form class="contentInfo-inputs" id="update_user">
                            <input type="hidden" id="id_user" value="<?php echo $data['id']; ?>">
                            <div class="inputGroup">
                                <input type="text" name="email" id="emailUp" required="" autocomplete="off" class="email">
                            </div>

                            <div class="inputGroup">
                                <select name="proyecto" id="proyectoUp" class=" select2">
                                    <option value="">Seleccionar ceco</option>
                                    <?php
                                    foreach ($result_ceco as $ceco) {
                                    ?>
                                        <option value="<?php echo $ceco['id_ceco']; ?>"><?php echo $ceco['id_ceco']; ?> - <?php echo $ceco['nombre_ceco']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="inputGroup">
                                <select name="rol" id="rolUp" class=" select2">
                                    <option value="">Seleccionar rol</option>
                                    <?php
                                    foreach ($result_rol as $rol) {
                                    ?>
                                        <option value="<?php echo $rol['id_rol']; ?>"><?php echo $rol['nombre_rol']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="inputGroup">
                                <input type="text" name="usuario" id="usuarioUp" required="" autocomplete="off" class="usuario">
                                <label for="tipeContrat">Ingresar usuario</label>
                            </div>
                            <div class="inputGroup">
                                <input type="password" required="" autocomplete="off" class="password" id="contrasena">
                                <label for="tipeContrat">*********</label>
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
            </div>

            <!-- ===========Modal alert eliminar ceco=========== -->
            <div id="confirmModal" class="modal">
                <div class="modal-content">
                    <input type="hidden" value="<?php echo $data['id']; ?>">
                    <h2>Confirmar eliminación</h2>
                    <p>¿Estás seguro de que quieres eliminar este dato?</p>
                    <div class="conbutton">
                        <button id="cancelBtn">Cancelar</button>
                        <button id="deleteBtn" value="<?php echo $data['id'] ?>">Eliminar</button>
                    </div>
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
    <script src="../../../script/usuario/activarNav.js"></script>
    <script src="../../../script/usuario/autoconpletNameUser.js"></script>
    <script src="../../../script/usuario/crearUsuario.js"></script>

    <script src="../../../script/usuario/update_delete_modal .js"></script>


    <!-- =====Datatable=== -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.js"></script>
    <script src="../../../script/usuario/datatable.js"></script>

    <!-- =========botonesDataTABLE======= -->
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