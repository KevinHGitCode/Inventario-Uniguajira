<?php
require_once __DIR__ . '/../helpers/dateHelper.php'; // Incluir el helper
?>

<h1>¡Bienvenido, <?= htmlspecialchars($username) ?>!</h1>

<h2 class="tittle-list-task">Tareas pendientes</h2>

<div class="container-list-task">
    <?php if (empty($dataTasks)): ?>
        <p>No tienes tareas pendientes.</p>
    <?php else: ?>
        <?php foreach ($dataTasks as $task): ?>
            <div class="task-card <?= $task['estado'] === 'completado' ? 'completed' : '' ?>">
                <!-- Botón de check -->
                <button class="button check" 
                        onclick="toggleTask(<?= $task['id'] ?>, this)">
                    <?= $task['estado'] === 'completado' ? '✓' : '' ?>
                </button>

                <!-- Contenido de la tarjeta -->
                <div class="task-content">
                    <h3 class="task-title"><?= htmlspecialchars($task['nombre']) ?></h3>
                    <?php if (!empty($task['descripcion'])): ?>
                        <p class="task-duration"><?= htmlspecialchars($task['descripcion']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Fecha -->
                <?php if (!empty($task['fecha_limite'])): ?>
                    <p class="task-date">
                        <?= formatDate($task['fecha_limite']) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <button class="add-task-button" onclick="window.location.href='/tasks/create'">
        <span class="icon">+</span>
        <span class="text">Añadir tarea</span>
    </button>
</div>

<script>
function toggleTask(taskId, element) {
    fetch('/api/tasks/toggle', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: taskId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskCard = element.closest('.task-card');
            taskCard.classList.toggle('completed');
            element.textContent = taskCard.classList.contains('completed') ? '✓' : '';
        } else {
            alert('Error: ' + (data.error || 'No se pudo actualizar la tarea'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar la tarea');
    });
}
</script>