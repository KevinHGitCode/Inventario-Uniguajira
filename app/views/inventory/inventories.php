<h2>Lista de Inventarios</h2>
<button class="create-btn" onclick="cerrarGrupo()">Cerrar</button>

<?php include 'app/views/inventory/searchInventory.html' ?>

<div class="bienes-grid">
    <?php if (isset($dataInventories)): ?>
        <?php foreach ($dataInventories as $inventory): ?>
            <div class="bien-card">
                <div class="bien-info">
                    <h3><?= htmlspecialchars($inventory['nombre']) ?></h3>
                </div>
                <div class="actions">
                    <button class="create-btn" onclick="abrirInventario(<?= htmlspecialchars($inventory['id']) ?>)">Abrir</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay inventarios disponibles.</p>
    <?php endif; ?>
</div>

