<?php require_once 'app/controllers/sessionCheck.php'; ?>

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
    <button id="btnCrear" class="create-btn" onclick="mostrarModal('#modalCrearBien')">Crear</button>
    <?php endif; ?>
       
</div>

<!-- Barra de control para bienes -->
<?php if ($_SESSION['user_rol'] === 'administrador'): ?>
<div id="control-bar-good" class="control-bar">
    <div class="selected-name">1 seleccionado</div>
    <div class="control-actions">
        <button class="control-btn" title="Renombrar" onclick="btnRenombrarBien()">
            <i class="fas fa-pen"></i>
        </button>
        <button class="control-btn" title="Cambiar cantidad" onclick="btnCambiarCantidadBien()">
            <i class="fas fa-sort-numeric-up"></i>
        </button>
        <button class="control-btn" title="Mover" onclick="btnMoverBien()">
            <i class="fas fa-exchange-alt"></i>
        </button>
        <button class="control-btn" title="Eliminar" onclick="btnEliminarBien()">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
<?php endif; ?>

<div class="bienes-grid">
    <?php if (isset($dataGoodsInventory) && !empty($dataGoodsInventory)): ?>

        <!-- Por cada bien del inventario -->
        <?php foreach ($dataGoodsInventory as $good): ?>
            <div class="bien-card card-item"
                <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                    data-id="<?= htmlspecialchars($good['id'] ?? '') ?>" 
                    data-name="<?= htmlspecialchars($good['bien']) ?>"
                    data-cantidad="<?= htmlspecialchars($good['cantidad']) ?>"
                    data-type="good"
                    onclick="toggleSelectItem(this)"
                <?php endif; ?>
            >

                <img
                    src="<?= htmlspecialchars($good['imagen']) ?>"
                    class="bien-image"
                    alt="<?= htmlspecialchars($good['bien']) ?>"
                />
                <div class="bien-info">
                    <h3 class="name-item"><?= htmlspecialchars($good['bien']) ?></h3>
                    <p>
                        <b>Cantidad:</b>
                        <?= $good['cantidad'] ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
    <div class="empty-state">
        <i class="fas fa-box-open fa-3x"></i>
        <p>No hay bienes disponibles en este inventario.</p>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Crear Bien -->
<div id="modalCrearBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCrearBien')">&times;</span>
        <h2>Nuevo Bien</h2>
        <form id="formCrearBien" autocomplete="off" action="/api/bien/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioIdBien" name="inventarioId" value="" />
            
            <div>
                <label for="nombreBien">Nombre del bien:</label>
                <input type="text" name="nombre" id="nombreBien" required />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>
