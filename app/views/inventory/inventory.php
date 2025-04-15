<div class="container content">
    <h1>Inventario</h1>

    <div id="groups">
        <h2 class="text-secondary fs-5 fw-normal mb-4">Grupos</h2>

        <div class="top-bar">
            <div class="search-container">
                <input
                    id="searchGroup"
                    class="search-bar searchInput"
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
                    <div class="card card-item">
                        <div class="card-left">
                            <i class="fas fa-layer-group icon-folder"></i>
                        </div>
                        
                        <div class="card-center">
                            <div id="group-name<?=$group['id']?>" 
                            class="title name-item"> <?= htmlspecialchars($group['nombre']) ?> </div>
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


<!-- Modal Crear Grupo -->
<div id="modalCrearGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Nuevo Grupo</h2>
        <form
            id="formCrearGrupo"
            action="/api/grupos/create"
            method="POST"
        >
            <div>
                <label>Nombre del grupo:</label>
                <input type="text" name="nombre" required />
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->

<!-- Modal Actualizar Grupo -->
<div id="modalActualizarGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalActualizarGrupo" class="close">&times;</span>
        <h2>Actualizar Grupo</h2>
        <form id="formActualizarGrupo">
            <input type="hidden" name="id" id="actualizarGrupoId" />

            <div>
                <label>Nombre del grupo:</label>
                <input
                    type="text"
                    name="nombre"
                    id="actualizarGrupoNombre"
                    required
                />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->


<!-- Modal Crear Inventario -->
<div id="modalCrearInventario" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Nuevo Inventario</h2>
        <form id="formCrearInventario" action="/api/inventario/create" method="POST">
            <div>
                <label>Nombre del inventario:</label>
                <input type="text" name="nombre" required />
            </div>

            <div>
                <label>Fecha:</label>
                <input type="date" name="fecha" required />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->


<!-- Modal Actualizar Inventario -->
<div id="modalActualizarInventario" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalActualizarInventario" class="close">&times;</span>
        <h2>Actualizar Inventario</h2>
        <form id="formActualizarInventario">
            <input type="hidden" name="id" id="actualizarInventarioId" />

            <div>
                <label>Nombre del inventario:</label>
                <input type="text" name="nombre" id="actualizarInventarioNombre" required />
            </div>

            <div>
                <label>Fecha:</label>
                <input type="date" name="fecha" id="actualizarInventarioFecha" required />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
