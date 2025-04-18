// Funciones para el modal de tareas
const showTaskModal = () => {
    document.getElementById('taskModal').style.display = 'flex';
    document.getElementById('taskName').focus();
};

const hideTaskModal = () => {
    document.getElementById('taskModal').style.display = 'none';
};

//-------------------------------------------------------------------------------------------------------//

// Función para crear tareas
const createTask = (event) => {
    event.preventDefault();
    
    const taskName = document.getElementById('taskName').value.trim();
    const taskDesc = document.getElementById('taskDesc').value.trim();
    
    if (!taskName) {
        showNotification('El nombre de la tarea es requerido', 'error');
        return;
    }

    const taskData = {
        name: taskName,
        description: taskDesc
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
            // Cerrar el modal
            hideTaskModal();
            
            // Limpiar el formulario
            document.getElementById('taskForm').reset();
            
            // Añadir la nueva tarea al DOM
            //addTaskToDOM(data.task);
            loadContent('/home');
            showNotification('Tarea creada exitosamente', 'success');
        } else {
            showNotification(data.error || 'Error al crear tarea', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error en la conexión', 'error');
    });
};

//-------------------------------------------------------------------------------------------------------//

// Función para alternar el estado de una tarea (completada/pendiente)
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
            // Obtener la tarjeta de tarea
            const taskCard = button.closest('.task-card');
            
            // Alternar la clase 'completed' en la tarjeta
            taskCard.classList.toggle('completed');
            
            // Alternar la clase 'completed' en el botón
            button.classList.toggle('completed');
            
            // Mover la tarjeta a la sección correcta
            moveTaskToProperSection(taskCard);
            
            // Reorganizar el grid después de mover la tarea
            reorganizeTasksGrid();
            
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

//-------------------------------------------------------------------------------------------------------//

// Función para mover la tarjeta de tarea a la sección correcta
// Esta función se llama después de completar o eliminar una tarea

function moveTaskToProperSection(taskCard) {
    const isCompleted = taskCard.classList.contains('completed');
    
    // Obtener contenedores
    const pendingContainer = document.querySelector('.container-list-task:not(.completed-tasks)');
    let completedContainer = document.querySelector('.completed-tasks');
    
    // Si no existe contenedor de completadas pero se necesita
    if (!completedContainer && isCompleted) {
        // Verificar si existe el título
        const completedTitle = document.querySelector('.completed-tasks-title');
        if (!completedTitle) return;
        
        // Crear el contenedor
        completedContainer = document.createElement('div');
        completedContainer.className = 'container-list-task completed-tasks';
        completedTitle.insertAdjacentElement('afterend', completedContainer);
    }
    
    // Mover tarjeta al contenedor apropiado
    const targetContainer = isCompleted ? completedContainer : pendingContainer;
    if (targetContainer) {
        // En lugar de solo añadir al final, vamos a ordenar por fecha
        targetContainer.appendChild(taskCard);
        
        // Ordenar tareas por fecha después de añadir la nueva tarea
        sortTasksByDate(targetContainer);
        
        // Si el contenedor es de tareas completadas, asegurar estilo de grid
        if (isCompleted && completedContainer) {
            completedContainer.style.display = 'grid';
            completedContainer.style.gridTemplateColumns = '1fr 1fr';
            completedContainer.style.gap = '12px';
        }
    }
}

//-------------------------------------------------------------------------------------------------------//

// Nueva función para ordenar tareas por fecha (más reciente primero)

function sortTasksByDate(container) {
    const taskCards = Array.from(container.querySelectorAll('.task-card'));
    
    // Ordenar por fecha (más reciente primero)
    taskCards.sort((a, b) => {
        const dateA = a.querySelector('.task-date').textContent;
        const dateB = b.querySelector('.task-date').textContent;
        
        // Convertir fechas para comparación
        // Primero verificamos si hay textos como "Hoy", "Ayer" que tienen prioridad
        const priorityOrder = {"Hoy": 3, "Ayer": 2};
        
        const priorityA = priorityOrder[dateA.trim()] || 0;
        const priorityB = priorityOrder[dateB.trim()] || 0;
        
        if (priorityA !== priorityB) {
            return priorityB - priorityA; // Mayor prioridad primero
        }
        
        // Si no son fechas especiales o tienen la misma prioridad
        // Intentamos convertir fechas normales (asumiendo formato dd/mm/yyyy o similar)
        try {
            // Para fechas como "15 Abr" convertimos a una fecha temporal para comparar
            const parseCustomDate = (dateText) => {
                if (dateText.includes('Abr')) return new Date(2025, 3, parseInt(dateText));
                if (dateText.includes('May')) return new Date(2025, 4, parseInt(dateText));
                if (dateText.includes('/')) {
                    const parts = dateText.split('/');
                    return new Date(parts[2], parts[1]-1, parts[0]);
                }
                return new Date(); // Fallback
            };
            
            const dateObjA = parseCustomDate(dateA);
            const dateObjB = parseCustomDate(dateB);
            
            // Ordenar descendentemente (más reciente primero)
            return dateObjB - dateObjA;
        } catch (e) {
            console.error("Error al ordenar fechas:", e);
            return 0;
        }
    });
    
    // Reorganizar elementos según el orden
    taskCards.forEach(card => container.appendChild(card));
}

//-------------------------------------------------------------------------------------------------------//

// Ordenar las tareas al cargar la página

document.addEventListener('DOMContentLoaded', function() {
    // Ordenar tareas pendientes
    const pendingContainer = document.querySelector('.container-list-task:not(.completed-tasks)');
    if (pendingContainer) {
        sortTasksByDate(pendingContainer);
    }
    
    // Ordenar tareas completadas
    const completedContainer = document.querySelector('.completed-tasks');
    if (completedContainer) {
        sortTasksByDate(completedContainer);
    }
    
    // Asegurarte de que los contenedores tienen el estilo correcto
    const allContainers = document.querySelectorAll('.container-list-task');
    allContainers.forEach(container => {
        container.style.display = 'grid';
        container.style.gridTemplateColumns = '1fr 1fr';
        container.style.gap = '12px';
    });
});

//-------------------------------------------------------------------------------------------------------//

// Función para reorganizar el grid de tareas
// Esta función se llama después de eliminar o completar una tarea
function reorganizeTasksGrid() {
    // Asegurar que los contenedores de tareas tengan el layout correcto
    setTimeout(() => {
        // Primero el contenedor de tareas pendientes
        const pendingContainer = document.querySelector('.container-list-task:not(.completed-tasks)');
        if (pendingContainer) {
            // Refrescar estilos si es necesario
            pendingContainer.style.display = 'grid';
            pendingContainer.style.gridTemplateColumns = '1fr 1fr';
            pendingContainer.style.gap = '12px';
        }
        
        // Luego el contenedor de tareas completadas
        const completedContainer = document.querySelector('.completed-tasks');
        if (completedContainer) {
            completedContainer.style.display = 'grid';
            completedContainer.style.gridTemplateColumns = '1fr 1fr';
            completedContainer.style.gap = '12px';
        }
    }, 50); // Un poco más de tiempo para asegurar que el DOM se actualice
}

//-------------------------------------------------------------------------------------------------------//

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

//-------------------------------------------------------------------------------------------------------//

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

//-------------------------------------------------------------------------------------------------------//

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
            loadContent('/home'); // Recargar el contenido de la página
        } else {
            alert('Error: ' + (data.error || 'Error al actualizar tarea'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error en la conexión');
    });
};

//-------------------------------------------------------------------------------------------------------//

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

//-------------------------------------------------------------------------------------------------------//

// Función para marcar tarea como completada o no
function addTaskToDOM(taskData) {
    // Crear elemento de tarjeta
    const taskCard = document.createElement('div');
    taskCard.className = 'task-card';
    
    // Formatear fecha para mostrar
    const taskDate = new Date(taskData.date);
    const formattedDate = formatDateForDisplay(taskDate);
    
    // Construir el HTML de la tarjeta
    taskCard.innerHTML = `
        <button class="button check" onclick="toggleTask(${taskData.id}, this)">✓</button>
        <div class="task-content">
            <div class="task-text">
                <h3 class="task-title">${escapeHtml(taskData.name)}</h3>
                ${taskData.description ? `<p class="task-description">${escapeHtml(taskData.description)}</p>` : ''}
            </div>
        </div>
        <p class="task-date">${formattedDate}</p>
        <button class="button edit-task" onclick="showEditTaskModal(${taskData.id}, '${escapeHtml(taskData.name)}', '${escapeHtml(taskData.description || '')}', '${taskData.date}')">
            <i class="fas fa-edit"></i>
        </button>
        <button class="button delete-task" onclick="deleteTask(${taskData.id}, this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    // Añadir al contenedor de tareas pendientes
    const pendingContainer = document.querySelector('.container-list-task:not(.completed-tasks)');
    if (pendingContainer) {
        pendingContainer.prepend(taskCard); // Añadir al inicio para mantener orden
        
        // Ordenar tareas por fecha
        sortTasksByDate(pendingContainer);
        
        // Forzar actualización del grid si es necesario
        reorganizeTasksGrid();
    }
}

//-------------------------------------------------------------------------------------------------------//

// Función auxiliar para escapar HTML
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

//-------------------------------------------------------------------------------------------------------//

// Función para formatear fecha para mostrar
function formatDateForDisplay(date) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const taskDate = new Date(date);
    taskDate.setHours(0, 0, 0, 0);
    
    if (taskDate.getTime() === today.getTime()) {
        return 'Hoy';
    }
    
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    if (taskDate.getTime() === yesterday.getTime()) {
        return 'Ayer';
    }
    
    return taskDate.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
}

//-------------------------------------------------------------------------------------------------------//

// Exportar funciones al ámbito global
window.taskFunctions = {
    showTaskModal,
    hideTaskModal,
    createTask,
    addTaskToDOM,
    deleteTask,
    showNotification,
    showEditTaskModal,
    hideEditTaskModal,
    updateTask,
    toggleTask
};

//-------------------------------------------------------------------------------------------------------//