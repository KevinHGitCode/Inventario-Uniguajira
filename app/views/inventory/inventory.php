<div class="container content">
    <h1>Inventario</h1>

    <div id="groups">
        <h2 class="text-secondary fs-5 fw-normal mb-4">Grupos</h2>

        <div class="top-bar">
            <div class="search-container">
                <input
                    class="search-bar"
                    type="text"
                    placeholder="Buscar o agregar grupos..."
                />
                <i class="search-icon fas fa-search"></i>
            </div>
            <button id="btnCrear" class="create-btn">Crear</button>
        </div>

        <div class="card-grid">
            <?php if (isset($dataGroups)): ?>
                <?php foreach ($dataGroups as $group): ?>
                    <div class="card">
                        <div class="card-left">
                            <i class="fas fa-layer-group icon-folder"></i>
                        </div>
                        
                        <div class="card-center">
                            <div id="group-name<?=$group['id']?>" 
                            class="title"> <?= htmlspecialchars($group['nombre']) ?> </div>
                            <div class="stats">
                                <span class="stat-item">
                                    <i class="fas fa-folder"></i>
                                    <?= $group['total_inventarios'] ?? 0 ?> inventarios
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-right">
                            <button class="btn-open" onclick="abrirGrupo(<?= htmlspecialchars($group['id']) ?>)">
                                <i class="fas fa-external-link-alt"></i> Abrir
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-layer-group fa-3x"></i>
                    <p>No hay grupos disponibles</p>
                </div>
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
