/**
 * selection.js - Utilidades para manejo de selección de elementos
 * 
 * Este archivo contiene funciones para gestionar la selección de elementos en la aplicación.
 * Proporciona funcionalidades para seleccionar y deseleccionar elementos, así como para
 * actualizar la barra de control correspondiente al tipo de elemento seleccionado.
 * 
 * Reglas de comportamiento:
 * - Solo se puede tener un elemento seleccionado a la vez
 * - Al seleccionar un elemento se muestra su barra de control específica
 * - Un elemento seleccionado se puede deseleccionar haciendo clic en él nuevamente
 * - La selección se limpia al hacer clic fuera de cualquier elemento seleccionable
 * - La barra de control muestra el nombre del elemento seleccionado
 * - el item tiene un boton, al hacer click en el botn no se debe seleccionar el item entero
 * 
 * La implementación incluye protección contra deselección accidental cuando hay modales activos,
 * permitiendo interacciones simultáneas entre el sistema de modales y el sistema de selección.
 * 
 * @version 2.1
 * @date 2025-05-06
 */


// Variable para almacenar el elemento seleccionado
let selectedItem = null;

// Variable para controlar si se permite la deselección
let allowDeselection = true;

/**
 * Función para seleccionar un elemento
 * @param {HTMLElement} element - El elemento DOM a seleccionar
 * @param {Event} event - El evento de clic que desencadenó la selección (opcional)
 */
function toggleSelectItem(element, event) {
    // Si el evento proviene de un botón dentro del item, no seleccionar el item
    if (event && (event.target.closest('button') || event.target.tagName === 'BUTTON')) {
        console.log('Click en botón detectado, no se selecciona el item');
        return;
    }
    
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
    console.log('Estado de selección actualizado:', selectedItem); // Depuración: mostrar el elemento seleccionado
}

/**
 * Función para actualizar la barra de control
 * @param {string} type - Tipo de elemento seleccionado
 */
function updateControlBar(type) {
    // Ocultar todas las barras de control primero
    const allControlBars = document.querySelectorAll('.control-bar');
    allControlBars.forEach(bar => bar.classList.remove('visible'));
    
    // Mostrar la barra de control específica si hay un elemento seleccionado
    if (selectedItem && selectedItem.type === type) {
        const controlBar = document.getElementById(`control-bar-${type}`);
        if (controlBar) {
            controlBar.classList.add('visible');
            const nameElement = controlBar.querySelector('.selected-name');
            if (nameElement) {
                nameElement.textContent = selectedItem.name;
            }
        }
    }
}

/**
 * Función para limpiar la selección actual
 */
function clearSelection() {
    if (selectedItem) {
        selectedItem.element.classList.remove('selected');
        const type = selectedItem.type;
        selectedItem = null;
        updateControlBar(type);
        console.log('Selección limpiada');
    }
}

/**
 * Manejador de eventos para clicks fuera de los elementos
 * @param {Event} event - El evento de clic
 */
function handleOutsideClick(event) {
    // Si la deselección no está permitida, no hacer nada
    if (!allowDeselection) {
        console.log('Deselección no permitida en este momento');
        return;
    }

    // Si se hace clic en la barra de control, no deseleccionar
    if (event.target.closest('.control-bar')) {
        console.log('Click en barra de control, no se limpia la selección');
        return;
    }
    
    // Si se hace clic en un botón dentro de un elemento, no afectar la selección
    if (event.target.closest('button') || event.target.tagName === 'BUTTON') {
        console.log('Click en botón, manteniendo selección actual');
        return;
    }
    
    const cardItem = event.target.closest('.card-item');
    // Si se hizo clic fuera de un item, limpiar la selección
    if (!cardItem) {
        console.log('Click fuera de cualquier item, limpiando selección');
        clearSelection();
    }
}

/**
 * Función para inicializar la selección
 */
function initializeSelection() {
    // Eliminar listeners previos para evitar duplicados
    document.removeEventListener('click', handleOutsideClick);
    
    // Agregar el listener para manejar clicks
    document.addEventListener('click', handleOutsideClick);
    
    // Configurar listeners para elementos seleccionables
    const selectableItems = document.querySelectorAll('.card-item');
    selectableItems.forEach(item => {
        item.addEventListener('click', function(event) {
            toggleSelectItem(this, event);
        });
    });
    
    console.log('Sistema de selección inicializado correctamente');
}

/**
 * Habilita o deshabilita la deselección (útil para modales)
 * @param {boolean} enable - True para permitir deselección, false para impedirla
 */
function setDeselectionState(enable) {
    allowDeselection = enable;
    console.log(`Deselección ${enable ? 'habilitada' : 'deshabilitada'}`);
}

/**
 * Función para desactivar la selección
 */
function deactivateSelection() {
    clearSelection();
    document.removeEventListener('click', handleOutsideClick);
    
    // Eliminar event listeners de elementos seleccionables
    const selectableItems = document.querySelectorAll('.card-item');
    selectableItems.forEach(item => {
        item.removeEventListener('click', function(event) {
            toggleSelectItem(this, event);
        });
    });
    
    console.log('Sistema de selección desactivado');
}

// Exportar funciones para uso en otros módulos
// export {
//     initializeSelection,
//     deactivateSelection,
//     toggleSelectItem,
//     clearSelection,
//     setDeselectionState
// };