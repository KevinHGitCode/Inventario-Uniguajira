<?php

require_once '../../app/models/Goods.php';

function runTests() {
    testGetAll();
    // testCreate("Laptop", 2);
    // testUpdateName(13, "PC Gamer");
    // testDelete(13);
}

function testGetAll() {
    $goods = new Goods();
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
}

function testCreate($nombre, $tipo, $rutaImagen) {
    $goods = new Goods();
    echo "Testing create()... <br>";
    if ($goods->create($nombre, $tipo, $rutaImagen)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateName($id, $nuevoNombre) {
    $goods = new Goods();
    echo "Testing updateName()... <br>";
    if ($goods->updateName($id, $nuevoNombre)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDelete($idEliminar) {
    $goods = new Goods();
    echo "Testing delete()... <br>";
    if ($goods->delete($idEliminar)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

runTests();
