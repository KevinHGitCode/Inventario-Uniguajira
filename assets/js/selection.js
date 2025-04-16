// Variables para controlar la selección
let selectedItems = {
    group: [],
    inventory: [],
    good: []
};

// Función para alternar la selección de un elemento
function toggleSelectItem(element, type) {
    // Evitar que se active la funcionalidad de "Abrir" al hacer clic en botones dentro del card
    if (event && event.target.closest('.btn-open')) {
        return; // No hacer nada si se hizo clic en el botón Abrir
    }

    // Prevenir la navegación si estamos seleccionando
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const itemId = element.dataset.id;
    const itemName = element.dataset.name;
    
    // Si el elemento ya está seleccionado, lo deseleccionamos
    if (element.classList.contains('selected')) {
        element.classList.remove('selected');
        selectedItems[type] = [];
    } else {
        // Deseleccionar todos los demás elementos del mismo tipo
        const allSelectedItems = document.querySelectorAll(`.card-item.selected[data-id]`);
        allSelectedItems.forEach(item => {
            item.classList.remove('selected');
        });
        
        // Limpiar la matriz de elementos seleccionados
        selectedItems[type] = [];
        
        // Seleccionar este elemento
        element.classList.add('selected');
        selectedItems[type].push({ id: itemId, name: itemName, element: element });
    }
    
    updateControlBar(type);
}

// Función para actualizar la barra de control
function updateControlBar(type) {
    // Para "inventory", necesitamos usar "inventories" en el ID
    let controlBarId;
    if (type === 'inventory') {
        controlBarId = 'control-bar-inventories';
    } else {
        controlBarId = `control-bar-${type}s`;
    }
    
    const controlBar = document.getElementById(controlBarId);
    
    if (!controlBar) {
        console.warn(`Control bar not found: ${controlBarId}`);
        return;
    }
    
    const count = selectedItems[type].length;
    
    if (count > 0) {
        controlBar.classList.add('visible');
        const nameElement = controlBar.querySelector('.selected-name');
        nameElement.textContent = selectedItems[type][0].name;
    } else {
        controlBar.classList.remove('visible');
    }
}

// Función para limpiar la selección cuando se cambia de vista
function clearSelection(type) {
    if (!type) {
        // Limpiar todas las selecciones
        Object.keys(selectedItems).forEach(key => {
            clearSelectionByType(key);
        });
    } else {
        // Limpiar solo el tipo especificado
        clearSelectionByType(type);
    }
}

// Helper function para limpiar por tipo
function clearSelectionByType(type) {
    // Deseleccionar todos los elementos del DOM con esta clase
    const allSelectedItems = document.querySelectorAll(`.card-item.selected[data-id]`);
    allSelectedItems.forEach(item => {
        item.classList.remove('selected');
    });
    
    // Limpiar el array
    selectedItems[type] = [];
    
    // Actualizar la barra de control
    updateControlBar(type);
}

// Modificar las funciones de navegación existentes para limpiar la selección
const originalAbrirGrupo = abrirGrupo;
abrirGrupo = function(idGroup) {
    clearSelection('group');
    originalAbrirGrupo(idGroup);
};

const originalCerrarGrupo = cerrarGrupo;
cerrarGrupo = function() {
    clearSelection('inventory');
    originalCerrarGrupo();
};

const originalAbrirInventario = abrirInventario;
abrirInventario = function(idInventory) {
    clearSelection('inventory');
    originalAbrirInventario(idInventory);
};

const originalCerrarInventario = cerrarInventario;
cerrarInventario = function() {
    clearSelection('good');
    originalCerrarInventario();
};

// Agregar evento para detectar clics fuera de los elementos seleccionables
document.addEventListener('click', function(event) {
    // Si se hace clic en un botón dentro de un card o en la barra de control, no desseleccionar
    if (event.target.closest('.btn-open') || 
        event.target.closest('.control-btn') || 
        event.target.closest('.control-bar')) {
        return;
    }
    
    const cardItem = event.target.closest('.card-item');
    if (!cardItem) {
        // Si se hizo clic fuera de un item y fuera de la barra de control, limpiar todas las selecciones
        clearSelection();
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Asegurar que las barras de control están ocultas inicialmente
    const controlBars = document.querySelectorAll('.control-bar');
    controlBars.forEach(bar => bar.classList.remove('visible'));
});