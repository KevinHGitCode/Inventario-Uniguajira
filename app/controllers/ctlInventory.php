<?php
require_once __DIR__ . '/sessionCheck.php';
require_once 'app/models/Groups.php';
require_once 'app/models/Inventory.php';
require_once 'app/models/GoodsInventory.php';
require_once 'app/helpers/validate-http.php';

class ctlInventory  {
    private $group;
    private $inventory;
    private $goodsInventory;

    public function __construct() {
        $this->group = new Groups();
        $this->inventory = new Inventory();
        $this->goodsInventory = new GoodsInventory();
    }

    // para navegacion
    public function getInventoriesOfGroup($id_group) {
        $dataInventories = $this->group->getInventoriesByGroup($id_group);
        $dataIdGroup = $id_group;
        require 'app/views/inventory/inventories.php';
    }

    // para navegacion
    public function getGoodsOfInventory($id_inventory) {
        $dataGoodsInventory = $this->goodsInventory->getAllGoodsByInventory($id_inventory);
        require 'app/views/inventory/goods-inventory.php';
    }


    /**
     * ====================================================================
     * ======================== CRUD Inventory ============================
     * ====================================================================
     */
    
    public function create() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['nombre', 'grupo_id'])) {
            return;
        }

        $nombre = $_POST['nombre'];
        $grupoId = $_POST['grupo_id'];

        $resultado = $this->inventory->create($nombre, $grupoId);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Inventario creado exitosamente.']);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'No se pudo crear el inventario.']);
        }
    }

    public function rename() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['inventory_id', 'nombre'])) { return; }

        $id = $_POST['inventory_id'];
        $newName = $_POST['nombre'];

        $resultado = $this->inventory->updateName($id, $newName);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Inventario renombrado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo renombrar el inventario.']);
        }
    }
    
    // TODO: Not implement yet
    public function setState() {
        header('Content-Type: application/json');

        if (!validateHttpRequest('POST', ['id', 'estado'])) {
            return;
        }

        $id = $_POST['id'];
        $estado = $_POST['estado'];

        $resultado = $this->inventory->updateConservation($id, $estado);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Estado del inventario actualizado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del inventario.']);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');

        if (!validateHttpRequest('DELETE')) { return; }

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID requerido.']);
            return;
        }

        $resultado = $this->inventory->delete($id);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Inventario eliminado exitosamente.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el inventario. Puede que tenga bienes asociados o no exista.']);
        }
    }

}