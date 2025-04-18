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

$runner->registerTest('getAllQuantityGoodsByInventory', 
    function($inventoryId) {
        $goodsInventory = new GoodsInventory();
        echo "<p>Testing getAllQuantityGoodsByInventory()...</p>";

        $result = $goodsInventory->getAllQuantityGoodsByInventory($inventoryId);

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

$runner->registerTest('addQuantityGoodToInventory', 
    function($inventoryId, $goodId, $quantity) {
        $goodsInventory = new GoodsInventory();
        echo "<p>Testing addQuantityGoodToInventory()...</p>";

        if ($goodsInventory->addQuantityGoodToInventory($inventoryId, $goodId, $quantity)) {
            return true;
        } else {
            echo "<p>Failed to add quantity good to inventory</p>";
            return false;
        }
    }, [
        1,   // ID del inventario
        84,  // ID del bien
        10   // Cantidad a agregar
    ]
);

$runner->registerTest('addSerialGoodToInventory', 
    function($inventoryId, $goodId) {
        $goodsInventory = new GoodsInventory();
        echo "<p>Testing addSerialGoodToInventory()...</p>";

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
            return true;
        } else {
            echo "<p>Failed to add serial good to inventory</p>";
            return false;
        }
    }, [
        1, // ID del inventario
        2  // ID del bien
    ]
);

// Actualizar bien por cantidad
$runner->registerTest('updateQuantityGood', function($inventoryGoodId, $newQuantity) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing updateQuantityGood()...</p>";

    if ($goodsInventory->updateQuantityGood($inventoryGoodId, $newQuantity)) {
        return true;
    } else {
        echo "<p>Failed to update quantity good</p>";
        return false;
    }
}, [
    1, // ID del bien en inventario
    10 // Nueva cantidad
]);

// Actualizar bien serializado
$runner->registerTest('updateSerialGood', function($inventoryGoodId) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing updateSerialGood()...</p>";

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
        return true;
    } else {
        echo "<p>Failed to update serial good</p>";
        return false;
    }
}, [
    1 // ID del bien serializado en inventario
]);

// Transferir bienes por cantidad entre inventarios
$runner->registerTest('transferQuantityGoods', function($sourceInventoryId, $targetInventoryId, $goodId, $quantity) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing transferQuantityGoods()...</p>";

    try {
        if ($goodsInventory->transferQuantityGoods($sourceInventoryId, $targetInventoryId, $goodId, $quantity)) {
            return true;
        } else {
            echo "<p>Failed to transfer quantity goods</p>";
            return false;
        }
    } catch (Exception $e) {
        echo "<p>Exception: " . $e->getMessage() . "</p>";
        return false;
    }
}, [
    1, // Inventario origen
    2, // Inventario destino
    1, // ID del bien
    2  // Cantidad a transferir
]);

// Transferir bien serializado entre inventarios
$runner->registerTest('transferSerialGoods', function($sourceInventoryId, $targetInventoryId, $goodId) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing transferSerialGoods()...</p>";

    if ($goodsInventory->transferSerialGoods($sourceInventoryId, $targetInventoryId, $goodId)) {
        return true;
    } else {
        echo "<p>Failed to transfer serial goods</p>";
        return false;
    }
}, [
    1, // Inventario origen
    2, // Inventario destino
    2  // ID del bien serializado
]);

// Desactivar bien por cantidad
$runner->registerTest('deactivateQuantityGoods', function($inventoryGoodId) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing deactivateQuantityGoods()...</p>";

    if ($goodsInventory->deactivateQuantityGoods($inventoryGoodId)) {
        return true;
    } else {
        echo "<p>Failed to deactivate quantity goods</p>";
        return false;
    }
}, [
    1 // ID del bien en inventario
]);

// Desactivar bien serializado
$runner->registerTest('deactivateSerialGoods', function($inventoryGoodId) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing deactivateSerialGoods()...</p>";

    if ($goodsInventory->deactivateSerialGoods($inventoryGoodId)) {
        return true;
    } else {
        echo "<p>Failed to deactivate serial goods</p>";
        return false;
    }
}, [
    1 // ID del bien serializado en inventario
]);


// Prueba para crear bien por cantidad o serial
$runner->registerTest('create', function($inventoryId, $goodId, $data, $type) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing create() with type: $type</p>";
    
    if ($type === 'quantity') {
        $quantity = $data;
        if ($goodsInventory->create($inventoryId, $goodId, $quantity, $type)) {
            echo "<p>Quantity create successful</p>";
            return true;
        } else {
            echo "<p>Quantity create failed</p>";
            return false;
        }
    } else if ($type === 'serial') {
        // Datos predeterminados para tipo serial
        if (is_numeric($data)) {
            $data = [
                'description' => 'Test create equipment',
                'brand' => 'CreateBrand',
                'model' => 'CreateModel',
                'serial' => 'SN11223344',
                'state' => 'activo',
                'color' => 'blue',
                'technical_conditions' => 'new',
                'entry_date' => date('Y-m-d')
            ];
        }
        
        if ($goodsInventory->create($inventoryId, $goodId, $data, $type)) {
            echo "<p>Serial create successful</p>";
            return true;
        } else {
            echo "<p>Serial create failed</p>";
            return false;
        }
    } else {
        echo "<p>Invalid type: $type</p>";
        return false;
    }
}, [
    1,   // ID de inventario
    1,   // ID de bien
    3,   // Datos (en cantidad para este caso)
    'quantity' // Tipo de creación
]);

// Prueba para actualizar bien por cantidad o serial
$runner->registerTest('update', function($inventoryGoodId, $data, $type) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing update() with type: $type</p>";
    
    if ($type === 'quantity') {
        $newQuantity = $data;
        if ($goodsInventory->update($inventoryGoodId, $newQuantity, $type)) {
            echo "<p>Quantity update successful</p>";
            return true;
        } else {
            echo "<p>Quantity update failed</p>";
            return false;
        }
    } else if ($type === 'serial') {
        // Datos predeterminados para tipo serial
        if (is_numeric($data)) {
            $data = [
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
        }
        
        if ($goodsInventory->update($inventoryGoodId, $data, $type)) {
            echo "<p>Serial update successful</p>";
            return true;
        } else {
            echo "<p>Serial update failed</p>";
            return false;
        }
    } else {
        echo "<p>Invalid type: $type</p>";
        return false;
    }
}, [
    1,   // ID de bien en inventario
    15,  // Datos de actualización (nueva cantidad o datos serial)
    'quantity' // Tipo de actualización
]);

// Prueba para eliminar bien por cantidad o serial
$runner->registerTest('delete', function($inventoryGoodId, $type) {
    $goodsInventory = new GoodsInventory();
    echo "<p>Testing delete() with type: $type</p>";
    
    if ($goodsInventory->delete($inventoryGoodId, $type)) {
        echo "<p>$type delete successful</p>";
        return true;
    } else {
        echo "<p>$type delete failed</p>";
        return false;
    }
}, [
    1,      // ID de bien en inventario
    'quantity' // Tipo de eliminación
]);

// If accessed directly (not through .init-tests.php), redirect to index
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}