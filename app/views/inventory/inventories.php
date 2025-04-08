<style>
    .inventory-card {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
    .inventory-card button {
        margin-top: 10px;
        padding: 5px 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .inventory-card button:hover {
        background-color: #218838;
    }
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

