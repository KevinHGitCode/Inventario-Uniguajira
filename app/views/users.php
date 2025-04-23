<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container">
    <?php echo $_SESSION['timezone_offset'] ?>
    <?php echo $_SESSION['timezone_name'] ?>
    <h2>Lista de Usuarios</h2>

    <div class="top-bar">
        <div class="search-container">
            <input
                type="text"
                id="searchUserInput"
                placeholder="Buscar o agregar usuario"
                class="search-bar"
            />
            <i class="search-icon fas fa-search"></i>
        </div>
        <button
            id="btnCrear"
            class="create-btn"
            onclick="mostrarModal('#modalCrear')"
        >
            Crear
        </button>
    </div>

    <div>

        <!-- 
        SELECTORES CSS:

        .table
        .table-bordered
        .actions-cell
        .dropdown
        .dropdown-btn
        .dropdown-content
        .edit-user
        .delete-user

        .fas
        .fa-ellipsis-v
        .fa-edit
        .fa-trash-alt
        -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Nombre Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha Creación</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (isset($dataUsers)): ?>
                <?php foreach ($dataUsers as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($user['nombre_usuario']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['rol']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($user['fecha_creacion']); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($user['fecha_ultimo_acceso']); ?>
                    </td>
                    <td class="actions-cell">
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-content">
                                <a
                                    class="edit-user"
                                    data-id="<?= $user['id']; ?>"
                                    data-nombre="<?= htmlspecialchars($user['nombre']); ?>"
                                    data-nombre-usuario="<?= htmlspecialchars($user['nombre_usuario']); ?>"
                                    data-email="<?= htmlspecialchars($user['email']); ?>"
                                    data-rol="<?= htmlspecialchars($user['rol']); ?>"
                                    onclick="btnEditarUser(this)"
                                >
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a
                                    class="delete-user"
                                    onclick="mostrarConfirmacion(<?= $user['id']; ?>)"
                                >
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6">No hay usuarios disponibles.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div></div>
    </div>

    <!-- ====================================================== -->
    <!-- ============== MODALES DE USER.PHP =================== -->
    <!-- ====================================================== -->
    
    <!-- Modal crear usuario-->
    <div id="modalCrear" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close" onclick="ocultarModal('#modalCrear')"
                >&times;</span
            >
            <h2>Nuevo Usuario</h2>
            <form
                id="formCrearUser"
                action="/api/users/create"
                method="POST"
                enctype="multipart/form-data"
                autocomplete="off"
            >
                <div>
                    <label for="crear-nombre">Nombre:</label>
                    <input
                        type="text"
                        name="nombre"
                        id="crear-nombre"
                        required
                    />
                </div>

                <div>
                    <label for="crear-nombre_usuario">Nombre de Usuario:</label>
                    <input
                        type="text"
                        name="nombre_usuario"
                        id="crear-nombre_usuario"
                        required
                    />
                </div>
                <div>
                    <label for="crear-email">Email:</label>
                    <input type="text" name="email" id="crear-email" required />
                </div>
                <div>
                    <label for="crear-contraseña">Contraseña:</label>
                    <input
                        type="password"
                        name="contraseña"
                        id="crear-contraseña"
                        required
                    />
                </div>
                <div>
                    <label for="crear-rol">Rol:</label>
                    <select name="rol" id="crear-rol" required>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                </div>

                <div style="margin-top: 10px">
                    <button type="submit" class="create-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div id="modalEditar" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
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

                <div style="margin-top: 10px">
                    <button type="submit" class="edit-btn">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmar Eliminación -->
    <div id="modalConfirmarEliminar" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirmar Eliminación</h2>
            <p>
                ¿Estás seguro de que deseas eliminar este usuario? Esta acción
                no se puede deshacer.
            </p>
            <input type="hidden" id="delete-user-id" />
            <div>
                <button
                    id="btnCancelarEliminar"
                    class="cancel-btn"
                    onclick="ocultarModal('#modalConfirmarEliminar')"
                >
                    Cancelar
                </button>
                <button
                    id="btnConfirmarEliminar"
                    class="delete-btn"
                    data-id=""
                    onclick="eliminarUser(this)"
                >
                    Eliminar
                </button>
            </div>
        </div>
    </div>

</div>
