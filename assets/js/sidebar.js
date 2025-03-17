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

// cuando este cargado el dom clickea la opcion con la clase selected
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.selected').click();
});