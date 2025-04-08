<style>
    .goods-card {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
    .goods-card h3 {
        margin: 0;
        font-size: 18px;
    }
    .goods-card p {
        margin: 5px 0;
    }
</style>

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
