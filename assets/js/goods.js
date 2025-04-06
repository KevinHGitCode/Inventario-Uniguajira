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

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch("/api/goods/create", {
                method: "POST",
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    // Aquí capturamos si el status no es 200
                    throw new Error("Error HTTP: " + res.status);
                }
                return res.json(); // Ya es seguro hacer .json()
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Puedes cerrar el modal y recargar la lista
                    document.getElementById("modalCrear").style.display = "none";
                    loadContent('/goods');
                } else {
                    alert("No se pudo guardar el bien: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error al crear el bien:", err);
                alert("Hubo un error al enviar el formulario.");
            });
        });

    });
}







