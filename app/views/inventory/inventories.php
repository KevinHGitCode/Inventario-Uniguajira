<style>

</style>

<h2>Lista de Inventarios</h2>
<?php if (isset($dataInventories)): ?>
    <?php foreach ($dataInventories as $inventory): ?>
        <div class="inventory-card">
            <h3><?= htmlspecialchars($inventory['nombre']) ?></h3>
            <button onclick="abrirInventario(<?= htmlspecialchars($inventory['id']) ?>)">Abrir</button>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay inventarios disponibles.</p>
<?php endif; ?>

