function renombrarGrupo() {
    console.log(selectedItem);
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


function abrirInventario(idInventory) {
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divInventories = document.getElementById('inventories');
    
    // Mostrar loader mientras carga
    divGoodsInventory.innerHTML = '<p>Cargando bienes...</p>';
    divGoodsInventory.classList.remove('hidden');
    divInventories.classList.add('hidden');

    fetch(`/api/get/goodsInventory/${idInventory}`)
    .then(res => res.text())
    .then(html => {
        divGoodsInventory.innerHTML = html;
        const inventoryName = document.getElementById(`inventory-name${idInventory}`).textContent;
        document.getElementById('inventory-name').innerText = inventoryName;

        iniciarBusqueda('searchGoodInventory');
    })
    .catch(error => {
        console.error('Error:', error);
        divGoodsInventory.innerHTML = '<p>Error al cargar los bienes</p>';
    });
}

// cerrar inventario
function cerrarInventario() {
    document.getElementById('goods-inventory').classList.add('hidden');
    document.getElementById('inventories').classList.remove('hidden');

    const input = document.getElementById('searchInventory');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Backspace', code: 'Backspace' }));
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));
}


function escapeHtml(unsafe) {
    return unsafe?.toString().replace(/[&<"'>]/g, match => {
        switch(match) {
            case '&': return '&amp;';
            case '<': return '&lt;';
            case '>': return '&gt;';
            case '"': return '&quot;';
            case "'": return '&#039;';
        }
    }) || '';
}


// -------------------------------------


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



// -----------------------------------------------------
// ------------------- Inventories ---------------------
// -----------------------------------------------------

// Función para inicializar el formulario de Crear Inventario
function inicializarFormularioCrearInventario() {
    const form = document.getElementById("formCrearInventario");
    const modal = document.getElementById("modalCrearInventario");

    if (!form) return;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        
        // Asumiendo que tenemos el ID del grupo actual en alguna variable global
        // o lo podemos obtener de la URL
        const grupoId = obtenerGrupoIdActual(); // Esta función debería implementarse
        
        // Agregar el grupoId al formData
        formData.append("grupo_id", grupoId);

        fetch("/api/inventario/create", {
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
                    // Recargar los inventarios del grupo actual
                    abrirGrupo(grupoId); // Asumiendo que esta función ya existe
                }, 1500);
            }
        })
        .catch(err => {
            console.error("Error:", err);
            showToast({
                success: false,
                message: 'Error: No se pudo crear el inventario. Intente nuevamente.'
            });
        });
    });
}

// Función auxiliar para obtener el ID del grupo actual
// Esta es una función de ejemplo, deberás adaptarla según tu implementación
function obtenerGrupoIdActual() {
    // Podrías obtener el ID del grupo de una variable global
    // o de un atributo data en algún elemento HTML
    // o de la URL actual
    
    // Ejemplo de obtención desde la URL: /inventario?grupo=123
    const urlParams = new URLSearchParams(window.location.search);
    const grupoId = urlParams.get('grupo');
    
    return grupoId || 0; // Devuelve 0 o algún valor predeterminado si no hay ID
}