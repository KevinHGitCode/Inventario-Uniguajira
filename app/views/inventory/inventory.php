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

        <div class="bienes-grid">
            <?php if (isset($dataGroups)): ?>

                <!-- Por cada grupo de inventarios -->
                <?php foreach ($dataGroups as $group): ?>
                    <div class="bien-card">
                        <div class="bien-info">
                            <h3 id="group-name<?=$group['id']?>" ><?= htmlspecialchars($group['nombre']) ?></h3>
                        </div>
                        <div class="actions">
                            <button
                                class="create-btn"
                                onclick="abrirGrupo(<?= htmlspecialchars($group['id']) ?>)"
                            >
                                Abrir
                            </button>
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
