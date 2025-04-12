<?php
require_once __DIR__ . '/../helpers/dateHelper.php';
?>

<h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?>!</h1>

<h2 class="tittle-list-task">Tareas pendientes</h2>

<div class="container-list-task">
    <?php if (empty($dataTasks['pendientes'])): ?>
        <p>No tienes tareas pendientes.</p>
    <?php else: ?>
        <?php foreach ($dataTasks['pendientes'] as $task): ?>
            <div class="task-card">
                <button class="button check" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                <div class="task-content">
                    <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if (!empty($task['descripcion'])): ?>
                        <p class="task-duration"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
                <p class="task-date"><?= formatDate($task['fecha']) ?></p>
                <button class="button delete-task" onclick="deleteTask(<?= $task['id'] ?>, this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
        <button class="add-task-button" onclick="showTaskModal()">
            <span class="icon">+</span>
            <span class="text">Añadir tarea</span>
        </button>
    <?php endif; ?>
</div>

<!-- Modal para crear tareas -->
<div id="taskModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-modal" onclick="hideTaskModal()">&times;</span>
        <h2>Crear Nueva Tarea</h2>
        <form id="taskForm" onsubmit="createTask(event)">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" id="taskName" required>
            </div>
            <div class="form-group">
                <label for="taskDesc">Descripción:</label>
                <textarea id="taskDesc"></textarea>
            </div>
            <div class="form-group">
                <label>Fecha de creación:</label>
                <input type="text" id="taskDate" value="<?= date('d/m/Y') ?>" readonly>
            </div>
            <button type="submit" class="btn-submit">Guardar</button>
        </form>
    </div>
</div>