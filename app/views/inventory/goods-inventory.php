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
        <form id="formCrearBien" autocomplete="off" action="/api/goods-inventory/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="inventarioId" name="inventarioId" />
            <div id="search-container">
                <input type="hidden" id="bien_id" name="bien_id"  />
                <label for="nombreBien">Nombre del bien:</label>
                <input type="text" name="nombre" id="nombreBien" class="form-control" placeholder="Buscar..." required />
                <ul class="suggestions"></ul>
            </div>

            <div style="display: none">
                <label for="cantidadBien">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidadBien" min="1" />
            </div>

            <div style="display: none">
                <label for="descripcionBien">Descripción:</label>
                <textarea name="descripcion" id="descripcionBien"></textarea>
            </div>

            <div style="display: none">
                <label for="marcaBien">Marca:</label>
                <input type="text" name="marca" id="marcaBien" />
            </div>

            <div style="display: none">
                <label for="modeloBien">Modelo:</label>
                <input type="text" name="modelo" id="modeloBien" />
            </div>

            <div style="display: none">
                <label for="serialBien">Serial:</label>
                <input type="text" name="serial" id="serialBien" />
            </div>

            <div style="display: none">
                <label for="estadoBien">Estado:</label>
                <select name="estado" id="estadoBien">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="en mantenimiento">En mantenimiento</option>
                </select>
            </div>

            <div style="display: none">
                <label for="colorBien">Color:</label>
                <input type="text" name="color" id="colorBien" />
            </div>

            <div style="display: none">
                <label for="condicionBien">Condición técnica:</label>
                <input type="text" name="condicion_tecnica" id="condicionBien" />
            </div>

            <div style="display: none">
                <label for="fechaIngresoBien">Fecha de ingreso:</label>
                <input type="date" name="fecha_ingreso" id="fechaIngresoBien" />
            </div>

            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal para Cambiar Cantidad de Bien -->
<div id="modalCambiarCantidadBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalCambiarCantidadBien')">&times;</span>
        <h2>Cambiar Cantidad</h2>
        <form id="formCambiarCantidadBien" action="/api/goods-inventory/update-quantity" method="POST" autocomplete="off">
            <input type="hidden" name="bien_id" id="cambiarCantidadBienId" />
            <div>
                <label for="cantidadBien">Nueva cantidad:</label>
                <input type="number" min="0" name="cantidad" id="cantidadBien" required />
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Mover Bien -->
<div id="modalMoverBien" class="modal" style="display: none">
    <div class="modal-content">
        <span class="close" onclick="ocultarModal('#modalMoverBien')">&times;</span>
        <h2>Mover Bien</h2>
        <form id="formMoverBien" action="/api/goods-inventory/move" method="POST" autocomplete="off">
            <input type="hidden" name="bien_id" id="moverBienId" />
            <div>
                <label for="inventarioDestinoId">Inventario destino:</label>
                <select name="inventario_destino_id" id="inventarioDestinoId" required>
                    <!-- Las opciones se cargarán dinámicamente con JavaScript -->
                </select>
            </div>
            <div style="margin-top: 10px">
                <button type="submit" class="create-btn">Mover Bien</button>
            </div>
        </form>
    </div>
</div>
