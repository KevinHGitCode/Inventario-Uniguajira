function editarPerfil() {
    const form = document.getElementById("formEditarPerfil");
    const modal = document.getElementById("modalEditarPerfil");

    if (!form || !modal) {
        console.warn("Formulario de editar perfil no está completamente cargado.");
        return;
    }

    modal.style.display = "flex";

    form.onsubmit = function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("/api/users/editProfile", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                modal.style.display = "none";
                loadContent('/profile');
            }
            showToast(response);
        }).catch(err => { showToast(err) });
    };

    cerrarModalHandler = function (e) {
        if (e.target === modal) {
            ocultarModal('#modalEditarPerfil');
        }
    };
    window.addEventListener("click", cerrarModalHandler);
}