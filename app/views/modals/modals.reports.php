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


<!-- Modal Crear Reporte de un inventario - ACTUALIZADO -->
<div id="modalCrearReporteDeUnInventario" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeUnInventario')">&times;</span>
        <h2>Reporte de un inventario</h2>
        <form id="formReporteDeUnInventario" action="/api/reports/create" method="POST" autocomplete="off">
            <!-- Campo oculto para folder_id -->
            <input type="hidden" name="folder_id" id="folderIdInventario" />
            
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporte" required />
            </div>
            <div>
                <label for="grupoSeleccionado">Grupo</label>
                <select name="grupoSeleccionado" id="grupoSeleccionado" required>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>
            <div>
                <label for="inventarioSeleccionado">Inventario</label>
                <select name="inventarioSeleccionado" id="inventarioSeleccionado" required disabled>    
                    <!-- Las opciones se cargarán dinámicamente basadas en el grupo seleccionado -->
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-inventary" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Crear Reporte de un grupo - ACTUALIZADO -->
<div id="modalCrearReporteDeUnGrupo" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeUnGrupo')">&times;</span>
        <h2>Reporte de un grupo</h2>
        <form id="formReporteDeUnGrupo" action="/api/reports/create" method="POST" autocomplete="off">
            <!-- Campo oculto para folder_id -->
            <input type="hidden" name="folder_id" id="folderIdGrupo" />
            
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporteOfGrupo" required />
            </div>
            <div>
                <label for="grupoSeleccionado">Grupo</label>
                <select name="grupoSeleccionado" id="grupoSeleccionadoOfGrupo" required>
                    <!-- Las opciones se cargarán dinámicamente -->
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-Grupo" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Crear Reporte de un grupo - ACTUALIZADO -->
<div id="modalCrearReporteDeUnTodosLosInventarios" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeUnTodosLosInventarios')">&times;</span>
        <h2>Reporte de todos los inventarios</h2>
        <form id="formReporteDeTodosLosInventarios" action="/api/reports/create" method="POST" autocomplete="off">
            <!-- Campo oculto para folder_id -->
            <input type="hidden" name="folder_id" id="folderIdTodosLosInventarios" />
            
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporteDeTodosLosInventarios" required />
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-all-inventary" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Crear Reporte de un grupo - ACTUALIZADO -->
<div id="modalCrearReporteDeBienes" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeBienes')">&times;</span>
        <h2>Reporte de bienes</h2>
        <form id="formReporteDeBienes" action="/api/reports/create" method="POST" autocomplete="off">
            <!-- Campo oculto para folder_id -->
            <input type="hidden" name="folder_id" id="folderIdDeBienes" />
            
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporteDeBienes" required />
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-goods" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Crear Reporte de un grupo - ACTUALIZADO -->
<div id="modalCrearReporteDeEquipos" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#modalCrearReporteDeEquipos')">&times;</span>
        <h2>Reporte de equipos</h2>
        <form id="formReporteDeEquipos" action="/api/reports/create" method="POST" autocomplete="off">
            <!-- Campo oculto para folder_id -->
            <input type="hidden" name="folder_id" id="folderIdDeEquipos" />
            
            <div>
                <label for="nombreReporte">Nombre del reporte</label>
                <input type="text" name="nombreReporte" id="nombreReporteDeEquipos" required />
            </div>
            <div class="form-actions">
                <button type="submit" id="create-btn-report-serial" class="btn submit-btn">Crear</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Renombrar reporte -->
<div id="modalRenombrarReporte" class="modal">
    <div class="modal-content modal-content-small">
        <span id="cerrarModalRenombrarReporte" class="close" onclick="ocultarModal('#modalRenombrarReporte')">&times;</span>
        <h2>Renombrar reporte</h2>
        <form 
            id="formRenombrarReporte" 
            action="/api/reports/rename"
            method="POST"
            autocomplete="off"
        >
            <input type="hidden" name="report_id" id="reporteRenombrarId" />

            <div>
                <label for="reporteRenombrarNombre">Nuevo Nombre:</label>
                <input
                    type="text"
                    name="nombre"
                    id="reporteRenombrarNombre"
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