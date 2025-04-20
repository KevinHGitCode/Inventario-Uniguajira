<?php
require_once '../../app/models/GoodsInventory.php';

// Create a TestRunner instance
$runner = new TestRunner();

// Register all available tests
$runner->registerTest('getAllGoodsByInventory', 
    function($inventoryId) {
        $goodsInventory = new GoodsInventory();
        echo "<p>Testing getAllGoodsByInventory()...</p>";

        $result = $goodsInventory->getAllGoodsByInventory($inventoryId);

        if (is_array($result)) {
            renderTable($result);
            return true;
        } else {
            echo "<p>Could not get data as an array</p>";
            return false;
        }
    }, [
        5 // ID del inventario
    ]
);

$runner->registerTest('getAllSerialGoodsByInventory', 
    function($inventoryId) {
        $goodsInventory = new GoodsInventory();
        echo "<p>Testing getAllSerialGoodsByInventory()...</p>";

        $result = $goodsInventory->getAllSerialGoodsByInventory($inventoryId);

        if (is_array($result)) {
            renderTable($result);
            return true;
        } else {
            echo "<p>Could not get data as an array</p>";
            return false;
        }
    }, [
        5 // ID del inventario
    ]
);


// If accessed directly (not through .init-tests.php), redirect to index
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}