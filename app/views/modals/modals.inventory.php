<!-- Modal Crear Grupo -->
<div id="modalCrearGrupo" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearGrupo')">&times;</span>
        <h2>Nuevo Grupo</h2>
        <form
            id="formCrearGrupo"
            action="/api/groups/create"
            method="POST"
            autocomplete="off"
        >
            <div>
                <label for="nombreGrupo">Nombre del grupo:</label>
                <input type="text" name="nombre" id="nombreGrupo" required />
            </div>
            <div class="form-actions">
                <button 
                    type="submit" 
                    id="create-btn-grupo" 
                    class="btn submit-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ---------------------------------------------------------------------- -->
<!-- Modal Renombrar Grupo -->
<div id="modalRenombrarGrupo" class="modal">
    <div class="modal-content modal-content-small">
        <span id="cerrarModalRenombrarGrupo" class="close" onclick="ocultarModal('#modalRenombrarGrupo')">&times;</span>
        <h2>Renombrar Grupo</h2>
        <form 
            id="formRenombrarGrupo" 
            action="/api/groups/rename"
            autocomplete="off"
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

            <div class="form-actions">
                <button type="submit" class="btn submit-btn">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- --------------------------------------------------------------- -->


<!-- Modal Crear Inventario -->
<div id="modalCrearInventario" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearInventario')">&times;</span>
        <h2>Nuevo Inventario</h2>
        <form 
            id="formCrearInventario" 
            action="/api/inventories/create" 
            method="POST"
            autocomplete="off"
        >
            <input type="hidden" name="grupo_id" id="grupo_id_crear_inventario" required />
            <div>
                <label for="nombreInventario">Nombre del inventario:</label>
                <input type="text" name="nombre" id="nombreInventario" required />
            </div>

            <div class="form-actions">
                <button 
                id="btnCrearInventario"
                type="submit" 
                class="btn submit-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ---------------------------------------------------------------------- -->

<!-- Modal renombrar Inventario -->
<div id="modalRenombrarInventario" class="modal">
    <div class="modal-content modal-content-small">
        <span class="close" onclick="ocultarModal('#modalRenombrarInventario')">&times;</span>
        <h2>Renombrar Inventario</h2>
        <form 
            id="formRenombrarInventario"
            action="/api/inventories/rename"
            method="POST"
            autocomplete="off"
        >
            <input type="hidden" name="inventory_id" id="renombrarInventarioId" />
            <div>
                <label for="renombrarInventarioNombre">Nombre del inventario:</label>
                <input type="text" name="nombre" id="renombrarInventarioNombre" required />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Editar Responsable -->
<div id="modalEditarResponsable" class="modal">
    <div class="modal-content modal-content-small">
        <span class="close" onclick="ocultarModal('#modalEditarResponsable')">&times;</span>
        <h2>Editar Responsable</h2>
        <form id="formEditarResponsable" action="/api/inventories/updateResponsable" method="POST" autocomplete="off">
            <input type="hidden" name="id" id="editarResponsableId" />
            <div>
                <label for="editarResponsableNombre">Nuevo Responsable:</label>
                <input type="text" name="responsable" id="editarResponsableNombre" />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>


<!-- --------------------------------------------------------------- -->
<!-- ----------------- MODALES BIENES INVENTARIO ------------------- -->
<!-- --------------------------------------------------------------- -->

<?php include __DIR__ . "/modals.good-inventory.php"; ?>

<!-- Modal para Cambiar Cantidad de Bien -->
<div id="modalCambiarCantidadBien" class="modal">
    <div class="modal-content modal-content-small">
        <span class="close" onclick="ocultarModal('#modalCambiarCantidadBien')">&times;</span>
        <h2>Cambiar Cantidad</h2>
        <form id="formCambiarCantidadBien" action="/api/goods-inventory/update-quantity" method="POST" autocomplete="off">
            <input type="hidden" name="bien_id" id="cambiarCantidadBienId" />
            <div>
                <label for="cambiarCantidadBien">Nueva cantidad:</label>
                <input type="number" min="0" name="cantidad" id="cambiarCantidadBien" required />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para Mover Bien -->
<div id="modalMoverBien" class="modal">
    <div class="modal-content modal-content-small">
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
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Mover Bien</button>
            </div>
        </form>
    </div>
</div>