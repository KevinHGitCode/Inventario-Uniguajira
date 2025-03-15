
/*Animacion al cliquear el boton de Menu de usuario*/
function toggleUserMenu() {
    var menu = document.getElementById('userMenu');
    if (menu.style.display === 'block') {
        menu.style.transition = 'transform 0.2s ease-out, opacity 0.2s ease-out';
        menu.style.transform = 'scale(0.8)';
        menu.style.opacity = 0;
        setTimeout(function() {
        menu.style.display = 'none';
        }, 200);
    } else {
        menu.style.display = 'block';
        menu.style.transform = 'scale(0.8)';
        menu.style.opacity = 0;
        menu.style.transition = 'transform 0.2s ease-in, opacity 0.2s ease-in';
        setTimeout(function() {
        menu.style.transform = 'scale(1)';
        menu.style.opacity = 1;
        }, 10);
    }
}