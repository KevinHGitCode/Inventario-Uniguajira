<div class="card-grid">
    <?php if (isset($dataInventories) && count($dataInventories) > 0): ?>
        <?php foreach ($dataInventories as $inventory): ?>
            <div class="card card-item" 
                <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                    data-id="<?= htmlspecialchars($inventory['id']) ?>" 
                    data-name="<?= htmlspecialchars($inventory['nombre']) ?>"
                    data-responsable="<?= htmlspecialchars($inventory['responsable']) ?>"
                    data-estado="<?= htmlspecialchars($inventory['estado_conservacion']) ?>"
                    data-type="inventory"
                    onclick="toggleSelectItem(this)"
                <?php endif; ?>
            >

                <div class="card-left">
                    <i class="fas fa-folder icon-folder"></i>
                </div>

                <div class="card-center">
                    <div id="inventory-name<?= htmlspecialchars($inventory['id']) ?>" 
                    class="title name-item"> <?= htmlspecialchars($inventory['nombre']) ?> </div>
                    <div class="stats">
                        <span class="stat-item">
                            <i class="fas fa-shapes"></i>
                            <?= $inventory['cantidad_tipos_bienes'] ?? 0 ?> tipos
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-boxes"></i>
                            <?= $inventory['cantidad_total_bienes'] ?? 0 ?> bienes
                        </span>
                    </div>
                </div>

                <div class="card-right">
                    <button class="btn-open" onclick="abrirInventario(<?= htmlspecialchars($inventory['id']) ?>)">
                        <i class="fas fa-external-link-alt"></i> Abrir
                    </button>
                </div>
                
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-folder-open fa-3x"></i>
            <p>No hay inventarios disponibles</p>
        </div>
    <?php endif; ?>
</div>
