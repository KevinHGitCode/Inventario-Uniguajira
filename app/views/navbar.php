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
        <button class="user-menu-item" onclick="editProfile()">
            <img
                class="user-menu-icon"
                src="assets/icons/editarPerfil.svg"
                alt="edit"
            />
            <span>Editar Perfil</span>
        </button>

        <button class="user-menu-item" id="btnCambiarContraseña">
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

    <!-- Modal para cambiar la contraseña -->
    <div id="modalCambiarContraseña" class="modal" style="display: none">
        <div class="modal-content">
            <span id="cerrarModalCambiarContraseña" class="close">&times;</span>
            <h2>Cambiar Contraseña</h2>
            <form id="formCambiarContraseña" enctype="multipart/form-data">
                <div>
                    <label>Nueva Contraseña:</label>
                    <input
                        type="password"
                        name="nueva_contraseña"
                        id="ActualizarContraseña"
                        required
                    />
                </div>

                <div>
                    <label>Repita la nueva Contraseña:</label>
                    <input
                        type="password"
                        name="confirmar_contraseña"
                        id="ActualizarContraseñaRepetida"
                        required
                    />
                </div>

                <div>
                    <label>Digite su contraseña actual para confirmar:</label>
                    <input
                        type="password"
                        name="contraseña"
                        id="ContraseñaActual"
                        required
                    />
                </div>

                <div style="margin-top: 10px">
                    <button type="submit" class="create-btn">
                        Guardar Cambios
                    </button>
                </div>
            </form>
            <p style="margin-top: 15px; font-size: 0.9em; color: #555;">
                Nota: Al cambiar la contraseña es necesario que se vuelva a loguear.
            </p>
        </div>
    </div>
</div>
