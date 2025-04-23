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


    /**
     * ====================================================================
     * ======================== CRUD Goods Inventory ======================
     * ====================================================================
     */

    /**
     * Método para agregar un bien al inventario
     */
    // public function addGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['inventarioId', 'nombre'])) {
    //         return;
    //     }

    //     $inventarioId = $_POST['inventarioId'];
    //     $nombre = $_POST['nombre'];
    //     $cantidad = $_POST['cantidad'] ?? 1;
    //     $imagen = $_POST['imagen'] ?? 'assets/img/default-good.png';

    //     $resultado = $this->goodsInventory->addGood($inventarioId, $nombre, $cantidad, $imagen);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien agregado exitosamente.']);
    //     } else {
    //         http_response_code(409);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo agregar el bien al inventario.']);
    //     }
    // }

    /**
     * Método para cambiar la cantidad de un bien
     */
    // public function updateQuantity() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'cantidad'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $nuevaCantidad = $_POST['cantidad'];

    //     $resultado = $this->goodsInventory->updateQuantityGood($bienId, $nuevaCantidad);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Cantidad actualizada exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la cantidad.']);
    //     }
    // }

    /**
     * Método para mover un bien de un inventario a otro
     */
    // TODO: Not implement yet
    // public function moveGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'inventario_destino_id'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $inventarioDestinoId = $_POST['inventario_destino_id'];

    //     $resultado = $this->goodsInventory->moveGood($bienId, $inventarioDestinoId);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien movido exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo mover el bien.']);
    //     }
    // }

    /**
     * Método para eliminar un bien del inventario
     */
    // TODO: Not implement yet
    // public function removeGood() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];

    //     $resultado = $this->goodsInventory->removeGood($bienId);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Bien eliminado exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el bien.']);
    //     }
    // }

    /**
     * Método para cambiar el estado de un bien
     */
    // TODO: Not implement yet
    // public function changeState() {
    //     header('Content-Type: application/json');

    //     if (!validateHttpRequest('POST', ['bien_id', 'estado'])) {
    //         return;
    //     }

    //     $bienId = $_POST['bien_id'];
    //     $nuevoEstado = $_POST['estado'];

    //     $resultado = $this->goodsInventory->updateState($bienId, $nuevoEstado);

    //     if ($resultado) {
    //         echo json_encode(['success' => true, 'message' => 'Estado del bien actualizado exitosamente.']);
    //     } else {
    //         http_response_code(400);
    //         echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del bien.']);
    //     }
    // }
}