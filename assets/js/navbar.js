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

function activarModalActualizarContraseña() {
    // Obtiene el modal y los elementos relacionados (botón de abrir y cerrar)
    const modal = document.getElementById("modalCambiarContraseña");
    const btnCrear = document.getElementById("btnCambiarContraseña");
    const spanClose = document.getElementById("cerrarModalCambiarContraseña");

    // Agrega un evento para abrir el modal al hacer clic en el botón
    btnCrear.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Agrega un evento para cerrar el modal al hacer clic en el botón de cerrar
    spanClose.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Agrega un evento para cerrar el modal al hacer clic fuera de él
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
}



function inicializarFormularioActualizarContraseña() {
    const form = document.getElementById("formCambiarContraseña");
    const modal = document.getElementById("modalCambiarContraseña");
    const cerrar = document.getElementById("cerrarModalCambiarContraseña");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/api/users/update", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                modal.style.display = "none";
                logout(); // Redirigir a la página de inicio de sesión después de cambiar la contraseña
            }
            showToast(response)
        })
        .catch(err => {
            console.error("Error:", err);
            showToast({
                success: false,
                message: 'Error al enviar el formulario'
            });
        });

    });

    cerrar.addEventListener("click", () => {
        modal.style.display = "none";
    });
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