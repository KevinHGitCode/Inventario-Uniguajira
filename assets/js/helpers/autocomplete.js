/**
 * goods-autocomplete.js - Funcionalidad de autocompletado para búsqueda de bienes
 * 
 * Este archivo implementa un sistema de autocompletado para la búsqueda y selección de bienes
 * en el inventario. Consulta la API para obtener datos y proporciona sugerencias mientras el
 * usuario escribe, con soporte para navegación mediante teclado y ratón.
 * 
 * Reglas de comportamiento:
 * - Muestra sugerencias filtradas mientras el usuario escribe en el campo
 * - Indica cuando no hay coincidencias o cuando no hay bienes disponibles
 * - Permite navegar por las sugerencias usando las teclas de dirección (arriba/abajo)
 * - Selecciona automáticamente la única opción disponible al presionar Enter
 * - No realiza acción al presionar Enter si hay múltiples opciones sin selección
 * - Asigna el ID del bien seleccionado al campo oculto correspondiente
 * - Habilita campos adicionales según el tipo de bien (Cantidad o Serial)
 * 
 * Esta implementación mejora la experiencia de usuario al facilitar la búsqueda
 * y reducir errores en la selección de bienes durante el proceso de inventario.
 * 
 * @version 1.0
 * @date 2025-04-20
 */

/**
 * initAutocompletadoBienes - Inicializa el autocompletado para campo de nombre de bien
 * Consulta bienes disponibles y ofrece sugerencias mientras el usuario escribe
 */
function initAutocompletadoBienes() {
    // Referencia a elementos DOM
    const nombreBienInput = document.getElementById('nombreBien');
    const bienIdInput = document.getElementById('bien_id');
    const cantidadContainer = document.querySelector('[for="cantidadBien"]').parentElement;
    const serialContainer = document.querySelector('[for="serialBien"]').parentElement;
    
    // Variables para el control de sugerencias
    let bienes = [];
    let sugerenciasActivas = false;
    let indiceSeleccionado = -1;
    let sugerenciasList = null;
    
    // Crear contenedor de sugerencias
    function crearContenedorSugerencias() {
        // Si ya existe, no crear otro
        if (document.getElementById('sugerencias-bienes')) {
            return document.getElementById('sugerencias-bienes');
        }
        
        const sugerenciasContainer = document.createElement('div');
        sugerenciasContainer.id = 'sugerencias-bienes';
        sugerenciasContainer.className = 'sugerencias-container';
        sugerenciasContainer.style.position = 'absolute';
        sugerenciasContainer.style.width = `${nombreBienInput.offsetWidth}px`;
        sugerenciasContainer.style.maxHeight = '200px';
        sugerenciasContainer.style.overflowY = 'auto';
        sugerenciasContainer.style.border = '1px solid #ccc';
        sugerenciasContainer.style.borderTop = 'none';
        sugerenciasContainer.style.borderRadius = '0 0 4px 4px';
        sugerenciasContainer.style.backgroundColor = '#fff';
        sugerenciasContainer.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
        sugerenciasContainer.style.zIndex = '1000';
        sugerenciasContainer.style.display = 'none';
        
        // Insertar después del input
        nombreBienInput.parentNode.style.position = 'relative';
        nombreBienInput.parentNode.appendChild(sugerenciasContainer);
        
        return sugerenciasContainer;
    }
    
    // Cargar los bienes desde la API al inicio
    function cargarBienes() {
        fetch('/api/goods/get/json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al consultar bienes');
                }
                return response.json();
            })
            .then(data => {
                bienes = data;
                console.log('Bienes cargados:', bienes.length, '\n', bienes);
            })
            .catch(error => {
                console.error('Error cargando bienes:', error);
            });
    }
    
    // Mostrar sugerencias basadas en el texto ingresado
    function mostrarSugerencias() {
        const query = nombreBienInput.value.toLowerCase().trim();
        const sugerenciasContainer = crearContenedorSugerencias();
        
        // Limpiar sugerencias anteriores
        sugerenciasContainer.innerHTML = '';
        indiceSeleccionado = -1;
        
        // Si no hay texto, ocultar sugerencias
        if (!query) {
            sugerenciasContainer.style.display = 'none';
            sugerenciasActivas = false;
            return;
        }
        
        // Si no hay bienes disponibles
        if (!bienes || bienes.length === 0) {
            sugerenciasContainer.style.display = 'block';
            const noData = document.createElement('div');
            noData.className = 'sugerencia-item no-data';
            noData.textContent = 'No hay bienes disponibles';
            noData.style.padding = '8px 12px';
            noData.style.color = '#dc3545';
            sugerenciasContainer.appendChild(noData);
            sugerenciasActivas = true;
            return;
        }
        
        // Filtrar bienes que coincidan con la búsqueda
        const coincidencias = bienes.filter(bien => 
            bien.bien.toLowerCase().includes(query)
        );
        
        // Si no hay coincidencias
        if (coincidencias.length === 0) {
            sugerenciasContainer.style.display = 'block';
            const noMatch = document.createElement('div');
            noMatch.className = 'sugerencia-item no-match';
            noMatch.textContent = 'No hay coincidencias';
            noMatch.style.padding = '8px 12px';
            noMatch.style.color = '#dc3545';
            sugerenciasContainer.appendChild(noMatch);
            sugerenciasActivas = true;
            return;
        }
        
        // Si hay una coincidencia exacta, no mostrar sugerencias
        const coincidenciaExacta = coincidencias.length === 1 && 
                                   coincidencias[0].bien.toLowerCase() === query;
        
        if (coincidenciaExacta) {
            sugerenciasContainer.style.display = 'none';
            sugerenciasActivas = false;
            return;
        }
        
        // Mostrar las coincidencias
        sugerenciasContainer.style.display = 'block';
        sugerenciasActivas = true;
        
        // Crear lista de sugerencias
        sugerenciasList = document.createElement('ul');
        sugerenciasList.style.listStyle = 'none';
        sugerenciasList.style.margin = '0';
        sugerenciasList.style.padding = '0';
        
        coincidencias.forEach((bien, index) => {
            const item = document.createElement('li');
            item.className = 'sugerencia-item';
            item.textContent = bien.bien;
            item.dataset.id = bien.id;
            item.dataset.tipo = bien.tipo;
            item.style.padding = '8px 12px';
            item.style.cursor = 'pointer';
            
            // Resaltar parte coincidente
            const texto = bien.bien;
            const textoLower = texto.toLowerCase();
            const posicion = textoLower.indexOf(query);
            
            if (posicion !== -1) {
                item.innerHTML = texto.substring(0, posicion) + 
                                '<strong>' + texto.substring(posicion, posicion + query.length) + '</strong>' + 
                                texto.substring(posicion + query.length);
            }
            
            // Eventos al pasar el mouse
            item.addEventListener('mouseover', () => {
                desseleccionarTodos();
                item.style.backgroundColor = '#f0f0f0';
                indiceSeleccionado = index;
            });
            
            item.addEventListener('mouseout', () => {
                item.style.backgroundColor = '';
            });
            
            // Evento al hacer clic
            item.addEventListener('click', () => {
                seleccionarBien(bien);
            });
            
            sugerenciasList.appendChild(item);
        });
        
        sugerenciasContainer.appendChild(sugerenciasList);
    }
    
    // Seleccionar un bien de la lista
    function seleccionarBien(bien) {
        nombreBienInput.value = bien.bien;
        bienIdInput.value = bien.id;
        
        // Actualizar campos según el tipo de bien
        if (bien.tipo === 'Cantidad') {
            cantidadContainer.style.display = 'block';
            serialContainer.style.display = 'none';
        } else if (bien.tipo === 'Serial') {
            cantidadContainer.style.display = 'none';
            serialContainer.style.display = 'block';
        }
        
        // Ocultar sugerencias
        const sugerenciasContainer = document.getElementById('sugerencias-bienes');
        if (sugerenciasContainer) {
            sugerenciasContainer.style.display = 'none';
        }
        sugerenciasActivas = false;
    }
    
    // Quitar la selección de todos los elementos
    function desseleccionarTodos() {
        if (!sugerenciasList) return;
        
        const items = sugerenciasList.querySelectorAll('.sugerencia-item');
        items.forEach(item => {
            item.style.backgroundColor = '';
        });
    }
    
    // Navegar por las sugerencias con teclado
    function manejarTeclas(event) {
        // Si no hay sugerencias activas, no hacer nada
        if (!sugerenciasActivas || !sugerenciasList) return;
        
        const items = sugerenciasList.querySelectorAll('.sugerencia-item');
        if (items.length === 0) return;
        
        // Si es una sugerencia de "No hay coincidencias" o "No hay bienes disponibles"
        const tieneNoMatch = sugerenciasList.querySelector('.no-match') || sugerenciasList.querySelector('.no-data');
        if (tieneNoMatch) return;
        
        // Manejar teclas de navegación
        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                indiceSeleccionado = (indiceSeleccionado + 1) % items.length;
                actualizarSeleccion(items);
                break;
                
            case 'ArrowUp':
                event.preventDefault();
                indiceSeleccionado = (indiceSeleccionado - 1 + items.length) % items.length;
                actualizarSeleccion(items);
                break;
                
            case 'Enter':
                event.preventDefault();
                // Si hay elemento seleccionado
                if (indiceSeleccionado >= 0 && indiceSeleccionado < items.length) {
                    const bienSeleccionado = bienes.find(b => b.id == items[indiceSeleccionado].dataset.id);
                    if (bienSeleccionado) {
                        seleccionarBien(bienSeleccionado);
                    }
                } 
                // Si solo hay un elemento y no hay selección
                else if (items.length === 1) {
                    const bienSeleccionado = bienes.find(b => b.id == items[0].dataset.id);
                    if (bienSeleccionado) {
                        seleccionarBien(bienSeleccionado);
                    }
                }
                // Si hay múltiples opciones pero ninguna seleccionada, no hacer nada
                break;
                
            case 'Escape':
                // Ocultar sugerencias
                const sugerenciasContainer = document.getElementById('sugerencias-bienes');
                if (sugerenciasContainer) {
                    sugerenciasContainer.style.display = 'none';
                }
                sugerenciasActivas = false;
                break;
        }
    }
    
    // Actualizar visualmente la selección
    function actualizarSeleccion(items) {
        desseleccionarTodos();
        
        if (indiceSeleccionado >= 0 && indiceSeleccionado < items.length) {
            items[indiceSeleccionado].style.backgroundColor = '#f0f0f0';
            
            // Asegurar que el elemento seleccionado sea visible
            items[indiceSeleccionado].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }
    }
    
    // Cerrar sugerencias al hacer clic fuera
    function clickFuera(event) {
        const sugerenciasContainer = document.getElementById('sugerencias-bienes');
        if (sugerenciasContainer && !nombreBienInput.contains(event.target) && !sugerenciasContainer.contains(event.target)) {
            sugerenciasContainer.style.display = 'none';
            sugerenciasActivas = false;
        }
    }
    
    // Inicializar eventos
    function inicializar() {
        // Cargar bienes al inicio
        cargarBienes();
        
        // Mostrar sugerencias al escribir
        nombreBienInput.addEventListener('input', mostrarSugerencias);
        
        // Navegación con teclado
        nombreBienInput.addEventListener('keydown', manejarTeclas);
        
        // Cerrar sugerencias al hacer clic fuera
        document.addEventListener('click', clickFuera);
        
        // Prevenir envío de formulario al presionar Enter en el campo
        nombreBienInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && sugerenciasActivas) {
                e.preventDefault();
            }
        });
    }
    
    // Iniciar funcionalidad
    inicializar();
    
    // Retornar un objeto con métodos públicos si es necesario
    return {
        recargarBienes: cargarBienes
        // Puedes añadir más métodos públicos aquí si los necesitas
    };
};

// Para usar esta función:
// let autocomplete = initAutocompletadoBienes();
// 
// Para recargar los bienes si es necesario:
// autocomplete.recargarBienes();