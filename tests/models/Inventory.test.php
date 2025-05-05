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

// Prueba para crear un inventario con nombre duplicado en el mismo grupo
$runner->registerTest('createInventory_duplicateInSameGroup', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    // Obtener el nombre y grupo_id del inventario creado
    $stmt = $inventory->getConnection()->prepare("SELECT nombre, grupo_id FROM inventarios WHERE id = ?");
    $stmt->bind_param("i", $testData['inventoryId']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $duplicateName = $result['nombre'];
    $grupoId = $result['grupo_id'];

    echo "<p>Testing create('$duplicateName', $grupoId) - nombre duplicado en mismo grupo...</p>";
    $result = $inventory->create($duplicateName, $grupoId);

    if ($result === false) {
        echo "<p>Correcto: No se permitió crear un inventario con nombre duplicado en el mismo grupo.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió crear un inventario con nombre duplicado en el mismo grupo.</p>";
        $inventory->delete($result); // Limpieza
        return false;
    }
});

// Prueba para crear un inventario con el mismo nombre en diferente grupo (debe permitirse)
$runner->registerTest('createInventory_sameNameDifferentGroup', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    // Obtener el nombre del inventario creado
    $stmt = $inventory->getConnection()->prepare("SELECT nombre, grupo_id FROM inventarios WHERE id = ?");
    $stmt->bind_param("i", $testData['inventoryId']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $sameName = $result['nombre'];
    $differentGroupId = $result['grupo_id'] + 1; // Usar un grupo diferente

    echo "<p>Testing create('$sameName', $differentGroupId) - mismo nombre en diferente grupo...</p>";
    $newId = $inventory->create($sameName, $differentGroupId);

    if ($newId !== false) {
        echo "<p>Correcto: Se permitió crear un inventario con mismo nombre en diferente grupo.</p>";
        $inventory->delete($newId); // Limpieza
        return true;
    } else {
        echo "<p>Error: No se permitió crear un inventario con mismo nombre en diferente grupo.</p>";
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
$runner->registerTest('updateInventoryName_invalidData', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    $invalidNames = ["", str_repeat("a", 101)]; // Nombre vacío y nombre muy largo

    foreach ($invalidNames as $invalidName) {
        echo "<p>Testing updateName({$testData['inventoryId']}, '$invalidName')...</p>";
        $result = $inventory->updateName($testData['inventoryId'], $invalidName);

        if ($result) {
            echo "<p>Error: Se permitió actualizar con nombre inválido '$invalidName'.</p>";
            return false;
        }
    }

    echo "<p>Correcto: No se permitió actualizar con nombres inválidos.</p>";
    return true;
});

// Prueba para actualizar el nombre de un inventario a uno existente en el mismo grupo
$runner->registerTest('updateInventoryName_duplicateInSameGroup', function() use (&$testData) {
    if (!isset($testData['inventoryId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createInventory_success'.</p>";
        return false;
    }

    $inventory = new Inventory();
    // Crear un segundo inventario temporal para la prueba
    $tempName = "Inventario Temporal 2 " . time();
    $stmt = $inventory->getConnection()->prepare("SELECT grupo_id FROM inventarios WHERE id = ?");
    $stmt->bind_param("i", $testData['inventoryId']);
    $stmt->execute();
    $grupoId = $stmt->get_result()->fetch_assoc()['grupo_id'];
    
    $tempId = $inventory->create($tempName, $grupoId);
    if (!$tempId) {
        echo "<p>Error: No se pudo crear el inventario temporal para la prueba.</p>";
        return false;
    }

    // Intentar actualizar el nombre al del primer inventario
    $stmt = $inventory->getConnection()->prepare("SELECT nombre FROM inventarios WHERE id = ?");
    $stmt->bind_param("i", $testData['inventoryId']);
    $stmt->execute();
    $existingName = $stmt->get_result()->fetch_assoc()['nombre'];

    echo "<p>Testing updateName($tempId, '$existingName') - nombre duplicado en mismo grupo...</p>";
    $result = $inventory->updateName($tempId, $existingName);

    // Limpieza
    $inventory->delete($tempId);

    if (!$result) {
        echo "<p>Correcto: No se permitió actualizar a un nombre duplicado en el mismo grupo.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió actualizar a un nombre duplicado en el mismo grupo.</p>";
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
$runner->registerTest('limpieza_final', function() use (&$testData) {
    $inventory = new Inventory(); // Instanciar Inventory dentro de la función
    
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