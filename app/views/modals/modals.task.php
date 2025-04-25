<!-- Modal para crear tareas -->
<div id="taskModal" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#taskModal')">&times;</span>
        <h2>Crear Nueva Tarea</h2>
        <form id="taskForm" autocomplete="off" action="/api/tasks/create" method="POST">
            <div>
                <label for="taskName">Nombre:</label>
                <input type="text" id="taskName" name="name" required>
            </div>
            <div>
                <label for="taskDesc">Descripción:</label>
                <textarea id="taskDesc" name="description"></textarea>
            </div>
            <div>
                <label for="taskDate">Fecha de creación:</label>
                <input type="text" id="taskDate" name="date" value="<?= date('d/m/Y') ?>" readonly>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar tareas -->
<div id="editTaskModal" class="modal">
    <div class="modal-content modal-content-medium">
        <span class="close" onclick="ocultarModal('#editTaskModal')">&times;</span>
        <h2>Editar Tarea</h2>
        <form id="editTaskForm" autocomplete="off" 
            action="/api/tasks/update"
            method="PUT"
        >
            <input type="hidden" id="editTaskId">
            <div>
                <label for="editTaskName">Nombre:</label>
                <input type="text" id="editTaskName" required>
            </div>
            <div>
                <label for="editTaskDesc">Descripción:</label>
                <textarea id="editTaskDesc"></textarea>
            </div>
            <div>
                <label for="editTaskDate">Fecha de creación:</label>
                <input type="text" id="editTaskDate" readonly>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn submit-btn">Actualizar</button>
            </div>
        </form>
    </div>
</div>