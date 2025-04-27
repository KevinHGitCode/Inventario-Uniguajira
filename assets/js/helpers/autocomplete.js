/**
 * autocompleteSearch.js - Funcionalidad configurable de autocompletado para búsquedas
 * 
 * Este script proporciona un sistema de autocompletado configurable y reutilizable que
 * puede implementarse en cualquier campo de búsqueda. Obtiene datos de una API configurable
 * y ofrece sugerencias mientras el usuario escribe, con navegación mediante teclado y ratón.
 * 
 * 
 * Reglas de comportamiento:
 * - Muestra sugerencias filtradas mientras el usuario escribe en el campo
 * - Indica cuando no hay coincidencias o cuando no hay datos disponibles
 * - Permite navegar por las sugerencias usando las teclas de dirección (arriba/abajo)
 * - Selecciona automáticamente la única opción disponible al presionar Enter
 * - No realiza acción al presionar Enter si hay múltiples opciones sin selección
 * - Asigna el ID del elemento seleccionado al campo oculto correspondiente (si se especifica)
 * - Ejecuta una función callback personalizada al seleccionar un elemento (si se proporciona)
 * - Cierra las sugerencias al hacer clic fuera del contenedor o al presionar Escape
 * - Resalta visualmente la parte coincidente del texto en las sugerencias
 * - Previene el envío de formularios al presionar Enter cuando hay sugerencias activas
 *
 * @version 2.1
 * @date 2025-04-26
 */

/**
 * initAutocompleteSearch - Inicializa el autocompletado para campos de búsqueda
 * 
 * @param {string} containerSelector - Selector CSS del contenedor que incluirá input y lista ul
 * @param {Object} options - Opciones de configuración
 * @param {string} options.dataUrl - URL para obtener los datos JSON
 * @param {string} options.inputSelector - Selector CSS del input dentro del contenedor (default: 'input')
 * @param {string} options.listSelector - Selector CSS de la lista de sugerencias (default: '.suggestions')
 * @param {string} options.dataKey - Propiedad del objeto JSON a usar como texto visible (default: segundo key)
 * @param {string} options.idKey - Propiedad del objeto JSON a usar como ID (default: primer key)
 * @param {string} options.hiddenInputSelector - Selector CSS del input oculto para almacenar el ID seleccionado (opcional)
 * @param {Function} options.onSelect - Función a ejecutar cuando se selecciona un elemento
 * @param {string} options.noMatchText - Texto a mostrar cuando no hay coincidencias (default: 'No hay coincidencias')
 * @param {string} options.noDataText - Texto a mostrar cuando no hay datos disponibles (default: 'No hay datos disponibles')
 * @returns {Object} - Objeto con métodos públicos (recargarDatos)
 */
function initAutocompleteSearch(containerSelector, options = {}) {
    // Opciones por defecto
    const defaultOptions = {
        dataUrl: '/api/data',
        inputSelector: 'input',
        listSelector: '.suggestions',
        dataKey: null, // Se determinará automáticamente
        idKey: null, // Se determinará automáticamente
        hiddenInputSelector: null,
        onSelect: null,
        noMatchText: 'No hay coincidencias',
        noDataText: 'No hay datos disponibles'
    };
    
    // Combinar opciones default con las proporcionadas
    const settings = { ...defaultOptions, ...options };
    
    // Variables y referencias DOM
    const container = document.querySelector(containerSelector);
    if (!container) {
        console.error(`Contenedor no encontrado: ${containerSelector}`);
        return { recargarDatos: () => {} };
    }
    
    const inputField = container.querySelector(settings.inputSelector);
    const suggestionsList = container.querySelector(settings.listSelector);
    const hiddenInput = settings.hiddenInputSelector ? 
                       document.querySelector(settings.hiddenInputSelector) : null;
    
    let dataItems = [];
    let isActive = false;
    let selectedIndex = -1;
    
    /**
     * Carga los datos desde la URL especificada
     */
    function cargarDatos() {
        fetch(settings.dataUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error al consultar datos desde ${settings.dataUrl}`);
                }
                return response.json();
            })
            .then(data => {
                dataItems = data;
                console.log('Datos cargados:', dataItems.length, '\n', dataItems);
                
                // Si no se especificaron las claves, intentamos determinarlas
                if (!settings.idKey && dataItems.length > 0) {
                    // El primer key como ID por defecto
                    settings.idKey = Object.keys(dataItems[0])[0];
                }
                
                if (!settings.dataKey && dataItems.length > 0) {
                    // El segundo key como texto visible por defecto, o el primero si no hay segundo
                    const keys = Object.keys(dataItems[0]);
                    settings.dataKey = keys.length > 1 ? keys[1] : keys[0];
                }
            })
            .catch(error => {
                console.error('Error cargando datos:', error);
                dataItems = [];
            });
    }
    
    /**
     * Muestra sugerencias basadas en el texto ingresado
     */
    function mostrarSugerencias() {
        const query = inputField.value.toLowerCase().trim();
        
        // Limpiar sugerencias anteriores
        suggestionsList.innerHTML = '';
        selectedIndex = -1;
        
        // Si no hay texto, ocultar sugerencias
        if (!query) {
            ocultarSugerencias();
            return;
        }
        
        // Si no hay datos disponibles
        if (!dataItems || dataItems.length === 0) {
            mostrarMensaje(settings.noDataText, 'text-danger');
            isActive = true;
            return;
        }
        
        // Filtrar elementos que coincidan con la búsqueda
        const matches = dataItems.filter(item => 
            item[settings.dataKey].toLowerCase().includes(query)
        );
        
        // Si no hay coincidencias
        if (matches.length === 0) {
            mostrarMensaje(settings.noMatchText, 'text-danger');
            isActive = true;
            return;
        }
        
        // Si hay una coincidencia exacta, no mostrar sugerencias
        const exactMatch = matches.length === 1 && 
                          matches[0][settings.dataKey].toLowerCase() === query;
        
        if (exactMatch) {
            ocultarSugerencias();
            return;
        }
        
        // Mostrar las coincidencias
        suggestionsList.classList.remove('d-none');
        isActive = true;
        
        // Crear sugerencias
        matches.forEach((item, index) => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item list-group-item-action';
            listItem.dataset.id = item[settings.idKey];
            
            // Resaltar parte coincidente
            const text = item[settings.dataKey];
            const textLower = text.toLowerCase();
            const position = textLower.indexOf(query);
            
            if (position !== -1) {
                listItem.innerHTML = text.substring(0, position) + 
                                    '<strong>' + text.substring(position, position + query.length) + '</strong>' + 
                                    text.substring(position + query.length);
            } else {
                listItem.textContent = text;
            }
            
            // Asignar eventos
            listItem.addEventListener('mouseover', () => {
                desseleccionarTodos();
                listItem.classList.add('active');
                selectedIndex = index;
            });
            
            listItem.addEventListener('mouseout', () => {
                listItem.classList.remove('active');
            });
            
            listItem.addEventListener('click', () => {
                seleccionarItem(item);
            });
            
            suggestionsList.appendChild(listItem);
        });
    }
    
    /**
     * Oculta la lista de sugerencias
     */
    function ocultarSugerencias() {
        suggestionsList.classList.add('d-none');
        isActive = false;
    }
    
    /**
     * Muestra un mensaje en la lista de sugerencias
     * @param {string} text - Texto del mensaje
     * @param {string} className - Clase CSS adicional para el mensaje
     */
    function mostrarMensaje(text, className = '') {
        suggestionsList.innerHTML = '';
        suggestionsList.classList.remove('d-none');
        
        const messageItem = document.createElement('li');
        messageItem.className = `list-group-item ${className}`;
        messageItem.textContent = text;
        
        suggestionsList.appendChild(messageItem);
    }
    
    /**
     * Selecciona un item de la lista de sugerencias
     * @param {Object} item - Objeto de datos seleccionado
     */
    function seleccionarItem(item) {
        inputField.value = item[settings.dataKey];
        
        // Si hay un input oculto, establecer el ID
        if (hiddenInput) {
            hiddenInput.value = item[settings.idKey];
        }
        
        // Ejecutar función personalizada si existe
        if (typeof settings.onSelect === 'function') {
            settings.onSelect(item);
        }
        
        // Ocultar sugerencias inmediatamente
        ocultarSugerencias();
    }
    
    /**
     * Quita la selección de todos los elementos
     */
    function desseleccionarTodos() {
        const items = suggestionsList.querySelectorAll('.list-group-item');
        items.forEach(item => {
            item.classList.remove('active');
        });
    }
    
    /**
     * Maneja las teclas para navegación
     * @param {KeyboardEvent} event - Evento de teclado
     */
    function manejarTeclas(event) {
        if (!isActive) return;
        
        const items = suggestionsList.querySelectorAll('.list-group-item-action');
        if (items.length === 0) return;
        
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                actualizarSeleccion(items);
                break;
                
            case 'ArrowUp':
                event.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                actualizarSeleccion(items);
                break;
                
            case 'Enter':
                event.preventDefault();
                // Si hay elemento seleccionado
                if (selectedIndex >= 0 && selectedIndex < items.length) {
                    const selectedId = items[selectedIndex].dataset.id;
                    const selectedItem = dataItems.find(item => item[settings.idKey] == selectedId);
                    if (selectedItem) {
                        seleccionarItem(selectedItem);
                    }
                } 
                // Si solo hay un elemento y no hay selección
                else if (items.length === 1) {
                    const selectedId = items[0].dataset.id;
                    const selectedItem = dataItems.find(item => item[settings.idKey] == selectedId);
                    if (selectedItem) {
                        seleccionarItem(selectedItem);
                    }
                }
                break;
                
            case 'Escape':
                ocultarSugerencias();
                break;
        }
    }
    
    /**
     * Actualiza visualmente la selección en la lista
     * @param {NodeList} items - Lista de elementos
     */
    function actualizarSeleccion(items) {
        desseleccionarTodos();
        
        if (selectedIndex >= 0 && selectedIndex < items.length) {
            items[selectedIndex].classList.add('active');
            
            // Asegurar que el elemento seleccionado sea visible
            items[selectedIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }
    
    /**
     * Cierra las sugerencias al hacer clic fuera
     * @param {MouseEvent} event - Evento de clic
     */
    function clickFuera(event) {
        if (!container.contains(event.target)) {
            ocultarSugerencias();
        }
    }
    
    /**
     * Inicializa eventos y configuración
     */
    function inicializar() {
        // Verificar estructura del DOM
        if (!inputField) {
            console.error(`Input no encontrado dentro de ${containerSelector}`);
            return;
        }
        
        if (!suggestionsList) {
            console.error(`Lista de sugerencias no encontrada dentro de ${containerSelector}`);
            return;
        }
        
        // Configurar lista con clases de Bootstrap y estilos
        suggestionsList.classList.add('list-group');
        suggestionsList.classList.add('d-none');
        suggestionsList.style.position = 'absolute';
        suggestionsList.style.width = '100%';
        suggestionsList.style.zIndex = '1000';
        suggestionsList.style.top = '100%'; // Colocar debajo del input
        suggestionsList.style.maxHeight = '200px'; // Altura máxima
        suggestionsList.style.overflowY = 'auto'; // Scroll vertical si es necesario
        suggestionsList.style.marginTop = '2px'; // Espacio entre input y sugerencias
        suggestionsList.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)'; // Sombra
        
        // Asegurar que el contenedor tenga posición relativa
        if (getComputedStyle(container).position === 'static') {
            container.style.position = 'relative';
        }
        
        // Cargar datos
        cargarDatos();
        
        // Asignar eventos
        inputField.addEventListener('input', mostrarSugerencias);
        inputField.addEventListener('keydown', manejarTeclas);
        
        // Mejorar la detección de clics fuera del contenedor
        document.addEventListener('click', clickFuera);
        document.addEventListener('touchstart', clickFuera); // Para dispositivos táctiles
        
        // Prevenir envío de formulario al presionar Enter cuando hay sugerencias activas
        inputField.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && isActive) {
                e.preventDefault();
            }
        });
    }
    
    // Inicializar
    inicializar();
    
    // Devolver métodos públicos
    return {
        recargarDatos: cargarDatos,
        ocultarSugerencias: ocultarSugerencias
    };
}

// Ejemplos de uso:
/* 
// Ejemplo básico
const autocomplete = initAutocompleteSearch('#search-container', {
    dataUrl: '/api/items/list'
});

// Ejemplo con opciones personalizadas
const autocompleteCustom = initAutocompleteSearch('#product-search', {
    dataUrl: '/api/products',
    dataKey: 'nombre', 
    idKey: 'producto_id',
    hiddenInputSelector: '#product_id',
    onSelect: function(item) {
        console.log('Producto seleccionado:', item);
        // Acciones adicionales al seleccionar
    },
    noMatchText: 'No se encontraron productos'
});

// Para recargar datos
autocomplete.recargarDatos();
*/