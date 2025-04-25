window.onload = () => {
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


}