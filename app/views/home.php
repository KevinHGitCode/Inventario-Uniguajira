<?php
require_once __DIR__ . '/../helpers/dateHelper.php';
?>

<h1>¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?>!</h1>

<?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'administrador'): ?>
    <div class="header-tasks">
        <h2 class="tittle-list-task">Tareas pendientes</h2>
        <button class="add-task-button" onclick="showTaskModal()" aria-label="Agregar tarea">+</button>
    </div>

    <div class="container-list-task">
        <?php if (empty($dataTasks['pendientes'])): ?>
            <p>No tienes tareas pendientes.</p>
        <?php else: ?>
            <?php foreach ($dataTasks['pendientes'] as $task): ?>
                <div class="task-card">
                    <button class="button check" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                    <div class="task-content">
                        <div class="task-text">
                            <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['descripcion'])): ?>
                                <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="task-date"><?= formatDate($task['fecha']) ?></p>
                    <button class="button edit-task" onclick="showEditTaskModal(<?= $task['id'] ?>, '<?= htmlspecialchars(addslashes($task['nombre']), ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars(addslashes($task['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?>', '<?= $task['fecha'] ?>')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="button delete-task" onclick="deleteTask(<?= $task['id'] ?>, this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($dataTasks['completadas'])): ?>
        <details>   
            <summary class="tittle-list-task completed-tasks-title">Tareas completadas</summary>  
        <div class="container-list-task completed-tasks">
            <?php foreach ($dataTasks['completadas'] as $task): ?>
                <div class="task-card completed">
                    <button class="button check completed" onclick="toggleTask(<?= $task['id'] ?>, this)">✓</button>
                    <div class="task-content">
                        <div class="task-text">
                            <h3 class="task-title"><?= htmlspecialchars($task['nombre'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if (!empty($task['descripcion'])): ?>
                                <p class="task-description"><?= htmlspecialchars($task['descripcion'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="task-date"><?= formatDate($task['fecha']) ?></p>
                    <button class="button edit-task" onclick="showEditTaskModal(<?= $task['id'] ?>, '<?= htmlspecialchars(addslashes($task['nombre']), ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars(addslashes($task['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8') ?>', '<?= $task['fecha'] ?>')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="button delete-task" onclick="deleteTask(<?= $task['id'] ?>, this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        </details>
    <?php endif; ?>


    
    <!-- Modal para crear tareas -->
    <div id="taskModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" onclick="hideTaskModal()">&times;</span>
            <h2>Crear Nueva Tarea</h2>
            <form id="taskForm" autocomplete="off" onsubmit="createTask(event)">
                <div class="form-group">
                    <label for="taskName">Nombre:</label>
                    <input type="text" id="taskName" required>
                </div>
                <div class="form-group">
                    <label for="taskDesc">Descripción:</label>
                    <textarea id="taskDesc"></textarea>
                </div>
                <div class="form-group">
                    <label for="taskDate">Fecha de creación:</label>
                    <input type="text" id="taskDate" value="<?= date('d/m/Y') ?>" readonly>
                </div>
                <button type="submit" class="btn-submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar tareas -->
    <div id="editTaskModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" onclick="hideEditTaskModal()">&times;</span>
            <h2>Editar Tarea</h2>
            <form id="editTaskForm" autocomplete="off" onsubmit="updateTask(event)">
                <input type="hidden" id="editTaskId">
                <div class="form-group">
                    <label for="editTaskName">Nombre:</label>
                    <input type="text" id="editTaskName" required>
                </div>
                <div class="form-group">
                    <label for="editTaskDesc">Descripción:</label>
                    <textarea id="editTaskDesc"></textarea>
                </div>
                <div class="form-group">
                    <label for="editTaskDate">Fecha de creación:</label>
                    <input type="text" id="editTaskDate" readonly>
                </div>
                <button type="submit" class="btn-submit">Actualizar</button>
            </form>
        </div>
    </div>


<?php endif; ?>
