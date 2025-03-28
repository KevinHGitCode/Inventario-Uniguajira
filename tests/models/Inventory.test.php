<?php

require_once '../../app/models/Inventory.php';

$inventory = new Inventory();


// Prueba 1: Obtener todos los inventarios
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


/*
// Prueba 2: Crear un nuevo inventario
$name = "Nuevo Inventario";
$grupoId = 1;
echo "Testing create()... <br>";
if ($inventory->create($name, $grupoId)) {
    echo "PASSED<br>";
} else {
    echo "FAILED<br>";
}
*/

/*
// Prueba 3: Cambiar estado de conservación
$id = 5;
$newConservation = 2; // Regular
echo "Testing updateConservation()... <br>";
if ($inventory->updateConservation($id, $newConservation)) {
    echo "PASSED<br>";
} else {
    echo "FAILED<br>";
}
*/

/*
// Prueba 4: Editar inventario existente
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
*/

/*
// Prueba 5: Intentar eliminar un inventario sin bienes asociados
$deleteId = 5;
echo "Testing delete()... <br>";
if ($inventory->delete($deleteId)) {
    echo "PASSED<br>";
} else {
    echo "FAILED<br>";
}
*/

/*
// Prueba 6: Intentar eliminar un inventario con bienes asociados
$deleteIdWithItems = 1;
echo "Testing delete() with associated goods... <br>";
if ($inventory->delete($deleteIdWithItems)) {
    echo "PASSED<br>";
} else {
    echo "FAILED<br>";
}
*/


?>
