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
            id="searchGoodInventory"
            class="search-bar searchInput"
            type="text"
            placeholder="Buscar o agregar bienes"
        />
        <i class="search-icon fas fa-search"></i>
    </div>
    <button id="btnCrear" class="create-btn">Crear</button>
</div>

<!-- Barra de control para bienes -->
<div id="control-bar-goods" class="control-bar">
    <div class="selected-name">1 seleccionado</div>
    <div class="control-actions">
        <button class="control-btn" title="Renombrar">
            <i class="fas fa-pen"></i>
        </button>
        <button class="control-btn" title="Cambiar cantidad">
            <i class="fas fa-sort-numeric-up"></i>
        </button>
        <button class="control-btn" title="Mover">
            <i class="fas fa-exchange-alt"></i>
        </button>
        <button class="control-btn" title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>
        <button class="control-btn" title="Más acciones">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>
</div>

<div class="bienes-grid">
    <?php if (isset($dataGoodsInventory)): ?>

        <!-- Por cada bien del inventario -->
        <?php foreach ($dataGoodsInventory as $good): ?>
            <div class="bien-card card-item" data-id="<?= htmlspecialchars($good['id'] ?? '') ?>" data-name="<?= htmlspecialchars($good['bien']) ?>" onclick="toggleSelectItem(this, 'good')">
                <img
                    src="<?= htmlspecialchars($good['imagen']) ?>"
                    class="bien-image"
                />
                <div class="bien-info">
                    <h3 class="name-item"><?= htmlspecialchars($good['bien']) ?></h3>
                    <p>
                        <b>Cantidad:</b>
                        <?= $good['cantidad'] ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
    <p>No hay bienes disponibles en este inventario.</p>
    <?php endif; ?>
</div>
