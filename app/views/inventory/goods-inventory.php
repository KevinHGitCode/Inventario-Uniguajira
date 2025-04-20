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
        <form id="formCrearBien" action="/api/bien/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioIdBien" name="inventarioId" value="" />
            
            <div>
                <label>Nombre del bien:</label>
                <input type="text" name="nombre" required />
            </div>

            <div>
                <label>Cantidad:</label>
                <input type="number" name="cantidad" min="1" value="1" required />
            </div>

            <div>
                <label>Imagen:</label>
                <input type="file" name="imagen" accept="image/*" />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Renombrar Bien -->
<div id="modalRenombrarBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalRenombrarBien')">&times;</span>
        <h2>Renombrar Bien</h2>
        <form id="formRenombrarBien" action="/api/bien/rename" method="POST">
            <input type="hidden" id="renombrarBienId" name="id" value="" />
            
            <div>
                <label>Nuevo nombre:</label>
                <input type="text" id="renombrarBienNombre" name="nombre" required />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cambiar Cantidad -->
<div id="modalCambiarCantidad" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCambiarCantidad')">&times;</span>
        <h2>Cambiar Cantidad</h2>
        <form id="formCambiarCantidad" action="/api/bien/updateQuantity" method="POST">
            <input type="hidden" id="cambiarCantidadBienId" name="id" value="" />
            
            <div>
                <label>Bien:</label>
                <input type="text" id="cambiarCantidadBienNombre" readonly />
            </div>
            
            <div>
                <label>Nueva cantidad:</label>
                <input type="number" id="cambiarCantidadValor" name="cantidad" min="1" required />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Actualizar Cantidad</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Mover Bien -->
<div id="modalMoverBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalMoverBien')">&times;</span>
        <h2>Mover Bien a Otro Inventario</h2>
        <form id="formMoverBien" action="/api/bien/move" method="POST">
            <input type="hidden" id="moverBienId" name="bienId" value="" />
            <input type="hidden" id="moverBienInventarioActualId" name="inventarioActualId" value="" />
            
            <div>
                <label>Bien:</label>
                <input type="text" id="moverBienNombre" readonly />
            </div>
            
            <div>
                <label>Cantidad a mover:</label>
                <input type="number" id="moverBienCantidad" name="cantidad" min="1" required />
            </div>
            
            <div>
                <label>Inventario destino:</label>
                <select id="moverBienInventarioDestinoId" name="inventarioDestinoId" required>
                    <option value="">Seleccione un inventario...</option>
                    <!-- Las opciones se cargarán dinámicamente con JS -->
                </select>
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Mover Bien</button>
            </div>
        </form>
    </div>
</div>