<!-- En este h2 se insertara el nombre del inventario (inventory.js) -->
<div class="back-and-title">
    <span id="inventory-name" class="location">Bienes en el Inventario</span>
    <button class="btn-back" onclick="cerrarInventario()">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Volver</span>
    </button>
</div>

<div class="top-bar">
    <div class="search-container">
        <input
            class="search-bar"
            type="text"
            placeholder="Buscar o agregar bienes"
        />
        <i class="search-icon fas fa-search"></i>
    </div>
    <button id="btnCrear" class="create-btn">Crear</button>
</div>

<div class="bienes-grid">
    <?php if (isset($dataGoodsInventory)): ?>

        <!-- Por cada bien del inventario -->
        <?php foreach ($dataGoodsInventory as $good): ?>
            <div class="bien-card">
                <div class="bien-info">
                    <h3><?= htmlspecialchars($good['bien']) ?></h3>
                    <p>
                        <strong>Cantidad:</strong>
                        <?= htmlspecialchars($good['cantidad']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
    <p>No hay bienes disponibles en este inventario.</p>
    <?php endif; ?>
</div>
