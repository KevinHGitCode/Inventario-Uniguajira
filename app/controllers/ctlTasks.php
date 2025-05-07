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
        $fecha = $_POST['date'] ?? date('Y-m-d'); // Nueva fecha desde el formulario

        // Crear tarea en base de datos
        $result = $this->tasksModel->create($name, $description, $_SESSION['user_id'], $fecha);

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

        $taskId = (int)$id;
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

        $taskId = (int)$data['id'];
        if ($taskId <= 0) {
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

        $taskId = (int)$data['id'];
        $name = trim($data['name']);
        $description = trim($data['description'] ?? '');
        $fecha = $data['date'] ?? null;

        $result = $this->tasksModel->updateName(
            $taskId,
            $name,
            $description,
            $_SESSION['user_id'],
            $fecha
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