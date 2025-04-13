<!-- En este h2 se insertara el nombre del grupo (inventory.js) -->
<h2 id="group-name" class="text-secondary fs-5 fw-normal mb-4">dsdsd</h2>
<button class="create-btn" onclick="cerrarGrupo()">Cerrar</button>

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

<div class="bienes-grid">
    <?php if (isset($dataInventories)): ?>

        <!-- Por cada inventario -->
        <?php foreach ($dataInventories as $inventory): ?>
            <div class="bien-card">
                <div class="bien-info">
                    <h3 id="inventory-name<?=$inventory['id']?>"><?= htmlspecialchars($inventory['nombre']) ?></h3>
                </div>
                <div class="actions">
                    <button
                        class="create-btn"
                        onclick="abrirInventario(<?= htmlspecialchars($inventory['id']) ?>)"
                    >
                        Abrir
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No hay inventarios disponibles.</p>
    <?php endif; ?>
</div>
