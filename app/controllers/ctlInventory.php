<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/models/Inventory.php';
require_once 'app/models/GoodsInventory.php';
require_once 'app/helpers/validate-http.php';
require_once 'app/helpers/CacheManager.php'; // Include our new cache manager

class ctlInventory {
    private $cache;

    public function __construct() {
        $this->cache = new CacheManager();
    }

    // para navegacion - con caché
    public function getInventoriesOfGroup($id_group) {
        $group = new Groups(); // Instantiate Groups here
        $cacheKey = "group_{$id_group}_inventories";
        
        $dataInventories = $this->cache->remember($cacheKey, function() use ($group, $id_group) {
            return $group->getInventoriesByGroup($id_group);
        }, 600); // Cache for 10 minutes
        
        $dataIdGroup = $id_group;
        require 'app/views/inventory/inventories.php';
    }

    // para navegacion - con caché
    public function getGoodsOfInventory($id_inventory) {
        $goodsInventory = new GoodsInventory(); // Instantiate GoodsInventory here
        $cacheKey = "inventory_{$id_inventory}_goods";
        
        $dataGoodsInventory = $this->cache->remember($cacheKey, function() use ($goodsInventory, $id_inventory) {
            return $goodsInventory->getAllGoodsByInventory($id_inventory);
        }, 600); // Cache for 10 minutes
        
        header('Content-Type: application/json');
        echo json_encode($dataGoodsInventory);
    }

    public function create() {
        $inventory = new Inventory(); // Instantiate Inventory here
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['nombre', 'grupo_id'])) {
            return;
        }

        $nombre = $_POST['nombre'];
        $grupoId = $_POST['grupo_id'];

        $resultado = $inventory->create($nombre, $grupoId);

        if ($resultado) {
            // Invalidar directamente la clave de caché específica
            $this->cache->remove("group_{$grupoId}_inventories");
            
            // Como respaldo, también intentamos invalidar usando el método original
            $this->cache->invalidateEntity("group_{$grupoId}");
            
            echo json_encode(['success' => true, 'message' => 'Inventario creado exitosamente.']);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'No se pudo crear el inventario.']);
        }
    }

    public function rename() {
        $inventory = new Inventory(); // Instantiate Inventory here
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['inventory_id', 'nombre'])) { return; }

        $id = $_POST['inventory_id'];
        $newName = $_POST['nombre'];

        $resultado = $inventory->updateName($id, $newName);

        if ($resultado) {
            // Invalidar caché de bienes de este inventario
            $this->cache->remove("inventory_{$id}_goods");
            
            // Obtener el grupo asociado y actualizar esa caché también
            $inventoryData = $inventory->getInventoryById($id);
            if ($inventoryData && isset($inventoryData['grupo_id'])) {
                $grupoId = $inventoryData['grupo_id'];
                $this->cache->remove("group_{$grupoId}_inventories");
                $this->cache->invalidateEntity("group_{$grupoId}");
            }
            
            echo json_encode(['success' => true, 'message' => 'Inventario renombrado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo renombrar el inventario.']);
        }
    }
    
    public function setState() {
        $inventory = new Inventory(); // Instantiate Inventory here
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['id', 'estado'])) {
            return;
        }

        $id = $_POST['id'];
        $estado = $_POST['estado'];

        $resultado = $inventory->updateConservation($id, $estado);

        if ($resultado) {
            // Invalidar caché de bienes de este inventario
            $this->cache->remove("inventory_{$id}_goods");
            
            // Obtener el grupo asociado y actualizar esa caché también
            $inventoryData = $inventory->getInventoryById($id);
            if ($inventoryData && isset($inventoryData['grupo_id'])) {
                $grupoId = $inventoryData['grupo_id'];
                $this->cache->remove("group_{$grupoId}_inventories");
                $this->cache->invalidateEntity("group_{$grupoId}");
            }
            
            echo json_encode(['success' => true, 'message' => 'Estado del inventario actualizado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del inventario.']);
        }
    }

    public function delete($id) {
        $inventory = new Inventory(); // Instantiate Inventory here
        header('Content-Type: application/json');

        if (!validateHttpRequest('DELETE')) { return; }

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }

        $inventoryData = $inventory->getInventoryById($id);
        $grupoId = $inventoryData['grupo_id'] ?? null;

        // Verificar si el inventario tiene bienes asociados
        if ($inventory->hasAssociatedAssets($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El inventario tiene bienes asociados.']);
            return;
        }

        $resultado = $inventory->delete($id);

        if ($resultado) {
            // Invalidar todas las cachés relacionadas
            $this->cache->remove("inventory_{$id}_goods");
            
            if ($grupoId) {
                $this->cache->remove("group_{$grupoId}_inventories");
                $this->cache->invalidateEntity("group_{$grupoId}");
            }
            
            echo json_encode(['success' => true, 'message' => 'Inventario eliminado.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el inventario.']);
        }
    }
}