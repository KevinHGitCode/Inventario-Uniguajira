<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="content">
    <h2>Lista de bienes</h2>

    <div class="top-bar">
        <div class="search-container">
            <input
                type="text"
                id="searchInput"
                placeholder="Buscar o agregar bien"
                class="search-bar"
            />
            <i class="search-icon fas fa-search"></i>
        </div>
        <button id="btnCrear" class="create-btn">Crear</button>
    </div>

    <div class="bienes-grid">
        <?php foreach ($dataGoods as $bien): ?>
        <div class="bien-card">
            <!-- TODO: si la ruta de la imagen no devuelve, usar la imagen por defecto -->
            <img
                src="<?= htmlspecialchars($bien['imagen'] ?: 
                            'assets/uploads/img/default.jpg') ?>"
                class="bien-image"
            />
            <div class="bien-info">
                <h3><?= htmlspecialchars($bien['bien']) ?></h3>
                <p>
                    Cantidad:
                    <?= $bien['total_cantidad'] ?>
                </p>
            </div>
            <div class="actions">
                <a href="#"><i class="fas fa-edit"></i></a>
                <a href="#" class="btn-eliminar" data-id="<?= $bien['bien_id'] ?>"
                    ><i class="fas fa-trash"></i
                ></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal -->
    <div id="modalCrear" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nuevo Bien</h2>
            <form
                id="formCrearBien"
                action="/api/goods/create"
                method="POST"
                enctype="multipart/form-data"
            >
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" required />
                </div>
                <div>
                    <label>Tipo:</label>
                    <select name="tipo" required>
                        <option value="1">Cantidad</option>
                        <option value="2">Serial</option>
                    </select>
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
</div>
