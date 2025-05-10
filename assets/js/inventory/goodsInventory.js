// Inicializa las funciones relacionadas con bienes del inventario
function initGoodsInventoryFunctions() {
    // Inicializa el formulario para crear un bien en el inventario
    inicializarFormularioAjax('#formCrearBienInventario', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            const inventarioId = localStorage.getItem('openInventory');
            // abrirInventario(inventarioId, false);
            loadContent('/inventory', false);
        }
    });
    
    initAutocompleteForBien();
}

function abrirModalCrearBien() {
    const inventarioId = localStorage.getItem('openInventory');
    if (!inventarioId) {
        showToast({ success: false, message: 'No se ha seleccionado un inventario abierto' });
        return;
    }

    document.getElementById('inventarioId').value = inventarioId;
    document.getElementById('nombreBienEnInventario').value = '';
    document.getElementById('bien_id').value = '';
    document.getElementById('dynamicFields').style.display = 'none';

    mostrarModal('#modalCrearBienInventario');
}


function verDetalleBienSerialInventario(bien_id, inventario_id) {
    console.log(bien_id);
    console.log(inventario_id);
}