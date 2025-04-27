// Función para inicializar todas las funciones de bienes del inventario
function initGoodsInventoryFunctions() {
    // Inicializar formulario para crear bien
    inicializarFormularioAjax('#formCrearBienInventario', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            abrirInventario(inventarioId, false);
        },
        onBeforeSubmit: () => {
            // Validación adicional antes de enviar el formulario
            const bienId = document.getElementById('bien_id').value;
            if (!bienId) {
                showToast({success: false, message: 'Debe seleccionar un bien válido'});
                return false;
            }
            return true;
        }
    });

    // Otros inicializadores...

    // Uso con el autocomplete
    // const autocomplete = initAutocompleteSearch('#search-container', {
    //     dataUrl: '/api/goods/get/json',
    //     inputSelector: '#nombreBien',
    //     listSelector: '.suggestions',
    //     hiddenInputSelector: '#bien_id',
    //     onSelect: gestionarCamposBien
    // });

    
    // Inicializar el autocompletado para búsqueda de bienes
    const bienesAutocomplete = initAutocompleteSearch('#search-container', {
        dataUrl: '/api/goods/get/json', // Ajustar a la ruta correcta de tu API
        dataKey: 'nombre',           // Campo para mostrar en las sugerencias
        idKey: 'id',                 // Campo para el valor del ID
        hiddenInputSelector: '#bien_id',
        onSelect: function(item) {
            console.log('Bien seleccionado:', item);
            
            // Mostrar campos dinámicos según el tipo de bien
            const dynamicFields = document.getElementById('dynamicFields');
            const camposCantidad = document.getElementById('camposCantidad');
            const camposSerial = document.getElementById('camposSerial');
            
            // Mostrar el contenedor principal
            dynamicFields.style.display = 'block';
            
            // Mostrar campos según el tipo (este es un ejemplo, ajustarlo según tu lógica)
            if (item.tipo === 'cantidad') {
                camposCantidad.style.display = 'block';
                camposSerial.style.display = 'none';
            } else if (item.tipo === 'serial') {
                camposCantidad.style.display = 'none';
                camposSerial.style.display = 'block';
            }
        },
        noMatchText: 'No se encontraron bienes con ese nombre',
        noDataText: 'No hay bienes disponibles'
    });
    
    // Cuando se abre el modal, asegurarse de que las sugerencias estén ocultas
    const modalBtn = document.querySelector('[data-target="#modalCrearBienInventario"]');
    if (modalBtn) {
        modalBtn.addEventListener('click', function() {
            bienesAutocomplete.ocultarSugerencias();
        });
    }
    
    // Cuando se cierra el modal, asegurarse de que las sugerencias estén ocultas
    document.querySelector('#modalCrearBienInventario .close').addEventListener('click', function() {
        bienesAutocomplete.ocultarSugerencias();
    });
}

/**
 * Gestiona la visibilidad de los campos según el tipo de bien seleccionado
 * @param {Object} item - Objeto del bien seleccionado
 */
function gestionarCamposBien(item) {
    console.log("Bien seleccionado:", item);
    
    // Mostrar el contenedor principal de campos dinámicos
    const dynamicFields = document.getElementById('dynamicFields');
    dynamicFields.style.display = 'block';
    
    // Obtener los contenedores de los diferentes tipos de campos
    const camposCantidad = document.getElementById('camposCantidad');
    const camposSerial = document.getElementById('camposSerial');
    
    // Ocultar todos los campos primero
    camposCantidad.style.display = 'none';
    camposSerial.style.display = 'none';
    
    // Mostrar campos según el tipo
    if (item.tipo === 'Cantidad') {
        camposCantidad.style.display = 'block';
        // Establecer el valor mínimo a 1 por defecto
        document.getElementById('cantidadBien').value = 1;
    } else if (item.tipo === 'Serial') {
        camposSerial.style.display = 'block';
        // Limpiar campos del formulario de serial
        limpiarCamposSerial();
    }
}

/**
 * Limpia los campos del formulario de bienes tipo Serial
 */
function limpiarCamposSerial() {
    document.getElementById('descripcionBien').value = '';
    document.getElementById('marcaBien').value = '';
    document.getElementById('modeloBien').value = '';
    document.getElementById('serialBien').value = '';
    document.getElementById('estadoBien').value = 'activo';
    document.getElementById('colorBien').value = '';
    document.getElementById('condicionBien').value = '';
    
    // Establecer la fecha actual
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0];
    document.getElementById('fechaIngresoBien').value = formattedDate;
}

// Al abrir el modal de creación de bien
function abrirModalCrearBien() {
    // Obtener el ID del inventario desde localStorage
    const inventarioId = localStorage.getItem('openInventory');
    if (!inventarioId) {
        showToast({ success: false, message: 'No se ha seleccionado un inventario abierto' });
        return;
    }

    // Establecer el ID del inventario en el formulario
    document.getElementById('inventarioId').value = inventarioId;

    // Limpiar el campo de búsqueda
    document.getElementById('nombreBien').value = '';
    document.getElementById('bien_id').value = '';

    // Ocultar todos los campos dinámicos
    document.getElementById('dynamicFields').style.display = 'none';

    // Mostrar el modal
    mostrarModal('#modalCrearBienInventario');
}