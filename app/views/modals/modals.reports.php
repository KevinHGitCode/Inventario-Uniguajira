<!-- Modal para crear carpeta de reportes -->
<div id="modalCrearCarpeta" class="report-modal">
    <div class="report-modal-content">
        <div class="report-modal-header">
            <h2>Crear Carpeta de Reportes</h2>
            <span class="report-modal-close" onclick="cerrarModal('#modalCrearCarpeta')">&times;</span>
        </div>
        <div class="report-modal-body">
            <form id="formCrearCarpeta" onsubmit="crearCarpetaReporte(event)">
                <div class="report-form-group">
                    <label for="folderName">Nombre:</label>
                    <input type="text" id="folderName" name="folderName" placeholder="Ej: Reportes 2025-1" required>
                </div>
                <div class="report-form-actions">
                    <button type="button" class="report-btn-cancel" onclick="cerrarModal('#modalCrearCarpeta')">Cancelar</button>
                    <button type="submit" class="report-btn-save">Crear</button>
                </div>
            </form>
        </div>
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