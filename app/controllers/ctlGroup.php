<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/helpers/CacheManager.php'; // Include our cache manager

class ctlGroup {
    private $group;
    private $cache;

    public function __construct() {
        $this->group = new Groups();
        $this->cache = new CacheManager();
    }
    
    // $router->add('/api/groups', 'ctlGroup', 'getAll');
    public function getAll() {
        // Use cache for all groups list
        $cacheKey = "all_groups";
        
        $allGrupos = $this->cache->remember($cacheKey, function() {
            return $this->group->getAllGroups();
        }, 600); // Cache for 10 minutes
        
        header('Content-Type: application/json');
        echo json_encode($allGrupos);
    }

    public function create() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        $nombre = $_POST['nombre'] ?? '';
        if (empty($nombre)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El nombre del grupo es requerido.']);
            return;
        }
    
        $id = $this->group->createGroup($nombre);
        
        if ($id !== false) {
            // Invalidate the all groups cache
            $this->cache->remove("all_groups");
            
            echo json_encode(['success' => true, 'message' => 'Grupo creado exitosamente.', 'id' => $id]);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Ya existe un grupo con ese nombre.']);
        }
    }
    
    public function rename() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        $id = $_POST['id'] ?? null;
        $newName = $_POST['nombre'] ?? '';
    
        if (empty($id) || empty($newName)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID y nuevo nombre son requeridos.']);
            return;
        }
    
        $resultado = $this->group->renameGroup($id, $newName);
    
        if ($resultado) {
            // Invalidate related caches
            $this->cache->remove("all_groups");
            $this->cache->invalidateEntity('group_' . $id);
            
            echo json_encode(['success' => true, 'message' => 'Grupo actualizado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el grupo. Puede que el grupo no exista o el nombre sea igual al anterior.']);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }
    
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }
    
        $resultado = $this->group->deleteGroup($id);
    
        if ($resultado) {
            // Invalidate related caches
            $this->cache->remove("all_groups");
            $this->cache->invalidateEntity('group_' . $id);
            
            echo json_encode(['success' => true, 'message' => 'Grupo eliminado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el grupo. Puede que no exista o tenga inventarios asociados.']);
        }
    }
}