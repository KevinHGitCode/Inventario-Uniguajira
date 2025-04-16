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
    })
    .catch(error => {
        console.error('Error:', error);
        divInventories.innerHTML = '<p>Error al cargar los inventarios</p>';
    });

    

    // * RENDERIZADO CON JS
    // fetch(`/api/get/inventories/${idGroup}`)
    //     .then(res => res.json())
    //     .then(inventories => {
    //         renderInventoriesToContainer(divInventories, inventories);
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         divInventories.innerHTML = '<p>Error al cargar los inventarios</p>';
    //     });
}

/**
 * RENDERIZADO CON JS
 * Anteriormente se renderizaba a traves de un json sin embargo por motivos
 * de desarrollo se comentara esta forma de renderizado por un innerHTML con PHP
 */
// Función para renderizar inventarios en el contenedor
// function renderInventoriesToContainer(container, inventories) {
//     // Crear elementos base
//     const header = document.createElement('h2');
//     header.textContent = 'Lista de Inventarios';
    
//     const closeBtn = document.createElement('button');
//     closeBtn.className = 'create-btn';
//     closeBtn.textContent = 'Cerrar';
//     closeBtn.onclick = cerrarGrupo;
    
//     const searchHTML = `<?php include 'app/views/inventory/searchInventory.html' ?>`;
    
//     const grid = document.createElement('div');
//     grid.className = 'bienes-grid';
    
//     // Limpiar contenedor
//     container.innerHTML = '';
    
//     // Agregar elementos al contenedor
//     container.appendChild(header);
//     container.appendChild(closeBtn);
//     container.insertAdjacentHTML('beforeend', searchHTML);
//     container.appendChild(grid);
    
//     // Renderizar cada inventario
//     if (inventories && inventories.length > 0) {
//         inventories.forEach(inventory => {
//             const card = document.createElement('div');
//             card.className = 'bien-card';
            
//             card.innerHTML = `
//                 <div class="bien-info">
//                     <h3>${escapeHtml(inventory.nombre)}</h3>
//                 </div>
//                 <div class="actions">
//                     <button class="create-btn" data-inventory-id="${inventory.id}">Abrir</button>
//                 </div>
//             `;
            
//             // Asignar evento de click dinámicamente
//             const btn = card.querySelector('.create-btn');
//             btn.addEventListener('click', () => {
//                 abrirInventario(inventory.id);
//             });
            
//             grid.appendChild(card);
//         });
//     } else {
//         grid.innerHTML = '<p>No hay inventarios disponibles.</p>';
//     }
// }

// Función para cerrar grupo (mejorada)
function cerrarGrupo() {
    document.getElementById('groups').classList.remove('hidden');
    document.getElementById('inventories').classList.add('hidden');

    const input = document.getElementById('searchGroup');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));
}


function abrirInventario(idInventory) {
    const divGoodsInventory = document.getElementById('goods-inventory');
    const divInventories = document.getElementById('inventories');
    
    // Mostrar loader mientras carga
    divGoodsInventory.innerHTML = '<p>Cargando bienes...</p>';
    divGoodsInventory.classList.remove('hidden');
    divInventories.classList.add('hidden');

    fetch(`/api/get/goodsInventory/${idInventory}`)
    .then(res => res.text())
    .then(html => {
        divGoodsInventory.innerHTML = html;
        const inventoryName = document.getElementById(`inventory-name${idInventory}`).textContent;
        document.getElementById('inventory-name').innerText = inventoryName;

        iniciarBusqueda('searchGoodInventory');
    })
    .catch(error => {
        console.error('Error:', error);
        divGoodsInventory.innerHTML = '<p>Error al cargar los bienes</p>';
    });

    // fetch(`/api/get/goodsInventory/${idInventory}`)
    // .then(res => res.json())
    // .then(goods => {
    //     renderGoodsToContainer(divGoodsInventory, goods);
    // })
    // .catch(error => {
    //     console.error('Error:', error);
    //     divGoodsInventory.innerHTML = '<p>Error al cargar los bienes</p>';
    // });
}

// cerrar inventario
function cerrarInventario() {
    document.getElementById('goods-inventory').classList.add('hidden');
    document.getElementById('inventories').classList.remove('hidden');

    const input = document.getElementById('searchInventory');
    input.value = ''; // Borra el valor
    input.dispatchEvent(new Event('input')); // Notifica que el valor cambió
    input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Backspace', code: 'Backspace' }));
    input.dispatchEvent(new KeyboardEvent('keyup', { key: 'Backspace', code: 'Backspace' }));
}


// function renderGoodsToContainer(container, goods) {
//     // Crear elementos base
//     const header = document.createElement('h2');
//     header.textContent = 'Bienes en el Inventario';
    
//     const closeBtn = document.createElement('button');
//     closeBtn.className = 'create-btn';
//     closeBtn.textContent = 'Cerrar';
//     closeBtn.onclick = cerrarInventario;
    
//     const searchHTML = `<?php include 'app/views/inventory/searchInventory.html' ?>`;
    
//     const grid = document.createElement('div');
//     grid.className = 'bienes-grid';
    
//     // Limpiar contenedor
//     container.innerHTML = '';
    
//     // Agregar elementos al contenedor
//     container.appendChild(header);
//     container.appendChild(closeBtn);
//     container.insertAdjacentHTML('beforeend', searchHTML);
//     container.appendChild(grid);
    
//     // Renderizar cada bien
//     if (goods && goods.length > 0) {
//         goods.forEach(good => {
//             const card = document.createElement('div');
//             card.className = 'bien-card';
            
//             card.innerHTML = `
//                 <div class="bien-info">
//                     <h3>${escapeHtml(good.bien)}</h3>
//                     <p><strong>Cantidad:</strong> ${escapeHtml(good.cantidad)}</p>
//                 </div>
//             `;
            
//             grid.appendChild(card);
//         });
//     } else {
//         grid.innerHTML = '<p>No hay bienes disponibles en este inventario.</p>';
//     }
// }

// Función helper para seguridad XSS
function escapeHtml(unsafe) {
    return unsafe?.toString().replace(/[&<"'>]/g, match => {
        switch(match) {
            case '&': return '&amp;';
            case '<': return '&lt;';
            case '>': return '&gt;';
            case '"': return '&quot;';
            case "'": return '&#039;';
        }
    }) || '';
}