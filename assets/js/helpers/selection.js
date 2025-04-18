// Variable para almacenar el elemento seleccionado
let selectedItem = null;

// Función para seleccionar un elemento
function toggleSelectItem(element) {
    const itemId = element.dataset.id;
    const itemName = element.dataset.name;
    const type = element.dataset.type;
    
    // Si el elemento ya está seleccionado, lo deseleccionamos
    if (element.classList.contains('selected')) {
        element.classList.remove('selected');
        selectedItem = null;
    } else {
        // Deseleccionar el elemento anteriormente seleccionado (si existe)
        if (selectedItem) {
            selectedItem.element.classList.remove('selected');
        }
        
        // Seleccionar este elemento
        element.classList.add('selected');
        selectedItem = { id: itemId, name: itemName, type: type, element: element };
    }
    
    updateControlBar(type);
    console.log(selectedItem); // Depuración: mostrar el elemento seleccionado
}

// Función para actualizar la barra de control
function updateControlBar(type) {
    const controlBar = document.getElementById(`control-bar-${type}`);
    
    if (selectedItem && selectedItem.type === type) {
        controlBar.classList.add('visible');
        const nameElement = controlBar.querySelector('.selected-name');
        nameElement.textContent = selectedItem.name;
    } else {
        controlBar.classList.remove('visible');
    }
}

// Función para limpiar la selección
function clearSelection() {
    if (selectedItem) {
        selectedItem.element.classList.remove('selected');
        const type = selectedItem.type;
        selectedItem = null;
        updateControlBar(type);
    }
}

// Manejador de eventos para clicks fuera de los elementos
function handleOutsideClick(event) {
    // Si se hace clic en la barra de control, no deseleccionar
    if (event.target.closest('.control-bar')) return;
    
    const cardItem = event.target.closest('.card-item');
    // Si se hizo clic fuera de un item, limpiar la selección
    if (!cardItem) clearSelection();
}

// Función para inicializar la selección
function initializeSelection() {
    document.addEventListener('click', handleOutsideClick);
    console.log('Selection functionality initialized');
}

// Función para desactivar la selección
function deactivateSelection() {
    clearSelection();
    document.removeEventListener('click', handleOutsideClick);
    console.log('Selection functionality deactivated');
}