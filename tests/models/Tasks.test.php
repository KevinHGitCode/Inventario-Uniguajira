<?php

require_once '../../app/models/Tasks.php';

function runTests() {
    testGetAll();
    // testCreate(1, "Barrer la cancha", "Ir a la cancha y barrerla", "por hacer");
    // testUpdateName(2, "Poner un foco", "Poner foco en la sala 3");
    // testDelete(5);
    // testChangeState(6);
}

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

function testCreate($usuario_id, $nombre, $description, $estado) {
    $tasks = new Tasks();
    echo "Testing create()... <br>";
    if ($tasks->create($nombre, $description, $usuario_id, $estado)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateName($id, $nombre, $descripcion, $idUsuario) {
    $tasks = new Tasks();
    echo "Testing updateName()... <br>";
    if ($tasks->updateName($id, $nombre, $descripcion, $idUsuario)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDelete($idTarea, $idUsuario) {
    $tasks = new Tasks();
    echo "Testing delete()... <br>";
    if ($tasks->delete($idTarea, $idUsuario)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testChangeState($idTarea, $idUsuario) {
    $tasks = new Tasks();
    echo "Testing changeState()... <br>";
    if ($tasks->changeState($idTarea, $idUsuario)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

runTests();
