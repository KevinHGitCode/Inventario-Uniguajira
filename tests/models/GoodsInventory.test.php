<?php
require_once '../../app/models/GoodsInventory.php';
require_once '../../app/models/Goods.php';
require_once '../../app/models/Inventory.php';

// Iniciar sesión y establecer el usuario actual
session_start();
$_SESSION['user_id'] = 1;

// Crear instancias de los modelos necesarios
$goodsInventory = new GoodsInventory();
$goods = new Goods();
$inventory = new Inventory();

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales en la sesión
if (!isset($_SESSION['testData'])) {
    $_SESSION['testData'] = [
        'goodId' => null,
        'inventoryId' => null,
        'goodInventoryId' => null,
        'serialGoodId' => null
    ];
}
$testData = &$_SESSION['testData'];

// PREPARACIÓN - Crear inventarios temporales para las pruebas
$runner->registerTest('crear_inventarios_temporales', 
    function() use (&$testData, $inventory) {
        echo "<p>Creando inventarios temporales para pruebas...</p>";
        
        // Crear un grupo temporal primero
        $stmt = $inventory->getConnection()->prepare("INSERT INTO grupos (nombre) VALUES (?)");
        $groupName = "Grupo Temporal Test " . rand();
        $stmt->bind_param("s", $groupName);
        $stmt->execute();
        $groupId = $inventory->getConnection()->insert_id;
        
        if (!$groupId) {
            echo "<p>Error al crear grupo temporal.</p>";
            return false;
        }
        
        // Guardar el ID del grupo para limpieza posterior
        $testData['groupId'] = $groupId;
        
        // Crear dos inventarios temporales
        $inventoryName1 = "Inventario Temporal 1 " . rand();
        $inventoryName2 = "Inventario Temporal 2 " . rand();
        
        $invId1 = $inventory->create($inventoryName1, $groupId);
        $invId2 = $inventory->create($inventoryName2, $groupId);
        
        if (!$invId1 || !$invId2) {
            echo "<p>Error al crear inventarios temporales.</p>";
            return false;
        }
        
        // Guardar los IDs para las pruebas siguientes
        $testData['inventoryId1'] = $invId1;
        $testData['inventoryId2'] = $invId2;
        
        echo "<p>Inventarios temporales creados con éxito: ID1=$invId1, ID2=$invId2</p>";
        return true;
    }
);

// PREPARACIÓN - Crear bienes temporales para las pruebas
$runner->registerTest('crear_bienes_temporales', 
    function() use (&$testData, $goods) {
        echo "<p>Creando bienes temporales para pruebas...</p>";
        
        // Crear un bien de tipo cantidad
        $goodName1 = "Bien Cantidad Temporal " . rand();
        $goodId1 = $goods->create($goodName1, 1, 'placeholder.jpg');
        
        // Crear un bien de tipo serial
        $goodName2 = "Bien Serial Temporal " . rand();
        $goodId2 = $goods->create($goodName2, 2, 'placeholder.jpg');
        
        if (!$goodId1 || !$goodId2) {
            echo "<p>Error al crear bienes temporales.</p>";
            return false;
        }
        
        // Guardar los IDs para las pruebas siguientes
        $testData['goodId'] = $goodId1;
        $testData['serialGoodId'] = $goodId2;
        
        echo "<p>Bienes temporales creados con éxito: ID1=$goodId1, ID2=$goodId2</p>";
        return true;
    }
);

// CASOS DE PRUEBA PARA AÑADIR BIENES A INVENTARIO

// Caso 1: Añadir un bien de tipo cantidad a un inventario
$runner->registerTest('añadir_bien_cantidad', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['goodId']) || !isset($testData['inventoryId1'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas de preparación.</p>";
            return false;
        }

        $quantity = 10;
        echo "<p>Testing addQuantity({$testData['inventoryId1']}, {$testData['goodId']}, $quantity)...</p>";

        $result = $goodsInventory->addQuantity($testData['inventoryId1'], $testData['goodId'], $quantity);
        if ($result !== false) {
            echo "<p>Bien añadido exitosamente con ID: $result.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['goodInventoryId'] = $result;
            return true;
        } else {
            echo "<p>Error al añadir el bien al inventario.</p>";
            return false;
        }
    }
);

// Caso 2: Añadir un bien de tipo serial a un inventario
$runner->registerTest('añadir_bien_serial', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['serialGoodId']) || !isset($testData['inventoryId1'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas de preparación.</p>";
            return false;
        }

        $details = [
            'description' => 'Descripción de prueba',
            'brand' => 'Marca Test',
            'model' => 'Modelo Test',
            'serial' => 'SERIAL' . rand(), // Serial único
            'state' => 'activo',
            'color' => 'Negro',
            'technical_conditions' => 'En perfectas condiciones',
            'entry_date' => date('Y-m-d')
        ];
        
        echo "<p>Testing addSerial({$testData['inventoryId1']}, {$testData['serialGoodId']}, [...])...</p>";

        $result = $goodsInventory->addSerial($testData['inventoryId1'], $testData['serialGoodId'], $details);
        if ($result !== false) {
            echo "<p>Bien serial añadido exitosamente con ID: $result.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['serialEquipmentId'] = $result;
            
            // También guardar la relación bien-inventario
            $sql = "SELECT bien_inventario_id FROM bienes_equipos WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $result);
            $stmt->execute();
            $bienInvId = $stmt->get_result()->fetch_assoc()['bien_inventario_id'];
            $testData['serialGoodInventoryId'] = $bienInvId;
            
            return true;
        } else {
            echo "<p>Error al añadir el bien serial al inventario.</p>";
            return false;
        }
    }
);

// Caso 3: Intentar añadir un bien serial con un serial ya existente
$runner->registerTest('añadir_bien_serial_duplicado', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['serialGoodId']) || !isset($testData['inventoryId1']) || !isset($testData['serialEquipmentId'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        // Obtener el serial del equipo recién creado
        $sql = "SELECT serial FROM bienes_equipos WHERE id = ?";
        $stmt = $goodsInventory->getConnection()->prepare($sql);
        $stmt->bind_param("i", $testData['serialEquipmentId']);
        $stmt->execute();
        $serialExistente = $stmt->get_result()->fetch_assoc()['serial'];
        
        $details = [
            'description' => 'Otra descripción',
            'brand' => 'Otra marca',
            'model' => 'Otro modelo',
            'serial' => $serialExistente, // Usar el mismo serial (debe fallar)
            'state' => 'activo',
            'color' => 'Azul',
            'technical_conditions' => 'Nuevas condiciones',
            'entry_date' => date('Y-m-d')
        ];
        
        echo "<p>Testing addSerial con serial duplicado: '$serialExistente'...</p>";

        $result = $goodsInventory->addSerial($testData['inventoryId1'], $testData['serialGoodId'], $details);
        if ($result === false) {
            echo "<p>Correcto: No se permitió añadir un bien con serial duplicado.</p>";
            return true;
        } else {
            echo "<p>Error: Se permitió añadir un bien con serial duplicado.</p>";
            // Eliminar el registro creado para limpieza
            $stmt = $goodsInventory->getConnection()->prepare("DELETE FROM bienes_equipos WHERE id = ?");
            $stmt->bind_param("i", $result);
            $stmt->execute();
            return false;
        }
    }
);

// Caso 4: Obtener bienes por inventario
$runner->registerTest('obtener_bienes_por_inventario', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['inventoryId1'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing getAllGoodsByInventory({$testData['inventoryId1']})...</p>";

        $goods = $goodsInventory->getAllGoodsByInventory($testData['inventoryId1']);
        if (!empty($goods)) {
            echo "<p>Bienes recuperados exitosamente:</p>";
            renderTable($goods);
            return true;
        } else {
            echo "<p>Error o no hay bienes en el inventario.</p>";
            return false;
        }
    }
);

// Caso 5: Obtener bienes seriales por inventario
$runner->registerTest('obtener_bienes_seriales_por_inventario', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['inventoryId1'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing getAllSerialGoodsByInventory({$testData['inventoryId1']})...</p>";

        $serialGoods = $goodsInventory->getAllSerialGoodsByInventory($testData['inventoryId1']);
        if (!empty($serialGoods)) {
            echo "<p>Bienes seriales recuperados exitosamente:</p>";
            renderTable($serialGoods);
            return true;
        } else {
            echo "<p>Error o no hay bienes seriales en el inventario.</p>";
            return false;
        }
    }
);

// CASOS DE PRUEBA PARA ACTUALIZAR CANTIDAD

// Caso 1: Actualizar cantidad de un bien
$runner->registerTest('actualizar_cantidad', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['goodInventoryId'])) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'añadir_bien_cantidad'.</p>";
            return false;
        }

        $nuevaCantidad = 20;
        echo "<p>Testing updateQuantity({$testData['goodInventoryId']}, $nuevaCantidad)...</p>";

        $result = $goodsInventory->updateQuantity($testData['goodInventoryId'], $nuevaCantidad);
        if ($result) {
            echo "<p>Cantidad actualizada correctamente.</p>";
            
            // Verificar que la actualización se realizó correctamente
            $sql = "SELECT cantidad FROM bienes_cantidad WHERE bien_inventario_id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['goodInventoryId']);
            $stmt->execute();
            $cantidadActual = $stmt->get_result()->fetch_assoc()['cantidad'];
            
            echo "<p>Nueva cantidad en BD: $cantidadActual</p>";
            return $cantidadActual == $nuevaCantidad;
        } else {
            echo "<p>Error al actualizar la cantidad.</p>";
            return false;
        }
    }
);

// Caso 2: Intentar actualizar cantidad con valor inválido
$runner->registerTest('actualizar_cantidad_invalida', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['goodInventoryId'])) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'añadir_bien_cantidad'.</p>";
            return false;
        }

        // La restricción CHECK en la BD no permite cantidades negativas
        $cantidadInvalida = -5;
        echo "<p>Testing updateQuantity({$testData['goodInventoryId']}, $cantidadInvalida)...</p>";

        try {
            $result = $goodsInventory->updateQuantity($testData['goodInventoryId'], $cantidadInvalida);
            if ($result === false) {
                echo "<p>Correcto: No se permitió actualizar a una cantidad negativa.</p>";
                return true;
            } else {
                echo "<p>Error: Se permitió actualizar a una cantidad negativa.</p>";
                // Restaurar la cantidad correcta
                $goodsInventory->updateQuantity($testData['goodInventoryId'], 20);
                return false;
            }
        } catch (Exception $e) {
            echo "<p>Correcto: Se capturó excepción al intentar actualizar a cantidad negativa: " . $e->getMessage() . "</p>";
            return true;
        }
    }
);

// CASOS DE PRUEBA PARA MOVER BIENES

// Caso 1: Mover un bien de tipo cantidad a otro inventario
$runner->registerTest('mover_bien_cantidad', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['goodInventoryId']) || !isset($testData['inventoryId2'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing moveGood({$testData['goodInventoryId']}, {$testData['inventoryId2']})...</p>";

        $result = $goodsInventory->moveGood($testData['goodInventoryId'], $testData['inventoryId2']);
        if ($result) {
            echo "<p>Bien movido correctamente al inventario destino.</p>";
            
            // Consultar para verificar que el bien ahora está en el inventario destino
            $sql = "SELECT bi.id FROM bienes_inventarios bi
                   INNER JOIN bienes_cantidad bc ON bi.id = bc.bien_inventario_id
                   WHERE bi.inventario_id = ? AND bi.bien_id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("ii", $testData['inventoryId2'], $testData['goodId']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Bien encontrado en inventario destino con ID: {$row['id']}</p>";
                // Actualizar el ID para pruebas posteriores
                $testData['goodInventoryId'] = $row['id'];
                return true;
            } else {
                echo "<p>Error: El bien no se encontró en el inventario destino.</p>";
                return false;
            }
        } else {
            echo "<p>Error al mover el bien.</p>";
            return false;
        }
    }
);

// Caso 2: Mover un bien de tipo serial a otro inventario
$runner->registerTest('mover_bien_serial', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['serialGoodInventoryId']) || !isset($testData['inventoryId2'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing moveGood({$testData['serialGoodInventoryId']}, {$testData['inventoryId2']})...</p>";

        $result = $goodsInventory->moveGood($testData['serialGoodInventoryId'], $testData['inventoryId2']);
        if ($result) {
            echo "<p>Bien serial movido correctamente al inventario destino.</p>";
            
            // Consultar para verificar que el equipo ahora está asociado a un bien_inventario en el destino
            $sql = "SELECT bi.id FROM bienes_inventarios bi
                   INNER JOIN bienes_equipos be ON bi.id = be.bien_inventario_id
                   WHERE bi.inventario_id = ? AND bi.bien_id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("ii", $testData['inventoryId2'], $testData['serialGoodId']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Bien serial encontrado en inventario destino con ID: {$row['id']}</p>";
                // Actualizar el ID para pruebas posteriores
                $testData['serialGoodInventoryId'] = $row['id'];
                return true;
            } else {
                echo "<p>Error: El bien serial no se encontró en el inventario destino.</p>";
                return false;
            }
        } else {
            echo "<p>Error al mover el bien serial.</p>";
            return false;
        }
    }
);

// CASOS DE PRUEBA PARA ELIMINAR BIENES

// Caso 1: Eliminar un bien de tipo cantidad
$runner->registerTest('eliminar_bien_cantidad', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['goodInventoryId'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing delete({$testData['goodInventoryId']})...</p>";

        $result = $goodsInventory->delete($testData['goodInventoryId']);
        if ($result) {
            echo "<p>Bien eliminado correctamente.</p>";
            
            // Verificar que ya no existe
            $sql = "SELECT id FROM bienes_inventarios WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['goodInventoryId']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                echo "<p>Verificado: El bien ya no existe en la base de datos.</p>";
                // Resetear el ID para mostrar que ya no existe
                $testData['goodInventoryId'] = null;
                return true;
            } else {
                echo "<p>Error: El bien sigue existiendo en la base de datos.</p>";
                return false;
            }
        } else {
            echo "<p>Error al eliminar el bien.</p>";
            return false;
        }
    }
);

// Caso 2: Eliminar un bien de tipo serial
$runner->registerTest('eliminar_bien_serial', 
    function() use (&$testData, $goodsInventory) {
        if (!isset($testData['serialGoodInventoryId'])) {
            echo "<p>Error: Primero deben ejecutarse las pruebas anteriores.</p>";
            return false;
        }

        echo "<p>Testing delete({$testData['serialGoodInventoryId']})...</p>";

        $result = $goodsInventory->delete($testData['serialGoodInventoryId']);
        if ($result) {
            echo "<p>Bien serial eliminado correctamente.</p>";
            
            // Verificar que ya no existe
            $sql = "SELECT id FROM bienes_inventarios WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['serialGoodInventoryId']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                echo "<p>Verificado: El bien serial ya no existe en la base de datos.</p>";
                // Resetear el ID para mostrar que ya no existe
                $testData['serialGoodInventoryId'] = null;
                $testData['serialEquipmentId'] = null;
                return true;
            } else {
                echo "<p>Error: El bien serial sigue existiendo en la base de datos.</p>";
                return false;
            }
        } else {
            echo "<p>Error al eliminar el bien serial.</p>";
            return false;
        }
    }
);

// Caso 3: Intentar eliminar un bien inexistente
$runner->registerTest('eliminar_bien_inexistente', 
    function() use ($goodsInventory) {
        $idInexistente = 999999; // ID que probablemente no exista
        
        echo "<p>Testing delete($idInexistente) - bien inexistente...</p>";

        $result = $goodsInventory->delete($idInexistente);
        if ($result === false) {
            echo "<p>Correcto: No se pudo eliminar un bien inexistente.</p>";
            return true;
        } else {
            echo "<p>Error: Se reportó eliminación exitosa de un bien inexistente.</p>";
            return false;
        }
    }
);

// Prueba final de limpieza - eliminar registros temporales
$runner->registerTest('limpieza_final', 
    function() use (&$testData, $goodsInventory) {
        echo "<p>Realizando limpieza de registros temporales...</p>";
        
        // Eliminar bienes temporales si aún existen
        if (isset($testData['goodId'])) {
            $sql = "DELETE FROM bienes WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['goodId']);
            $stmt->execute();
            echo "<p>Bien temporal ID {$testData['goodId']} eliminado.</p>";
        }
        
        if (isset($testData['serialGoodId'])) {
            $sql = "DELETE FROM bienes WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['serialGoodId']);
            $stmt->execute();
            echo "<p>Bien serial temporal ID {$testData['serialGoodId']} eliminado.</p>";
        }
        
        // Eliminar inventarios temporales
        if (isset($testData['inventoryId1'])) {
            $sql = "DELETE FROM inventarios WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['inventoryId1']);
            $stmt->execute();
            echo "<p>Inventario temporal ID {$testData['inventoryId1']} eliminado.</p>";
        }
        
        if (isset($testData['inventoryId2'])) {
            $sql = "DELETE FROM inventarios WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['inventoryId2']);
            $stmt->execute();
            echo "<p>Inventario temporal ID {$testData['inventoryId2']} eliminado.</p>";
        }
        
        // Eliminar grupo temporal
        if (isset($testData['groupId'])) {
            $sql = "DELETE FROM grupos WHERE id = ?";
            $stmt = $goodsInventory->getConnection()->prepare($sql);
            $stmt->bind_param("i", $testData['groupId']);
            $stmt->execute();
            echo "<p>Grupo temporal ID {$testData['groupId']} eliminado.</p>";
        }
        
        return true; // Esta prueba siempre pasa, es solo para limpieza
    }
);

// Si se accede directamente a este archivo (no a través de init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}