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
                loadContent('/goods'); // Recarga después de 1.5 segundos
            }
        }).catch(err => { showToast(err) });
    });
}

/**
 * Configura los botones de eliminación para manejar la eliminación de bienes con confirmación.
 */
function eliminarBien(id) {
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
                loadContent('/goods');
            }
            showToast(response);
        }).catch(err => { showToast(err) });
    });
}


function ActualizarBien(id, nombre) {
    const modal = document.getElementById("modalActualizarBien");
    const form = document.getElementById("formActualizarBien");

    // Configurar los valores iniciales del formulario
    document.getElementById("actualizarId").value = id;
    document.getElementById("actualizarNombre").value = nombre;
    document.getElementById("actualizarImagen").value = ""; // Limpiar imagen seleccionada

    // Mostrar el modal
    modal.style.display = "flex";

    // Configurar el evento de envío del formulario
    form.onsubmit = function (e) {
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
                loadContent('/goods');
            }
            showToast(response);
        }).catch(err => { showToast(err) });
    };

    cerrarModalHandler = function (e) {
        if (e.target === modal) {
            ocultarModal('#modalActualizarBien');
        }
    };
    window.addEventListener("click", cerrarModalHandler);
}

