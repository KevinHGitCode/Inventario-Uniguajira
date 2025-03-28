<?php

require_once '../../app/models/Tasks.php';

$tasks = new Tasks();

/*
// Prueba 1: Obtener todas las tareas
echo "Testing getAll()... <br>";
$allTasks = $tasks->getAll();
if (is_array($allTasks)) {
    echo "PASSED<br>";
    foreach ($allTasks as $task) {
        echo "ID: {$task['id']}, Nombre: {$task['nombre']}, Descripcion: {$task['descripcion']}, 
        Estado: {$task['estado']}<br>";
    }
} else {
    echo "FAILED\n";
}
*/

/*
// Prueba 2: Crear una tarea
echo "Testing create()... <br>";
$usuario_id = 1;  // Cambiar según un ID válido en la base de datos
$nombre = "Barrer la cancha";
$description = "Ir a la cancha y barrerla";
$estado = "por hacer";
if ($tasks->create($nombre, $description, $usuario_id, $estado)) {
    echo "PASSED\n";
} else {
    echo "FAILED\n";
}
*/

/*
// Prueba 3: Modificar una tarea (asumiendo que existe una tarea con id = 1)
echo "Testing updateName()... <br>";
$id = 2;  // Cambiar según un ID válido en la base de datos
$nuevoNombre = "Poner un foco";
$nuevoDescripcion = "Poner foco en la sala 3";
if ($tasks->updateName($id, $nuevoNombre, $nuevoDescripcion)) {
    echo "PASSED\n";
} else {
    echo "FAILED\n";
}
*/

/*
// Prueba 4: Eliminar una tarea (asumiendo que existe una tarea con id = 4)
echo "Testing delete()... <br>";
$idEliminar = 5;  // Cambiar según un ID válido en la base de datos
if ($tasks->delete($idEliminar)) {
    echo "PASSED\n";
} else {
    echo "FAILED\n";
}
*/


echo "Testing changeState()... <br>";
$id = 6;  // Cambiar según un ID válido en la base de datos
if ($tasks->changeState($id)) {
    echo "PASSED\n";
} else {
    echo "FAILED\n";
}


?>