<?php require_once 'app/controllers/sessionCheck.php'; ?>

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

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <button id="btnCrearGrupo" class="create-btn" onclick="mostrarModal('#modalCrearGrupo')">Crear</button>
            <?php endif; ?>
            
        </div>

        <!-- Barra de control para grupos -->
        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <div id="control-bar-group" class="control-bar">
            <div class="selected-name">1 seleccionado</div>
            <div class="control-actions">
                <button class="control-btn" title="Renombrar" onclick="btnRenombrarGrupo()">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="control-btn" title="Eliminar" onclick="btnEliminarGrupo()">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- <button class="control-btn" title="Más acciones">
                    <i class="fas fa-ellipsis-v"></i>
                </button> -->
            </div>
        </div>
        <?php endif; ?>

        <div class="card-grid">
            <?php if (isset($dataGroups)): ?>
                <?php foreach ($dataGroups as $group): ?>
                    <div class="card card-item" 
                        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                            data-id="<?= htmlspecialchars($group['id']) ?>" 
                            data-name="<?= htmlspecialchars($group['nombre']) ?>" 
                            data-type="group"
                            onclick="toggleSelectItem(this)"
                        <?php endif; ?>
                    >
                        <!-- inicio contenido -->
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
                        <!-- fin contenido -->
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


<!-- ---------------------------------------------------------------------- -->


<!-- Modal Crear Grupo -->
<div id="modalCrearGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearGrupo')">&times;</span>
        <h2>Nuevo Grupo</h2>
        <form
            id="formCrearGrupo"
            action="/api/groups/create"
            method="POST"
        >
            <div>
                <label for="nombreGrupo">Nombre del grupo:</label>
                <input type="text" name="nombre" id="nombreGrupo" required />
            </div>
            <div style="margin-top: 10px">
                <button 
                    type="submit" 
                    id="create-btn-grupo" 
                    class="create-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->
<!-- Modal Renombrar Grupo -->
<div id="modalRenombrarGrupo" class="modal" style="display: none">
    <div class="modal-content">
        <span id="cerrarModalRenombrarGrupo" class="close" onclick="ocultarModal('#modalRenombrarGrupo')">&times;</span>
        <h2>Renombrar Grupo</h2>
        <form 
            id="formRenombrarGrupo" 
            action="/api/groups/rename"
        >
            <input type="hidden" name="id" id="grupoRenombrarId" />

            <div>
                <label for="grupoRenombrarNombre">Nuevo Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="grupoRenombrarNombre"
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

