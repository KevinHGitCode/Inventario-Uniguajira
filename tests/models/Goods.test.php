<?php

require_once '../../app/models/Goods.php';

$goods = new Goods();



// Prueba 1: Obtener todos los bienes
echo "Testing getAll()... <br>";
$allGoods = $goods->getAll();
if (is_array($allGoods)) {
    echo "PASSED<br>";
    foreach ($allGoods as $good) {
        echo "ID: {$good['id']}, Nombre: {$good['nombre']}, Tipo: {$good['tipo']}<br>";
    }
} else {
    echo "FAILED<br>";
}


/*
// Prueba 2: Crear un nuevo bien
echo "Testing create()... <br>";
$nombre = "Laptop";
$tipo = 2;
if ($goods->create($nombre, $tipo)) {
    echo "PASSED\n";
} else {
    echo "FAILED<br>";
}
*/

/*
// Prueba 3: Modificar un bien (asumiendo que existe un bien con id = 1)
echo "Testing updateName()... <br>";
$id = 13;  // Cambiar según un ID válido en la base de datos
$nuevoNombre = "PC Gamer";
if ($goods->updateName($id, $nuevoNombre)) {
    echo "PASSED\n";
} else {
    echo "FAILED<br>";
}
*/

/*
// Prueba 4: Eliminar un bien (asumiendo que existe un bien con id = 2 y no tiene relaciones)
echo "Testing delete()... <br>";
$idEliminar = 13;  // Cambiar según un ID válido en la base de datos
if ($goods->delete($idEliminar)) {
    echo "PASSED\n";
} else {
    echo "FAILED<br>";
}
*/



?>
