<!-- Modal Crear Grupo -->
<div id="modalCrearCarpeta" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearCarpeta')">&times;</span>
        <h2>Nueva Carpeta</h2>
        <form
            id="formCrearCarpeta"
            action="/api/folders/create"
            method="POST"
            autocomplete="off"
        >
            <div>
                <label for="nombreCarpeta">Nombre del grupo:</label>
                <input type="text" name="nombreCarpeta" id="nombreCarpeta" required />
            </div>
            <div class="form-actions">
                <button 
                    type="submit" 
                    id="create-btn-folder" 
                    class="btn submit-btn">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Modal Renombrar carpeta -->
<div id="modalRenombrarCarpeta" class="modal">
    <div class="modal-content modal-content-small">
        <span id="cerrarModalRenombrarCarpeta" class="close" onclick="ocultarModal('#modalRenombrarCarpeta')">&times;</span>
        <h2>Renombrar Grupo</h2>
        <form 
            id="formRenombrarCarpeta" 
            action="/api/folders/rename"
            method="POST"
            autocomplete="off"
        >
            <input type="hidden" name="folder_id" id="carpetaRenombrarId" />

            <div>
                <label for="carpetaRenombrarNombre">Nuevo Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="carpetaRenombrarNombre"
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