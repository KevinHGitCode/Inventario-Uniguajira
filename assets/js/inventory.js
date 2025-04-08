function abrirGrupo(idGroup) {
    fetch(`/api/get/inventories/${idGroup}`)
    .then(res => res.text())
    .then(html => {
        divGroups = document.getElementById('groups');
        divInventories = document.getElementById('inventories');
        // divGoodsInventory = document.getElementById('goods-inventory');

        divInventories.innerHTML = html;

        // toggle class hidden
        divGroups.classList.toggle('hidden');
        divInventories.classList.toggle('hidden');
    });
}

// cerrar grupo
function cerrarGrupo() {
    divGroups = document.getElementById('groups');
    divInventories = document.getElementById('inventories');

    // toggle class hidden
    divGroups.classList.toggle('hidden');
    divInventories.classList.toggle('hidden');
}


// abrir inventario
function abrirInventario(idInventory) {
    fetch(`/api/get/goodsInventory/${idInventory}`)
    .then(res => res.text())
    .then(html => {
        divInventories = document.getElementById('inventories');
        divGoodsInventory = document.getElementById('goods-inventory');

        divGoodsInventory.innerHTML = html;

        // toggle class hidden
        divInventories.classList.toggle('hidden');
        divGoodsInventory.classList.toggle('hidden');
    });
}

// cerrar inventario
function cerrarInventario() {
    divInventories = document.getElementById('inventories');
    divGoodsInventory = document.getElementById('goods-inventory');

    // toggle class hidden
    divInventories.classList.toggle('hidden');
    divGoodsInventory.classList.toggle('hidden');
}