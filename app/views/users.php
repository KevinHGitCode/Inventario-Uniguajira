<!-- < ?php require_once 'app/controllers/sessionCheck.php'; ?> -->

<div class="container">
    <?php echo $_SESSION['timezone_offset'] . ' ' . $_SESSION['timezone_name'] ?>
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
                    <td><?php echo htmlspecialchars($user['id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['nombre'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['nombre_usuario'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['rol'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['fecha_creacion'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($user['fecha_ultimo_acceso'] ?? ''); ?></td>
                    <td class="actions-cell">
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-content">
                                <a
                                    class="edit-user"
                                    data-id="<?= $user['id'] ?? ''; ?>"
                                    data-nombre="<?= htmlspecialchars($user['nombre'] ?? ''); ?>"
                                    data-nombre-usuario="<?= htmlspecialchars($user['nombre_usuario'] ?? ''); ?>"
                                    data-email="<?= htmlspecialchars($user['email'] ?? ''); ?>"
                                    data-rol="<?= htmlspecialchars($user['rol'] ?? ''); ?>"
                                    onclick="btnEditarUser(this)"
                                >
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a
                                    class="delete-user"
                                    onclick="mostrarConfirmacion(<?= $user['id'] ?? 'null'; ?>)"
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

</div>
