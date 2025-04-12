<?php

require_once '../../app/models/GoodsInventory.php';
require '.tableHelper.php';

function runTests() {
    // testGetAllGoodsByInventory();
    testGetAllQuantityGoodsByInventory(1);
    // testGetAllSerialGoodsByInventory();
    // testAddQuantityGoodToInventory();
    // testAddSerialGoodToInventory();
    // testUpdateQuantityGood();
    // testUpdateSerialGood();
    // testTransferQuantityGoods();
    // testTransferSerialGoods();
    // testDeactivateQuantityGoods();
    // testDeactivateSerialGoods();
    // testCreate();
    // testUpdate();
    // testDelete();
}

function testGetAllGoodsByInventory($inventoryId ) {
    $goodsInventory = new GoodsInventory();
    echo "Testing getAllGoodsByInventory()... <br>";

    $result = $goodsInventory->getAllGoodsByInventory($inventoryId);

    if (is_array($result)) {
        renderTable($result);
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testGetAllQuantityGoodsByInventory($inventoryId) {
    $goodsInventory = new GoodsInventory();
    echo "Testing getAllQuantityGoodsByInventory()... <br>";

    $result = $goodsInventory->getAllQuantityGoodsByInventory($inventoryId);

    if (is_array($result)) {
        renderTable($result);
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}


function testGetAllSerialGoodsByInventory($inventoryId) {
    $goodsInventory = new GoodsInventory();
    echo "Testing getAllSerialGoodsByInventory()... <br>";
    $result = $goodsInventory->getAllSerialGoodsByInventory($inventoryId);
    
    if (is_array($result)) {
        echo "PASSED<br>";
        foreach ($result as $item) {
            echo "Inventory ID: {$item['inventario_id']}, Good ID: {$item['bien_id']}, Serial: {$item['serial']}, Model: {$item['modelo']}<br>";
        }
    } else {
        echo "FAILED<br>";
    }
}

function testAddQuantityGoodToInventory() {
    $goodsInventory = new GoodsInventory();
    echo "Testing addQuantityGoodToInventory()... <br>";
    $inventoryId = 1; // Replace with valid IDs
    $goodId = 1;      // Replace with valid IDs
    $quantity = 5;
    
    if ($goodsInventory->addQuantityGoodToInventory($inventoryId, $goodId, $quantity)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testAddSerialGoodToInventory() {
    $goodsInventory = new GoodsInventory();
    echo "Testing addSerialGoodToInventory()... <br>";
    $inventoryId = 1; // Replace with valid IDs
    $goodId = 2;      // Replace with valid IDs
    
    $details = [
        'description' => 'Test equipment',
        'brand' => 'TestBrand',
        'model' => 'TestModel',
        'serial' => 'SN12345678',
        'state' => 'activo',
        'color' => 'black',
        'technical_conditions' => 'good',
        'entry_date' => date('Y-m-d')
    ];
    
    if ($goodsInventory->addSerialGoodToInventory($inventoryId, $goodId, $details)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateQuantityGood() {
    $goodsInventory = new GoodsInventory();
    echo "Testing updateQuantityGood()... <br>";
    $inventoryGoodId = 1; // Replace with a valid inventory good ID
    $newQuantity = 10;
    
    if ($goodsInventory->updateQuantityGood($inventoryGoodId, $newQuantity)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateSerialGood() {
    $goodsInventory = new GoodsInventory();
    echo "Testing updateSerialGood()... <br>";
    $inventoryGoodId = 1; // Replace with a valid inventory good ID
    
    $newData = [
        'description' => 'Updated equipment',
        'brand' => 'UpdatedBrand',
        'model' => 'UpdatedModel',
        'serial' => 'SN98765432',
        'state' => 'activo',
        'color' => 'white',
        'technical_conditions' => 'excellent',
        'entry_date' => date('Y-m-d'),
        'exit_date' => null
    ];
    
    if ($goodsInventory->updateSerialGood($inventoryGoodId, $newData)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testTransferQuantityGoods() {
    $goodsInventory = new GoodsInventory();
    echo "Testing transferQuantityGoods()... <br>";
    $sourceInventoryId = 1; // Replace with valid IDs
    $targetInventoryId = 2; // Replace with valid IDs
    $goodId = 1;            // Replace with valid IDs
    $quantity = 2;
    
    try {
        if ($goodsInventory->transferQuantityGoods($sourceInventoryId, $targetInventoryId, $goodId, $quantity)) {
            echo "PASSED<br>";
        } else {
            echo "FAILED<br>";
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "<br>";
    }
}

function testTransferSerialGoods() {
    $goodsInventory = new GoodsInventory();
    echo "Testing transferSerialGoods()... <br>";
    $sourceInventoryId = 1; // Replace with valid IDs
    $targetInventoryId = 2; // Replace with valid IDs
    $goodId = 2;            // Replace with valid IDs
    
    if ($goodsInventory->transferSerialGoods($sourceInventoryId, $targetInventoryId, $goodId)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDeactivateQuantityGoods() {
    $goodsInventory = new GoodsInventory();
    echo "Testing deactivateQuantityGoods()... <br>";
    $inventoryGoodId = 1; // Replace with a valid inventory good ID
    
    if ($goodsInventory->deactivateQuantityGoods($inventoryGoodId)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDeactivateSerialGoods() {
    $goodsInventory = new GoodsInventory();
    echo "Testing deactivateSerialGoods()... <br>";
    $inventoryGoodId = 1; // Replace with a valid inventory good ID
    
    if ($goodsInventory->deactivateSerialGoods($inventoryGoodId)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testCreate() {
    $goodsInventory = new GoodsInventory();
    echo "Testing create()... <br>";
    
    // Test quantity type
    $inventoryId = 1;
    $goodId = 1;
    $quantity = 3;
    if ($goodsInventory->create($inventoryId, $goodId, $quantity, 'quantity')) {
        echo "Quantity create PASSED<br>";
    } else {
        echo "Quantity create FAILED<br>";
    }
    
    // Test serial type
    $details = [
        'description' => 'Test create equipment',
        'brand' => 'CreateBrand',
        'model' => 'CreateModel',
        'serial' => 'SN11223344',
        'state' => 'activo',
        'color' => 'blue',
        'technical_conditions' => 'new',
        'entry_date' => date('Y-m-d')
    ];
    if ($goodsInventory->create($inventoryId, $goodId, $details, 'serial')) {
        echo "Serial create PASSED<br>";
    } else {
        echo "Serial create FAILED<br>";
    }
}

function testUpdate() {
    $goodsInventory = new GoodsInventory();
    echo "Testing update()... <br>";
    
    // Test quantity type
    $inventoryGoodId = 1; // Replace with valid ID
    $newQuantity = 15;
    if ($goodsInventory->update($inventoryGoodId, $newQuantity, 'quantity')) {
        echo "Quantity update PASSED<br>";
    } else {
        echo "Quantity update FAILED<br>";
    }
    
    // Test serial type
    $newData = [
        'description' => 'Updated via test',
        'brand' => 'UpdateBrand',
        'model' => 'UpdateModel',
        'serial' => 'SN55667788',
        'state' => 'activo',
        'color' => 'red',
        'technical_conditions' => 'used',
        'entry_date' => date('Y-m-d'),
        'exit_date' => null
    ];
    if ($goodsInventory->update($inventoryGoodId, $newData, 'serial')) {
        echo "Serial update PASSED<br>";
    } else {
        echo "Serial update FAILED<br>";
    }
}

function testDelete() {
    $goodsInventory = new GoodsInventory();
    echo "Testing delete()... <br>";
    
    // Test quantity type
    $inventoryGoodId = 1; // Replace with valid ID
    if ($goodsInventory->delete($inventoryGoodId, 'quantity')) {
        echo "Quantity delete PASSED<br>";
    } else {
        echo "Quantity delete FAILED<br>";
    }
    
    // Test serial type
    if ($goodsInventory->delete($inventoryGoodId, 'serial')) {
        echo "Serial delete PASSED<br>";
    } else {
        echo "Serial delete FAILED<br>";
    }
}

runTests();