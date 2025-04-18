<div class="container-perfil">
    <h2 class="titulo-perfil">Mi perfil</h2>
    
    <div class="card-perfil">
        <div class="contenido-perfil">
            <div class="perfil-img">
                <img
                    id="userImage"
                    src="<?=htmlspecialchars($_SESSION['user_img'] ?? 'assets/uploads/img/users/defaultProfile.jpg')?>"
                    alt="Foto de perfil"
                >
            </div>
            <div class="perfil-info">
                <h3 id="userName"><?=htmlspecialchars($_SESSION['user_name'] ?? 'Usuario')?></h3>
                <h4 id="userUsername">@<?=htmlspecialchars($_SESSION['user_username'] ?? 'Usuario no definido')?></h4>
                <h5 id="userEmail"><?=htmlspecialchars($_SESSION['user_email'] ?? 'Email no definido')?></h5>
                <h5 id="userRol"><?=htmlspecialchars($_SESSION['user_rol'] ?? 'Rol no definido')?></h5>
                <button
                    id="btnEditar"
                    onclick="editarPerfil()"
                    class="create-btn">
                    Editar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Actualizar -->
<div id="modalEditarPerfil" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalEditarPerfil" onclick="ocultarModal('#modalEditarPerfil')" class="close"> &times;</span>
        <h2>Editar Usuario</h2>
        </h2>
        <form id="formEditarPerfil" 
                action="/api/users/editProfile" 
                method="POST" 
                enctype="multipart/form-data">
            <input type="hidden" name="id" id="Id_Usuario" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" />

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
                <label>Nombre Usuario:</label>
                <input
                    type="text"
                    name="nombre_usuario"
                    value="<?= htmlspecialchars($_SESSION['user_username']) ?>"
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
