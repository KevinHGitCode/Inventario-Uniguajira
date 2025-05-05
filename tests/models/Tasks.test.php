<?php
require_once '../../app/models/Tasks.php';

// Crear una instancia única del modelo Tasks
$tasks = new Tasks();

// Crear una instancia del runner
$runner = new TestRunner();

// Variable para almacenar IDs temporales
$testData = [
    'taskId' => null
];

// PRUEBAS DE LECTURA
$runner->registerTest('obtener_tareas_usuario', function($userId) use ($tasks) {
    echo "<p>Testing getByUser para usuario ID $userId...</p>";
    
    $userTasks = $tasks->getByUser($userId);
    if (is_array($userTasks)) {
        renderTable($userTasks);
        return true;
    }
    echo "<p>Error: No se pudieron obtener las tareas como array</p>";
    return false;
}, [1]);
// PRUEBAS DE CREACIÓN
$runner->registerTest('crear_tarea_valida', function() use (&$testData, $tasks) {
    $nombre = "Tarea Temporal " . time();
    $descripcion = "Descripción de prueba";
    $usuario_id = 1;

    echo "<p>Testing create con datos válidos...</p>";
    $taskId = $tasks->create($nombre, $descripcion, $usuario_id);

    if ($taskId !== false) {
        echo "<p>Tarea creada con ID: $taskId</p>";
        $testData['taskId'] = $taskId;
        return true;
    }
    echo "<p>Error al crear la tarea</p>";
    return false;
});

$runner->registerTest('crear_tarea_nombre_vacio', function() use ($tasks) {
    $nombre = "";
    $descripcion = "Descripción de prueba";
    $usuario_id = 1;

    echo "<p>Testing create con nombre vacío...</p>";
    if ($tasks->create($nombre, $descripcion, $usuario_id) === false) {
        echo "<p>Correcto: No se permitió crear con nombre vacío</p>";
        return true;
    }
    echo "<p>Error: Se permitió crear con nombre vacío</p>";
    return false;
});

// PRUEBAS DE ACTUALIZACIÓN
$runner->registerTest('actualizar_tarea_valida', function() use (&$testData, $tasks) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Requiere crear_tarea_valida primero</p>";
        return false;
    }

    $nuevoNombre = "Tarea Actualizada " . time();
    $nuevaDescripcion = "Descripción actualizada";

    echo "<p>Testing updateName con datos válidos...</p>";
    if ($tasks->updateName($testData['taskId'], $nuevoNombre, $nuevaDescripcion, 1)) {
        echo "<p>Tarea actualizada correctamente</p>";
        return true;
    }
    echo "<p>Error al actualizar la tarea</p>";
    return false;
});

$runner->registerTest('actualizar_tarea_nombre_vacio', function() use (&$testData, $tasks) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Requiere crear_tarea_valida primero</p>";
        return false;
    }

    $nuevoNombre = "";
    $nuevaDescripcion = "Descripción inválida";

    echo "<p>Testing updateName con nombre vacío...</p>";
    if (!$tasks->updateName($testData['taskId'], $nuevoNombre, $nuevaDescripcion, 1)) {
        echo "<p>Correcto: No se permitió actualizar con nombre vacío</p>";
        return true;
    }
    echo "<p>Error: Se permitió actualizar con nombre vacío</p>";
    return false;
});

// PRUEBAS DE ESTADO
$runner->registerTest('cambiar_estado_tarea', function() use (&$testData, $tasks) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Requiere crear_tarea_valida primero</p>";
        return false;
    }

    echo "<p>Testing changeState...</p>";
    if ($tasks->changeState($testData['taskId'], 1)) {
        echo "<p>Estado cambiado correctamente</p>";
        return true;
    }
    echo "<p>Error al cambiar el estado</p>";
    return false;
});

// PRUEBAS DE ELIMINACIÓN
$runner->registerTest('eliminar_tarea_existente', function() use (&$testData, $tasks) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Requiere crear_tarea_valida primero</p>";
        return false;
    }

    echo "<p>Testing delete tarea existente...</p>";
    if ($tasks->delete($testData['taskId'], 1)) {
        echo "<p>Tarea eliminada correctamente</p>";
        $testData['taskId'] = null;
        return true;
    }
    echo "<p>Error al eliminar la tarea</p>";
    return false;
});

$runner->registerTest('eliminar_tarea_inexistente', function() use ($tasks) {
    $idInexistente = 999999;

    echo "<p>Testing delete tarea inexistente...</p>";
    if (!$tasks->delete($idInexistente, 1)) {
        echo "<p>Correcto: No se permitió eliminar tarea inexistente</p>";
        return true;
    }
    echo "<p>Error: Se permitió eliminar tarea inexistente</p>";
    return false;
});

// PRUEBA FINAL DE LIMPIEZA
$runner->registerTest('limpieza_final', function() use (&$testData, $tasks) {
    if ($testData['taskId'] !== null) {
        echo "<p>Limpieza: Eliminando tarea temporal ID {$testData['taskId']}...</p>";
        if ($tasks->delete($testData['taskId'], 1)) {
            echo "<p>Tarea temporal eliminada correctamente</p>";
            $testData['taskId'] = null;
        } else {
            echo "<p>Nota: La tarea temporal no pudo ser eliminada. Puede requerir limpieza manual</p>";
        }
    } else {
        echo "<p>No hay tareas temporales para limpiar</p>";
    }

    return true;
});

if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}