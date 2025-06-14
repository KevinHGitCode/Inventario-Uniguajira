<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container content">
    <h1>Carpetas</h1>

    <div id="folders">
        <div class="top-bar">
            <div class="search-container">
                <input
                    id="searchFolder"
                    class="search-bar searchInput"
                    type="text"
                    placeholder="Buscar Carpeta"
                />
                <i class="search-icon fas fa-search"></i>
            </div>

            <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                <button id="btnCrearCarpeta" class="create-btn" onclick="mostrarModal('#modalCrearCarpeta')">Crear</button>
            <?php endif; ?>
        </div>

        <!-- Barra de control para carpetas -->
        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
        <div id="control-bar-folder" class="control-bar">
            <div class="selected-name">1 seleccionado</div>
            <div class="control-actions">
                <button class="control-btn" title="Renombrar" onclick="btnRenombrarCarpeta()">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="control-btn" title="Eliminar" onclick="btnEliminarCarpeta()">
                    <i class="fas fa-trash"></i>
                </button>
                <!-- <button class="control-btn" title="Más acciones">
                    <i class="fas fa-ellipsis-v"></i>
                </button> -->
            </div>
        </div>
        <?php endif; ?>

        <div class="report-grid">
            <?php if (isset($dataReportFolders) && !empty($dataReportFolders)): ?>
                <?php foreach ($dataReportFolders as $folder): ?>
                    <div class="report-folder-card card-item" 
                        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
                            data-id="<?= htmlspecialchars($folder['id']) ?>" 
                            data-name="<?= htmlspecialchars($folder['nombre']) ?>" 
                            data-type="folder"
                            onclick="toggleSelectItem(this)"
                        <?php endif; ?>
                    >        

                        <!-- inicio contenido -->
                        <div class="report-folder-left">
                            <i class="fas fa-folder fa-2x report-folder-icon"></i>
                        </div>
                        
                        <div class="report-folder-center">
                            <div class="report-folder-name name-item"><?= htmlspecialchars($folder['nombre']) ?></div>
                            <div class="report-folder-stats">
                                <span class="report-stat-item">
                                    <i class="fas fa-file-alt"></i>
                                    <?= htmlspecialchars($folder['total_reportes']) ?> reportes
                                </span>
                            </div>
                        </div>
                        
                        <div class="report-folder-right">
                            <button class="btn-open" onclick="abrirCarpeta(<?= htmlspecialchars($folder['id']) ?>)">
                                <i class="fas fa-external-link-alt"></i> Abrir
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

    <div id="report-content" class="hidden">
        <div class="report-back-and-title">
            <span id="folder-name" class="location">Reportes</span>
            <button class="report-btn-back" onclick="cerrarCarpeta()">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </button>
        </div>

        <div class="top-bar">
            <div class="search-container">
                <input
                    id="searchReport"
                    class="search-bar searchInput"
                    type="text"
                    placeholder="Buscar Reporte"
                />
                <i class="search-icon fas fa-search"></i>
            </div>
        </div>

        <div class="report-options-panel">
            <div class="report-option-box">
                <h3>Generar</h3>
                <ul class="report-generation-list">
                    <li onclick="mostrarModalReporte('#modalCrearReporteDeUnInventario')">
                        <i class="fas fa-arrow-right"></i> reporte de un inventario
                    </li>
                    <li onclick="mostrarModalReporte('#modalCrearReporteDeUnGrupo')">
                        <i class="fas fa-arrow-right"></i> reporte de un grupo
                    </li>
                    <li onclick="mostrarModalReporte('#modalCrearReporteDeUnTodosLosInventarios')">
                        <i class="fas fa-arrow-right"></i> reporte de todos los inventarios
                    </li>
                    <li onclick="mostrarModalReporte('#modalCrearReporteDeBienes')">
                        <i class="fas fa-arrow-right"></i> reporte de bienes
                    </li>
                    <li onclick="mostrarModalReporte('#modalCrearReporteDeEquipos')">
                        <i class="fas fa-arrow-right"></i> reporte de equipos
                    </li>
                </ul>
            </div>
        </div>

        <!-- Barra de control para reportes -->
        <?php if ($_SESSION['user_rol'] === 'administrador'): ?>
            <div id="control-bar-report" class="control-bar">
                <div class="selected-name">0 seleccionados</div>
                <div class="control-actions">
                    <button class="control-btn" title="Renombrar" onclick="btnRenombrarReporte()">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="control-btn" title="Eliminar" onclick="btnEliminarReporte()">
                        <i class="fas fa-trash"></i>
                    </button>
                    <!-- <button class="control-btn" title="Más acciones">
                        <i class="fas fa-ellipsis-v"></i>
                    </button> -->
                </div>
            </div>
        <?php endif; ?>

        <div id="report-content-item" class="report-item-grid">
            <!-- Aquí se cargarán dinámicamente los reportes -->
        </div>
    </div>
</div>
