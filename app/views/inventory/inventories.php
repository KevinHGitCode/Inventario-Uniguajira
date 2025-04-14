<!-- En este h2 se insertara el nombre del grupo (inventory.js) -->
<div class="back-and-title">
    <span id="group-name" class="location">Grupo</span>
    <button class="btn-back" onclick="cerrarGrupo()">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Volver</span>
    </button>
</div>

<div class="top-bar">
    <div class="search-container">
        <input
            class="search-bar"
            type="text"
            placeholder="Buscar o agregar inventarios"
        />
        <i class="search-icon fas fa-search"></i>
    </div>
    <button id="btnCrear" class="create-btn">Crear</button>
</div>


<div class="card-grid">
    <?php if (isset($dataInventories)): ?>
        <?php foreach ($dataInventories as $inventory): ?>
            <div class="card">

                <div class="card-left">
                    <i class="fas fa-folder icon-folder"></i>
                </div>

                <div class="card-center">
                    <div id="inventory-name<?= htmlspecialchars($inventory['id']) ?>" class="title">
                        <?= htmlspecialchars($inventory['nombre']) ?>
                    </div>
                    <div class="stats">
                        <span class="stat-item">
                            <i class="fas fa-shapes"></i>
                            <?= $inventory['tipos_bienes'] ?? 0 ?> tipos
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-boxes"></i>
                            <?= $inventory['total_bienes'] ?? 0 ?> bienes
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
