<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container content">
    <h1>Reportes</h1>

    <div id="report-folders">
        <div class="report-top-bar">
            <div class="report-search-container">
                <input
                    id="searchReportFolder"
                    class="report-search-bar"
                    type="text"
                    placeholder="Buscar Inventario"
                />
                <i class="fas fa-search report-search-icon"></i>
            </div>

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <button id="btnCrearCarpetaReporte" class="report-create-btn">Crear</button>
            <?php endif; ?>
        </div>

        <div class="report-grid">
            <?php if (isset($dataReportFolders) && !empty($dataReportFolders)): ?>
                <?php foreach ($dataReportFolders as $folder): ?>
                    <div class="report-folder-card">
                        <div class="report-folder-left">
                            <i class="fas fa-folder fa-2x report-folder-icon"></i>
                        </div>
                        
                        <div class="report-folder-center">
                            <div class="report-folder-name"><?= htmlspecialchars($folder['nombre']) ?></div>
                            <div class="report-folder-stats">
                                <span class="report-stat-item">
                                    <i class="fas fa-file-alt"></i>
                                    <?= htmlspecialchars($folder['total_reportes']) ?> reportes
                                </span>
                            </div>
                        </div>
                        
                        <div class="report-folder-right">
                            <button class="report-btn-open" onclick="abrirCarpetaReporte(<?= htmlspecialchars($folder['id']) ?>, '<?= htmlspecialchars($folder['nombre']) ?>')">
                                Abrir
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="report-empty-state">
                    <i class="fas fa-folder fa-3x"></i>
                    <p>No hay carpetas de reportes disponibles</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="folder-reports" class="hidden">
        <div class="report-back-and-title">
            <span id="folder-name" class="report-location">Reporte</span>
            <button class="report-btn-back" onclick="cerrarCarpetaReporte()">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </button>
        </div>

        <div class="report-top-bar">
            <div class="report-search-container">
                <input
                    id="searchReportItem"
                    class="report-search-bar"
                    type="text"
                    placeholder="Buscar Inventario"
                />
                <i class="fas fa-search report-search-icon"></i>
            </div>
        </div>

        <div class="report-options-panel">
            <div class="report-option-box">
                <h3>Generar</h3>
                <ul class="report-generation-list">
                    <li onclick="generarReporte('inventario')"><i class="fas fa-arrow-right"></i> reporte de un inventario</li>
                    <li onclick="generarReporte('grupo')"><i class="fas fa-arrow-right"></i> reporte de un grupo de inventarios</li>
                    <li onclick="generarReporte('todos')"><i class="fas fa-arrow-right"></i> reporte de todos los inventarios</li>
                    <li onclick="generarReporte('bienes')"><i class="fas fa-arrow-right"></i> reporte de bienes</li>
                    <li onclick="generarReporte('equipos')"><i class="fas fa-arrow-right"></i> reporte de equipos</li>
                </ul>
            </div>
        </div>

        <div id="report-items-content" class="report-item-grid">
            <!-- Aquí se cargarán dinámicamente los reportes -->
        </div>
    </div>
</div>

<!-- Modal para crear carpeta de reportes -->
<div id="modalCrearCarpetaReporte" class="report-modal">
    <div class="report-modal-content">
        <div class="report-modal-header">
            <h2>Crear Carpeta de Reportes</h2>
            <span class="report-modal-close" onclick="cerrarModal('#modalCrearCarpetaReporte')">&times;</span>
        </div>
        <div class="report-modal-body">
            <form id="formCrearCarpetaReporte" onsubmit="crearCarpetaReporte(event)">
                <div class="report-form-group">
                    <label for="folderName">Nombre:</label>
                    <input type="text" id="folderName" name="folderName" placeholder="Ej: 2025-1" required>
                </div>
                <div class="report-form-actions">
                    <button type="button" class="report-btn-cancel" onclick="cerrarModal('#modalCrearCarpetaReporte')">Cancelar</button>
                    <button type="submit" class="report-btn-save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para opciones de reporte -->
<div id="modalOpcionesReporte" class="report-modal">
    <div class="report-modal-content">
        <div class="report-modal-header">
            <h2 id="modalReportTitle">Generar Reporte</h2>
            <span class="report-modal-close" onclick="cerrarModal('#modalOpcionesReporte')">&times;</span>
        </div>
        <div class="report-modal-body">
            <form id="formGenerarReporte" onsubmit="generarReporteSubmit(event)">
                <input type="hidden" id="reportType" name="reportType">
                <input type="hidden" id="folderId" name="folderId">
                
                <div id="opcionesReporte">
                    <!-- Las opciones se cargarán dinámicamente según el tipo de reporte -->
                </div>
                
                <div class="report-form-actions">
                    <button type="button" class="report-btn-cancel" onclick="cerrarModal('#modalOpcionesReporte')">Cancelar</button>
                    <button type="submit" class="report-btn-save">Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>