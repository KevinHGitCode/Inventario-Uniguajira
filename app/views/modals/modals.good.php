<!-- Los formularios solo se cargan para el administrador -->
<?php if ($_SESSION['user_rol'] === 'administrador'): ?>
<?php endif; ?>

<!-- Modal -->
<div id="modalCrearBien" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearBien')">&times;</span>
        <h2>Nuevo Bien</h2>
        <form
            id="formCrearBien"
            action="/api/goods/create"
            method="POST"
            enctype="multipart/form-data"
            autocomplete="off"
        >
            <div>
                <label for="nombreBien">Nombre:</label>
                <input type="text" name="nombre" id="nombreBien" required />
            </div>
            <div>
                <label for="tipoBien">Tipo:</label>
                <select name="tipo" id="tipoBien" required>
                    <option value="1">Cantidad</option>
                    <option value="2">Serial</option>
                </select>
            </div>
            <div>
                <label for="imagenBien">Imagen:</label>
                <input type="file" name="imagen" id="imagenBien" accept="image/*" />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Actualizar -->
<div id="modalActualizarBien" class="modal">
    <div class="modal-content modal-content-medium">
        <span id="cerrarModalActualizarBien" class="close" onclick="ocultarModal('#modalActualizarBien')">&times;</span>
        <h2>Actualizar Bien</h2>
        <form id="formActualizarBien" action="/api/goods/update" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="id" id="actualizarId" />

            <div>
                <label for="actualizarNombreBien">Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="actualizarNombreBien"
                    required
                />
            </div>

            <div>
                <label for="actualizarImagenBien">Imagen (opcional):</label>
                <input
                    type="file"
                    name="imagen"
                    id="actualizarImagenBien"
                    accept="image/*"
                />
            </div>

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>