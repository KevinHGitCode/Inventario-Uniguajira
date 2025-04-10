

<div class="container">
    <h2>Lista de Usuarios</h2>

    <div class="top-bar">
        <div class="search-container">
            <input type="text" id="searchuUserInput" placeholder="Buscar o agregar usuario" class="search-bar">
            <i class="search-icon fas fa-search"></i>
        </div>
        <button id="btnCrear" class="create-btn">Crear</button>
    </div>




    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Rol</th>
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
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="5">No hay usuarios disponibles.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
