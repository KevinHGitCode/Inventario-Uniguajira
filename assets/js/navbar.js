/*Animacion al cliquear el boton de Menu de usuario*/
function toggleUserMenu() {
    var menu = document.getElementById('userMenu');
    if (menu) {
        menu.classList.toggle('hidden'); // Usar una sola clase para mostrar/ocultar
    } else {
        console.error('El elemento con ID "userMenu" no existe.');
    }
}

function logout() {
    window.location.href = '/api/logout'; // Redirigir al endpoint correcto del backend
}