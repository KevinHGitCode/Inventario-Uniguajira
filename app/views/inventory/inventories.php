<?php require_once 'app/controllers/sessionCheck.php'; ?>

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
            id="searchInventory"
            class="search-bar searchInput"
            type="text"
            placeholder="Buscar o agregar inventarios"
        />
        <i class="search-icon fas fa-search"></i>
    </div>
    
    <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
    <button id="btnCrearInventorio" class="create-btn" onclick="mostrarModal('#modalCrearInventario')">Crear</button>
    <?php endif; ?>
            
</div>

<!-- Barra de control para inventarios -->
<?php if ($_SESSION['user_rol'] === 'administrador'): ?>
<div id="control-bar-inventory" class="control-bar">
    <div class="selected-name">1 seleccionado</div>
    <div class="control-actions">
        <button class="control-btn" title="Renombrar" onclick="renombrarInventario()">
            <i class="fas fa-pen"></i>
        </button>
        <button class="control-btn" title="Editar" onclick="editarInventario()">
            <i class="fas fa-edit"></i>
        </button>
        <button class="control-btn" title="Eliminar" onclick="eliminarInventario()">
            <i class="fas fa-trash"></i>
        </button>
        <!-- <button class="control-btn" title="Más acciones">
            <i class="fas fa-ellipsis-v"></i>
        </button> -->
    </div>
</div>
<?php endif; ?>

<div class="card-grid">
    <?php if (isset($dataInventories)): ?>
        <?php foreach ($dataInventories as $inventory): ?>
            <div class="card card-item" 
                <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                    data-id="<?= htmlspecialchars($inventory['id']) ?>" 
                    data-name="<?= htmlspecialchars($inventory['nombre']) ?>"
                    data-type="inventory"
                    onclick="toggleSelectItem(this)"
                <?php endif; ?>
            >

                <div class="card-left">
                    <i class="fas fa-folder icon-folder"></i>
                </div>

                <div class="card-center">
                    <div id="inventory-name<?= htmlspecialchars($inventory['id']) ?>" 
                    class="title name-item"> <?= htmlspecialchars($inventory['nombre']) ?> </div>
                    <div class="stats">
                        <span class="stat-item">
                            <i class="fas fa-shapes"></i>
                            <?= $inventory['cantidad_tipos_bienes'] ?? 0 ?> tipos
                        </span>
                        <span class="stat-item">
                            <i class="fas fa-boxes"></i>
                            <?= $inventory['cantidad_total_bienes'] ?? 0 ?> bienes
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


<!-- TODO: Cambiar de lugar -->

<!-- ---------------------------------------------------------------------- -->


<!-- Modal Crear Inventario -->
<div id="modalCrearInventario" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearInventario')">&times;</span>
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
                <button 
                id="btnCrearInventario"
                type="submit" 
                class="create-btn">
                    Guardar
                </button>
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
