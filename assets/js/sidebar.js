// Este script maneja la visibilidad del sidebar al hacer clic en el menú.
// Añade o quita la clase CSS 'menu-toggle' para mostrar u ocultar el sidebar.
const menu = document.getElementById('menu');
const sidebar = document.getElementById('sidebar');
const main = document.getElementById('main');

// Función para verificar si es móvil
const isMobile = () => window.matchMedia('(max-width: 500px)').matches;

// Función para alternar el menú
const toggleMenu = () => {
    sidebar.classList.toggle('menu-toggle');
    menu.classList.toggle('menu-toggle');
    main.classList.toggle('menu-toggle');
};

// Evento del menú (toggle)
menu.addEventListener('click', (e) => {
    e.stopPropagation(); // Evita que el evento se propague al documento
    toggleMenu();
});

// Cerrar menú al hacer clic fuera (solo en móviles)
document.addEventListener('click', (e) => {
    if (isMobile() && sidebar.classList.contains('menu-toggle')) {
        // Verifica si el clic fue fuera del sidebar y del botón del menú
        if (!sidebar.contains(e.target) && e.target !== menu && !menu.contains(e.target)) {
            toggleMenu();
        }
    }
});

// Cerrar sidebar al hacer clic en opciones (solo móvil)
document.querySelectorAll('#sidebar a').forEach(link => {
    link.addEventListener('click', () => {
        if (isMobile() && sidebar.classList.contains('menu-toggle')) {
            toggleMenu();
        }
    });
});

// Opcional: Prevenir el cierre al hacer clic dentro del sidebar
sidebar.addEventListener('click', (e) => {
    e.stopPropagation();
});

function loadContent(path) {
    fetch(path)
    .then(res => res.text())
    .then(html => {
        document.getElementById('main-content').innerHTML = html;

        // TODO: convertir a onclick
        activarModalActualizarContraseña();
        inicializarFormularioActualizarContraseña();

        if (path === '/goods') {
            iniciarBusqueda('searchGood');
            inicializarModalBien();
            inicializarFormularioBien();
            inicializarBotonesEliminar();
            activarModalActualizarBien();
            inicializarFormularioActualizarBien();
        }
        if (path === '/inventory') {
            iniciarBusqueda('searchGroup');
        }
        if (path === '/users') {
            activarBusquedaEnTabla();
            inicializarModalUser();
            inicializarCrearUsuario();
            inicializarBotonesEliminar();   
        }
        if (path === '/profile') {
            inicializarFormularioEditarPerfil();
            inicializarModalEditUser();
        }

        // Hacer scroll hacia arriba
        window.scrollTo(0, 0);
    });
}


// asignar evento click a las etiquetas <a> del sidebar
// para asignar la clase selected al elemento clickeado
// y eliminar la clase selected de los demás elementos
const links = document.querySelectorAll('.sidebar a');
links.forEach(link => {
    link.addEventListener('click', () => {
        links.forEach(l => l.classList.remove('selected'));
        link.classList.add('selected');

        // guardar la ruta del elemento seleccionado en localStorage
        const path = link.getAttribute('onclick');
        console.log(path)
        if (path) {
            localStorage.setItem('lastSelected', path);
        }
    });
});

// cuando se carga la pagina, cargar la última opción seleccionada
// o hacer click en el elemento con id home si no hay nada guardado
window.onload = () => {
    const lastSelected = localStorage.getItem('lastSelected');
    if (lastSelected) {
        const matchingLink = Array.from(links).find(link => link.getAttribute('onclick') === lastSelected);
        if (matchingLink) {
            console.log(`Cargando el elemento guardado: ${lastSelected}`);
            matchingLink.click();
        }
    } else {
        const homeElement = document.getElementById('home');
        if (homeElement) {
            console.log('No hay elemento guardado, cargando el elemento con id "home".');
            homeElement.click();
        }
    }
};