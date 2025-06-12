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
    const searchInput = document.getElementById('searchRecordInput');
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

// Función para inicializar todo
function inicializarHistorial() {
    iniciarBusquedaHistorial('searchRecordInput');
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

    // Cargar usuarios en la sección de filtros
    const userListContainer = document.getElementById('userList');
    if (userListContainer && window.allUserNames) {
        window.allUserNames.forEach(userName => {
            const userCheckbox = document.createElement('label');
            userCheckbox.className = 'checkbox-container';
            userCheckbox.innerHTML = `
                <input type="checkbox" class="user-checkbox" value="${userName}">
                <span class="checkmark"></span>
                <span class="checkbox-label">${userName}</span>
            `;
            userListContainer.appendChild(userCheckbox);
        });
    }
}


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
    ocultarModal('#Modalfiltrarhistorial')
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
    ocultarModal('#Modalfiltrarhistorial')
}



// scripts boton reporte---------------------------->>>>>>>
function generatePDF() {

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

        // Cargar la imagen
        const img = new Image();
        img.src = 'assets/images/logoUniguajira.png'; // Ruta de la imagen

        img.onload = function() {
            // Agregar imagen al encabezado
            doc.addImage(img, 'WEBP', 10, 10, 50, 20);

            const titleY = 45;
            doc.setFontSize(16);
            doc.text('Reporte de Historial', 20, titleY);
            doc.setFontSize(12);
            doc.text('Fecha de generación: ' + new Date().toLocaleDateString(), 20, titleY + 15);

            const table = document.querySelector('.record-table');
            if (!table) {
                console.error('No se encontró la tabla de historial.');
                alert('Error: No se encontró la tabla de historial.');
                return;
            }

            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
            
            // CAMBIO PRINCIPAL: Solo incluir filas visibles (no ocultas por filtros)
            const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => {
                return row.style.display !== 'none'; // Solo filas que no están ocultas
            });

            const rows = visibleRows.map(row => {
                return Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.trim());
            });

            // Agregar información sobre filtros aplicados
            let filterInfo = '';
            const totalRows = table.querySelectorAll('tbody tr').length;
            const visibleRowsCount = visibleRows.length;
            
            if (visibleRowsCount < totalRows) {
                filterInfo = `Registros mostrados: ${visibleRowsCount} de ${totalRows} (filtros aplicados)`;
                doc.setFontSize(10);
                doc.text(filterInfo, 20, titleY + 25);
            }

            // Verificar si hay datos para mostrar
            if (rows.length === 0) {
                doc.setFontSize(12);
                doc.text('No hay registros que coincidan con los filtros aplicados.', 20, titleY + 40);
            } else {
                doc.autoTable({
                    head: [headers],
                    body: rows,
                    startY: titleY + (filterInfo ? 35 : 30),
                });
            }

            doc.save('historial_reporte.pdf');
            console.log('✅ PDF generado exitosamente con filtros aplicados');
        };

        img.onerror = function() {
            console.error('❌ Error al cargar la imagen.');
            alert('Error al cargar la imagen.');
        };

    } catch (error) {
        console.error('❌ Error al generar PDF:', error);
        alert('Error al generar PDF: ' + error.message);
    }
}