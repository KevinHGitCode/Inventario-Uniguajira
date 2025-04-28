<?php
require_once '../../app/models/Inventory.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'inventoryId' => null
];

// Prueba para obtener todos los inventarios
$runner->registerTest('getAll', function() {
    $inventory = new Inventory();
    echo "<p>Testing getAll()...</p>";
    
    $allInventories = $inventory->getAll();
    if (is_array($allInventories)) {
        renderTable($allInventories);
        return true;
    } else {
        echo "<p>No se pudo obtener los inventarios como un array</p>";
        return false;
    }
});

// Prueba para crear un inventario con datos válidos
$runner->registerTest('createInventory_success', function() use (&$testData) {
    $inventory = new Inventory();
    $name = "Inventario Temporal " . time();
    $grupoId = 1;

    echo "<p>Testing create('$name', $grupoId)...</p>";
    $inventoryId = $inventory->create($name, $grupoId);

    if ($inventoryId !== false) {
        echo "<p>Inventario creado exitosamente con ID: $inventoryId.</p>";
        $testData['inventoryId'] = $inventoryId; // Guardar el ID para pruebas posteriores
        return true;
    } else {
        echo "<p>Error al crear el inventario.</p>";
        return false;
    }
});

// Prueba para crear un inventario con un nombre duplicado
$runner->registerTest('createInventory_duplicate', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    $stmt = $inventory->getConnection()->prepare("SELECT nombre FROM inventarios WHERE id = ?");
    $stmt->bind_param("i", $testData['inventoryId']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $duplicateName = $result['nombre'];

    echo "<p>Testing create('$duplicateName', 1)...</p>";
    $result = $inventory->create($duplicateName, 1);

    if ($result === false) {
        echo "<p>Correcto: No se permitió crear un inventario con nombre duplicado.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió crear un inventario con nombre duplicado.</p>";
        $inventory->delete($result); // Limpieza
        return false;
    }
});

// Prueba para actualizar el nombre de un inventario con datos válidos
$runner->registerTest('updateInventoryName_success', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    $newName = "Inventario Actualizado " . time();

    echo "<p>Testing updateName({$testData['inventoryId']}, '$newName')...</p>";
    $result = $inventory->updateName($testData['inventoryId'], $newName);

    if ($result) {
        echo "<p>Nombre del inventario actualizado correctamente.</p>";
        return true;
    } else {
        echo "<p>Error al actualizar el nombre del inventario.</p>";
        return false;
    }
});

// Prueba para actualizar el nombre de un inventario con datos inválidos
$runner->registerTest('updateInventoryName_invalid', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    $invalidName = ""; // Nombre vacío (inválido)

    echo "<p>Testing updateName({$testData['inventoryId']}, '$invalidName')...</p>";
    $result = $inventory->updateName($testData['inventoryId'], $invalidName);

    if (!$result) {
        echo "<p>Correcto: No se permitió actualizar el nombre del inventario con datos inválidos.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió actualizar el nombre del inventario con datos inválidos.</p>";
        return false;
    }
});

// Prueba para eliminar un inventario sin bienes asociados
$runner->registerTest('deleteInventory_success', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    echo "<p>Testing delete({$testData['inventoryId']})...</p>";
    $result = $inventory->delete($testData['inventoryId']);

    if ($result) {
        echo "<p>Inventario eliminado correctamente.</p>";
        $testData['inventoryId'] = null; // Resetear el ID
        return true;
    } else {
        echo "<p>Error al eliminar el inventario.</p>";
        return false;
    }
});

// Prueba para eliminar un inventario con bienes asociados
$runner->registerTest('deleteInventory_withItems', function() {
    $inventory = new Inventory();

    // Buscar un inventario con bienes asociados
    $stmt = $inventory->getConnection()->prepare("
        SELECT i.id FROM inventarios i
        INNER JOIN bienes_inventarios bi ON i.id = bi.inventario_id
        LIMIT 1
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<p>No se encontró ningún inventario con bienes asociados para la prueba.</p>";
        return true; // No es un error, simplemente no hay datos para probar
    }

    $row = $result->fetch_assoc();
    $inventoryIdWithItems = $row['id'];

    echo "<p>Testing delete($inventoryIdWithItems)...</p>";
    $result = $inventory->delete($inventoryIdWithItems);

    if (!$result) {
        echo "<p>Correcto: No se permitió eliminar un inventario con bienes asociados.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió eliminar un inventario con bienes asociados.</p>";
        return false;
    }
});

// Prueba final de limpieza
$runner->registerTest('limpieza_final', function() use (&$testData, $inventory) {
    if ($testData['inventoryId'] !== null) {
        echo "<p>Limpieza: Eliminando inventario temporal ID {$testData['inventoryId']}...</p>";
        $result = $inventory->delete($testData['inventoryId']);
        if ($result) {
            echo "<p>Inventario temporal eliminado correctamente.</p>";
            $testData['inventoryId'] = null;
        } else {
            echo "<p>Nota: El inventario temporal no pudo ser eliminado. Puede requerir limpieza manual.</p>";
        }
    } else {
        echo "<p>No hay inventarios temporales para limpiar.</p>";
    }

    return true; // Esta prueba siempre pasa, es solo para limpieza
});

// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}