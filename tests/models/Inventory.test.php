<?php

require_once '../../app/models/Inventory.php';
require '.tableHelper.php';

function runTests() {
    testGetAll();
    // testCreate("Nuevo Inventario", 1);
    // testUpdateName(3, "3A");
    // testUpdateGroup(3, 1);
    // testUpdateConservation(5, 2);
    // testDeleteWithoutItems(5);
    // testDeleteWithItems(1);
}

function testGetAll() {
    $inventory = new Inventory();
    echo "Testing getAll()... <br>";

    $allInventories = $inventory->getAll();
    if (is_array($allInventories)) {
        renderTable($allInventories);
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}
    

function testCreate($name, $grupoId) {
    $inventory = new Inventory();
    echo "Testing create()... <br>";
    if ($inventory->create($name, $grupoId)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateName($updateId, $updateName) {
    $inventory = new Inventory();
    echo "Testing updateName()... <br>";
    if ($inventory->updateName($updateId, $updateName)) {
        echo "PASSED: Nombre actualizado correctamente<br>";
    } else {
        echo "FAILED: No se pudo actualizar el nombre<br>";
    }
}

function testUpdateGroup($updateId, $newGroupId) {
    $inventory = new Inventory();
    echo "Testing updateGroup()... <br>";
    if ($inventory->updateGroup($updateId, $newGroupId)) {
        echo "PASSED: Grupo actualizado correctamente<br>";
    } else {
        echo "FAILED: No se pudo actualizar el grupo<br>";
    }
}

function testUpdateConservation($id, $newConservation) {
    $inventory = new Inventory();
    echo "Testing updateConservation()... <br>";
    if ($inventory->updateConservation($id, $newConservation)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDeleteWithoutItems($deleteId) {
    $inventory = new Inventory();
    echo "Testing delete()... <br>";
    if ($inventory->delete($deleteId)) {
        echo "PASSED: Inventario eliminado <br>";
    } else {
        echo "FAILED: El inventario tiene bienes, no se puede eliminar <br>";
    }
}

function testDeleteWithItems($deleteIdWithItems) {
    $inventory = new Inventory();
    echo "Testing delete() with associated goods... <br>";
    if ($inventory->delete($deleteIdWithItems)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

runTests();
