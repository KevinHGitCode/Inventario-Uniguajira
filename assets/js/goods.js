function iniciarBusqueda() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) {
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll(".bien-card");

        cards.forEach(item => {
            const text = item.querySelector("h3").textContent.toLowerCase();
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

function inicializarModalBien() {
    const modal = document.getElementById("modalCrear");
    const btnCrear = document.getElementById("btnCrear");
    const spanClose = modal?.querySelector(".close");

    if (!modal || !btnCrear || !spanClose) {
        console.warn("Elementos del modal no encontrados.");
        return;
    }

    btnCrear.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    spanClose.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
}

function inicializarFormularioBien() {
    const form = document.getElementById("formCrearBien");
    const modal = document.getElementById("modalCrear");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Evita recargar la página

        const formData = new FormData(form);

        fetch("/api/goods/create", {
            method: "POST",
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                throw new Error("Error HTTP: " + res.status);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bien guardado!',
                    text: data.message,
                    confirmButtonColor: '#a31927',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    modal.style.display = "none";
                    loadContent('/goods');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(err => {
            console.error("Error al crear el bien:", err);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un error al enviar el formulario.'
            });
        });
    });
}



function inicializarBotonesEliminar() {
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;

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
                if (result.isConfirmed) {
                    fetch(`/api/goods/delete/${id}`, {
                        method: 'DELETE'
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Eliminado!', data.message, 'success')
                                .then(() => loadContent('/goods'));
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error("Error al eliminar el bien:", err);
                        Swal.fire('Oops...', 'Hubo un error al eliminar.', 'error');
                    });
                }
            });
        });
    });
}






