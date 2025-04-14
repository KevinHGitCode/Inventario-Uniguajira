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

function editProfile() {

    var menu = document.getElementById('userMenu');
    if (menu) {
        menu.classList.add('hidden');
    }
    // Redirigir a la página de edición de perfil
    loadContent('/profile'); 
    
}

// Cerrar el menú al hacer clic fuera de él
document.addEventListener('click', function (event) {
    const userMenu = document.getElementById('userMenu');
    const userImage = document.querySelector('.user');

    // Si el menú está visible y el clic no fue dentro del menú ni sobre la imagen del usuario
    if (userMenu && !userMenu.classList.contains('hidden') &&
        !userMenu.contains(event.target) && !userImage.contains(event.target)) {
        userMenu.classList.add('hidden');
    }
});