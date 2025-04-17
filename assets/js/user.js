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
        fetch('/api/users/create', {
            method: 'POST',
            
            body:formData
        })
        .then(res => res.json())
        .then(response => {
            showToast(response);
            if (response.success) {
                 modalCrear.style.display = 'none';
                 setTimeout(() => loadContent('/users'), 1500); // Recarga después de 1.5 segundos
                    
            }
        })
        .catch(err => { showToast(err) });



        
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
        fetch(`/api/users/delete/`, {
            method: 'delete',
           
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
        .catch(err => {
            console.error('Error:', err);
            showToast({
                success: false,
                message: 'Error al eliminar el usuario elegido'
            });
        });
       
    });
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target == modalConfirmar) {
            modalConfirmar.style.display = 'none';
        }
    });
}


// function inicializarBotonesEdicion1() {
//     const botonesEditar = document.querySelectorAll('.edit-user');
//     const modalEditar = document.getElementById('modalEditar');
//     const closeBtn = modalEditar.querySelector('.close');


//     fetch('/api/users/updateUser')
//     botonesEditar.forEach(boton => {
//         boton.addEventListener('click', function (event) {
//             event.preventDefault();

//             // Obtén los datos del usuario desde los atributos del botón
//             const id = this.getAttribute('data-id');
//             const nombre = this.getAttribute('data-nombre');
//             const nombreUsuario = this.getAttribute('data-nombre-usuario');
//             const email = this.getAttribute('data-email');
//             const rol = this.getAttribute('data-rol');
//             console.log (id);
//             // Llena los campos del formulario con los datos del usuario
//             document.getElementById('edit-id').value = id;
//             document.getElementById('edit-nombre').value = nombre;
//             document.getElementById('edit-nombre_usuario').value = nombreUsuario;
//             document.getElementById('edit-email').value = email;
//             // document.getElementById('edit-rol').value = rol;

//             // Muestra el modal
//             modalEditar.style.display = 'block';
            
//         });
//     });

//     // Cierra el modal al hacer clic en el botón de cerrar
//     closeBtn.addEventListener('click', function () {
//         modalEditar.style.display = 'none';
//     });

//     // Cierra el modal al hacer clic fuera del contenido del modal
//     window.addEventListener('click', function (event) {
//         if (event.target === modalEditar) {
//             modalEditar.style.display = 'none';
//         }
//     });
// }

function inicializarBotonesEdicion() {
    const botonesEditar = document.querySelectorAll('.edit-user');
    const modalEditar = document.getElementById('modalEditar');
    const closeBtn = modalEditar.querySelector('.close');
    const formEditar = document.getElementById('formEditarUser');

    botonesEditar.forEach(boton => {
        boton.addEventListener('click', function (event) {
            event.preventDefault();

            // Obtén los datos del usuario desde los atributos del botón
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const nombreUsuario = this.getAttribute('data-nombre-usuario');
            const email = this.getAttribute('data-email');
            const rol = this.getAttribute('data-rol');

            // Llena los campos del formulario con los datos del usuario
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-nombre_usuario').value = nombreUsuario;
            document.getElementById('edit-email').value = email;
            
            // Si hay un campo de rol, asígnalo también
            const rolInput = document.getElementById('edit-rol');
            if (rolInput) {
                rolInput.value = rol;
            }

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

    // Maneja el envío del formulario de edición
    if (formEditar) {
        formEditar.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const formData = new FormData(formEditar);
            
             fetch('/api/users/edit', {
                method: 'POST',
                body: formData
            })
            
            .then(res => res.json())
            .then(response => {
                showToast(response);
                if (response.success) {
                     modalCrear.style.display = 'none';
                     setTimeout(() => loadContent('/users'), 1500); // Recarga después de 1.5 segundos
                        
                }
            })
            .catch(err => { 
                console.log(err)
                showToast(err) });

    
        });
    }
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