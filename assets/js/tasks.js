// Métodos que quedaron en la versión simplificada

// showTaskModal (replaced) - Abre el modal para crear tareas
// hideTaskModal (replaced) - Cierra el modal para crear tareas
// showEditTaskModal -> btnEditTask - Abre el modal para editar tareas
// hideEditTaskModal (replaced) - Cierra el modal para editar tareas

// createTask - Crea una nueva tarea
// updateTask - Actualiza una tarea existente
// toggleTask - Cambia el estado de una tarea (completada/pendiente)
// deleteTask - Elimina una tarea
// showNotification - Muestra notificaciones al usuario
// moveTaskToProperSection - Mueve tarjetas de tarea entre secciones
// sortTasksByDate - Ordena las tareas por fecha

function btnEditTask(id, name, description, date) {
    document.getElementById('editTaskId').value = id;
    document.getElementById('editTaskName').value = decodeURIComponent(name);
    document.getElementById('editTaskDesc').value = decodeURIComponent(description);
    document.getElementById('editTaskDate').value = new Date(date).toLocaleDateString('es-ES');
    
    // document.getElementById('editTaskName').focus();
    mostrarModal('#editTaskModal');
};

// Operaciones CRUD


function initFormsTask() {
    // Para crear tareas
    inicializarFormularioAjax('#taskForm', {
        onBefore: (form, config) => {
            const taskName = document.getElementById('taskName').value.trim();
            if (!taskName) {
                showNotification('El nombre de la tarea es requerido', 'error');
                return false; // Cancelar envío
            }
        },
        closeModalOnSuccess: true,
        resetOnSuccess: true,
        onSuccess: (data) => {
            loadContent('/home');
            showNotification('Tarea creada exitosamente', 'success');
        }
    });

    // Para actualizar tareas
    inicializarFormularioAjax('#editTaskForm', {
        contentType: 'application/json',
        customBody: (form) => {
            return {
                id: document.getElementById('editTaskId').value,
                name: document.getElementById('editTaskName').value,
                description: document.getElementById('editTaskDesc').value
            };
        },
        onSuccess: (data) => {
            loadContent('/home');
            showNotification('Tarea actualizada exitosamente', 'success');
        }
    });
}

const toggleTask = (taskId, button) => {
    fetch('/api/tasks/toggle', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: taskId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskCard = button.closest('.task-card');
            taskCard.classList.toggle('completed');
            button.classList.toggle('completed');
            moveTaskToProperSection(taskCard);
            showNotification('Estado de tarea actualizado', 'success');
        } else {
            throw new Error(data.error || 'Error al actualizar el estado de la tarea');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    });
};

const deleteTask = (taskId) => {

    eliminarRegistro({
        url: `/api/tasks/delete/${taskId}`,
        onSuccess: (response) => {
            if (response.success) {
                loadContent('/goods', false);
            }
            showToast(response);

        }
    });
};

// Utilitarios
const showNotification = (message, type) => {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
};

// Funciones para manipulación del DOM
function moveTaskToProperSection(taskCard) {
    const isCompleted = taskCard.classList.contains('completed');
    
    const pendingContainer = document.querySelector('.container-list-task:not(.completed-tasks)');
    let completedContainer = document.querySelector('.completed-tasks');
    
    if (!completedContainer && isCompleted) {
        const completedTitle = document.querySelector('.completed-tasks-title');
        if (!completedTitle) return;
        
        completedContainer = document.createElement('div');
        completedContainer.className = 'container-list-task completed-tasks';
        completedContainer.style.display = 'grid';
        completedContainer.style.gridTemplateColumns = '1fr 1fr';
        completedContainer.style.gap = '12px';
        completedTitle.insertAdjacentElement('afterend', completedContainer);
    }
    
    const targetContainer = isCompleted ? completedContainer : pendingContainer;
    if (targetContainer) {
        targetContainer.appendChild(taskCard);
        sortTasksByDate(targetContainer);
    }
}

function sortTasksByDate(container) {
    const taskCards = Array.from(container.querySelectorAll('.task-card'));
    
    taskCards.sort((a, b) => {
        const dateA = a.querySelector('.task-date').textContent;
        const dateB = b.querySelector('.task-date').textContent;
        
        const priorityOrder = {"Hoy": 3, "Ayer": 2};
        const priorityA = priorityOrder[dateA.trim()] || 0;
        const priorityB = priorityOrder[dateB.trim()] || 0;
        
        if (priorityA !== priorityB) {
            return priorityB - priorityA;
        }
        
        try {
            const parseCustomDate = (dateText) => {
                if (dateText.includes('Abr')) return new Date(2025, 3, parseInt(dateText));
                if (dateText.includes('May')) return new Date(2025, 4, parseInt(dateText));
                if (dateText.includes('/')) {
                    const parts = dateText.split('/');
                    return new Date(parts[2], parts[1]-1, parts[0]);
                }
                return new Date();
            };
            
            return parseCustomDate(dateB) - parseCustomDate(dateA);
        } catch (e) {
            console.error("Error al ordenar fechas:", e);
            return 0;
        }
    });
    
    taskCards.forEach(card => container.appendChild(card));
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.container-list-task');
    containers.forEach(container => {
        sortTasksByDate(container);
        container.style.display = 'grid';
        container.style.gridTemplateColumns = '1fr 1fr';
        container.style.gap = '12px';
    });
});
