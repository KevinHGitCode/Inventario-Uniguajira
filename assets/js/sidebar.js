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
        .then(response => response.text())
        .then(html => document.getElementById('main').innerHTML = html)
        .catch(error => console.error('Error:', error));
}

// asignar evento click a las etiquetas <a> del sidebar
// para asignar la clase seleted al elemento clickeado
// y eliminar la clase selected de los demás elementos
const links = document.querySelectorAll('.sidebar a');
links.forEach(link => {
    link.addEventListener('click', () => {
        links.forEach(l => l.classList.remove('selected'));
        link.classList.add('selected');

        // imprimir el atributo on click
        console.log(link.getAttribute('onclick'));
    });
});

// cuando se carga la pagina, hacer click en el elemento con id home
window.onload = () => {
    const homeElement = document.getElementById('home');
    if (homeElement) {
        homeElement.click();
        // console.log("click en home");
    }
};