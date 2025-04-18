<?php
require_once '../../app/models/Goods.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Registrar todas las pruebas disponibles
$runner->registerTest('getAll', function() {
    $goods = new Goods();
    echo "<p>Testing getAll()...</p>";
    
    $allGoods = $goods->getAll();
    if (is_array($allGoods)) {
        renderTable($allGoods);
        return true;
    } else {
        echo "<p>No se pudo obtener los datos como un array</p>";
        return false;
    }
});

$runner->registerTest('getAllGoods', function() {
    $goods = new Goods();
    echo "<p>Testing getAllGoods()...</p>";
    
    $allGoods = $goods->getAllGoods();
    if (is_array($allGoods)) {
        renderTable($allGoods);
        return true;
    } else {
        echo "<p>No se pudo obtener los datos como un array</p>";
        return false;
    }
});

$runner->registerTest('create', 
    function($nombre, $tipo, $rutaImagen) {
        $goods = new Goods();
        echo "<p>Testing create() with nombre: $nombre, tipo: $tipo</p>";

        if ($goods->create($nombre, $tipo, $rutaImagen)) {
            echo "<p>Se creó el item correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo crear el item</p>";
            return false;
        }
    }, [
        "Laptop", // Nombre del bien
        2,        // Tipo de bien
        ""        // Ruta de la imagen
    ]
);

$runner->registerTest('updateName', 
    function($id, $nuevoNombre) {
        $goods = new Goods();
        echo "<p>Testing updateName() para ID $id con nuevo nombre: '$nuevoNombre'</p>";

        if ($goods->update($id, $nuevoNombre)) {
            echo "<p>Se actualizó el nombre correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar el nombre</p>";
            return false;
        }
    }, [
        1,           // ID del bien a actualizar
        "PC Gamer"   // Nuevo nombre del bien
    ]
);

$runner->registerTest('delete', 
    function($idEliminar) {
        $goods = new Goods();
        echo "<p>Testing delete() para ID $idEliminar</p>";

        if ($goods->delete($idEliminar)) {
            echo "<p>Se eliminó el item correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar el item</p>";
            return false;
        }
    }, [
        1 // ID del bien a eliminar
    ]
);


// Ya NO manejamos directamente la solicitud web aquí
// En su lugar, exponemos el objeto $runner para que .init-tests.php pueda utilizarlo

// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}