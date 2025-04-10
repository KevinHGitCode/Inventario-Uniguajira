<h2>Bienes en el Inventario</h2>

<button class="create-btn" onclick="cerrarInventario()">Cerrar</button>

<?php include 'app/views/inventory/searchInventory.html' ?>

<div class="bienes-grid">
    <?php if (isset($dataGoodsInventory)): ?>
        <?php foreach ($dataGoodsInventory as $good): ?>
            <div class="bien-card">
                <div class="bien-info">
                    <h3><?= htmlspecialchars($good['bien']) ?></h3>
                    <p><strong>Cantidad:</strong> <?= htmlspecialchars($good['cantidad']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay bienes disponibles en este inventario.</p>
    <?php endif; ?>
</div>
