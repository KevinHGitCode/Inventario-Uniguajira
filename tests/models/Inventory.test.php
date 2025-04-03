<?php

require_once '../../app/models/Inventory.php';

function runTests() {
    testGetAll();
    // Descomente las siguientes líneas para ejecutar pruebas adicionales
    // testCreate();
    // testUpdateName();
    // testUpdateGroup();
    // testUpdateConservation();
    // testUpdate();
    // testDeleteWithoutItems();
    // testDeleteWithItems();
}

function testGetAll() {
    $inventory = new Inventory();
    echo "Testing getAll()... <br>";
    $allInventories = $inventory->getAll();
    if (is_array($allInventories)) {
        foreach ($allInventories as $inv) {
            echo "ID: {$inv['id']}, Nombre: {$inv['nombre']}, Estado: {$inv['estado_conservacion']}, Grupo ID: {$inv['grupo_id']}<br>";
        }
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testCreate() {
    $inventory = new Inventory();
    $name = "Nuevo Inventario";
    $grupoId = 1;
    echo "Testing create()... <br>";
    if ($inventory->create($name, $grupoId)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}



/* Hay que ver que se hace con este
function testUpdate() {
    $inventory = new Inventory();
    $updateId = 5;
    $updateName = "Inventario Actualizado";
    $updateGrupoId = 2;
    $updateState = 3; // Malo
    echo "Testing update()... <br>";
    if ($inventory->update($updateId, $updateName, $updateGrupoId, $updateState)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}
*/

function testUpdateName() {
    $inventory = new Inventory();
    $updateId = 3;
    $updateName = "3A";
    echo "Testing updateName()... <br>";
    if ($inventory->updateName($updateId, $updateName)) {
        echo "PASSED: Nombre actualizado correctamente<br>";
    } else {
        echo "FAILED: No se pudo actualizar el nombre<br>";
    }
}

function testUpdateGroup() {
    $inventory = new Inventory();
    $updateId = 3;
    $newGroupId = 1;
    echo "Testing updateGroup()... <br>";
    if ($inventory->updateGroup($updateId, $newGroupId)) {
        echo "PASSED: Grupo actualizado correctamente<br>";
    } else {
        echo "FAILED: No se pudo actualizar el grupo<br>";
    }
}


function testUpdateConservation() {
    $inventory = new Inventory();
    $id = 5;
    $newConservation = 2; // Regular
    echo "Testing updateConservation()... <br>";
    if ($inventory->updateConservation($id, $newConservation)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDeleteWithoutItems() {
    $inventory = new Inventory();
    $deleteId = 5;
    echo "Testing delete()... <br>";
    if ($inventory->delete($deleteId)) {
        echo "PASSED: Inventario eliminado <br>";
    } else {
        echo "FAILED: El invetario tiene bienes, no se puede eliminar <br>";
    }
}

function testDeleteWithItems() {
    $inventory = new Inventory();
    $deleteIdWithItems = 1;
    echo "Testing delete() with associated goods... <br>";
    if ($inventory->delete($deleteIdWithItems)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}


runTests();
