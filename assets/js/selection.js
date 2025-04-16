// Variables para controlar la selección
let selectedItems = {
    group: [],
    inventory: [],
    good: []
};

// Función para alternar la selección de un elemento
function toggleSelectItem(element, type) {
    // Prevenir la navegación si estamos seleccionando
    if (event && event.target.tagName !== 'BUTTON') {
        event.stopPropagation();
        
        // Si ya hay elementos seleccionados, prevenir la navegación
        if (selectedItems[type].length > 0) {
            event.preventDefault();
        }
    }

    const itemId = element.dataset.id;
    const itemName = element.dataset.name;
    const isSelected = element.classList.toggle('selected');
    
    // Determinar qué barra de control mostrar
    const controlBarId = `control-bar-${type}s`;
    
    if (isSelected) {
        // Agregar a seleccionados
        selectedItems[type].push({ id: itemId, name: itemName, element: element });
    } else {
        // Remover de seleccionados
        selectedItems[type] = selectedItems[type].filter(item => item.id !== itemId);
    }
    
    updateControlBar(type);
}

// Función para actualizar la barra de control
function updateControlBar(type) {
    const controlBarId = `control-bar-${type}s`;
    const controlBar = document.getElementById(controlBarId);
    
    if (!controlBar) return;
    
    const count = selectedItems[type].length;
    
    if (count > 0) {
        controlBar.classList.add('visible');
        const nameElement = controlBar.querySelector('.selected-name');
        
        if (count === 1) {
            nameElement.textContent = selectedItems[type][0].name;
        } else {
            nameElement.textContent = `${count} seleccionados`;
        }
    } else {
        controlBar.classList.remove('visible');
    }
}

// Función para limpiar la selección cuando se cambia de vista
function clearSelection(type) {
    if (!type) {
        // Limpiar todas las selecciones
        Object.keys(selectedItems).forEach(key => {
            selectedItems[key].forEach(item => {
                if (item.element) {
                    item.element.classList.remove('selected');
                }
            });
            selectedItems[key] = [];
            updateControlBar(key);
        });
    } else {
        // Limpiar solo el tipo especificado
        selectedItems[type].forEach(item => {
            if (item.element) {
                item.element.classList.remove('selected');
            }
        });
        selectedItems[type] = [];
        updateControlBar(type);
    }
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
    const cardItem = event.target.closest('.card-item');
    if (!cardItem) {
        // Si se hizo clic fuera de un item, limpiar todas las selecciones
        clearSelection();
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Asegurar que las barras de control están ocultas inicialmente
    const controlBars = document.querySelectorAll('.control-bar');
    controlBars.forEach(bar => bar.classList.remove('visible'));
});