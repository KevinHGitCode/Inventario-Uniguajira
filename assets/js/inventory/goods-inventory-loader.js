// Función para cargar bienes del inventario desde JSON
async function cargarBienesInventario(idInventory) {
    const divContent = document.getElementById('goods-inventory-content');
    
    // Mostrar loader mientras carga
    divContent.innerHTML = '<p>Cargando bienes...</p>';
    
    try {
        console.time('Cargar bienes'); // Marca de tiempo inicial
        console.time('Fetch bienes');  // Solo fetch
        // Realizar solicitud para obtener datos JSON
        const response = await fetch(`/api/get/goodsInventory/${idInventory}`);
        console.timeEnd('Fetch bienes');
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        console.time('Parsear JSON');  // Solo parseo
        const bienes = await response.json();
        console.timeEnd('Parsear JSON');
        
        // Limpiar el contenedor
        divContent.innerHTML = '';
        
        // Crear el contenedor principal para los bienes
        const bienesGrid = document.createElement('div');
        bienesGrid.className = 'bienes-grid';
        
        // Agregar inmediatamente el grid al DOM para que sea visible
        divContent.appendChild(bienesGrid);
        
        // Verificar si hay bienes
        if (bienes && bienes.length > 0) {
            console.time('Renderizar bienes'); // Marca de tiempo para renderizado
            
            // Procesar todos los bienes de una vez
            bienes.forEach((bien) => {
                const bienElement = document.createElement('div');
                bienElement.className = 'bien-card card-item';
                
                // Verificar si el usuario es administrador para agregar atributos de datos
                const userRol = getUserRol();
                
                if (userRol === 'administrador') {
                    bienElement.dataset.id = bien.id || '';
                    bienElement.dataset.name = bien.bien;
                    bienElement.dataset.cantidad = bien.cantidad;
                    bienElement.dataset.type = 'good';
                    bienElement.onclick = function() { toggleSelectItem(this); };
                }
                
                // Crear la estructura HTML del bien
                bienElement.innerHTML = `
                    <img
                        src="${bien.imagen || 'assets/uploads/img/goods/default.jpg'}"
                        class="bien-image"
                        alt="${bien.bien}"
                    />
                    <div class="bien-info">
                        <h3 class="name-item">
                            ${bien.bien}
                            <img
                                src="assets/icons/${bien.tipo === 'Cantidad' ? 'bienCantidad.svg' : 'bienSerial.svg'}"
                                alt="Tipo ${bien.tipo}"
                                class="bien-icon"
                            />
                        </h3>
                        <p>
                            <b>Cantidad:</b>
                            ${bien.cantidad}
                        </p>
                    </div>
                `;
                
                // Agregar al contenedor de bienes
                bienesGrid.appendChild(bienElement);
            });
            
            console.timeEnd('Renderizar bienes'); // Fin del tiempo de renderizado
            
        } else {
            // Mostrar estado vacío si no hay bienes
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state';
            emptyState.innerHTML = `
                <i class="fas fa-box-open fa-3x"></i>
                <p>No hay bienes disponibles en este inventario.</p>
            `;
            bienesGrid.appendChild(emptyState);
        }
        
        console.timeEnd('Cargar bienes'); // Fin del tiempo total
        
    } catch (error) {
        console.error('Error al cargar bienes:', error);
        divContent.innerHTML = '<p>Error al cargar los bienes</p>';
    }
}

// Función auxiliar para obtener el rol del usuario
function getUserRol() {
    // Obtener el rol desde la sesión global window.sesion
    return window.sesion?.user_rol || 'invitado';
}
