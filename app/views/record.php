<?php require_once 'app/controllers/sessionCheck.php'; ?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">



<!-- LIBRERÍAS PARA GENERAR PDF - VERSIONES CORREGIDAS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="assets/js/historial.js"></script>


<div class="record-container">
    <h1>Historial</h1>

    <div class="top-bar">
        <div class="search-container">
            <input
                type="text"
                id="searchRecordInput"
                placeholder="Buscar historial"
                class="search-bar"
            />
            <i class="search-icon fas fa-search"></i>
        </div>
    
 <!-- Botón de filtro -->
        <button class="filter-btn" id="filterBtn"  onclick="mostrarModal('#Modalfiltrarhistorial')"> 
            <i class="fas fa-filter"></i>
            Filtros
        </button>
        <button class="report-btn" id="reportBtn"  onclick="generatePDF()"> 
            <i class="fas fa-file-pdf"></i> <!-- Icon for PDF file -->
            Reporte
        </button>

    <table class="record-table">
        <thead>
            <tr>
                <th class= "record-th">N°</th>
                <th class= "record-th">Usuario</th>
                <th class= "record-th">Acción</th>
                <th class= "record-th">Tabla</th>
                <th class= "record-th">Registro ID</th>
                <th class= "record-th">Detalles</th>
                <th class= "record-th">Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataRecords as $record): ?>
                <tr>
                    <td class= "record-td"><?= htmlspecialchars($record['id']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['usuario']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['accion']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['tabla']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['registro_id']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['detalles']) ?></td>
                    <td class= "record-td"><?= htmlspecialchars($record['fecha_hora']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Modal de Filtros -->
    <div id="Modalfiltrarhistorial" class="modal">
        <div class="modal-container">
            <!-- Header -->
            <div class="modal-header">
                <h2 class="modal-title">
                    <svg class="section-icon" viewBox="0 0 24 24">
                        <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrar Tabla
                </h2>
                <button class="close-btn" onclick="closeModal()" aria-label="Cerrar modal">
                    ×
                </button>
            </div>
            
            <!-- Body -->
            <div class="modal-body">
                <!-- Filtro por Usuario -->
                <div class="filter-section">
                    <h3 class="section-title">
                        <svg class="section-icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Usuarios
                    </h3>
                    
                    <div class="select-all">
                        <label class="checkbox-container">
                            <input type="checkbox" id="allUsers" onchange="toggleAllUsers()">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Todos los usuarios</span>
                        </label>
                    </div>
                    
                    <div class="options-list" id="userList">
                        <?php 
                        // Obtener nombres únicos de los usuarios en la tabla historial
                        $uniqueUserNames = array_unique(array_column($dataRecords, 'usuario'));
                        foreach ($uniqueUserNames as $userName): 
                        ?>
                            <label class="checkbox-container">
                                <input type="checkbox" class="user-checkbox" value="<?= htmlspecialchars($userName) ?>">
                                <span class="checkmark"></span>
                                <span class="checkbox-label"><?= htmlspecialchars($userName) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Filtro por Acción -->
                <div class="filter-section">
                    <h3 class="section-title">
                        <svg class="section-icon" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        Acciones
                    </h3>
                    
                    <div class="select-all">
                        <label class="checkbox-container">
                            <input type="checkbox" id="allActions" onchange="toggleAllActions()">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">Todas las acciones</span>
                        </label>
                    </div>
                    
                    <div class="options-list">
                        <label class="checkbox-container">
                            <input type="checkbox" class="action-checkbox" value="INSERT" onchange="updateActionSelection()">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">
                                INSERT
                                <span class="action-badge action-insert">Crear</span>
                            </span>
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" class="action-checkbox" value="UPDATE" onchange="updateActionSelection()">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">
                                UPDATE
                                <span class="action-badge action-update">Modificar</span>
                            </span>
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" class="action-checkbox" value="DELETE" onchange="updateActionSelection()">
                            <span class="checkmark"></span>
                            <span class="checkbox-label">
                                DELETE
                                <span class="action-badge action-delete">Eliminar</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="clearFilters()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                    Limpiar Campos
                </button>
                <button class="btn btn-primary" onclick="applyFilters()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    Aplicar
                </button>
            </div>
        </div>
    </div>

<!-- Asegúrate de incluir FontAwesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




