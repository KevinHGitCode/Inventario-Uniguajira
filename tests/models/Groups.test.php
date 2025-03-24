<?php

require_once '../../app/models/Groups.php';
require_once '../../app/config/config.php';

$database = new Database();
$connection = $database->getConnection();
$group = new Groups($connection);



// Prueba 1: Obtener todos los grupos
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



/*
// Prueba 2: Obtener inventarios de un grupo específico
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
*/


/*
// Prueba 3: Crear un nuevo grupo
$newGroupName = "Grupo de prueba2";
echo "Testing createGroup('$newGroupName')...<br>";
if ($group->createGroup($newGroupName)) {
    echo "PASSED<br>";
    echo "Grupo '$newGroupName' creado exitosamente.<br>";
} else {
    echo "FAILED<br>";
    echo "Error al crear el grupo.<br>";
}
*/



/*
// Prueba 4: Editar un grupo
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
*/



/*
// Prueba 5: Eliminar un grupo
$groupIdToDelete = 6; // Cambia esto a un ID sin inventarios asociados
echo "Testing deleteGroup($groupIdToDelete)...<br>";
if ($group->deleteGroup($groupIdToDelete)) {
    echo "PASSED<br>";
    echo "Grupo eliminado correctamente.<br>";
} else {
    echo "FAILED<br>";
    echo "No se pudo eliminar el grupo (puede tener inventarios asociados o no existe).<br>";
}
*/

?>
