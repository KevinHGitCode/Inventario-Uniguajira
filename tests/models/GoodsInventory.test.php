<?php

require_once '../../app/models/GoodsInventory.php';

function runTests() {
    testGetAllGoodsByInventory(1); // Replace with a valid inventory ID
    // testCreate(1, 2, 10); // Replace with valid inventory ID, good ID, and quantity
    // testUpdate(1, ['quantity' => 20]); // Replace with valid inventoryGoodId and new data
    // testDelete(1); // Replace with valid inventoryGoodId
}

function testGetAllGoodsByInventory($inventoryId) {
    $goodsInventory = new GoodsInventory();
    echo "Testing getAllGoodsByInventory($inventoryId)...<br>";
    try {
        $goods = $goodsInventory->getAllGoodsByInventory($inventoryId);
        if (!empty($goods)) {
            echo "PASSED<br>";
            foreach ($goods as $good) {
                echo "Inventario ID: {$good['inventario_id']}, Bien ID: {$good['bien_id']}, Cantidad: {$good['cantidad']}, Cantidad: {$good['bien']}<br>";
            }
        } else {
            echo "FAILED: No goods found for inventory ID $inventoryId<br>";
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "<br>";
    }
}

function testCreate($inventoryId, $goodId, $quantity) {
    $goodsInventory = new GoodsInventory();
    echo "Testing create($inventoryId, $goodId, $quantity)...<br>";
    try {
        if ($goodsInventory->create($inventoryId, $goodId, $quantity)) {
            echo "PASSED: Good added to inventory<br>";
        } else {
            echo "FAILED: Could not add good to inventory<br>";
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "<br>";
    }
}

function testUpdate($inventoryGoodId, $newData) {
    $goodsInventory = new GoodsInventory();
    echo "Testing update($inventoryGoodId, newData)...<br>";
    try {
        if ($goodsInventory->update($inventoryGoodId, $newData)) {
            echo "PASSED: Good updated in inventory<br>";
        } else {
            echo "FAILED: Could not update good in inventory<br>";
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "<br>";
    }
}

function testDelete($inventoryGoodId) {
    $goodsInventory = new GoodsInventory();
    echo "Testing delete($inventoryGoodId)...<br>";
    try {
        if ($goodsInventory->delete($inventoryGoodId)) {
            echo "PASSED: Good removed from inventory<br>";
        } else {
            echo "FAILED: Could not remove good from inventory<br>";
        }
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "<br>";
    }
}

runTests();
