<?php
require_once '../../app/models/Tasks.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'taskId' => null
];

// Prueba para obtener tareas por usuario
$runner->registerTest('getByUser', function($idUser) {
    $tasks = new Tasks();
    echo "<p>Testing getByUser() para usuario ID $idUser...</p>";
    
    $allTasks = $tasks->getByUser($idUser);
    if (is_array($allTasks)) {
        renderTable($allTasks);
        return true;
    } else {
        echo "<p>No se pudo obtener las tareas como un array</p>";
        return false;
    }
}, [1]); // Valor predeterminado

// Prueba para crear una tarea con datos válidos
$runner->registerTest('createTask_success', function() use (&$testData) {
    $tasks = new Tasks();
    $usuario_id = 1;
    $nombre = "Tarea Temporal " . time();
    $descripcion = "Descripción de prueba";
    $estado = "por hacer";

    echo "<p>Testing create('$nombre', '$descripcion', $usuario_id, '$estado')...</p>";
    $taskId = $tasks->create($nombre, $descripcion, $usuario_id, $estado);

    if ($taskId !== false) {
        echo "<p>Tarea creada exitosamente con ID: $taskId.</p>";
        $testData['taskId'] = $taskId; // Guardar el ID para pruebas posteriores
        return true;
    } else {
        echo "<p>Error al crear la tarea.</p>";
        return false;
    }
});

// Prueba para crear una tarea con datos inválidos
$runner->registerTest('createTask_invalid', function() {
    $tasks = new Tasks();
    $usuario_id = 1;
    $nombre = ""; // Nombre vacío (inválido)
    $descripcion = "Descripción inválida";
    $estado = "por hacer";

    echo "<p>Testing create('$nombre', '$descripcion', $usuario_id, '$estado')...</p>";
    $taskId = $tasks->create($nombre, $descripcion, $usuario_id, $estado);

    if ($taskId === false) {
        echo "<p>Correcto: No se permitió crear una tarea con datos inválidos.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió crear una tarea con datos inválidos.</p>";
        return false;
    }
});

// Prueba para actualizar una tarea con datos válidos
$runner->registerTest('updateTask_success', function() use (&$testData) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createTask_success'.</p>";
        return false;
    }

    $tasks = new Tasks();
    $nuevoNombre = "Tarea Actualizada " . time();
    $nuevaDescripcion = "Descripción actualizada";

    echo "<p>Testing updateName({$testData['taskId']}, '$nuevoNombre', '$nuevaDescripcion', 1)...</p>";
    $result = $tasks->updateName($testData['taskId'], $nuevoNombre, $nuevaDescripcion, 1);

    if ($result) {
        echo "<p>Tarea actualizada correctamente.</p>";
        return true;
    } else {
        echo "<p>Error al actualizar la tarea.</p>";
        return false;
    }
});

// Prueba para actualizar una tarea con datos inválidos
$runner->registerTest('updateTask_invalid', function() use (&$testData) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createTask_success'.</p>";
        return false;
    }

    $tasks = new Tasks();
    $nuevoNombre = ""; // Nombre vacío (inválido)
    $nuevaDescripcion = "Descripción inválida";

    echo "<p>Testing updateName({$testData['taskId']}, '$nuevoNombre', '$nuevaDescripcion', 1)...</p>";
    $result = $tasks->updateName($testData['taskId'], $nuevoNombre, $nuevaDescripcion, 1);

    if (!$result) {
        echo "<p>Correcto: No se permitió actualizar la tarea con datos inválidos.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió actualizar la tarea con datos inválidos.</p>";
        return false;
    }
});

// Prueba para cambiar el estado de una tarea
$runner->registerTest('changeTaskState', function() use (&$testData) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createTask_success'.</p>";
        return false;
    }

    $tasks = new Tasks();
    echo "<p>Testing changeState({$testData['taskId']}, 1)...</p>";
    $result = $tasks->changeState($testData['taskId'], 1);

    if ($result) {
        echo "<p>Estado de la tarea cambiado correctamente.</p>";
        return true;
    } else {
        echo "<p>Error al cambiar el estado de la tarea.</p>";
        return false;
    }
});

// Prueba para eliminar una tarea existente
$runner->registerTest('deleteTask_success', function() use (&$testData) {
    if (!isset($testData['taskId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createTask_success'.</p>";
        return false;
    }

    $tasks = new Tasks();
    echo "<p>Testing delete({$testData['taskId']}, 1)...</p>";
    $result = $tasks->delete($testData['taskId'], 1);

    if ($result) {
        echo "<p>Tarea eliminada correctamente.</p>";
        $testData['taskId'] = null; // Resetear el ID
        return true;
    } else {
        echo "<p>Error al eliminar la tarea.</p>";
        return false;
    }
});

// Prueba para eliminar una tarea inexistente
$runner->registerTest('deleteTask_nonexistent', function() {
    $tasks = new Tasks();
    $idInexistente = 999999; // ID que probablemente no exista

    echo "<p>Testing delete($idInexistente, 1)...</p>";
    $result = $tasks->delete($idInexistente, 1);

    if (!$result) {
        echo "<p>Correcto: No se permitió eliminar una tarea inexistente.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió eliminar una tarea inexistente.</p>";
        return false;
    }
});

// Prueba final de limpieza
$runner->registerTest('limpieza_final', function() use (&$testData, $tasks) {
    if ($testData['taskId'] !== null) {
        echo "<p>Limpieza: Eliminando tarea temporal ID {$testData['taskId']}...</p>";
        $result = $tasks->delete($testData['taskId'], 1);
        if ($result) {
            echo "<p>Tarea temporal eliminada correctamente.</p>";
            $testData['taskId'] = null;
        } else {
            echo "<p>Nota: La tarea temporal no pudo ser eliminada. Puede requerir limpieza manual.</p>";
        }
    } else {
        echo "<p>No hay tareas temporales para limpiar.</p>";
    }

    return true; // Esta prueba siempre pasa, es solo para limpieza
});

// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}