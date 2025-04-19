function initGroupFunctions() {

    // Inicializar formulario para crear grupo
    inicializarFormularioAjax('#formCrearGrupo', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/inventory', false);
        }
    });

    // Inicializar formulario para renombrar grupo
    inicializarFormularioAjax('#formRenombrarGrupo', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/inventory', false);
        }
    });
}

function btnRenombrarGrupo() {
    console.log(selectedItem); // mensaje de depuración
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    document.getElementById("grupoRenombrarId").value = id;
    document.getElementById("grupoRenombrarNombre").value = nombreActual;

    mostrarModal('#modalRenombrarGrupo');
}

// eliminarGrupo()
function btnEliminarGrupo() {
    const idGrupo = selectedItem.id;

    eliminarRegistro({
        url: `/api/groups/delete/${idGrupo}`,
        onSuccess: (response) => {
            if (response.success) {
                loadContent('/inventory', false);
            }
            showToast(response);
        }
    });
}

// Función para abrir grupo y cargar inventarios
function abrirGrupo(idGroup) {
    const divGroups = document.getElementById('groups');
    const divInventories = document.getElementById('inventories');

    // Mostrar loader
    divInventories.innerHTML = '<p>Cargando inventarios...</p>';
    divGroups.classList.add('hidden');
    divInventories.classList.remove('hidden');

    fetch(`/api/get/inventories/${idGroup}`)
    .then(res => res.text())
    .then(html => {
        divInventories.innerHTML = html;
        const grupoName = document.getElementById(`group-name${idGroup}`).textContent;
        document.getElementById('group-name').innerText = grupoName;

        iniciarBusqueda('searchInventory');
        inicializarFormularioCrearInventario(); // TODO:

        // si hay un inventario almacenado, abrir
        if (localStorage.getItem('openInventory')) {
            const idInventory = localStorage.getItem('openInventory');
            abrirInventario(idInventory);
        } else {
            localStorage.setItem('openGroup', idGroup);
            window.scrollTo(0, 0);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        divInventories.innerHTML = '<p>Error al cargar los inventarios</p>';
    });
}

// Función para cerrar grupo (mejorada)
function cerrarGrupo() {
    document.getElementById('groups').classList.remove('hidden');
    document.getElementById('inventories').classList.add('hidden');

    const input = document.getElementById('searchGroup');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));

    localStorage.removeItem('openGroup');
}
