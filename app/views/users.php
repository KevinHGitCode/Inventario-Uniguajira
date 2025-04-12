
<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container">
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
        <button id="btnCrear" class="create-btn">Crear</button>
    </div>

    <!-- Modal -->
    <div id="modalCrear" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nuevo Usuario</h2>
            <form
            
                method="POST"
                id="formCrearUser"
                enctype="multipart/form-data"
                action="/api/users/create"
            >

            
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" required />
                </div>
                
                <div>
                    <label>Nombre de Usuario:</label>
                    <input type="text" name="nombre_usuario" required />
                </div>
                <div>
                    <label>Email:</label>
                    <input type="text" name="email" required />
                </div>
                <div>
                    <label>Contraseña:</label>
                    <input type="password" name="contraseña" required />
                </div>
                <div>
                    <label>Rol:</label>
                    <select name="rol" required>
                        <option value= "1" >Admin</option>
                        <option value= "2" >User</option>
                    </select>
                </div>


                <div style="margin-top: 10px">
                    <button type="submit" class="create-btn">Guardar</button>
                </div>
            </form>
        </div>
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

<script src="/assets/js/user.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa la funcionalidad de búsqueda de usuarios
        iniciarBusquedaUser();

        // Inicializa el modal para crear usuarios
        inicializarModalUser();

        // Inicializa los botones de eliminación de usuarios
        inicializarBotonesEliminarUser();
    });
</script>
