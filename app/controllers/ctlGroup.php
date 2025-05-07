<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/helpers/CacheManager.php'; // Include our cache manager
require_once 'app/helpers/validate-http.php';

class ctlGroup {
    private $group;
    private $cache;

    public function __construct() {
        $this->group = new Groups();
        $this->cache = new CacheManager();
    }
    
    // $router->add('/api/groups', 'ctlGroup', 'getAll');
    public function getAll() {
        $cacheKey = "all_groups";
        
        $allGrupos = $this->cache->remember($cacheKey, function() {
            return $this->group->getAllGroups();
        }, 600); // Cache for 10 minutes
        
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
        
        $cacheKey = "group_{$id}";
        
        $groupData = $this->cache->remember($cacheKey, function() use ($id) {
            return $this->group->getById($id);
        }, 600); // Cache for 10 minutes
        
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
            // Invalidate the all groups cache
            $this->cache->remove("all_groups");
            
            // También invalidar cualquier caché específica que podría existir para este grupo
            // aunque acaba de ser creado, es una buena práctica para mantener la coherencia
            $this->cache->remove("group_{$id}_inventories");
            $this->cache->invalidateEntity("group_{$id}");
            
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
            // Invalidate related caches
            $this->cache->remove("all_groups");
            $this->cache->remove("group_{$id}_inventories");
            $this->cache->invalidateEntity("group_{$id}");
            
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
            // Invalidar todas las cachés relacionadas
            $this->cache->remove("all_groups");
            $this->cache->remove("group_{$id}_inventories");
            $this->cache->invalidateEntity("group_{$id}");
            
            echo json_encode(['success' => true, 'message' => 'Grupo eliminado exitosamente.']);
        }
    }
    
}