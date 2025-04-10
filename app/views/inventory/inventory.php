<div class="container content">
    <h1>Inventario</h1>

    <div id="groups">
        <h2>Grupos</h2>

        <?php include 'app/views/inventory/searchInventory.html' ?>
        
        <div class="bienes-grid">
            <?php if (isset($dataGroups)): ?>
                <?php foreach ($dataGroups as $group): ?>
                    <div class="bien-card">
                        <div class="bien-info">
                            <h3><?= htmlspecialchars($group['nombre']) ?></h3>
                        </div>
                        <div class="actions">
                            <button class="create-btn" onclick="abrirGrupo(<?= htmlspecialchars($group['id']) ?>)">Abrir</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay grupos disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="inventories" class="hidden">
        <!-- Content for inventorys -->
    </div>

    <div id="goods-inventory" class="hidden">
        <!-- Content for bienes-inventario -->
    </div>
</div>
