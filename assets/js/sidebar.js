// Este script maneja la visibilidad del sidebar al hacer clic en el menú.
// Añade o quita la clase CSS 'menu-toggle' para mostrar u ocultar el sidebar.
const menu = document.getElementById('menu');
const sidebar = document.getElementById('sidebar');
const main = document.getElementById('main');

menu.addEventListener('click',()=>{
    sidebar.classList.toggle('menu-toggle');
    menu.classList.toggle('menu-toggle');
    main.classList.toggle('menu-toggle');
});

function loadContent(path) {
    fetch(path)
        .then(res => res.text())
        .then(html => {
            document.getElementById('main').innerHTML = html;
            if (path === '/goods') {
                iniciarBusqueda(); 
            }
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