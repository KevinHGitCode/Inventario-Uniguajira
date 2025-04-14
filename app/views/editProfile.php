
<div class="container mt-5">
    <h2 class="mb-4 text-center">Mi perfil</h2>

    <div class="card perfil-card mx-auto p-4 shadow rounded-4">
        <div class="text-center">
            <img
                id="userImage"
                src="<?= htmlspecialchars($_SESSION['user_img'] ?? 'assets/uploads/img/users/defaultProfile.jpg') ?>"
                class="perfil-img img-thumbnail rounded-circle mb-3"
                alt="Foto de perfil"
            >
            <h3 id="userName"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></h3>
            <h5 id="userEmail" class="text-muted"><?= htmlspecialchars($_SESSION['user_email'] ?? 'Email no definido') ?></h5>
            <h5 id="userRol" class="text-muted"><?= htmlspecialchars($_SESSION['user_rol'] ?? 'Rol no definido') ?></h5>

            <button id="btnEditar" class="create-btn">Editar</button>
        </div>
    </div>
</div>


<!-- Modal Actualizar -->
<div id="modalEditarPerfil" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalEditarPerfil" class="close">&times;</span>
        <h2>Editar Usuario</h2>
        </h2>
        <form id="formEditarPerfil" 
                action="/api/users/edit" 
                method="POST" 
                enctype="multipart/form-data">
            <input type="hidden" name="id" id="Id_Usuario" />

            <div>
                <label>Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    value="<?= htmlspecialchars($_SESSION['user_name']) ?>"
                    id="actualizarNombre"
                    required
                />
            </div>

            <div>
                <label>Email:</label>
                <input
                    type="text"
                    name="email"
                    value="<?= htmlspecialchars($_SESSION['user_email']) ?>"
                    id="actualizarEmail"
                    required
                />
            </div>

            <div>
                <label>Imagen (opcional):</label>
                <input
                    type="file"
                    name="imagen"
                    id="actualizarImagen"
                    accept="image/*"
                />
            </div>

            <div>
                <label>Digite su contraseña para confirmar</label>
                <input
                    type="password"
                    name="contraseña"
                    id="password"
                    required
                    placeholder="Contraseña"
                />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
