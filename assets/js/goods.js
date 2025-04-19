function initFormCrearBien() {
    inicializarFormularioAjax('#formCrearBien', {
        resetOnSuccess: true,
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/goods', false);
        }
    });
}

function eliminarBien(id) {
    eliminarRegistro({
        url: `/api/goods/delete/${id}`,
        onSuccess: (response) => {
            if (response.success) {
                loadContent('/goods', false);
            }
            showToast(response);
        }
    });
}

function ActualizarBien(id, nombre) {
    // Configurar los valores iniciales del formulario
    document.getElementById("actualizarId").value = id;
    document.getElementById("actualizarNombre").value = nombre;
    document.getElementById("actualizarImagen").value = ""; // Limpiar imagen seleccionada

    // Mostrar el modal
    mostrarModal('#modalActualizarBien')
    
    // Configurar el manejo del formulario con tu nueva función
    inicializarFormularioAjax('#formActualizarBien', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/goods', false);
        }
    });
}