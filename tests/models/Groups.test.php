<?php
require_once '../../app/models/Groups.php';

// Crear una instancia única del modelo Groups
$group = new Groups();

$database = Database::getInstance();
$database->setCurrentUser();

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'groupId' => null
];

// Obtener todos los grupos
$runner->registerTest('getAllGroups', function() use ($group) {
    echo "<p>Testing getAllGroups()...</p>";
    
    $groups = $group->getAllGroups();
    if (!empty($groups)) {
        renderTable($groups);
        return true;
    } else {
        echo "<p>No hay grupos registrados.</p>";
        return false;
    }
});

// Obtener inventarios de un grupo específico
$runner->registerTest('getInventoriesByGroup', 
    function($groupId) use ($group) {
        echo "<p>Testing getInventoriesByGroup($groupId)...</p>";

        $groups = $group->getInventoriesByGroup($groupId);
        if (!empty($groups)) {
            echo "<div class='test-output-detail'>";
            foreach ($groups as $groupsItem) {
                echo "ID: {$groupsItem['id']}, Nombre: {$groupsItem['nombre']}<br>";
            }
            echo "</div>";
            return true;
        } else {
            echo "<p>No hay inventarios en este grupo.</p>";
            // No es un error que un grupo no tenga inventarios
            return true;
        }
    }, [
        1 // ID del grupo
    ]
);

// CASOS DE PRUEBA PARA CREAR GRUPO

// Caso 1: Crear un grupo con nombre que no existe
$runner->registerTest('crear_grupo_nuevo', 
    function() use (&$testData, $group) {
        $nombreGrupo = "Grupo Temporal Test " . time(); // Nombre único
        echo "<p>Testing createGroup('$nombreGrupo')...</p>";

        $groupId = $group->create($nombreGrupo);
        if ($groupId !== false) {
            echo "<p>Grupo '$nombreGrupo' creado exitosamente con ID: $groupId.</p>";
            // Guardar el ID para pruebas posteriores
            $testData['groupId'] = $groupId;
            return true;
        } else {
            echo "<p>Error al crear el grupo.</p>";
            return false;
        }
    }
);

// Caso 2: Intentar crear un grupo con nombre que ya existe
$runner->registerTest('crear_grupo_nombre_existente', 
    function() use (&$testData, $group, $database) {
        if (!isset($testData['groupId']) || $testData['groupId'] === null) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'crear_grupo_nuevo'.</p>";
            return false;
        }

        // Obtener el nombre del grupo recién creado
        $stmt = $database->getConnection()->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $testData['groupId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nombreExistente = $row['nombre'];
        
        echo "<p>Testing createGroup('$nombreExistente') - grupo ya existente...</p>";

        $result = $group->create($nombreExistente);
        if ($result === false) {
            echo "<p>Correcto: No se creó un grupo con nombre duplicado.</p>";
            return true;
        } else {
            echo "<p>Error: Se permitió crear un grupo con nombre duplicado.</p>";
            // Eliminar el grupo duplicado para limpieza
            $group->delete($result);
            return false;
        }
    }
);

// Caso 3: Intentar crear un grupo con nombre que solo difiere en mayúsculas/minúsculas
$runner->registerTest('crear_grupo_case_sensitive', 
    function() use (&$testData, $group, $database) {
        if (!isset($testData['groupId']) || $testData['groupId'] === null) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'crear_grupo_nuevo'.</p>";
            return false;
        }

        // Obtener el nombre del grupo recién creado
        $stmt = $database->getConnection()->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $testData['groupId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nombreOriginal = $row['nombre'];
        
        // Cambiar a mayúsculas o minúsculas
        $nombreModificado = strtoupper($nombreOriginal) === $nombreOriginal ? 
                            strtolower($nombreOriginal) : 
                            strtoupper($nombreOriginal);
        
        echo "<p>Testing createGroup('$nombreModificado') - mismo nombre con diferente caso...</p>";

        $result = $group->create($nombreModificado);
        // Verificar comportamiento según la implementación actual
        // Si la base de datos es case-insensitive, debería fallar
        if ($result === false) {
            echo "<p>La base de datos parece ser case-insensitive para nombres de grupos.</p>";
            return true;
        } else {
            echo "<p>La base de datos parece ser case-sensitive para nombres de grupos.</p>";
            // Eliminar el grupo creado para limpieza
            $group->delete($result);
            return true;
        }
    }
);

// CASOS DE PRUEBA PARA RENOMBRAR GRUPO

// Caso 1: Renombrar grupo temporal a un nombre válido
$runner->registerTest('renombrar_grupo_exitoso', 
    function() use (&$testData, $group) {
        if (!isset($testData['groupId']) || $testData['groupId'] === null) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'crear_grupo_nuevo'.</p>";
            return false;
        }

        $nuevoNombre = "Grupo Temporal Renombrado " . time();
        echo "<p>Testing renameGroup({$testData['groupId']}, '$nuevoNombre')...</p>";

        if ($group->rename($testData['groupId'], $nuevoNombre)) {
            echo "<p>Grupo renombrado correctamente.</p>";
            return true;
        } else {
            echo "<p>Error al renombrar el grupo.</p>";
            return false;
        }
    }
);

// Caso 2: Renombrar grupo inexistente
$runner->registerTest('renombrar_grupo_inexistente', 
    function() use ($group) {
        $idInexistente = 999999; // ID que probablemente no exista
        $nuevoNombre = "Nombre para grupo inexistente";
        
        echo "<p>Testing renameGroup($idInexistente, '$nuevoNombre') - grupo inexistente...</p>";

        if ($group->rename($idInexistente, $nuevoNombre)) {
            echo "<p>Error: Se permitió renombrar un grupo inexistente.</p>";
            return false;
        } else {
            echo "<p>Correcto: No se permitió renombrar un grupo inexistente.</p>";
            return true;
        }
    }
);

// Caso 3: Renombrar con el mismo nombre (no debería tener efecto)
$runner->registerTest('renombrar_mismo_nombre', 
    function() use (&$testData, $group, $database) {
        if (!isset($testData['groupId']) || $testData['groupId'] === null) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'crear_grupo_nuevo'.</p>";
            return false;
        }

        // Obtener el nombre actual del grupo
        $stmt = $database->getConnection()->prepare("SELECT nombre FROM grupos WHERE id = ?");
        $stmt->bind_param("i", $testData['groupId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nombreActual = $row['nombre'];
        
        echo "<p>Testing renameGroup({$testData['groupId']}, '$nombreActual') - mismo nombre...</p>";

        if ($group->rename($testData['groupId'], $nombreActual)) {
            echo "<p>Error: La función debería detectar que el nombre es el mismo.</p>";
            return false;
        } else {
            echo "<p>Correcto: No se realizaron cambios al intentar renombrar con el mismo nombre.</p>";
            return true;
        }
    }
);

// CASOS DE PRUEBA PARA ELIMINAR GRUPO

// Caso 1: Eliminar grupo sin inventarios (el grupo temporal)
$runner->registerTest('eliminar_grupo_sin_inventarios', 
    function() use (&$testData, $group) {
        if (!isset($testData['groupId']) || $testData['groupId'] === null) {
            echo "<p>Error: Primero debe ejecutarse la prueba 'crear_grupo_nuevo'.</p>";
            return false;
        }

        echo "<p>Testing deleteGroup({$testData['groupId']}) - grupo sin inventarios...</p>";

        if ($group->delete($testData['groupId'])) {
            echo "<p>Grupo temporal eliminado correctamente.</p>";
            // Resetear el ID para mostrar que ya no existe
            $testData['groupId'] = null;
            return true;
        } else {
            echo "<p>Error al eliminar el grupo temporal.</p>";
            return false;
        }
    }
);

// Caso 2: Eliminar grupo con inventarios (no debería permitirlo)
$runner->registerTest('eliminar_grupo_con_inventarios', 
    function() use ($group, $database) {
        
        // Buscar un grupo que tenga inventarios asociados
        $sql = "SELECT g.id FROM grupos g 
                INNER JOIN inventarios i ON g.id = i.grupo_id 
                GROUP BY g.id HAVING COUNT(i.id) > 0 LIMIT 1";
        $result = $database->getConnection()->query($sql);
        
        if ($result->num_rows === 0) {
            echo "<p>No se encontró ningún grupo con inventarios para la prueba.</p>";
            return true; // No es un error, simplemente no hay datos para probar
        }
        
        $row = $result->fetch_assoc();
        $groupIdWithInventories = $row['id'];
        
        echo "<p>Testing deleteGroup($groupIdWithInventories) - grupo con inventarios...</p>";

        if ($group->delete($groupIdWithInventories)) {
            echo "<p>Error: Se permitió eliminar un grupo con inventarios asociados.</p>";
            return false;
        } else {
            echo "<p>Correcto: No se permitió eliminar un grupo con inventarios asociados.</p>";
            return true;
        }
    }
);

// Caso 3: Intentar eliminar un grupo inexistente
$runner->registerTest('eliminar_grupo_inexistente', 
    function() use ($group, $database) {
        $idInexistente = 999999; // ID que probablemente no exista
        
        echo "<p>Testing deleteGroup($idInexistente) - grupo inexistente...</p>";

        if ($group->delete($idInexistente)) {
            echo "<p>Error: Se permitió eliminar un grupo inexistente.</p>";
            return false;
        } else {
            echo "<p>Correcto: No se permitió eliminar un grupo inexistente.</p>";
            return true;
        }
    }
);

// Prueba final de limpieza - asegurarse de eliminar el grupo temporal si quedó alguno
$runner->registerTest('limpieza_final', 
    function() use (&$testData, $group) {
        if ($testData['groupId'] !== null) {
            echo "<p>Limpieza: Eliminando grupo temporal ID {$testData['groupId']}...</p>";
            
            $result = $group->delete($testData['groupId']);
            if ($result) {
                echo "<p>Grupo temporal eliminado correctamente.</p>";
                $testData['groupId'] = null;
            } else {
                echo "<p>Nota: El grupo temporal no pudo ser eliminado. Puede requerir limpieza manual.</p>";
            }
        } else {
            echo "<p>No hay grupos temporales para limpiar.</p>";
        }
        
        return true; // Esta prueba siempre pasa, es solo para limpieza
    }
);

// Si se accede directamente a este archivo (no a través de init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}