<?php
require_once __DIR__ . '/../config/db.php';

class RecordController {
    private $connection;

    public function __construct() {
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
    }

    public function handleRequest() {
        // Get JSON data from request
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['action'])) {
            echo json_encode(['success' => false, 'message' => 'No action specified']);
            return;
        }

        switch ($data['action']) {
            case 'clone':
                return $this->cloneRecord($data);
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                return;
        }
    }

    private function cloneRecord($data) {
        if (!isset($data['table']) || !isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        try {
            // Start transaction
            $this->connection->begin_transaction();

            // Get the original record
            $stmt = $this->connection->prepare("SELECT * FROM " . $data['table'] . " WHERE id = ?");
            $stmt->bind_param("i", $data['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $record = $result->fetch_assoc();

            if (!$record) {
                throw new Exception('Record not found');
            }

            // Remove the ID field for the clone
            unset($record['id']);
            
            // Build the INSERT query
            $columns = implode(', ', array_keys($record));
            $values = implode(', ', array_fill(0, count($record), '?'));
            $query = "INSERT INTO " . $data['table'] . " (" . $columns . ") VALUES (" . $values . ")";
            
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception($this->connection->error);
            }

            // Bind parameters dynamically
            $types = str_repeat('s', count($record));
            $stmt->bind_param($types, ...array_values($record));
            
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            // Log the clone action
            $newId = $this->connection->insert_id;
            $userId = $_SESSION['user_id'] ?? 0;
            $details = "Clonado del registro " . $data['id'];
            
            $stmt = $this->connection->prepare("INSERT INTO historial (usuario_id, accion, tabla, registro_id, detalles) VALUES (?, 'clone', ?, ?, ?)");
            $stmt->bind_param("isis", $userId, $data['table'], $newId, $details);
            $stmt->execute();

            // Commit transaction
            $this->connection->commit();
            
            echo json_encode(['success' => true, 'message' => 'Record cloned successfully', 'newId' => $newId]);
        } catch (Exception $e) {
            $this->connection->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    
    
try {
    // Consulta para obtener usuarios administradores que aparecen en el historial
    $queryAdminUsers = "
        SELECT DISTINCT 
            u.id,
            u.nombre,
            u.nombre_usuario,
            u.email,
            u.rol,
            COUNT(h.usuario) as total_acciones
        FROM usuarios u
        INNER JOIN historial h ON u.nombre_usuario = h.usuario
        WHERE LOWER(u.rol) IN ('administrador', 'admin')
        GROUP BY u.id, u.nombre, u.nombre_usuario, u.email, u.rol
        ORDER BY u.nombre_usuario ASC
    ";
    
    $stmtAdminUsers = $pdo->prepare($queryAdminUsers);
    $stmtAdminUsers->execute();
    $adminUsers = $stmtAdminUsers->fetchAll(PDO::FETCH_ASSOC);

    // Alternativa si no sabes exactamente cómo están escritos los roles en tu BD
    $queryAdminUsersAlternative = "
        SELECT DISTINCT 
            u.id,
            u.nombre,
            u.nombre_usuario,
            u.email,
            u.rol,
            COUNT(h.usuario) as total_acciones
        FROM usuarios u
        INNER JOIN historial h ON u.nombre_usuario = h.usuario
        WHERE LOWER(u.rol) LIKE '%admin%' 
           OR LOWER(u.rol) LIKE '%administrador%'
        GROUP BY u.id, u.nombre, u.nombre_usuario, u.email, u.rol
        ORDER BY u.nombre_usuario ASC
    ";

} catch (PDOException $e) {
    $adminUsers = [];
    error_log("Error al obtener usuarios administradores del historial: " . $e->getMessage());
}

// Para debugging - puedes remover esto después
if (empty($adminUsers)) {
    // Verificar qué roles existen en la tabla usuarios
    $queryRoles = "SELECT DISTINCT rol FROM usuarios ORDER BY rol";
    $stmtRoles = $pdo->prepare($queryRoles);
    $stmtRoles->execute();
    $existingRoles = $stmtRoles->fetchAll(PDO::FETCH_COLUMN);
    
    error_log("Roles disponibles en la tabla usuarios: " . implode(', ', $existingRoles));
}


}

// Handle the request
$controller = new RecordController();
$controller->handleRequest();