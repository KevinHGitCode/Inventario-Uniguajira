// Función principal para inicializar todas las funciones de inventario
function initInventoryFunctions() {
    // Inicializar formulario para crear inventario
    inicializarFormularioAjax('#formCrearInventario', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const grupoId = localStorage.getItem('openGroup');
            abrirGrupo(grupoId);
        }
    });

    // Inicializar formulario para renombrar inventario
    inicializarFormularioAjax('#formRenombrarInventario', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const grupoId = localStorage.getItem('openGroup');
            abrirGrupo(grupoId);
        }
    });

    // Inicializar formulario para actualizar inventario
    inicializarFormularioAjax('#formActualizarInventario', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const grupoId = localStorage.getItem('openGroup');
            abrirGrupo(grupoId);
        }
    });

}

// Función para abrir el modal de renombrar inventario
function btnRenombrarInventario() {
    console.log(selectedItem); // mensaje de depuración
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    
    document.getElementById("renombrarInventarioId").value = id;
    document.getElementById("renombrarInventarioNombre").value = nombreActual;

    mostrarModal('#modalRenombrarInventario');
}

// Función para abrir el modal de editar inventario (puede ser la misma que renombrar)
function btnEditarInventario() {  // TODO:
    const id = selectedItem.id;
    const cardInventory = document.querySelector('.card-item.selected')
    console.log(cardInventory.dataset)
}

// Función para eliminar inventario
function btnEliminarInventario() {
    const idInventario = selectedItem.id;

    eliminarRegistro({
        url: `/api/inventories/delete/${idInventario}`,
        onSuccess: (response) => {
            if (response.success) {
                const grupoId = localStorage.getItem('openGroup');
                abrirGrupo(grupoId, false);
            }
            showToast(response);
        }
    });
}

function abrirInventario(idInventory, scrollUpRequired = true) {
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divInventories = document.getElementById('inventories');
    
    // Mostrar sección de bienes y ocultar inventarios
    divGoodsInventory.classList.remove('hidden');
    divInventories.classList.add('hidden');

    // Cargar bienes usando la nueva función
    cargarBienesInventario(idInventory);

    // Actualizar el nombre del inventario
    const inventoryName = document.getElementById(`inventory-name${idInventory}`).textContent;
    document.getElementById('inventory-name').innerText = inventoryName;

    // Inicializar búsqueda
    iniciarBusqueda('searchGoodInventory');
    
    // Guardar estado en localStorage
    localStorage.setItem('openInventory', idInventory);

    // Scroll a la parte superior si es necesario
    if (scrollUpRequired) {
        window.scrollTo(0, 0);
    }
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

    localStorage.removeItem('openInventory');
}
