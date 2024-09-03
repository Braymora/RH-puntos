
<div class="header">
    <button class="headerLogo">
        <img src="../../../assets/icons/chevrons-left.svg" alt="icono menu" class="headerLogoActive">
        <img src="../../../assets/icons/menu.svg" alt="icono menu" class="headerLogoMenu">
    </button>
    <div class="headerLogo-profile">

        <!-- <img src="../../../assets/image/profile.svg" alt="Logo usuario" class="headerLogo-profile__logo"> -->
        <span>¡Bienvenido!...</span>
        <span class="headerLogo-profile__name"><?php echo $_SESSION['nombre']; ?></span>

        <div class="iconmenu">
            <img src="../../../assets/icons/chevron-down.svg" alt="Inono menú">
            <ul class="headerLogo-profile__lista">
                <li class="headerLogo-profile__listaItems"><img src="../../../assets/icons/settings.svg" alt="Icono configuración" class="headerLogo-profile__items"><a href="#modal1">Configurar</a>
                </li>
                <li class="headerLogo-profile__listaItems"><img src="../../../assets/icons/user.svg" alt="Icono perfil" class="headerLogo-profile__items"><a href="#modal2">Pefil</a>
                </li>
                <li class="headerLogo-profile__listaItems"><img src="../../../assets/icons/log-out.svg" alt="Icono para cerra sesión" class="headerLogo-profile__items"><a href="../../includes/cerrar_Sesion/cerrarSesion.php">Salir</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--=====Modal Setting password=====-->
<div id="modal1" class="modalmask">
    <div class="modalbox movedown">
        <a href="#close" title="Close" class="close">X</a>
        <h2>Cambiar contraseña</h2>
        <div class="contentInfo">
            <img src="../../../assets/image/profile.svg" alt="">
            <form class="contentInfo-inputs" method="post" action="../../includes/updatePassword/update_password.php">
                <input type="hidden" name="iduser" value="<?php echo $_SESSION['idUser']; ?>">
                <div class="inputGroup">
                    <input type="password" name="password" required="" autocomplete="off">
                    <label for="pass">Contraseña actual</label>
                </div>
                <div class="inputGroup">
                    <input type="password" name="newpassword" required="" autocomplete="off">
                    <label for="newPass">Nueva contraseña</label>
                </div>
                <div class="inputGroup">
                    <input type="password" name="confirmpassword" required="" autocomplete="off">
                    <label for="confirPass">Confirmar contraseña:</label>
                </div>
                <button class="BtnCon" type="submit">
                    <span class="IconContainer">
                        <img src="../../../assets/icons/edit-3.svg" alt="iconSearch">
                    </span>
                    <p class="text">Actualizar</p>
                </button>
            </form>
        </div>
    </div>
</div>

<!--=====Modal personal information=====-->
<div id="modal2" class="modalmaskTwo">
    <div class="modalboxTwo movedownTwo">
        <a href="#closeTwo" title="Close" class="closeTwo">X</a>
        <h2>Perfil Usuario</h2>
        <div class="contentInfoTwo">
            <div class="contentInfoTwo_profile">
                <img src="../../../assets/image/profile.svg" alt="">
                <p><?php echo $_SESSION['nombre'] ?></p>
            </div>
            <form class="contentInfo-inputsTwo">
                <div class="contentInfo-inputsTwo__items">
                    <span>Usuario:</span>
                    <p><?php echo $_SESSION['user'] ?></p>
                </div>
                <div class="contentInfo-inputsTwo__items">
                    <span>Correo:</span>
                    <p><?php echo $_SESSION['email'] ?></p>
                </div>
                <div class="contentInfo-inputsTwo__items">
                    <span>Rol:</span>
                    <p><?php echo $_SESSION['rol_name'] ?></p>
                </div>
            </form>
        </div>
    </div>
</div>

