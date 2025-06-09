function iniciarBusquedaHistorial(searchInputID) {
    // Obtiene el campo de entrada para la búsqueda
    const searchInput = document.getElementById(searchInputID);
    if (!searchInput) {
        // Muestra una advertencia si no se encuentra el campo de búsqueda
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    // Agrega un evento para detectar cuando el usuario escribe en el campo de búsqueda
    searchInput.addEventListener('keyup', function () {
        // Convierte el texto ingresado a minúsculas para una búsqueda insensible a mayúsculas
        const filter = searchInput.value.toLowerCase();
        // Obtiene todas las tarjetas de bienes
        const cards = document.querySelectorAll(".card-item");

        // Itera sobre cada tarjeta y verifica si coincide con el texto de búsqueda
        cards.forEach(item => {
            const text = item.querySelector(".name-item").textContent.toLowerCase();
            // Muestra u oculta la tarjeta según si coincide con el filtro
            item.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

function activarBusquedaEnTablaHistorial() {
    // Obtiene el campo de entrada para la búsqueda
    const searchInput = document.getElementById('searchRecordInput');
    if (!searchInput) {
        console.warn("No se encontró el campo de búsqueda.");
        return;
    }

    // Agregar contenedor para el indicador de búsqueda
    const searchContainer = searchInput.parentElement;
    let searchSpinner = document.createElement('div');
    searchSpinner.className = 'search-spinner';
    searchSpinner.style.display = 'none';
    searchSpinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    searchContainer.appendChild(searchSpinner);

    // Agregar clases CSS para la modalidad de búsqueda
    searchInput.classList.add('search-input');
    
    // Contador de resultados
    let resultCounter = document.createElement('div');
    resultCounter.className = 'result-counter';
    resultCounter.style.display = 'none';
    resultCounter.innerHTML = 'Resultados encontrados: 0';
    searchContainer.appendChild(resultCounter);

    // Variable para el temporizador de debounce
    let searchTimeout = null;

    // Agrega un evento para detectar cuando el usuario escribe en el campo de búsqueda
    searchInput.addEventListener('keyup', function () {
        // Mostrar el spinner mientras se prepara la búsqueda
        searchSpinner.style.display = 'inline-block';
        
        // Añadir clase activa al campo de búsqueda
        searchInput.classList.add('searching');
        
        // Obtener la tabla y aplicar estilo de "cargando"
        const table = document.querySelector("table");
        if (table) {
            table.classList.add('table-searching');
        }
        
        // Cancelar temporizador anterior si existe
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Iniciar un nuevo temporizador para ejecutar la búsqueda después de 300ms
        searchTimeout = setTimeout(function() {
            const filter = searchInput.value.toLowerCase();
            // Obtiene todas las filas de la tabla
            const rows = document.querySelectorAll("table tbody tr");
            let foundCount = 0;

            // Itera sobre cada fila y verifica si coincide con el texto de búsqueda
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const match = text.includes(filter);
                
                // Muestra u oculta la fila según si coincide con el filtro
                row.style.display = match ? '' : 'none';
                
                // Efecto visual en las filas coincidentes
                if (match) {
                    foundCount++;
                    row.classList.add('highlight-row');
                    
                    // Quitar la clase de resaltado después de un tiempo
                    setTimeout(() => {
                        row.classList.remove('highlight-row');
                    }, 800);
                }
            });
            
            // Actualizar contador de resultados
            resultCounter.innerHTML = `Resultados encontrados: ${foundCount}`;
            resultCounter.style.display = 'block';
            
            // Ocultar el spinner
            searchSpinner.style.display = 'none';
            
            // Quitar clase activa de búsqueda
            searchInput.classList.remove('searching');
            
            // Quitar clase de "cargando" de la tabla
            if (table) {
                table.classList.remove('table-searching');
            }
            
            // Mostrar mensaje cuando no hay resultados
            const noResultsMsg = document.getElementById('noResultsMessage');
            if (foundCount === 0 && filter !== '') {
                // Crear mensaje si no existe
                if (!noResultsMsg) {
                    const msg = document.createElement('div');
                    msg.id = 'noResultsMessage';
                    msg.className = 'no-results';
                    msg.innerHTML = 'No se encontraron registros que coincidan con la búsqueda';
                    table.parentNode.appendChild(msg);
                } else {
                    noResultsMsg.style.display = 'block';
                }
            } else if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
            
        }, 300); // Debounce de 300ms
    });
    
    // Agregar evento para cuando se enfoca el campo de búsqueda
    searchInput.addEventListener('focus', function() {
        searchInput.classList.add('search-focus');
    });
    
    // Agregar evento para cuando se pierde el foco del campo de búsqueda
    searchInput.addEventListener('blur', function() {
        searchInput.classList.remove('search-focus');
    });
    
    // Agregar botón para limpiar la búsqueda
    // const clearButton = document.createElement('button');
    // clearButton.className = 'clear-search';
    // clearButton.innerHTML = '<i class="fas fa-times"></i>';
    // clearButton.style.display = 'none';
    // searchContainer.appendChild(clearButton);
    
    // // Mostrar/ocultar botón limpiar según contenido
    // searchInput.addEventListener('input', function() {
    //     clearButton.style.display = this.value ? 'block' : 'none';
    // });
    
    // // Limpiar búsqueda al hacer clic en el botón
    // clearButton.addEventListener('click', function() {
    //     searchInput.value = '';
    //     searchInput.dispatchEvent(new Event('keyup'));
    //     clearButton.style.display = 'none';
    //     resultCounter.style.display = 'none';
    // });
}

// CSS para los nuevos estilos (agregar al head del documento)
//  function agregarEstilosHistorial() {
//     const style = document.createElement('style');
//     style.textContent = `
//         .search-input {
//             padding-right: 30px;
//             transition: all 0.3s ease;
//             border: 1px solid #ced4da;
//             border-radius: 4px;
//         }
        
//         .search-focus {
//             box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
//             border-color: #80bdff;
//         }
        
//         .searching {
//             background-color: #f8f9fa;
//         }
        
//         .search-spinner {
//             position: absolute;
//             right: 40px;
//             top: 50%;
//             transform: translateY(-50%);
//             color: #007bff;
//         }
        
//         .result-counter {
//             font-size: 0.8rem;
//             color: #6c757d;
//             margin-top: 5px;
//         }
        
//         .table-searching {
//             opacity: 0.8;
//             transition: opacity 0.3s ease;
//         }
        
//         .highlight-row {
//             animation: highlightFade 1.5s ease;
//         }
        
//         @keyframes highlightFade {
//             0% { background-color: rgba(255, 255, 140, 0.8); }
//             100% { background-color: transparent; }
//         }
        
//         .clear-search {
//             position: absolute;
//             right: 10px;
//             top: 50%;
//             transform: translateY(-50%);
//             background: none;
//             border: none;
//             color: #6c757d;
//             cursor: pointer;
//             padding: 0;
//             font-size: 1rem;
//         }
        
//         .clear-search:hover {
//             color: #495057;
//         }
        
//         .no-results {
//             text-align: center;
//             padding: 20px;
//             color: #dc3545;
//             font-weight: bold;
//         }
//     `;
//     document.head.appendChild(style);
//  }

// Función para inicializar todo
function inicializarHistorial() {
    agregarEstilosHistorial();
    activarBusquedaEnTablaHistorial();
    
    // Asegurarse de que la estructura del contenedor de búsqueda sea correcta
    const searchInput = document.getElementById('searchRecordInput');
    if (searchInput && !searchInput.parentElement.classList.contains('search-container')) {
        const wrapper = document.createElement('div');
        wrapper.className = 'search-container';
        wrapper.style.position = 'relative';
        searchInput.parentNode.insertBefore(wrapper, searchInput);
        wrapper.appendChild(searchInput);
    }
}

// boton de filtro -----------------------

// Función para alternar todos los checkboxes de usuarios cuando se cambia "Todos los usuarios"
function toggleAllUsers() {
    const allUsersCheckbox = document.getElementById('allUsers');
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    userCheckboxes.forEach(cb => {
        cb.checked = allUsersCheckbox.checked;
    });
}

// Función para alternar todos los checkboxes de acciones cuando se cambia "Todas las acciones"
function toggleAllActions() {
    const allActionsCheckbox = document.getElementById('allActions');
    const actionCheckboxes = document.querySelectorAll('.action-checkbox');
    actionCheckboxes.forEach(cb => {
        cb.checked = allActionsCheckbox.checked;
    });
}

// Sincronizar el checkbox "Todas las acciones" si cambia algún checkbox de acción
function updateActionSelection() {
    const allActionsCheckbox = document.getElementById('allActions');
    const actionCheckboxes = document.querySelectorAll('.action-checkbox');
    const allChecked = Array.from(actionCheckboxes).every(cb => cb.checked);
    allActionsCheckbox.checked = allChecked;
}

// Aplicar filtros a la tabla de historial según los usuarios y acciones seleccionados
function applyFilters() {
    const userCheckboxes = document.querySelectorAll('.user-checkbox');
    const actionCheckboxes = document.querySelectorAll('.action-checkbox');

    const selectedUsers = Array.from(userCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    const selectedActions = Array.from(actionCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    const rows = document.querySelectorAll('.record-table tbody tr');

    rows.forEach(row => {
        const userCell = row.cells[1].textContent.trim();
        const actionCell = row.cells[2].textContent.trim();

        // Si no se seleccionan usuarios, se considera que se seleccionan todos
        const userMatch = selectedUsers.length === 0 || selectedUsers.includes(userCell);
        // Si no se seleccionan acciones, se considera que se seleccionan todas
        const actionMatch = selectedActions.length === 0 || selectedActions.includes(actionCell);

        if (userMatch && actionMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Cerrar el modal después de aplicar los filtros
    closeModal();
}

// Limpiar todos los filtros y mostrar todas las filas
function clearFilters() {
    // Desmarcar todos los checkboxes de usuarios y el de 'todos'
    const allUsersCheckbox = document.getElementById('allUsers');
    allUsersCheckbox.checked = false;
    document.querySelectorAll('.user-checkbox').forEach(cb => { cb.checked = false; });

    // Desmarcar todos los checkboxes de acciones y el de 'todos'
    const allActionsCheckbox = document.getElementById('allActions');
    allActionsCheckbox.checked = false;
    document.querySelectorAll('.action-checkbox').forEach(cb => { cb.checked = false; });

    // Mostrar todas las filas
    document.querySelectorAll('.record-table tbody tr').forEach(row => {
        row.style.display = '';
    });

    // Cerrar el modal
    closeModal();
}











// scripts boton reporte---------------------------->>>>>>>
function generatePDF() {
    console.log('=== DEBUG JSPDF ===');
    console.log('window.jspdf:', typeof window.jspdf);
    console.log('window.jsPDF:', typeof window.jsPDF);

    if ((!window.jspdf || !window.jspdf.jsPDF) && !window.jsPDF) {
        alert('Las librerías PDF no están cargadas. Cargando...');
        loadJsPDFDynamically();
        return;
    }

    let jsPDF;
    if (window.jspdf && window.jspdf.jsPDF) {
        jsPDF = window.jspdf.jsPDF;
    } else if (window.jsPDF) {
        jsPDF = window.jsPDF;
    } else {
        alert('Error: No se puede acceder a jsPDF');
        return;
    }

    try {
        const doc = new jsPDF();

        doc.setFontSize(16);
        doc.text('Reporte de Historial', 20, 20);
        doc.setFontSize(12);
        doc.text('Fecha de generación: ' + new Date().toLocaleDateString(), 20, 35);

        const table = document.querySelector('.record-table');
        if (!table) {
            console.error('No se encontró la tabla de historial.');
            alert('Error: No se encontró la tabla de historial.');
            return;
        }

        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(row => {
            return Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.trim());
        });

        doc.autoTable({
            head: [headers],
            body: rows,
            startY: 40,
        });

        doc.save('historial_reporte.pdf');
        console.log('✅ PDF generado exitosamente');

    } catch (error) {
        console.error('❌ Error al generar PDF:', error);
        alert('Error al generar PDF: ' + error.message);
    }
}

// Función para cargar jsPDF dinámicamente
function loadJsPDFDynamically() {
    const script1 = document.createElement('script');
    script1.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script1.onload = function() {
        const script2 = document.createElement('script');
        script2.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js';
        script2.onload = function() {
            console.log('Librerías cargadas dinámicamente');
            setTimeout(generatePDF, 100); // Reintentar después de cargar
        };
        document.head.appendChild(script2);
    };
    document.head.appendChild(script1);
}
document.addEventListener('DOMContentLoaded', () => {
    const reportBtn = document.getElementById('reportBtn');
    if (reportBtn) {
        reportBtn.addEventListener('click', () => {
            console.log('Botón Reportes presionado.');

            // Verificar si jsPDF está disponible
            if (!window.jspdf || !window.jspdf.jsPDF) {
                console.error('jsPDF no está cargado correctamente.');
                alert('Error: jsPDF no está disponible.');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título del documento
            doc.setFontSize(16);
            doc.text('Reporte de Historial', 20, 20);

            // Obtener datos de la tabla
            const table = document.querySelector('.record-table');
            if (!table) {
                console.error('No se encontró la tabla de historial.');
                alert('Error: No se encontró la tabla de historial.');
                return;
            }

            const rows = [];
            table.querySelectorAll('tbody tr').forEach(row => {
                const rowData = [];
                row.querySelectorAll('td').forEach(cell => {
                    rowData.push(cell.textContent.trim());
                });
                rows.push(rowData);
            });

            // Generar tabla en el PDF
            doc.autoTable({
                head: [['N°', 'Usuario', 'Acción', 'Tabla', 'Registro ID', 'Detalles', 'Fecha y Hora']],
                body: rows,
                startY: 30,
            });

            // Guardar el PDF
            doc.save('historial_reporte.pdf');
            console.log('✅ PDF generado exitosamente');
        });
    } else {
        console.error('No se encontró el botón Reportes.');
    }
});
// Test simple - añadir al final del archivo
console.log('historial.js cargado correctamente');

function testButton() {
    alert('¡El botón funciona!');
    console.log('Botón clickeado');
}

