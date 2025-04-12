/**
 * Inicializa la funcionalidad de búsqueda en tiempo real para filtrar elementos.
 */
function iniciarBusqueda() {
    // Obtiene el campo de entrada para la búsqueda
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) {
        // Muestra una advertencia si no se encuentra el campo de búsqueda
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    // Agrega un evento para detectar cuando el usuario escribe en el campo de búsqueda
    searchInput.addEventListener('keyup', function () {
        // Convierte el texto ingresado a minúsculas para una búsqueda insensible a mayúsculas
        const filter = searchInput.value.toLowerCase();
        // Obtiene todas las tarjetas de bienes
        const cards = document.querySelectorAll(".bien-card");

        // Itera sobre cada tarjeta y verifica si coincide con el texto de búsqueda
        cards.forEach(item => {
            const text = item.querySelector("h3").textContent.toLowerCase();
            // Muestra u oculta la tarjeta según si coincide con el filtro
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

/**
 * Configura el modal para la creación de bienes, incluyendo los eventos de apertura y cierre.
 */
function inicializarModalBien() {
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
 * Configura el formulario para la creación de bienes, manejando el envío y la respuesta del servidor.
 */
function inicializarFormularioBien() {
    const form = document.getElementById("formCrearBien");
    const modal = document.getElementById("modalCrear");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("/api/goods/create", {
            method: "POST",
            body: formData
        })
        .then(res =>  res.json())
        .then(response => {
            showToast(response);

            if (response.success) {
                modal.style.display = "none";
                setTimeout(() => loadContent('/goods'), 1500); // Recarga después de 1.5 segundos
            }
        })
        .catch(err => {
            console.error("Error:", err);
            showToast({
                success: false,
                message: 'Error: El registro ya existe. No se pueden crear duplicados.'
            });
        });
    });
}

/**
 * Configura los botones de eliminación para manejar la eliminación de bienes con confirmación.
 */
function inicializarBotonesEliminar() {
    // Selecciona todos los botones de eliminación
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        // Agrega un evento a cada botón para manejar la eliminación
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id; // Obtiene el ID del bien a eliminar

            // Muestra una alerta de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => { 
                if (!result.isConfirmed) return; // Si el usuario cancela, no hace nada  

                // Envía una solicitud para eliminar el bien y muestra el resultado
                fetch(`/api/goods/delete/${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        loadContent('/goods')
                    }
                    showToast(response)
                }).catch(err => { showToast(err) });
            });
        });
    });
}

function activarModalActualizarBien() {
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();

            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;

            document.getElementById("actualizarId").value = id;
            document.getElementById("actualizarNombre").value = nombre;
            document.getElementById("actualizarImagen").value = ""; // Limpiar imagen seleccionada

            document.getElementById("modalActualizarBien").style.display = "flex";
        });
    });
}



function inicializarFormularioActualizarBien() {
    const form = document.getElementById("formActualizarBien");
    const modal = document.getElementById("modalActualizarBien");
    const cerrar = document.getElementById("cerrarModalActualizarBien");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/api/goods/update", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                modal.style.display = "none";
                loadContent('/goods')
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


