<style>
    .group-card {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
    .group-card button {
        margin-top: 10px;
        padding: 5px 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .group-card button:hover {
        background-color: #0056b3;
    }
</style>

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