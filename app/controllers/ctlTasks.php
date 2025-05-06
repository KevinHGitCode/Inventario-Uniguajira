<?php
require_once __DIR__ . '/sessionCheck.php';
require_once __DIR__ . '/../models/Tasks.php';
require_once 'app/helpers/validate-http.php';

class ctlTasks {
    private $tasksModel;

    public function __construct() {
        $this->tasksModel = new Tasks();
    }

    public function create() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['name'])) {
            return;
        }

        // Obtener datos desde POST
        $name = trim($_POST['name']);
        $description = trim($_POST['description'] ?? '');

        // Crear tarea en base de datos
        $result = $this->tasksModel->create($name, $description, $_SESSION['user_id']);

        // Responder según el resultado
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Tarea creada exitosamente.', 'id' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear la tarea en la base de datos.']);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('DELETE')) {
            return;
        }

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }

        $taskId = filter_var($id, FILTER_VALIDATE_INT);
        if ($taskId === false) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de tarea inválido']);
            return;
        }

        $result = $this->tasksModel->delete($taskId, $_SESSION['user_id']);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Tarea eliminada exitosamente'
            ]);
        } else {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo eliminar la tarea o no tienes permisos'
            ]);
        }
    }

    public function toggle() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('PATCH')) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de tarea es requerido']);
            return;
        }

        $taskId = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($taskId === false) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de tarea inválido']);
            return;
        }
        
        // Alternar estado
        $result = $this->tasksModel->changeState($taskId, $_SESSION['user_id']);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Estado de tarea actualizado'
            ]);
        } else {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar la tarea o no tienes permisos'
            ]);
        }
    }

    public function home() {
        $tasksModel = new Tasks();
        $dataTasks = $tasksModel->getByUser($_SESSION['user_id']);
        
        // Separar tareas por estado
        $tasks = [
            'pendientes' => array_filter($dataTasks, function($task) {
                return $task['estado'] === 'por hacer';
            }),
            'completadas' => array_filter($dataTasks, function($task) {
                return $task['estado'] === 'completado';
            })
        ];
        
        include __DIR__ . '/../views/home.php';
    }

    public function update() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('PUT')) {
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de tarea es requerido']);
            return;
        }
        
        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El nombre de la tarea es requerido']);
            return;
        }

        $taskId = filter_var($data['id'], FILTER_VALIDATE_INT);
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['description'] ?? '', FILTER_SANITIZE_STRING);

        $result = $this->tasksModel->updateName(
            $taskId,
            $name,
            $description,
            $_SESSION['user_id']
        );

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Tarea actualizada exitosamente'
            ]);
        } else {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo actualizar la tarea o no tienes permisos'
            ]);
        }
    }
}