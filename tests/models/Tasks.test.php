<?php

require_once '../../app/models/Tasks.php';

function runTests() {
    testGetAll();
    // Descomente las siguientes líneas para ejecutar pruebas adicionales
    // testCreate();
    // testUpdateName();
    // testDelete();
    // testChangeState();
}

// TODO: Obtener las tareas de un usuario específico
function testGetAll() {
    $tasks = new Tasks();
    echo "Testing getAll()... <br>";
    $allTasks = $tasks->getAll();
    if (is_array($allTasks)) {
        echo "PASSED<br>";
        foreach ($allTasks as $task) {
            echo "ID: {$task['id']}, Nombre: {$task['nombre']}, Descripcion: {$task['descripcion']}, 
            Estado: {$task['estado']}<br>";
        }
    } else {
        echo "FAILED<br>";
    }
}

function testCreate() {
    $tasks = new Tasks();
    echo "Testing create()... <br>";
    $usuario_id = 1;  // Cambiar según un ID válido en la base de datos
    $nombre = "Barrer la cancha";
    $description = "Ir a la cancha y barrerla";
    $estado = "por hacer";
    if ($tasks->create($nombre, $description, $usuario_id, $estado)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateName() {
    $tasks = new Tasks();
    echo "Testing updateName()... <br>";
    $id = 2;  // Cambiar según un ID válido en la base de datos
    $nuevoNombre = "Poner un foco";
    $nuevoDescripcion = "Poner foco en la sala 3";
    if ($tasks->updateName($id, $nuevoNombre, $nuevoDescripcion)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDelete() {
    $tasks = new Tasks();
    echo "Testing delete()... <br>";
    $idEliminar = 5;  // Cambiar según un ID válido en la base de datos
    if ($tasks->delete($idEliminar)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testChangeState() {
    $tasks = new Tasks();
    echo "Testing changeState()... <br>";
    $id = 6;  // Cambiar según un ID válido en la base de datos
    if ($tasks->changeState($id)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}


runTests();
