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


