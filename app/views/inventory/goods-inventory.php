<h2>Bienes en el Inventario</h2>
<?php if (isset($dataGoodsInventory)): ?>
    <?php foreach ($dataGoodsInventory as $good): ?>
        <div class="goods-card">
            <h3><?= htmlspecialchars($good['bien']) ?></h3>
            <p><strong>Cantidad:</strong> <?= htmlspecialchars($good['cantidad']) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay bienes disponibles en este inventario.</p>
<?php endif; ?>
