
<?php require_once 'app/controllers/sessionCheck.php'; ?>

<style>

<?php include 'assets/css/user.css'; ?>
</style>    

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

    <!-- Modal crear usuario-->
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

<!-- Modal Editar Usuario -->
<div id="modalEditar" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Usuario</h2>
            <form
                method="POST"
                id="formEditarUser"
                enctype="multipart/form-data"
                action="/api/users/update"
            >
                <input type="hidden" name="id" id="edit-id" />
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" id="edit-nombre" required />
                </div>
                
                <div>
                    <label>Nombre de Usuario:</label>
                    <input type="text" name="nombre_usuario" id="edit-nombre_usuario" required />
                </div>
                <div>
                    <label>Email:</label>
                    <input type="text" name="email" id="edit-email" required />
                </div>
                <!-- <div>
                    <label>Contraseña:</label>
                    <input type="password" name="contraseña" id="edit-contraseña" placeholder="Dejar en blanco para mantener la actual" />
                </div>
                <div>
                    <label>Rol:</label>
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
            <p>¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.</p>
            <input type="hidden" id="delete-user-id">
            <div style="margin-top: 20px; display: flex; justify-content: space-between;">
                <button id="btnCancelarEliminar" class="cancel-btn">Cancelar</button>
                <button id="btnConfirmarEliminar" class="delete-btn">Eliminar</button>
            </div>
        </div>
    </div>

 <div>
 <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Rol</th>
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
                <td class="actions-cell">
                    <div class="dropdown">
                        <button class="dropdown-btn"><i class="fas fa-ellipsis-v"></i></button>
                        <div class="dropdown-content">
                            <a href="#" class="edit-user" data-id="<?php echo $user['id']; ?>" 
                               data-nombre="<?php echo htmlspecialchars($user['nombre']); ?>"
                               data-nombre-usuario="<?php echo htmlspecialchars($user['nombre_usuario']); ?>"
                               data-email="<?php echo htmlspecialchars($user['email']); ?>"
                               data-rol="<?php echo htmlspecialchars($user['rol']); ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="#" class="delete-user" data-id="<?php echo $user['id']; ?>">
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
 <div>
   
</div>



      



<!-- <script src="/assets/js/user.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa la funcionalidad de búsqueda de usuarios
        iniciarBusquedaUser();

        // Inicializa el modal para crear usuarios
        inicializarModalUser();

        // Inicializa el modal para editar usuarios
        inicializarBotonesEdicion();

        // Inicializa los botones de eliminación de usuarios
        inicializarBotonesEliminarUser();
    });
</script> -->
