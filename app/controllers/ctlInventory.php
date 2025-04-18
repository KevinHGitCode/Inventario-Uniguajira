<?php

require_once 'app/models/Groups.php';
require_once 'app/models/Inventory.php';
require_once 'app/models/GoodsInventory.php';

class ctlInventory  {
    private $group;
    private $inventory;
    private $goodsInventory;

    public function __construct() {
        $this->group = new Groups();
        $this->inventory = new Inventory();
        $this->goodsInventory = new GoodsInventory();
    }

    public function getInventoriesOfGroup($id_group) {
        $dataInventories = $this->group->getInventoriesByGroup($id_group);
        require 'app/views/inventory/inventories.php';
    }

    public function getGoodsOfInventory($id_inventory) {
        $dataGoodsInventory = $this->goodsInventory->getAllGoodsByInventory($id_inventory);
        require 'app/views/inventory/goods-inventory.php';
    }

    // createGroup
    public function createGroup() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $nombre = $_POST['nombre'] ?? null;

        $result = $this->group->createGroup($nombre);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Grupo creado correctamente.']);
        } else {
            // el nombre del grupo ya existe
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'El grupo ya existe.']);
        }
            
    } 
}