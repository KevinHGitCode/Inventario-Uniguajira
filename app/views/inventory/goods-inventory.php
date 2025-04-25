<!-- < ?php require_once 'app/controllers/sessionCheck.php'; ?> -->

<div class="bienes-grid">
    <?php if (isset($dataGoodsInventory) && !empty($dataGoodsInventory)): ?>

        <!-- Por cada bien del inventario -->
        <?php foreach ($dataGoodsInventory as $good): ?>
            <div class="bien-card card-item"
                <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                    data-id="<?= htmlspecialchars($good['id'] ?? '') ?>" 
                    data-name="<?= htmlspecialchars($good['bien']) ?>"
                    data-cantidad="<?= htmlspecialchars($good['cantidad']) ?>"
                    data-type="good"
                    onclick="toggleSelectItem(this)"
                <?php endif; ?>
            >

                <img
                    src="<?= htmlspecialchars($good['imagen']) ?>"
                    class="bien-image"
                    alt="<?= htmlspecialchars($good['bien']) ?>"
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
    <div class="empty-state">
        <i class="fas fa-box-open fa-3x"></i>
        <p>No hay bienes disponibles en este inventario.</p>
    </div>
    <?php endif; ?>
</div>
