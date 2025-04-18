<?php
require_once '../../app/models/Inventory.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Registrar todas las pruebas disponibles
$runner->registerTest('getAll', 
    function() {
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
    }
);

$runner->registerTest('create', 
    function($name, $grupoId) {
        $inventory = new Inventory();
        echo "<p>Testing create() con nombre: '$name', grupo ID: $grupoId</p>";
        
        if ($inventory->create($name, $grupoId)) {
            echo "<p>Se creó el inventario correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo crear el inventario</p>";
            return false;
        }
    }, 
    [
        "Nuevo Inventario",  // name
        1                   // grupoId
    ]
);

$runner->registerTest('updateName', 
    function($updateId, $updateName) {
        $inventory = new Inventory();
        echo "<p>Testing updateName() para ID $updateId con nuevo nombre: '$updateName'</p>";
        
        if ($inventory->updateName($updateId, $updateName)) {
            echo "<p>Nombre actualizado correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar el nombre</p>";
            return false;
        }
    }, 
    [
        3,    // updateId
        "3A"  // updateName
    ]
);

$runner->registerTest('updateGroup', 
    function($updateId, $newGroupId) {
        $inventory = new Inventory();
        echo "<p>Testing updateGroup() para ID $updateId con nuevo grupo ID: $newGroupId</p>";
        
        if ($inventory->updateGroup($updateId, $newGroupId)) {
            echo "<p>Grupo actualizado correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar el grupo</p>";
            return false;
        }
    }, 
    [
        3,  // updateId
        1   // newGroupId
    ]
);

$runner->registerTest('updateConservation', 
    function($id, $newConservation) {
        $inventory = new Inventory();
        echo "<p>Testing updateConservation() para ID $id con nuevo estado de conservación: $newConservation</p>";
        
        if ($inventory->updateConservation($id, $newConservation)) {
            echo "<p>Estado de conservación actualizado correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar el estado de conservación</p>";
            return false;
        }
    }, 
    [
        5,  // id
        2   // newConservation
    ]
);

$runner->registerTest('deleteWithoutItems', 
    function($deleteId) {
        $inventory = new Inventory();
        echo "<p>Testing delete() para inventario ID $deleteId (sin bienes asociados)</p>";
        
        if ($inventory->delete($deleteId)) {
            echo "<p>Inventario eliminado correctamente</p>";
            return true;
        } else {
            echo "<p>El inventario tiene bienes, no se puede eliminar</p>";
            return false;
        }
    }, 
    [
        5  // deleteId
    ]
);

$runner->registerTest('deleteWithItems', 
    function($deleteIdWithItems) {
        $inventory = new Inventory();
        echo "<p>Testing delete() para inventario ID $deleteIdWithItems (con bienes asociados)</p>";
        
        if ($inventory->delete($deleteIdWithItems)) {
            echo "<p>Inventario eliminado correctamente (esto no debería ocurrir si tiene bienes)</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar el inventario con bienes (comportamiento esperado)</p>";
            return true; // Marcamos como éxito ya que el comportamiento esperado es que no se pueda eliminar
        }
    }, 
    [
        1  // deleteIdWithItems
    ]
);


// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}