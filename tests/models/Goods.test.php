<?php
require_once '../../app/models/Goods.php';

// Crear una instancia del modelo Goods
$goods = new Goods();

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'goodId' => null,
    'serialGoodId' => null,
    'goodWithImageId' => null
];

// Obtener todos los bienes
$runner->registerTest('getAllGoods', function() use ($goods) {
    echo "<p>Testing getAllGoods()...</p>";
    
    $allGoods = $goods->getAllGoods();
    if (!empty($allGoods)) {
        renderTable($allGoods);
        return true;
    } else {
        echo "<p>No hay bienes registrados o error al recuperarlos.</p>";
        return false;
    }
});

// Obtener bienes desde la vista del sistema
$runner->registerTest('getAll', function() use ($goods) {
    echo "<p>Testing getAll()...</p>";
    
    $systemGoods = $goods->getAll();
    if (!empty($systemGoods)) {
        renderTable($systemGoods);
        return true;
    } else {
        echo "<p>No hay bienes registrados en la vista del sistema o error al recuperarlos.</p>";
        return false;
    }
});

// CASOS DE PRUEBA PARA CREAR BIENES

// Caso 1: Crear un bien de tipo cantidad con nombre único
$runner->registerTest('crear_bien_cantidad', 
    function() use (&$testData, $goods) {
        $nombreBien = "Bien Cantidad Test " . time(); // Nombre único
        echo "<p>Testing create('$nombreBien', 1, 'placeholder.jpg')...</p>";

        $goodId = $goods->create($nombreBien, 1, 'placeholder.jpg');
        if ($goodId !== false) {
            echo "<p>Bien '$nombreBien' creado exitosamente con ID: $goodId.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['goodId'] = $goodId;
            return true;
        } else {
            echo "<p>Error al crear el bien.</p>";
            return false;
        }
    }
);

// Caso 2: Crear un bien de tipo serial con nombre único
$runner->registerTest('crear_bien_serial', 
    function() use (&$testData, $goods) {
        $nombreBien = "Bien Serial Test " . time(); // Nombre único
        echo "<p>Testing create('$nombreBien', 2, 'placeholder.jpg')...</p>";

        $goodId = $goods->create($nombreBien, 2, 'placeholder.jpg');
        if ($goodId !== false) {
            echo "<p>Bien serial '$nombreBien' creado exitosamente con ID: $goodId.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['serialGoodId'] = $goodId;
            return true;
        } else {
            echo "<p>Error al crear el bien serial.</p>";
            return false;
        }
    }
);

// Caso 3: Crear un bien con una imagen específica
$runner->registerTest('crear_bien_con_imagen', 
    function() use (&$testData, $goods) {
        $nombreBien = "Bien Con Imagen Test " . time(); // Nombre único
        $rutaImagen = "assets/images/test_" . time() . ".jpg";
        echo "<p>Testing create('$nombreBien', 'Cantidad', '$rutaImagen')...</p>";

        $goodId = $goods->create($nombreBien, 1, $rutaImagen);
        if ($goodId !== false) {
            echo "<p>Bien con imagen '$nombreBien' creado exitosamente con ID: $goodId.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['goodWithImageId'] = $goodId;
            return true;
        } else {
            echo "<p>Error al crear el bien con imagen.</p>";
            return false;
        }
    }
);

// CASOS DE PRUEBA PARA MODIFICAR BIENES

// Caso 4: Modificar un bien existente
$runner->registerTest('modificar_bien', 
    function() use (&$testData, $goods) {
        $nuevoNombre = "Bien Modificado Test " . time();
        echo "<p>Testing update({$testData['goodId']}, '$nuevoNombre')...</p>";

        $resultado = $goods->update($testData['goodId'], $nuevoNombre);
        if ($resultado) {
            echo "<p>Bien modificado exitosamente.</p>";
            return true;
        } else {
            echo "<p>Error al modificar el bien.</p>";
            return false;
        }
    }
);

// Caso 5: Modificar un bien inexistente
$runner->registerTest('modificar_bien_inexistente', 
    function() use ($goods) {
        echo "<p>Testing update(999999, 'Nombre Inexistente')...</p>";

        $resultado = $goods->update(999999, 'Nombre Inexistente');
        if (!$resultado) {
            echo "<p>Correctamente falló al modificar un bien inexistente.</p>";
            return true;
        } else {
            echo "<p>Error: Se modificó un bien inexistente.</p>";
            return false;
        }
    }
);

// CASOS DE PRUEBA PARA OBTENER INFORMACION DE BIENES

// Caso 8: Obtener cantidad de un bien existente
$runner->registerTest('obtener_cantidad_bien', 
    function() use (&$testData, $goods) {
        echo "<p>Testing getQuantityById({$testData['goodId']})...</p>";

        $cantidad = $goods->getQuantityById($testData['goodId']);
        if ($cantidad >= 0) {
            echo "<p>Cantidad obtenida exitosamente: $cantidad.</p>";
            return true;
        } else {
            echo "<p>Error al obtener la cantidad del bien.</p>";
            return false;
        }
    }
);

// Caso 9: Obtener cantidad de un bien inexistente
$runner->registerTest('obtener_cantidad_bien_inexistente', 
    function() use ($goods) {
        echo "<p>Testing getQuantityById(999999)...</p>";

        $cantidad = $goods->getQuantityById(999999);
        if ($cantidad === 0) {
            echo "<p>Correctamente retornó 0 para un bien inexistente.</p>";
            return true;
        } else {
            echo "<p>Error: Retornó una cantidad no válida para un bien inexistente.</p>";
            return false;
        }
    }
);

// Caso 10: Obtener imagen de un bien existente
$runner->registerTest('obtener_imagen_bien', 
    function() use (&$testData, $goods) {
        echo "<p>Testing getImageById({$testData['goodId']})...</p>";

        $imagen = $goods->getImageById($testData['goodId']);
        if ($imagen) {
            echo "<p>Ruta de imagen obtenida exitosamente: $imagen.</p>";
            return true;
        } else {
            echo "<p>Error al obtener la ruta de la imagen del bien.</p>";
            return false;
        }
    }
);

// Caso 11: Obtener imagen de un bien inexistente
$runner->registerTest('obtener_imagen_bien_inexistente', 
    function() use ($goods) {
        echo "<p>Testing getImageById(999999)...</p>";

        $imagen = $goods->getImageById(999999);
        if ($imagen === null) {
            echo "<p>Correctamente retornó null para un bien inexistente.</p>";
            return true;
        } else {
            echo "<p>Error: Retornó una ruta de imagen no válida para un bien inexistente.</p>";
            return false;
        }
    }
);

// CASOS DE PRUEBA PARA ELIMINAR BIENES

// Caso 6: Eliminar un bien existente
$runner->registerTest('eliminar_bien', 
    function() use (&$testData, $goods) {
        echo "<p>Testing delete({$testData['goodId']})...</p>";

        $resultado = $goods->delete($testData['goodId']);
        if ($resultado) {
            echo "<p>Bien eliminado exitosamente.</p>";
            return true;
        } else {
            echo "<p>Error al eliminar el bien.</p>";
            return false;
        }
    }
);

// Caso 7: Eliminar un bien inexistente
$runner->registerTest('eliminar_bien_inexistente', 
    function() use ($goods) {
        echo "<p>Testing delete(999999)...</p>";

        $resultado = $goods->delete(999999);
        if (!$resultado) {
            echo "<p>Correctamente falló al eliminar un bien inexistente.</p>";
            return true;
        } else {
            echo "<p>Error: Se eliminó un bien inexistente.</p>";
            return false;
        }
    }
);

// Limpieza final: Eliminar todos los registros creados durante las pruebas
$runner->registerTest('limpieza_final', 
    function() use (&$testData, $goods) {
        echo "<p>Ejecutando limpieza final...</p>";

        foreach (['goodId', 'serialGoodId', 'goodWithImageId'] as $key) {
            if ($testData[$key] !== null) {
                echo "<p>Eliminando bien con ID {$testData[$key]}...</p>";
                $goods->delete($testData[$key]);
                $testData[$key] = null;
            }
        }

        echo "<p>Limpieza finalizada.</p>";
        return true;
    }
);