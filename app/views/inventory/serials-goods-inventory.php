<div class="bienes-grid">
    <?php if (isset($dataSerialGoodsInventory) && !empty($dataSerialGoodsInventory)): ?>

        <!-- Por cada bien serial del inventario -->
        <?php foreach ($dataSerialGoodsInventory as $serialGood): ?>            <div class="bien-card card-item"
                <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                    data-id="<?= htmlspecialchars($serialGood['bienes_equipos_id']) ?>" 
                    data-inventory-id="<?= htmlspecialchars($serialGood['inventario_id']) ?>"
                    data-bien-id="<?= htmlspecialchars($serialGood['bien_id']) ?>"
                    data-name="<?= htmlspecialchars($serialGood['bien']) ?>"
                    data-description="<?= htmlspecialchars($serialGood['descripcion'] ?? '') ?>"
                    data-brand="<?= htmlspecialchars($serialGood['marca'] ?? '') ?>"
                    data-model="<?= htmlspecialchars($serialGood['modelo'] ?? '') ?>"
                    data-serial="<?= htmlspecialchars($serialGood['serial']) ?>"
                    data-status="<?= htmlspecialchars($serialGood['estado'] ?? '') ?>"
                    data-color="<?= htmlspecialchars($serialGood['color'] ?? '') ?>"
                    data-condition="<?= htmlspecialchars($serialGood['condiciones_tecnicas'] ?? '') ?>"
                    data-entry-date="<?= htmlspecialchars($serialGood['fecha_ingreso'] ?? '') ?>"
                    data-type="serial-good"
                    onclick="toggleSelectItem(this)"
                <?php endif; ?>
            >

                <img
                    src="<?= htmlspecialchars($serialGood['imagen'] ?? 'assets/uploads/img/goods/default.jpg') ?>"
                    class="bien-image"
                    alt="<?= htmlspecialchars($serialGood['bien']) ?>"
                />
                <div class="bien-info">
                    <h3 class="name-item">
                        <?= htmlspecialchars($serialGood['bien']) ?>
                        <img
                            src="assets/icons/bienSerial.svg"
                            alt="Tipo Serial"
                            class="bien-icon"
                        />
                    </h3>
                    <p>
                        <b>Serial:</b>
                        <?= htmlspecialchars($serialGood['serial']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-box-open fa-3x"></i>
        <p>No hay bienes seriales disponibles en este inventario.</p>
    </div>
    <?php endif; ?>
</div>
