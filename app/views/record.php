<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="container">
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
</div>


