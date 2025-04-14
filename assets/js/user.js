function activarBusquedaEnTabla() {
    // Obtiene el campo de entrada para la búsqueda
    const searchInput = document.getElementById('searchUserInput');
    if (!searchInput) {
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    // Agrega un evento para detectar cuando el usuario escribe en el campo de búsqueda
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        // Obtiene todas las filas de la tabla
        const rows = document.querySelectorAll("table tbody tr");

        // Itera sobre cada fila y verifica si coincide con el texto de búsqueda
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            // Muestra u oculta la fila según si coincide con el filtro
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}
/**
 * Configura el modal para la creación de bienes, incluyendo los eventos de apertura y cierre.
 */
function inicializarModalUser() {
    // Obtiene el modal y los elementos relacionados (botón de abrir y cerrar)
    const modal = document.getElementById("modalCrear");
    const btnCrear = document.getElementById("btnCrear");
    const spanClose = modal?.querySelector(".close");

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

/**
 * Configura el formulario para la creación de usu, manejando el envío y la respuesta del servidor.
 */

function inicializarCrearUsuario() {
    const formCrearUsuario = document.getElementById('formCrearUser');
    const modalCrear = document.getElementById('modalCrear');
    const tablaUsuarios = document.querySelector('table tbody');

    formCrearUsuario.addEventListener('submit', async function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        // Obtén los datos del formulario
        const formData = new FormData(formCrearUsuario);
        const data = Object.fromEntries(formData.entries());

        try {
            // Realiza la solicitud POST al servidor
            const response = await fetch('/api/users/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                const newUser = await response.json();

                // Agrega el nuevo usuario a la tabla
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${newUser.id}</td>
                    <td>${newUser.nombre}</td>
                    <td>${newUser.nombre_usuario}</td>
                    <td>${newUser.email}</td>
                    <td>${newUser.rol}</td>
                `;
                tablaUsuarios.appendChild(newRow);

                // Cierra el modal y resetea el formulario
                modalCrear.style.display = 'none';
                formCrearUsuario.reset();

                alert('Usuario creado exitosamente.');
            } else {
                const error = await response.json();
                alert(`Error al crear usuario: ${error.message}`);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error al intentar crear el usuario.');
        }
    });
}


/**
 * Configura los botones de eliminación para manejar la eliminación de bienes con confirmación.
 */
function inicializarBotonesEliminarUser() {
   
}

function inicializarFormularioEditarPerfil() {
    const form = document.getElementById("formEditarPerfil");
    const modal = document.getElementById("modalEditarPerfil");
    const cerrar = document.getElementById("cerrarModalEditarPerfil");

    if (!form || !modal || !cerrar) {
        console.warn("Formulario de editar perfil no está completamente cargado.");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/api/users/edit", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            showToast(response);
            if (response.success) {
                modal.style.display = "none";
                loadContent('/profile');
            }
            
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

function inicializarModalEditUser() {
    // Obtiene el modal y los elementos relacionados (botón de abrir y cerrar)
    const modal = document.getElementById("modalEditarPerfil");
    const btnCrear = document.getElementById("btnEditar");
    const spanClose = document.getElementById("cerrarModalEditarPerfil");

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