<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/helpers/validate-http.php';

class ctlGroup {
    private $group;

    public function __construct() {
        $this->group = new Groups();
    }
    
    // $router->add('/api/groups', 'ctlGroup', 'getAll');
    public function getAll() {
        $allGrupos = $this->group->getAllGroups();
        
        header('Content-Type: application/json');
        echo json_encode($allGrupos);
    }

    /**
     * Obtener un grupo específico por su ID
     */
    public function getById($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El ID del grupo es requerido.']);
            return;
        }
        
        $groupData = $this->group->getById($id);
        
        if (!$groupData) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Grupo no encontrado.']);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $groupData]);
    }

    public function create() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['nombre'])) {
            return;
        }
    
        $nombre = $_POST['nombre'];
        $id = $this->group->create($nombre);
        
        if ($id !== false) {
            echo json_encode(['success' => true, 'message' => 'Grupo creado exitosamente.', 'id' => $id]);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Ya existe un grupo con ese nombre.']);
        }
    }
    
    public function rename() {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('POST', ['id', 'nombre'])) {
            return;
        }
    
        $id = $_POST['id'];
        $newName = $_POST['nombre'];
    
        $resultado = $this->group->rename($id, $newName);
    
        if (!$resultado) {
            $group = $this->group->getById($id);
            if (!$group) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el grupo. El grupo con ID especificado no existe.']);
            } elseif ($this->group->existsByName($newName)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el grupo. El nombre ya existe.']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el grupo por un error desconocido.']);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Grupo actualizado exitosamente.']);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
        
        if (!validateHttpRequest('DELETE')) {
            return;
        }

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El ID del grupo es requerido.']);
            return;
        }

        $inventories = $this->group->getInventoriesByGroup($id);
        if (!empty($inventories)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'El grupo tiene inventarios asociados.']);
            return;
        }

        $respuesta = $this->group->delete($id);
        if (!$respuesta) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Ocurrió un error al intentar eliminar el grupo.']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Grupo eliminado exitosamente.']);
        }
    }
}