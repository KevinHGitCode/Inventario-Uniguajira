<!-- Modal Actualizar -->
<div id="modalEditarPerfil" class="modal">
    <div class="modal-content modal-content-medium">
        <span id="cerrarModalEditarPerfil" onclick="ocultarModal('#modalEditarPerfil')" class="close">&times;</span>
        <h2>Editar Usuario</h2>
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

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Modal para cambiar la contraseña -->
<div id="modalCambiarContraseña" class="modal">
    <div class="modal-content">
        <button id="cerrarModalCambiarContraseña" class="close" onclick="ocultarModal('#modalCambiarContraseña')">&times;</button>
        <h2>Cambiar Contraseña</h2>
        <form 
            id="formCambiarContraseña" 
            action="/api/users/update"
            method="POST" 
            enctype="multipart/form-data"
            autocomplete="off"
        >
            <div>
                <label for="ActualizarContraseña">Nueva Contraseña:</label>
                <input
                    type="password"
                    name="nueva_contraseña"
                    id="ActualizarContraseña"
                    required
                />
            </div>

            <div>
                <label for="ActualizarContraseñaRepetida">Repita la nueva Contraseña:</label>
                <input
                    type="password"
                    name="confirmar_contraseña"
                    id="ActualizarContraseñaRepetida"
                    required
                />
            </div>

            <div>
                <label for="ContraseñaActual">Digite su contraseña actual para confirmar:</label>
                <input
                    type="password"
                    name="contraseña"
                    id="ContraseñaActual"
                    required
                />
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn">Guardar Cambios</button>
            </div>
        </form>
        <p style="margin-top: 15px; font-size: 0.9em; color: #555;">
            Nota: Al cambiar la contraseña es necesario que se vuelva a loguear.
        </p>
    </div>
</div>
