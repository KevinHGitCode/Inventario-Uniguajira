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


<!-- Modal Crear Reporte de un inventario -->
<div id="modalCrearReporteDeUnInventario" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeUnInventario')">&times;</span>
        <h2>Reporte de un inventario</h2>
        <form id="formReporteDeUnInventario" action="/api/reports/create" method="POST" autocomplete="off">
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporte" required />
            </div>
            <div>
                <label for="grupoSeleccionado">Grupo</label>
                <select name="grupoSeleccionado" id="grupoSeleccionado" required>
                    <option value="">Seleccione un grupo</option>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>
            <div>
                <label for="inventarioSeleccionado">Inventario</label>
                <select name="inventarioSeleccionado" id="inventarioSeleccionado" required disabled>
                    <option value="">Primero seleccione un grupo</option>
                    <!-- Las opciones se cargarán dinámicamente basadas en el grupo seleccionado -->
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Crear Reporte de un grupo -->
<div id="modalCrearReporteDeUnGrupo" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeUnGrupo')">&times;</span>
        <h2>Reporte de un grupo</h2>
        <form id="formReporteDeUnGrupo" action="/api/reports/create" method="POST" autocomplete="off">
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporteOfGrupo" required />
            </div>
            <div>
                <label for="grupoSeleccionado">Grupo</label>
                <select name="grupoSeleccionado" id="grupoSeleccionadoOfGrupo" required>
                    <option value="">Seleccione un grupo</option>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-Grupo" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>