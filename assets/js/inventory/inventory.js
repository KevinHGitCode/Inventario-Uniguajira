// Función principal para inicializar todas las funciones de inventario
function initInventoryFunctions() {
    // Inicializar formulario para crear inventario
    inicializarFormularioAjax('#formCrearInventario', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);

            loadContent('/inventory', false);
        }
    });

    // Inicializar formulario para renombrar inventario
    inicializarFormularioAjax('#formRenombrarInventario', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);

            console.log("hola esto es un cambio")

            loadContent('/inventory', false);
        }
    });

    // Inicializar formulario para editar responsable del inventario
    inicializarFormularioAjax('#formEditarResponsable', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/inventory', false);
        }
    });

}

// Función para inicializar el formulario de actualizar estado
// Esta funcion es llamada en sidebar.js 
function initEstadoInventarioForm() {
    // Inicializar formulario para cambiar estado del inventario
    inicializarFormularioAjax('#estadoInventarioForm', {
        onSuccess: (response, form) => {
            showToast(response);

            const estados = ['bueno', 'regular', 'malo'];
            const idx = form.querySelector('[name="estado"]').value;

            cambiarEstadoInventario(estados[idx - 1]);
            console.log('el estado se trato de cambiar a', estados[idx - 1])
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


// Función para eliminar inventario
function btnEliminarInventario() {
    const idInventario = selectedItem.id;

    eliminarRegistro({
        url: `/api/inventories/delete/${idInventario}`,
        onSuccess: (response) => {
            if (response.success) {
                const grupoId = localStorage.getItem('openGroup');
                loadContent('/inventory', false);
            }
            showToast(response);
        }
    });
}

function toggleInventoryControls(show) {
    const controls = document.querySelector('.inventory-controls');
    console.log(controls)
    if (controls) {
        if (show) {
            controls.classList.remove('hidden');
        } else {
            controls.classList.add('hidden');
        }
    }
}

function abrirInventario(idInventory, scrollUpRequired = true) {
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divInventories = document.getElementById('inventories');
    
    // Mostrar sección de bienes y ocultar inventarios
    divGoodsInventory.classList.remove('hidden');
    divInventories.classList.add('hidden');

    // Cargar bienes usando la nueva función
    cargarBienesInventario(idInventory);

    // Mostrar controles del inventario
    actualizarInfoInventario(idInventory);
    toggleInventoryControls(true);

    // Inicializar búsqueda
    iniciarBusqueda('searchGoodInventory');
    
    // Guardar estado en localStorage
    localStorage.setItem('openInventory', idInventory);

    // Scroll a la parte superior si es necesario
    if (scrollUpRequired) {
        window.scrollTo(0, 0);
    }
}

// Actualiza la información visual del inventario seleccionado
function actualizarInfoInventario(idInventory) {
    // Actualizar el nombre del inventario
    const inventoryCard = document.querySelector(`#inventories-content .card-item[data-id="${idInventory}"]`);
    const inventoryName = inventoryCard.getAttribute('data-name') || 'Inventario';
    document.getElementById('inventory-name').innerText = inventoryName;

    // Obtener y mostrar el responsable
    const responsable = inventoryCard.getAttribute('data-responsable') || '';
    document.getElementById('responsable-inventario').innerText = responsable ? `- Responsable: ${responsable}` : '';

    // obtenten el dato almacenado en localstorage y agrega ese valor en estado_id_inventario
    const id = document.getElementById('estado_id_inventario');
    const idInventario = localStorage.getItem('openInventory');
    id.value = idInventario || '';

    console.log("actualizarInfoInventario", inventoryCard.dataset)

    cambiarEstadoInventario(inventoryCard.dataset.estado)

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

    // Ocultar controles del inventario
    toggleInventoryControls(false);
}


// Función para cambiar el estado del inventario
function cambiarEstadoInventario(estado) {
    // Obtener todas las luces
    const luces = document.querySelectorAll('.light');

    // Eliminar clases activas e inactivas de todas las luces
    luces.forEach(luz => {
        luz.classList.remove('active');
        luz.classList.add('inactive');
    });

    // Activar la luz seleccionada según el estado
    let luzSeleccionada;
    switch (estado) {
        case 'malo':
            luzSeleccionada = document.querySelector('.light-red');
            break;
        case 'regular':
            luzSeleccionada = document.querySelector('.light-yellow');
            break;
        case 'bueno':
            luzSeleccionada = document.querySelector('.light-green');
            break;
    }

    if (luzSeleccionada) {
        luzSeleccionada.classList.remove('inactive');
        luzSeleccionada.classList.add('active');

        // Aquí puedes agregar el código para enviar el cambio al servidor
        console.log(`Estado cambiado a: ${estado}`);
        // Por ejemplo: guardarEstadoInventario(estado);
    }
}

// Función para abrir el modal de editar responsable del inventario
function btnEditarResponsable() {
    // Agrega el id del inventario desde localStorage al input oculto antes de enviar
    const idInventario = localStorage.getItem('openInventory');
    if (idInventario) {
        document.getElementById('editarResponsableId').value = idInventario;
    }
    mostrarModal('#modalEditarResponsable');
}