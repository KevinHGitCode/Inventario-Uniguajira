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

function cerrarInventarioSerial() {
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divSerialsGoodsInventory = document.getElementById('serials-goods-inventory');

    // Ocultar la vista de bienes seriales y mostrar la vista principal de bienes
    divSerialsGoodsInventory.classList.add('hidden');
    divGoodsInventory.classList.remove('hidden');

    toggleInventoryControls(true);
}

function verDetalleBienSerialInventario(inventarioId, bienId) {
    console.log(inventarioId, bienId)
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divSerialsGoodsInventory = document.getElementById('serials-goods-inventory');
    const divContent = document.getElementById('serials-goods-inventory-content');

    // Mostrar loader
    divContent.innerHTML = '<p>Cargando detalles del bien serial...</p>';
    divGoodsInventory.classList.add('hidden');
    divSerialsGoodsInventory.classList.remove('hidden');

    fetch(`/api/get/serialGoodsInventory/${inventarioId}/${bienId}`)
    .then(res => res.text())
    .then(data => {
        if (data) {
            divContent.innerHTML = data;
        } else {
            divContent.innerHTML = '<p>No se encontraron detalles para este bien serial.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        divContent.innerHTML = '<p>Error al cargar los detalles del bien serial.</p>';
    });

    // Ocultar controles del inventario
    toggleInventoryControls(false);
}