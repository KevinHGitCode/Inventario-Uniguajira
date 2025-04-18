function renombrarGrupo() {
    console.log(selectedItem);
}

// eliminarGrupo()
function eliminarGrupo() {
    const idGrupo = selectedItem.id; // Asumiendo que tienes el ID del grupo en selectedItem
    console.log(idGrupo);
}




// Función para abrir grupo y cargar inventarios
function abrirGrupo(idGroup) {
    const divGroups = document.getElementById('groups');
    const divInventories = document.getElementById('inventories');

    // Mostrar loader
    divInventories.innerHTML = '<p>Cargando inventarios...</p>';
    divGroups.classList.add('hidden');
    divInventories.classList.remove('hidden');

    fetch(`/api/get/inventories/${idGroup}`)
    .then(res => res.text())
    .then(html => {
        divInventories.innerHTML = html;
        const grupoName = document.getElementById(`group-name${idGroup}`).textContent;
        document.getElementById('group-name').innerText = grupoName;

        iniciarBusqueda('searchInventory');
        inicializarFormularioCrearInventario();
        
    })
    .catch(error => {
        console.error('Error:', error);
        divInventories.innerHTML = '<p>Error al cargar los inventarios</p>';
    });

}

// Función para cerrar grupo (mejorada)
function cerrarGrupo() {
    document.getElementById('groups').classList.remove('hidden');
    document.getElementById('inventories').classList.add('hidden');

    const input = document.getElementById('searchGroup');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));
}





// Función para inicializar el formulario de Crear Grupo
function inicializarFormularioCrearGrupo() {
    const form = document.getElementById("formCrearGrupo");
    const modal = document.getElementById("modalCrearGrupo");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("/api/grupos/create", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            showToast(response);

            if (response.success) {
                modal.style.display = "none";
                form.reset();
                setTimeout(() => {
                    // Recargar la sección de grupos
                    // Asumiendo que hay una función loadGroups() o se puede usar window.location.reload()
                    window.location.reload();
                }, 1500); // Recarga después de 1.5 segundos
            }
        })
        .catch(err => {
            console.error("Error:", err);
            showToast({
                success: false,
                message: 'Error: No se pudo crear el grupo. Intente nuevamente.'
            });
        });
    });
}

// Función para inicializar el modal de Actualizar Grupo
function inicializarModalActualizarGrupo() {
    // Obtiene el modal y el botón de cerrar
    const modal = document.getElementById("modalActualizarGrupo");
    const spanClose = document.getElementById("cerrarModalActualizarGrupo");
    
    // Podemos suponer que este modal se abrirá desde otra función
    // cuando se seleccione un grupo para editar
    
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

// Función para inicializar el formulario de Actualizar Grupo
function inicializarFormularioActualizarGrupo() {
    const form = document.getElementById("formActualizarGrupo");
    const modal = document.getElementById("modalActualizarGrupo");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const grupoId = document.getElementById("actualizarGrupoId").value;

        fetch(`/api/grupos/update/${grupoId}`, {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            showToast(response);

            if (response.success) {
                modal.style.display = "none";
                // Actualizar el nombre del grupo en la interfaz
                const nombreGrupo = document.getElementById(`group-name${grupoId}`);
                if (nombreGrupo) {
                    nombreGrupo.textContent = formData.get("nombre");
                }
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            console.error("Error:", err);
            showToast({
                success: false,
                message: 'Error: No se pudo actualizar el grupo. Intente nuevamente.'
            });
        });
    });
}
