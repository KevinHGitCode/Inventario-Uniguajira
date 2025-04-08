<?php

require_once '../../app/models/Groups.php';

function runTests() {
    testGetAllGroups();
    // testGetInventoriesByGroup(1);
    // testCreateGroup("Grupo de prueba2");
    // testUpdateGroup(6, "Nombre Actualizado 3");
    // testDeleteGroup(6);
}

function testGetAllGroups() {
    $group = new Groups();
    echo "Testing getAllGroups()...<br>";
    $groups = $group->getAllGroups();
    if (!empty($groups)) {
        echo "PASSED<br>";
        foreach ($groups as $group) {
            echo "ID: {$group['id']}, Nombre: {$group['nombre']}<br>";
        }
    } else {
        echo "No hay grupos registrados.<br>";
    }
}

function testGetInventoriesByGroup($groupId) {
    $group = new Groups();
    echo "Testing getInventoriesByGroup($groupId)...<br>";
    $groups = $group->getInventoriesByGroup($groupId);
    if (!empty($groups)) {
        echo "PASSED<br>";
        foreach ($groups as $groupsItem) {
            echo "ID: {$groupsItem['id']}, Nombre: {$groupsItem['nombre']}<br>";
        }
    } else {
        echo "FAILED<br>";
        echo "No hay inventarios en este grupo.<br>";
    }
}

function testCreateGroup($newGroupName) {
    $group = new Groups();
    echo "Testing createGroup('$newGroupName')...<br>";
    if ($group->createGroup($newGroupName)) {
        echo "PASSED<br>";
        echo "Grupo '$newGroupName' creado exitosamente.<br>";
    } else {
        echo "FAILED<br>";
        echo "Error al crear el grupo.<br>";
    }
}

function testUpdateGroup($groupIdToUpdate, $newGroupName) {
    $group = new Groups();
    echo "Testing updateGroup($groupIdToUpdate, '$newGroupName')...<br>";
    if ($group->updateGroup($groupIdToUpdate, $newGroupName)) {
        echo "PASSED<br>";
        echo "Grupo actualizado correctamente.<br>";
    } else {
        echo "FAILED<br>";
        echo "Error al actualizar el grupo.<br>";
    }
}

function testDeleteGroup($groupIdToDelete) {
    $group = new Groups();
    echo "Testing deleteGroup($groupIdToDelete)...<br>";
    if ($group->deleteGroup($groupIdToDelete)) {
        echo "PASSED<br>";
        echo "Grupo eliminado correctamente.<br>";
    } else {
        echo "FAILED<br>";
        echo "No se pudo eliminar el grupo (puede tener inventarios asociados o no existe).<br>";
    }
}

runTests();
