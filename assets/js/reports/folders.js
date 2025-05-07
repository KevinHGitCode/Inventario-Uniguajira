function initFoldersFunctions() {

    // Inicializar formulario para crear grupo
    // ruta del form: /api/groups/create
    inicializarFormularioAjax('#formCrearCarpeta', {
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/reports', false);
        }
    });

    // Inicializar formulario para renombrar grupo
    // ruta del form: /api/groups/rename
    inicializarFormularioAjax('#formRenombrarCarpeta', {
        closeModalOnSuccess: true,
        onSuccess: (response) => {
            showToast(response);
            loadContent('/reports', false);
        }
    });
}

function mostrarModalCrearInventario() {
    mostrarModal('#modalCrearCarpeta');
}

function btnRenombrarCarpeta() {
    console.log(selectedItem); // mensaje de depuración
    const id = selectedItem.id;
    const nombreActual = selectedItem.name;
    document.getElementById("carpetaRenombrarId").value = id;
    document.getElementById("carpetaRenombrarNombre").value = nombreActual;

    mostrarModal('#modalRenombrarCarpeta');
}

// eliminarGrupo()
function btnEliminarCarpeta() {
    const idFolder = selectedItem.id;

    eliminarRegistro({
        url: `/api/folders/delete/${idFolder}`,
        onSuccess: (response) => {
            if (response.success) {
                loadContent('/reports', false);
            }
            showToast(response);
        }
    });
}

// Función para abrir grupo y cargar inventarios
function abrirCarpeta(idFolder, scrollUpRequired = true) {
    const divFolders = document.getElementById('folders');
    const divReports = document.getElementById('report-content');
    const divContent = document.getElementById('report-content-item');

    console.log('hola'); // mensaje de depuración
    // Actualizar el campo oculto en el modal de crear inventario
    const folderIdInput = document.getElementById('folder_id_crear_reporte');
    if (folderIdInput) {
        folderIdInput.value = idFolder;
    }

    // Mostrar loader
    console.log(divReports);
    divContent.innerHTML = '<p>Cargando reportes...</p>';
    divFolders.classList.add('hidden');
    divReports.classList.remove('hidden');

    fetch(`/api/reports/getAll/${idFolder}`)
    .then(res => res.text())
    .then(html => {
        //const folderName = document.getElementById(`folder-name${idFolder}`).textContent;
        //document.getElementById('folder-name').innerText = folderName;
        console.log('hola 2'); // mensaje de depuración
        divContent.innerHTML = html;

        iniciarBusqueda('searchReport');
        console.log('hola 3'); // mensaje de depuración
            
        if (scrollUpRequired)
            window.scrollTo(0, 0);  
    })
    .catch(error => {
        console.error('Error:', error);
        divContent.innerHTML = '<p>Error al cargar los inventarios</p>';
    });
}

// Función para cerrar grupo (mejorada)
function cerrarCarpeta() {
    document.getElementById('folders').classList.remove('hidden');
    document.getElementById('reports').classList.add('hidden');

    const input = document.getElementById('searchFolder');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));

    localStorage.removeItem('openGroup');
}
