// Función para inicializar todas las funciones de bienes del inventario
function initGoodsInventoryFunctions() {
    // Inicializar formulario para crear bien
    inicializarFormularioAjax('#formCrearBien', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            abrirInventario(inventarioId, false);
        }
    });

    // Inicializar formulario para cambiar cantidad de bien
    inicializarFormularioAjax('#formCambiarCantidadBien', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            abrirInventario(inventarioId, false);
        }
    });

    // Inicializar formulario para mover bien
    inicializarFormularioAjax('#formMoverBien', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            abrirInventario(inventarioId, false);
        }
    });


    // Uso con el autocomplete
    const autocomplete = initAutocompleteSearch('#search-container', {
        dataUrl: '/api/goods/get/json',
        inputSelector: '#nombreBien',
        listSelector: '.suggestions',
        hiddenInputSelector: '#bien_id',
        onSelect: gestionarCamposBien
    });
}

// Función para abrir el modal de cambiar cantidad de bien
function btnCambiarCantidadBien() {
    console.log("Click en cambiar cantidad de bien");
    const id = selectedItem.id;
    const cantidad = selectedItem.cantidad;
    
    document.getElementById("cambiarCantidadBienId").value = id;
    document.getElementById("cantidadBien").value = cantidad;

    mostrarModal('#modalCambiarCantidadBien');
}

// Función para abrir el modal de mover bien
function btnMoverBien() {
    console.log("Click en mover bien");
    const id = selectedItem.id;
    
    document.getElementById("moverBienId").value = id;
    
    // Cargar inventarios disponibles para mover
    cargarInventariosDisponibles();
    
    mostrarModal('#modalMoverBien');
}

// Función para eliminar un bien directamente (alternativa al modal)
function eliminarBienInventario(idBien) {
    eliminarRegistro({
        url: `/api/goods-inventory/delete/${idBien}`,
        onSuccess: (response) => {
            if (response.success) {
                const inventarioId = localStorage.getItem('openInventory');
                abrirInventario(inventarioId, false);
            }
            showToast(response);
        }
    });
}


function gestionarCamposBien(item) {
    // Obtener el contenedor del campo cantidad
    const campoCantidad = document.getElementById('cantidadBien').parentElement;
    
    // Obtener los contenedores de los campos para bienes de tipo Serial
    const camposSerial = [
        document.getElementById('descripcionBien').parentElement,
        document.getElementById('marcaBien').parentElement,
        document.getElementById('modeloBien').parentElement,
        document.getElementById('serialBien').parentElement,
        document.getElementById('estadoBien').parentElement,
        document.getElementById('colorBien').parentElement,
        document.getElementById('condicionBien').parentElement,
        document.getElementById('fechaIngresoBien').parentElement
    ];
    
    // Ocultar todos los campos primero
    campoCantidad.style.display = 'none';
    camposSerial.forEach(campo => campo.style.display = 'none');
    
    // Mostrar campos según el tipo
    if (item.tipo === 'Cantidad') {
        campoCantidad.style.display = 'block';
    } else if (item.tipo === 'Serial') {
        camposSerial.forEach(campo => campo.style.display = 'block');
    }
}