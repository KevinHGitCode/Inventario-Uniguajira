// Funciones JavaScript
function showTaskModal() {
    document.getElementById('taskModal').style.display = 'flex';
    document.getElementById('taskName').focus();
}

function hideTaskModal() {
    document.getElementById('taskModal').style.display = 'none';
}

function createTask(event) {
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
}

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
            window.location.reload();
        } else {
            alert('Error: ' + (data.error || 'No se pudo actualizar la tarea'));
        }
    });
}