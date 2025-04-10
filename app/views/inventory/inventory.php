<div class="container">
    <h1>Inventario</h1>
    <div id="groups">
        <h2>Grupos</h2>
        <?php if (isset($dataGroups)): ?>
            <?php foreach ($dataGroups as $group): ?>
                <div class="group-card">
                    <h3><?= htmlspecialchars($group['nombre']) ?></h3>
                    <button onclick="abrirGrupo(<?= htmlspecialchars($group['id']) ?>)">Abrir</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay grupos disponibles.</p>
        <?php endif; ?>
    </div>

    <div id="inventories" class="hidden">
        <!-- Content for inventorys -->
    </div>

    <div id="goods-inventory" class="hidden">
        <!-- Content for bienes-inventario -->
    </div>
</div>