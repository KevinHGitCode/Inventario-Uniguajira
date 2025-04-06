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
    // Eliminar la última opción seleccionada antes de cerrar sesión
    localStorage.removeItem('lastSelected');
    window.location.href = '/api/logout'; // Redirigir al endpoint correcto del backend
}