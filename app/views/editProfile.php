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
                    onclick="mostrarModal('#modalEditarPerfil')"
                    class="create-btn">
                    Editar
                </button>
            </div>
        </div>
    </div>
</div>


