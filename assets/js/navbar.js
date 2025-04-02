/*Animacion al cliquear el boton de Menu de usuario*/
function toggleUserMenu() {
    var menu = document.getElementById('userMenu');
    menu.classList.toggle('hidden');
    menu.classList.toggle('visible');
}

function logout() {
    fetch('/api/logout')
        .then(response => {
            if (response.ok) {
                window.location.href = '/login'; // Redirigir al login después de cerrar sesión
            } else {
                console.error('Error al cerrar sesión:', response.statusText);
            }
        })
        .catch(error => console.error('Error:', error));
}