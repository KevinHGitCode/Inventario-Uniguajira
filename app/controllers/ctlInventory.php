<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/models/Inventory.php';
require_once 'app/models/GoodsInventory.php';
require_once 'app/helpers/validate-http.php';

class ctlInventory {
    public function __construct() {
        
    }

    // para navegacion
    public function getInventoriesOfGroup($id_group) {
        $group = new Groups(); // Instantiate Groups here
        $dataInventories = $group->getInventoriesByGroup($id_group);
        $dataIdGroup = $id_group;
        require 'app/views/inventory/inventories.php';
    }

    // para navegacion
    public function getGoodsOfInventory($id_inventory) {
        $goodsInventory = new GoodsInventory(); // Instantiate GoodsInventory here
        $dataGoodsInventory = $goodsInventory->getAllGoodsByInventory($id_inventory);
        
        header('Content-Type: application/json');
        echo json_encode($dataGoodsInventory);
    }

    public function getSerialGoodsOfInventory($id_inventory, $id_goodSerial) {
        $goodsInventory = new GoodsInventory(); // Instantiate GoodsInventory here
        $dataSerialGoodsInventory = $goodsInventory->getSerialGoodDetails($id_inventory, $id_goodSerial);

        // echo $dataSerialGoodsInventory;
        // header('Content-Type: application/json');
        // echo json_encode($dataSerialGoodsInventory);

        require 'app/views/inventory/serials-goods-inventory.php';
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
            echo json_encode(['success' => true, 'message' => 'Inventario eliminado.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el inventario.']);
        }
    }

    public function updateEstado() {
        $inventory = new Inventory();
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['id_inventario', 'estado'])) {
            return;
        }

        $id = $_POST['id_inventario'];
        $estado = $_POST['estado'];

        $resultado = $inventory->updateConservation($id, $estado);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Estado del inventario actualizado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del inventario.']);
        }
    }

    public function updateResponsable() {
        $inventory = new Inventory();
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['id'])) {
            return;
        }

        $id = $_POST['id'];
        $responsable = $_POST['responsable'];

        $resultado = $inventory->updateResponsable($id, $responsable);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Responsable del inventario actualizado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el responsable del inventario.']);
        }
    }
}