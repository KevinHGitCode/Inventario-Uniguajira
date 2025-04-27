<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container content">
    <h1>Inventario</h1>

    <div id="groups">
        <h2 class="location">Grupos</h2>

        <div class="top-bar">
            <div class="search-container">
                <input
                    id="searchGroup"
                    class="search-bar searchInput"
                    type="text"
                    placeholder="Buscar o agregar grupos..."
                />
                <svg class="search-icon">
                    <use href="/assets/sprites.svg#search" />
                </svg>
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
                            <svg class="icon-folder">
                                <use href="/assets/sprites.svg#layer-group" />
                            </svg>
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
            <button class="create-btn" onclick="mostrarModal('#modalCrearInventario')">Crear</button>
            <?php endif; ?>
                    
        </div>

        <!-- Barra de control para inventarios -->
        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <div id="control-bar-inventory" class="control-bar">
            <div class="selected-name">1 seleccionado</div>
            <div class="control-actions">
                <button class="control-btn" title="Renombrar" onclick="btnRenombrarInventario()">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="control-btn" title="Editar" onclick="btnEditarInventario()">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="control-btn" title="Eliminar" onclick="btnEliminarInventario()">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- <button class="control-btn" title="Más acciones">
                    <i class="fas fa-ellipsis-v"></i>
                </button> -->
            </div>
        </div>
        <?php endif; ?>

        <div id="inventories-content">
            <!-- Content for inventorys -->
        </div>
    </div>

    <div id="goods-inventory" class="hidden">
        <!-- En este h2 se insertara el nombre del inventario (inventory.js) -->
        <div class="back-and-title">
            <span id="inventory-name" class="location">Bienes en el Inventario</span>
            <button class="btn-back" onclick="cerrarInventario()">
                <i class="fas fa-arrow-left me-2"></i>
                <span>Volver</span>
            </button>
        </div>

        <div class="top-bar">
            <div class="search-container">
                <input
                    id="searchGoodInventory"
                    class="search-bar searchInput"
                    type="text"
                    placeholder="Buscar o agregar bienes"
                />
                <i class="search-icon fas fa-search"></i>
            </div>

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <button id="btnCrear" class="create-btn" onclick="abrirModalCrearBien()">Crear</button>
            <?php endif; ?>
            
        </div>

        <!-- Barra de control para bienes -->
        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <div id="control-bar-good" class="control-bar">
            <div class="selected-name">1 seleccionado</div>
            <div class="control-actions">
                <button class="control-btn" title="Cambiar cantidad" onclick="btnCambiarCantidadBien()">
                    <i class="fas fa-sort-numeric-up"></i>
                </button>
                <!-- TODO: Not implement yet -->
                <!-- <button class="control-btn" title="Mover" onclick="btnMoverBien()">
                    <i class="fas fa-exchange-alt"></i>
                </button> -->
                <button class="control-btn" title="Eliminar" onclick="btnEliminarBien()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>

        <div id="goods-inventory-content">
            <!-- Content for bienes-inventario -->
        </div>
    </div>
</div>
