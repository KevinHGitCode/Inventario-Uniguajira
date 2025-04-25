<header>
    <div class="left">
        <div class="menu-container">
            <div class="menu" id="menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="brand">
            <img
                src="assets/images/logo-uniguajira-blanco.webp"
                alt="icon-udemy"
                class="logo"
            />
        </div>
    </div>
    <div class="right">
        <!-- Iconos desactivados (Mantener en el codigo) -->
        <!-- <a href="#" class="icons-header">
            <img src="assets/icons/chat.svg" alt="chat" />
        </a>
        <a href="#" class="icons-header">
            <img src="assets/icons/question.svg" alt="question" />
        </a>
        <a href="#" class="icons-header">
            <img src="assets/icons/notification.svg" alt="notification" />
        </a> -->

        <img
            src="<?=htmlspecialchars($_SESSION['user_img'] ?? 'assets/uploads/img/users/defaultProfile.jpg')?>"
            alt="img-user"
            class="user"
            onclick="toggleUserMenu()"
        />
        
    </div>
</header>

<!-- TODO: Implementar las funciones editProfile y logout -->
<div class="user-menu">
    <div id="userMenu" class="user-menu-content hidden">
        <button class="user-menu-item" onclick="toggleUserMenu(); loadContent('/profile');">
            <img
                class="user-menu-icon"
                src="assets/icons/editarPerfil.svg"
                alt="edit"
            />
            <span>Editar Perfil</span>
        </button>

        <button class="user-menu-item" onclick="mostrarModal('#modalCambiarContraseña')">
            <img
                class="user-menu-icon"
                src="assets/icons/cambiarContraseña.svg"
                alt="logout"
            />
            <span>Cambiar Contraseña</span>
        </button>

        <button class="user-menu-item" onclick="logout()">
            <img
                class="user-menu-icon"
                src="assets/icons/cerrarSesion.svg"
                alt="logout"
            />
            <span>Cerrar Sesión</span>
        </button>
    </div>

</div>
