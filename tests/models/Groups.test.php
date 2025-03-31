<?php

require_once '../../app/models/Groups.php';

function runTests() {
    testGetAllGroups();
    // Descomente las siguientes líneas para ejecutar pruebas adicionales
    // testGetInventoriesByGroup();
    // testCreateGroup();
    // testUpdateGroup();
    // testDeleteGroup();
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

function testGetInventoriesByGroup() {
    $group = new Groups();
    $groupId = 1; // Cambia esto a un ID existente en la BD
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

function testCreateGroup() {
    $group = new Groups();
    $newGroupName = "Grupo de prueba2";
    echo "Testing createGroup('$newGroupName')...<br>";
    if ($group->createGroup($newGroupName)) {
        echo "PASSED<br>";
        echo "Grupo '$newGroupName' creado exitosamente.<br>";
    } else {
        echo "FAILED<br>";
        echo "Error al crear el grupo.<br>";
    }
}

function testUpdateGroup() {
    $group = new Groups();
    $groupIdToUpdate = 6; // Cambia esto a un ID existente en la BD
    $newGroupName = "Nombre Actualizado 3"; // Si se envia el mismo nombre no se actualiza
    echo "Testing updateGroup($groupIdToUpdate, '$newGroupName')...<br>";
    if ($group->updateGroup($groupIdToUpdate, $newGroupName)) {
        echo "PASSED<br>";
        echo "Grupo actualizado correctamente.<br>";
    } else {
        echo "FAILED<br>";
        echo "Error al actualizar el grupo.<br>";
    }
}

function testDeleteGroup() {
    $group = new Groups();
    $groupIdToDelete = 6; // Cambia esto a un ID sin inventarios asociados
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
