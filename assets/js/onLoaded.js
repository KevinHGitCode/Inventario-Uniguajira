// Inicializar funcionalidades cuando el DOM esté listo
window.onload = () => {
    // sesion data
    fetch('/api/session/get')
        .then(response => response.json())
        .then(data => {
            window.sesion = data;
            console.log('Sesión cargada:', data);
        })
        .catch(error => console.error('Error al cargar la sesión:', error));

    cargarUltimaSeleccion();

    editarPerfil();
    
    if (typeof initFormsTask === 'function') initFormsTask();

    if (typeof initFormsBien === 'function') initFormsBien();

    if (typeof initUserFunctions === 'function') initUserFunctions();

    if (typeof initGroupFunctions === 'function') {
        initGroupFunctions();
        initInventoryFunctions();
        initGoodsInventoryFunctions();
    }

    if (typeof initFoldersFunctions === 'function') {
        initFoldersFunctions();
    }

    if (typeof initReportsFunctions === 'function') {
        initReportsFunctions();
    }


}