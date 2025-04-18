<?php
require_once '../../app/models/Groups.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Registrar todas las pruebas disponibles
$runner->registerTest('getAllGroups', function() {
    $group = new Groups();
    echo "<p>Testing getAllGroups()...</p>";
    
    $groups = $group->getAllGroups();
    if (!empty($groups)) {
        renderTable($groups);
        return true;
    } else {
        echo "<p>No hay grupos registrados.</p>";
        return false;
    }
});

$runner->registerTest('getInventoriesByGroup', 
    function($groupId) {
        $group = new Groups();
        echo "<p>Testing getInventoriesByGroup($groupId)...</p>";

        $groups = $group->getInventoriesByGroup($groupId);
        if (!empty($groups)) {
            echo "<div class='test-output-detail'>";
            foreach ($groups as $groupsItem) {
                echo "ID: {$groupsItem['id']}, Nombre: {$groupsItem['nombre']}<br>";
            }
            echo "</div>";
            return true;
        } else {
            echo "<p>No hay inventarios en este grupo.</p>";
            return false;
        }
    }, [
        1 // ID del grupo
    ]
);


$runner->registerTest('createGroup', 
    function($newGroupName) {
        $group = new Groups();
        echo "<p>Testing createGroup('$newGroupName')...</p>";

        if ($group->createGroup($newGroupName)) {
            echo "<p>Grupo '$newGroupName' creado exitosamente.</p>";
            return true;
        } else {
            echo "<p>Error al crear el grupo.</p>";
            return false;
        }
    }, [
        "Grupo de prueba2" // Nombre del nuevo grupo
    ]
);

$runner->registerTest('updateGroup', 
    function($groupIdToUpdate, $newGroupName) {
        $group = new Groups();
        echo "<p>Testing updateGroup($groupIdToUpdate, '$newGroupName')...</p>";

        if ($group->updateGroup($groupIdToUpdate, $newGroupName)) {
            echo "<p>Grupo actualizado correctamente.</p>";
            return true;
        } else {
            echo "<p>Error al actualizar el grupo.</p>";
            return false;
        }
    }, [
        6, // ID del grupo a actualizar
        "Nombre Actualizado 3" // Nuevo nombre del grupo
    ]
);

$runner->registerTest('deleteGroup', 
    function($groupIdToDelete) {
        $group = new Groups();
        echo "<p>Testing deleteGroup($groupIdToDelete)...</p>";

        if ($group->deleteGroup($groupIdToDelete)) {
            echo "<p>Grupo eliminado correctamente.</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar el grupo (puede tener inventarios asociados o no existe).</p>";
            return false;
        }
    }, [
        6 // ID del grupo a eliminar
    ]
);


// Si se accede directamente a este archivo (no a través de init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}