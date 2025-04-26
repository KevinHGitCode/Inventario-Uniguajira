<?php require_once 'app/controllers/sessionCheck.php'; ?>

<div class="record-container">
    <h1>Historial</h1>
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
