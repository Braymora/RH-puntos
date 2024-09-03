<?php
if (empty($_SESSION['active'])) {
    header('location: ../../../index.php');
}
?>
<nav class="nav">
    <ul class="navList">
        <div class="navconLine">
            <h2 class="navTitle">Puntos Empleados</h2>
            <hr class="navLine">
        </div>
        <li class="navList-items"><img src="../../../assets/icons/home.svg" alt="Incono inicio"><a href="../../../app/views/dashboard/dashboard.php">Dashboard</a></li>
        <li class="navList-items"><img src="../../../assets/icons/clock.svg" alt="Icono historial"><a href="../../../app/views/historial/historial.php">Historial</a></li>
        <li class="navList-items opcionesGestion"><img src="../../../assets/icons/file-text.svg" alt="Icono Gestión"><a href="#">Gestión</a><img src="../../../assets/icons/chevron-down.svg" height="24px" alt="Inono menu" class="opmenuGestion">
            <ul class="navList-itemsListGestion">
                <li class="navList-itemsList__itemsGestion"><a href="../../../app/views/gestion/contrato.php">Crear
                        contrato</a></li>
                <li class="navList-itemsList__itemsGestion"><a href="../gestion/servicios.php">Crear
                        Servicio</a></li>
            </ul>
        </li>
        <?php
        if ($_SESSION['rol'] == 1) {
        ?>
            <li class="navList-items"><img src="../../../assets/icons/user.svg" alt="Icono usuarios"><a href="../../views/usuarios/usuarios.php">Usuarios</a></li>
        <?php } ?>
        <li class="navList-items"><img src="../../../assets/icons/users.svg" alt="Icono Colaboradores"><a href="../../views/colaboradores/colaboradores.php">Colaboradores</a></li>
        <li class="navList-items opciones"><img src="../../../assets/icons/list.svg" alt="Icono Opciones"><a href="#">Opciones</a><img src="../../../assets/icons/chevron-down.svg" alt="Inono menu" class="opmenu">
            <ul class="navList-itemsList">
                <li class="navList-itemsList__items"><a href="../centro_de _costo/ceco.php">Centros de costos</a></li>
                <li class="navList-itemsList__items"><a href="../cargo/cargo.php">Cargos</a></li>
                <li class="navList-itemsList__items"><a href="../ciudad/ciudad.php">Ciudades</a></li>
                <li class="navList-itemsList__items"><a href="../proyectos/proyectos.php">Proyectos</a></li>
                <li class="navList-itemsList__items"><a href="../tipos_contrato/tipo_contrato.php">Tipos de contratos</a></li>
            </ul>
        </li>
    </ul>
</nav>