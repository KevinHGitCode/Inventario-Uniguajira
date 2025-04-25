
<!-- Modal Actualizar -->
<div id="modalEditarPerfil" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalEditarPerfil" onclick="ocultarModal('#modalEditarPerfil')" class="close"> &times;</span>
        <h2>Editar Usuario</h2>
        </h2>
        <form 
            id="formEditarPerfil" 
            action="/api/users/editProfile" 
            method="POST" 
            enctype="multipart/form-data"
            autocomplete="off"
        >
            <input type="hidden" name="id" id="Id_Usuario" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" />

            <div>
                <label for="actualizarNombre">Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    value="<?= htmlspecialchars($_SESSION['user_name']) ?>"
                    id="actualizarNombre"
                    required
                />
            </div>

            <div>
                <label for="actualizarNombreUsuario">Nombre Usuario:</label>
                <input
                    type="text"
                    name="nombre_usuario"
                    value="<?= htmlspecialchars($_SESSION['user_username']) ?>"
                    id="actualizarNombreUsuario"
                    required
                />
            </div>

            <div>
                <label for="actualizarEmail">Email:</label>
                <input
                    type="text"
                    name="email"
                    value="<?= htmlspecialchars($_SESSION['user_email']) ?>"
                    id="actualizarEmail"
                    required
                />
            </div>

            <div>
                <label for="actualizarImagen">Imagen (opcional):</label>
                <input
                    type="file"
                    name="imagen"
                    id="actualizarImagen"
                    accept="image/*"
                />
            </div>

            <div>
                <label for="password">Digite su contraseña para confirmar</label>
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