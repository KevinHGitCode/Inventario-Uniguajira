<?php
require_once __DIR__ . '/../helpers/dateHelper.php';
?>

<div class="tasks-container">
    <h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?>!</h1>

    <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
        <div class="tasks-header">
            <h2 class="section-title">Tareas pendientes</h2>
            <button class="add-task-button" onclick="mostrarModal('#taskModal')">+</button>
        </div>

        <div class="tasks-flex">
            <?php if (empty($dataTasks['pendientes'])): ?>
                <p class="no-tasks-message">No tienes tareas pendientes.</p>
            <?php else: ?>
                <?php foreach ($dataTasks['pendientes'] as $task): ?>
                    <div class="task-card">
                        <button class="task-checkbox" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                        <div class="task-content" 
                            onclick="btnEditTask(<?= $task['id'] ?>, 
                                '<?= htmlspecialchars(addslashes($task['nombre']), ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= htmlspecialchars(addslashes($task['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?>', 
                                '<?= $task['fecha'] ?>')">
                            <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['descripcion'])): ?>
                                <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="task-date"><?= formatDate($task['fecha']) ?></span>
                        <button style="position: absolute; top: 15px; right: 60px;" onclick="deleteTask(<?= $task['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($dataTasks['completadas'])): ?>
            <details>   
                <summary class="completed-tasks-title">Tareas completadas</summary>  
                <div class="tasks-flex completed-tasks">
                    <?php foreach ($dataTasks['completadas'] as $task): ?>
                        <div class="task-card completed">
                            <button class="task-checkbox completed" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                            <div class="task-content" 
                                onclick="btnEditTask(<?= $task['id'] ?>, 
                                    '<?= htmlspecialchars(addslashes($task['nombre']), ENT_QUOTES, 'UTF-8') ?>', 
                                    '<?= htmlspecialchars(addslashes($task['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?>', 
                                    '<?= $task['fecha'] ?>')">
                                <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <?php if (!empty($task['descripcion'])): ?>
                                    <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>
                            </div>
                            <span class="task-date"><?= formatDate($task['fecha']) ?></span>
                            <button style="position: absolute; top: 15px; right: 60px;" onclick="deleteTask(<?= $task['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        <?php endif; ?>
    <?php endif; ?>
</div>
