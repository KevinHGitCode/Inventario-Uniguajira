<?php
require_once __DIR__ . '/../helpers/dateHelper.php';
?>

<div class="content">
    <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?>!</h1>

    <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
        <div class="tasks-header tasks-header-pending" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="section-title" style="user-select: text;">Tareas pendientes</h2>
            <button class="add-task-button" onclick="mostrarModal('#taskModal')">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <div class="tasks-flex">
            <?php if (empty($dataTasks['pendientes'])): ?>
                <div class="no-tasks-message">
                    <i class="fas fa-clipboard-list fa-3x" style="opacity: 0.6; color: #888;"></i>
                    <p>No tienes tareas pendientes.</p>
                </div>
            <?php else: ?>
                <?php foreach ($dataTasks['pendientes'] as $task): ?>
                    <div class="task-card">
                        <button class="task-checkbox" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                        <div class="task-content" 
                            onclick="btnEditTask(<?= $task['id'] ?>, 
                                '<?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= htmlspecialchars($task['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= $task['fecha'] ?>')">
                            <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['descripcion'])): ?>
                                <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="task-footer">
                            <span class="task-date <?= getDateStatus($task['fecha']) ?>">
                                <?= formatDate($task['fecha']) ?>
                            </span>
                            <button class="task-trash-button" onclick="deleteTask(<?= $task['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="tasks-header tasks-header-completed" style="display: flex; align-items: center; gap: 8px;" onclick="toggleCompletedTasks()">
            <i class="fas fa-chevron-down toggle-arrow" id="completedTasksArrow"></i>
            <h2 class="section-title" style="user-select: text;">Tareas completadas</h2>
        </div>
        <div class="tasks-flex completed-tasks collapsible">
            <?php if (empty($dataTasks['completadas'])): ?>
                <div class="no-tasks-message">
                    <i class="fas fa-box-open fa-3x"></i>
                    <p>No tienes tareas completadas.</p>
                </div>
            <?php else: ?>
                <?php foreach ($dataTasks['completadas'] as $task): ?>
                    <div class="task-card completed">
                        <button class="task-checkbox completed" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                        <div class="task-content" 
                            onclick="btnEditTask(<?= $task['id'] ?>, 
                                '<?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= htmlspecialchars($task['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= $task['fecha'] ?>')">
                            <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['descripcion'])): ?>
                                <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="task-footer">
                            <span class="task-date completo">
                                <?= formatDate($task['fecha']) ?>
                            </span>
                            <button class="task-trash-button" onclick="deleteTask(<?= $task['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
