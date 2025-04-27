<!-- ====================================================== -->
<!-- ============== MODALES DE USER.PHP =================== -->
<!-- ====================================================== -->

<!-- Modal crear usuario-->
<div id="modalCrearUsuaio" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearUsuaio')">&times;</span>
        <h2>Nuevo Usuario</h2>
        <form
            id="formCrearUser"
            action="/api/users/create"
            method="POST"
            enctype="multipart/form-data"
            autocomplete="off"
            class="form-grid"
        >
            <div class="form-row">
                <div class="form-column">
                    <label for="crear-nombre">Nombre:</label>
                    <input
                        type="text"
                        name="nombre"
                        id="crear-nombre"
                        required
                    />
                </div>

                <div class="form-column">
                    <label for="crear-nombre_usuario">Nombre de Usuario:</label>
                    <input
                        type="text"
                        name="nombre_usuario"
                        id="crear-nombre_usuario"
                        required
                    />
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <label for="crear-email">Email:</label>
                    <input type="text" name="email" id="crear-email" required />
                </div>
                <div class="form-column">
                    <label for="crear-contraseña">Contraseña:</label>
                    <input
                        type="password"
                        name="contraseña"
                        id="crear-contraseña"
                        required
                    />
                </div>
            </div>

            <div class="form-row">
                <div class="form-column">
                    <label for="crear-rol">Rol:</label>
                    <select name="rol" id="crear-rol" required>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modalEditarUsuario" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalEditarUsuario')">&times;</span>
        <h2>Editar Usuario</h2>
        <form
            method="POST"
            id="formEditarUser"
            enctype="multipart/form-data"
            action="/api/users/edit"
            autocomplete="off"
        >
            <input type="hidden" name="id" id="edit-id" />
            <div>
                <label for="edit-nombre">Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="edit-nombre"
                    required
                />
            </div>

            <div>
                <label for="edit-nombre_usuario">Nombre de Usuario:</label>
                <input
                    type="text"
                    name="nombre_usuario"
                    id="edit-nombre_usuario"
                    required
                />
            </div>
            <div>
                <label for="edit-email">Email:</label>
                <input type="text" name="email" id="edit-email" required />
            </div>
            <!-- <div>
                <label for="edit-contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="edit-contraseña" placeholder="Dejar en blanco para mantener la actual" />
            </div>
            <div>
                <label for="edit-rol">Rol:</label>
                <select name="rol" id="edit-rol" required>
                    <option value= "1" >Admin</option>
                    <option value= "2" >User</option>
                </select>
            </div> -->

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmar Eliminación -->
<div id="modalConfirmarEliminar" class="modal">
    <div class="modal-content modal-content-small">
        <span class="close" onclick="ocultarModal('#modalConfirmarEliminar')">&times;</span>
        <h2>Confirmar Eliminación</h2>
        <p>
            ¿Estás seguro de que deseas eliminar este usuario? Esta acción
            no se puede deshacer.
        </p>
        <input type="hidden" id="delete-user-id" />
        <div class="form-actions">
            <button
                id="btnCancelarEliminar"
                class="btn cancel-btn"
                onclick="ocultarModal('#modalConfirmarEliminar')"
            >
                Cancelar
            </button>
            <button
                id="btnConfirmarEliminar"
                class="btn submit-btn"
                data-id=""
                onclick="eliminarUser(this)"
            >
                Eliminar
            </button>
        </div>
    </div>
</div>