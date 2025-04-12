<?php
require_once __DIR__ . '/sessionCheck.php';
require_once __DIR__ . '/../models/Tasks.php';

class ctlTasks {
    private $tasksModel;

    public function __construct() {
        $this->tasksModel = new Tasks();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }

    public function create() {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido', 405);
            }

            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['name'])) {
                throw new Exception("El nombre de la tarea es requerido", 400);
            }

            $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($data['description'] ?? '', FILTER_SANITIZE_STRING);

            $result = $this->tasksModel->create(
                $name,
                $description,
                $_SESSION['user_id'],
                'por hacer'
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tarea creada exitosamente',
                    'task' => [
                        'name' => $name,
                        'description' => $description,
                        'date' => date('Y-m-d H:i:s') // Devuelve la fecha actual
                    ]
                ]);
            } else {
                throw new Exception('Error al crear la tarea en la base de datos', 500);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'session' => $_SESSION ?? null,
                    'post_data' => file_get_contents('php://input')
                ]
            ]);
            exit();
        }
    }
}