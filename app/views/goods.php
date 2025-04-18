<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="content">
    <h2>Lista de bienes</h2>

    <div class="top-bar">
        <div class="search-container">
            <input
                type="text"
                id="searchGood"
                placeholder="Buscar o agregar bien"
                class="search-bar searchInput"
            />
            <i class="search-icon fas fa-search"></i>
        </div>

        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <button id="btnCrear" class="create-btn" onclick="mostrarModal('#modalCrear')">Crear</button>
        <?php endif; ?>

    </div>

    <div class="bienes-grid">
        <?php foreach ($dataGoods as $bien): ?>
        <div class="bien-card card-item">
            <img
                src="<?= htmlspecialchars($bien['imagen'] ?: 'assets/uploads/img/goods/default.jpg') ?>"
                class="bien-image"
            />
            <div class="bien-info">
                <h3 class="name-item"><?= htmlspecialchars($bien['bien']) ?> <img
                    src="assets/icons/<?= $bien['tipo_bien'] === "Cantidad" ? 'bienCantidad.svg' : 'bienSerial.svg' ?>"
                    alt="Icono de tipo de bien"
                    class="bien-icon"
                /></h3>
                <p>
                    Cantidad:
                    <?= $bien['total_cantidad'] ?>
                </p>
            </div>

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <div class="actions">
                <a
                    class="btn-editar"
                    onclick="ActualizarBien(<?= $bien['bien_id'] ?>, '<?= htmlspecialchars($bien['bien']) ?>')"
                    ><i class="fas fa-edit"></i
                ></a>
                <a
                    class="btn-eliminar"
                    onclick="eliminarBien(<?= $bien['bien_id'] ?>)"
                    ><i class="fas fa-trash"></i
                ></a>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- Los formularios solo se cargan para el administrador -->
    <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
    <!-- Modal -->
    <div id="modalCrear" class="modal" style="display: none">
        <div class="modal-content">
            <span class="close" onclick="ocultarModal('#modalCrear')">&times;</span>
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

    <!-- Modal Actualizar -->
    <div id="modalActualizarBien" class="modal" style="display: none">
        <div class="modal-content">
            <span id="cerrarModalActualizarBien" class="close" onclick="ocultarModal('#modalActualizarBien')">&times;</span>
            <h2>Actualizar Bien</h2>
            <form id="formActualizarBien" action="/api/goods/update" enctype="multipart/form-data">
                <input type="hidden" name="id" id="actualizarId" />

                <div>
                    <label>Nombre:</label>
                    <input
                        type="text"
                        name="nombre"
                        id="actualizarNombre"
                        required
                    />
                </div>

                <div>
                    <label>Imagen (opcional):</label>
                    <input
                        type="file"
                        name="imagen"
                        id="actualizarImagen"
                        accept="image/*"
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
    <?php endif; ?>

</div>
