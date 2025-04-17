// Funciones para el modal de tareas
const showTaskModal = () => {
    document.getElementById('taskModal').style.display = 'flex';
    document.getElementById('taskName').focus();
};

const hideTaskModal = () => {
    document.getElementById('taskModal').style.display = 'none';
};

// Función para crear tareas
const createTask = (event) => {
    event.preventDefault();
    
    const taskData = {
        name: document.getElementById('taskName').value,
        description: document.getElementById('taskDesc').value
    };

    fetch('/api/tasks/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(taskData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Error al crear tarea'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error en la conexión');
    });
};

// Función para alternar estado de tarea (versión mejorada)
const toggleTask = (taskId, element) => {
    fetch('/api/tasks/toggle', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: taskId })
    })
    .then(async response => {
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Error al actualizar la tarea');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Recargar la página para mostrar los cambios
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    });
};

// Función para eliminar tarea
const deleteTask = (taskId, element) => {
    if (!confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
        return;
    }

    fetch(`/api/tasks/delete/${taskId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            element.closest('.task-card').remove();
            showNotification('Tarea eliminada correctamente', 'success');
        } else {
            throw new Error(data.error || 'Error al eliminar la tarea');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    });
};

// Funciones para el modal de edición
const showEditTaskModal = (id, name, description, date) => {
    document.getElementById('editTaskId').value = id;
    document.getElementById('editTaskName').value = decodeURIComponent(name);
    document.getElementById('editTaskDesc').value = decodeURIComponent(description);

    // Formatear la fecha para mostrarla correctamente
    const formattedDate = new Date(date).toLocaleDateString('es-ES');
    document.getElementById('editTaskDate').value = formattedDate;
    
    document.getElementById('editTaskModal').style.display = 'flex';
    document.getElementById('editTaskName').focus();
};

const hideEditTaskModal = () => {
    document.getElementById('editTaskModal').style.display = 'none';
};

const formatDateForInput = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES'); // Formato dd/mm/yyyy
};

// Función para actualizar tarea
const updateTask = (event) => {
    event.preventDefault();
    
    const taskData = {
        id: document.getElementById('editTaskId').value,
        name: document.getElementById('editTaskName').value,
        description: document.getElementById('editTaskDesc').value
    };

    fetch('/api/tasks/update', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(taskData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'Error al actualizar tarea'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error en la conexión');
    });
};

// Función para mostrar notificaciones
const showNotification = (message, type) => {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
};

// Exportar funciones al ámbito global
window.taskFunctions = {
    showTaskModal,
    hideTaskModal,
    createTask,
    toggleTask,
    deleteTask,
    showNotification,
    showEditTaskModal,
    hideEditTaskModal,
    updateTask
};