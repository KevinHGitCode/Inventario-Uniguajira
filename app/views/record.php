<?php require_once 'app/controllers/sessionCheck.php'; ?>


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

<!-- Asegúrate de incluir FontAwesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Incluir el script de historial -->
<script src="ruta/a/historial.js"></script>
