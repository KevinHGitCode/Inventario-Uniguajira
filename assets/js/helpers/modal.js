// TODO: Describir el archivo
// aqui estaran las funciones de modales para reutilizacion


// Mostrar el modal
function mostrarModal(selectorModal) {
    const modal = document.querySelector(selectorModal);
    modal.style.display = "flex";

    // Definimos la función y la asignamos solo una vez
    cerrarModalHandler = function (e) {
        if (e.target === modal) {
            ocultarModal(selectorModal);
        }
    };

    window.addEventListener("click", cerrarModalHandler);
}

// Ocultar el modal
function ocultarModal(selectorModal) {
    const modal = document.querySelector(selectorModal);
    modal.style.display = "none";

    // Remueve el event listener
    window.removeEventListener("click", cerrarModalHandler);
}

