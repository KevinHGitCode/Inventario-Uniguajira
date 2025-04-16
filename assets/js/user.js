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
    const deleteLinks = document.querySelectorAll('.delete-user');
    const modalConfirmar = document.getElementById('modalConfirmarEliminar');
    const closeConfirmModal = modalConfirmar.querySelector('.close');
    const btnCancelar = document.getElementById('btnCancelarEliminar');
    const btnConfirmar = document.getElementById('btnConfirmarEliminar');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Guardar ID del usuario a eliminar
            const userId = this.getAttribute('data-id');
            document.getElementById('delete-user-id').value = userId;
            
            // Mostrar modal de confirmación
            modalConfirmar.style.display = 'block';
        });
    });
    
    // Cerrar modal con X
    closeConfirmModal.addEventListener('click', function() {
        modalConfirmar.style.display = 'none';
    });
    
    // Cerrar modal con botón Cancelar
    btnCancelar.addEventListener('click', function() {
        modalConfirmar.style.display = 'none';
    });
    
    // Confirmar eliminación
    btnConfirmar.addEventListener('click', function() {
        const userId = document.getElementById('delete-user-id').value;
        
        // Realizar petición AJAX para eliminar usuario
        fetch(`/api/users/delete/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recargar página para reflejar cambios
                window.location.reload();
            } else {
                alert('Error al eliminar el usuario: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el usuario');
        })
        .finally(() => {
            modalConfirmar.style.display = 'none';
        });
    });
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target == modalConfirmar) {
            modalConfirmar.style.display = 'none';
        }
    });
}




function inicializarBotonesEdicion() {
    const botonesEditar = document.querySelectorAll('.edit-user');
    const modalEditar = document.getElementById('modalEditar');
    const closeBtn = modalEditar.querySelector('.close');

    botonesEditar.forEach(boton => {
        boton.addEventListener('click', function (event) {
            event.preventDefault();

            // Obtén los datos del usuario desde los atributos del botón
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const nombreUsuario = this.getAttribute('data-nombre-usuario');
            const email = this.getAttribute('data-email');
            const rol = this.getAttribute('data-rol');
            console.log (id);
            // Llena los campos del formulario con los datos del usuario
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-nombre_usuario').value = nombreUsuario;
            document.getElementById('edit-email').value = email;
            // document.getElementById('edit-rol').value = rol;

            // Muestra el modal
            modalEditar.style.display = 'block';
            
        });
    });

    // Cierra el modal al hacer clic en el botón de cerrar
    closeBtn.addEventListener('click', function () {
        modalEditar.style.display = 'none';
    });

    // Cierra el modal al hacer clic fuera del contenido del modal
    window.addEventListener('click', function (event) {
        if (event.target === modalEditar) {
            modalEditar.style.display = 'none';
        }
    });
}

