// Función para inicializar todas las funciones relacionadas con los bienes
function initGoodsInventoryFunctions() {
    // Inicializar formulario para crear bien
    inicializarFormularioAjax('#formCrearBien', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            if (inventarioId) {
                abrirInventario(inventarioId);
            }
        }
    });

    // Inicializar formulario para renombrar bien
    inicializarFormularioAjax('#formRenombrarBien', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            if (inventarioId) {
                abrirInventario(inventarioId);
            }
        }
    });

    // Inicializar formulario para cambiar cantidad
    inicializarFormularioAjax('#formCambiarCantidad', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            if (inventarioId) {
                abrirInventario(inventarioId);
            }
        }
    });

    // Inicializar formulario para mover bien
    inicializarFormularioAjax('#formMoverBien', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            if (inventarioId) {
                abrirInventario(inventarioId);
            }
        }
    });
    
    // Establecer el ID del inventario actual en el formulario de crear bien
    const inventarioId = localStorage.getItem('openInventory');
    if (inventarioId) {
        document.getElementById('inventarioIdBien').value = inventarioId;
    }
}

// Función para abrir el modal de crear bien
function btnCrearBien() {
    const inventarioId = localStorage.getItem('openInventory');
    if (inventarioId) {
        document.getElementById('inventarioIdBien').value = inventarioId;
        mostrarModal('#modalCrearBien');
    } else {
        showToast({
            success: false,
            message: 'No se ha seleccionado un inventario'
        });
    }
}

// Función para abrir el modal de renombrar bien
function btnRenombrarBien() {
    if (!selectedItem || selectedItem.type !== 'good') {
        showToast({
            success: false,
            message: 'Debe seleccionar un bien primero'
        });
        return;
    }
    
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    
    document.getElementById('renombrarBienId').value = id;
    document.getElementById('renombrarBienNombre').value = nombreActual;

    mostrarModal('#modalRenombrarBien');
}

// Función para abrir el modal de cambiar cantidad
function btnCambiarCantidadBien() {
    if (!selectedItem || selectedItem.type !== 'good') {
        showToast({
            success: false,
            message: 'Debe seleccionar un bien primero'
        });
        return;
    }
    
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    const cantidadActual = selectedItem.element.getAttribute('data-cantidad');
    
    document.getElementById('cambiarCantidadBienId').value = id;
    document.getElementById('cambiarCantidadBienNombre').value = nombreActual;
    document.getElementById('cambiarCantidadValor').value = cantidadActual;

    mostrarModal('#modalCambiarCantidad');
}

// Función para cargar inventarios disponibles para mover bienes
function cargarInventariosDisponibles(exceptoId) {
    fetch(`/api/get/inventarios-disponibles?excepto=${exceptoId}`)
        .then(response => response.json())
        .then(data => {
            const selectInventario = document.getElementById('moverBienInventarioDestinoId');
            selectInventario.innerHTML = '<option value="">Seleccione un inventario...</option>';
            
            if (data.success && data.inventarios) {
                data.inventarios.forEach(inventario => {
                    const option = document.createElement('option');
                    option.value = inventario.id;
                    option.textContent = inventario.nombre;
                    selectInventario.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar inventarios:', error);
            showToast({
                success: false,
                message: 'Error al cargar los inventarios disponibles'
            });
        });
}

// Función para abrir el modal de mover bien
function btnMoverBien() {
    if (!selectedItem || selectedItem.type !== 'good') {
        showToast({
            success: false,
            message: 'Debe seleccionar un bien primero'
        });
        return;
    }
    
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    const cantidadActual = selectedItem.element.getAttribute('data-cantidad');
    const inventarioActualId = localStorage.getItem('openInventory');
    
    if (!inventarioActualId) {
        showToast({
            success: false,
            message: 'No se puede determinar el inventario actual'
        });
        return;
    }
    
    document.getElementById('moverBienId').value = id;
    document.getElementById('moverBienNombre').value = nombreActual;
    document.getElementById('moverBienCantidad').value = cantidadActual;
    document.getElementById('moverBienInventarioActualId').value = inventarioActualId;
    
    // Cargar inventarios disponibles (excepto el actual)
    cargarInventariosDisponibles(inventarioActualId);

    mostrarModal('#modalMoverBien');
}

// Función para eliminar bien
function btnEliminarBien() {
    if (!selectedItem || selectedItem.type !== 'good') {
        showToast({
            success: false,
            message: 'Debe seleccionar un bien primero'
        });
        return;
    }
    
    const idBien = selectedItem.id;

    // Confirmar antes de eliminar
    if (confirm('¿Está seguro de eliminar este bien del inventario?')) {
        eliminarRegistro({
            url: `/api/bien/delete/${idBien}`,
            onSuccess: (response) => {
                showToast(response);
                if (response.success) {
                    const inventarioId = localStorage.getItem('openInventory');
                    if (inventarioId) {
                        abrirInventario(inventarioId);
                    }
                }
            }
        });
    }
}

// Función para iniciar la búsqueda de bienes en el inventario
function iniciarBusquedaBienes() {
    const input = document.getElementById('searchGoodInventory');
    const items = document.querySelectorAll('.bien-card');
    
    input.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        
        items.forEach(item => {
            const nombre = item.querySelector('.name-item').textContent.toLowerCase();
            
            if (nombre.includes(term)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
}

// Inicializar las funciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si estamos en la página de bienes del inventario
    const divGoodsInventory = document.getElementById('goods-inventory');
    if (divGoodsInventory && !divGoodsInventory.classList.contains('hidden')) {
        initGoodsInventoryFunctions();
        iniciarBusquedaBienes();
        
        // Auto-abrir el inventario guardado en localStorage si existe
        const inventarioId = localStorage.getItem('openInventory');
        if (inventarioId) {
            abrirInventario(inventarioId);
        }
    }
});

// Asegurar que el botón de crear bien use la función correcta
document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'btnCrear') {
        btnCrearBien();
    }
});