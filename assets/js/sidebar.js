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