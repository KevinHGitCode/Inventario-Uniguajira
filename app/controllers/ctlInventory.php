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

    public function index() {
        $dataGroups = $this->group->getAllGroups();
        require 'app/views/inventory.php';
    }

    public function getInventoriesOfGroup($id_group) {
        $dataInventories = $this->group->getInventoriesByGroup($id_group);

        // echo json_encode($dataInventories);
        // echo __DIR__;
        require 'app/views/inventory/inventories.php';
    }

    public function getGoodsOfInventory($id_inventory) {
        $dataGoodsInventory = $this->goodsInventory->getAllGoodsByInventory($id_inventory);
        // echo json_encode($dataInventory);
        // echo __DIR__;
        require 'app/views/inventory/goods-inventory.php';
    }
}