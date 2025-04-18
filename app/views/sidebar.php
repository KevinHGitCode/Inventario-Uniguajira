<div class="sidebar" id="sidebar">
    <nav>
        <ul class="list-unstyled">
            <li>
                <a
                    id="home"
                    class="selected"
                    onclick="loadContent('/home')"
                >
                    <img src="assets/icons/home.svg" alt="" />
                    <span>Inicio</span>
                </a>
            </li>

            <li>
                <a 
                    onclick="loadContent('/goods')"
                >
                    <img src="assets/icons/bienes.svg" alt="" />
                    <span>Bienes</span>
                </a>
            </li>
            
            <li>
                <a 
                    onclick="loadContent('/inventory')"
                >
                    <img src="assets/icons/inventario.svg" alt="" />
                    <span>Inventarios</span>
                </a>
            </li>
            
            <li>
                <a 
                    onclick="loadContent('/reports')"
                >
                    <img src="assets/icons/reportes.svg" alt="">
                    <span>Reportes</span>
                </a>
            </li>
            
            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <li>
                <a 
                    onclick="loadContent('/users')"
                >
                    <img src="assets/icons/usuarios.svg" alt="" />
                    <span>Usuarios</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Opción comentada que puedes descomentar si es necesario
            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <li>
                <a 
                    onclick="loadContent('app/views/record.php')"
                >
                    <img src="assets/icons/historial.svg" alt="">
                    <span>Historial</span>
                </a>
            </li>
            <?php endif; ?>
            -->
        </ul>
    </nav>
</div>