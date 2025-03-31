<?php

require_once '../../app/models/Goods.php';

function runTests() {
    testGetAll();
    // Descomente las siguientes líneas para ejecutar pruebas adicionales
    // testCreate();
    // testUpdateName();
    // testDelete();
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

function testCreate() {
    $goods = new Goods();
    echo "Testing create()... <br>";
    $nombre = "Laptop";
    $tipo = 2;
    if ($goods->create($nombre, $tipo)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testUpdateName() {
    $goods = new Goods();
    echo "Testing updateName()... <br>";
    $id = 13;  // Cambiar según un ID válido en la base de datos
    $nuevoNombre = "PC Gamer";
    if ($goods->updateName($id, $nuevoNombre)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}

function testDelete() {
    $goods = new Goods();
    echo "Testing delete()... <br>";
    $idEliminar = 13;  // Cambiar según un ID válido en la base de datos
    if ($goods->delete($idEliminar)) {
        echo "PASSED<br>";
    } else {
        echo "FAILED<br>";
    }
}


runTests();
