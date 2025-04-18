<?php
require_once '../../app/models/Tasks.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

$runner->registerTest('getByUser', 
    function($idUser) {
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
    }, 
    [1]  // Valor predeterminado
);

$runner->registerTest('create', 
    function($usuario_id, $nombre, $descripcion, $estado) {
        $tasks = new Tasks();
        echo "<p>Testing create() con nombre: '$nombre', para usuario ID $usuario_id</p>";
        
        if ($tasks->create($nombre, $descripcion, $usuario_id, $estado)) {
            echo "<p>Se creó la tarea correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo crear la tarea</p>";
            return false;
        }
    }, 
    [
        1,                      // usuario_id
        "Barrer la cancha",     // nombre
        "Ir a la cancha y barrerla", // descripcion
        "por hacer"             // estado
    ]
);

$runner->registerTest('updateName', 
    function($id, $nombre, $descripcion, $idUsuario) {
        $tasks = new Tasks();
        echo "<p>Testing updateName() para tarea ID $id con nuevo nombre: '$nombre'</p>";
        
        if ($tasks->updateName($id, $nombre, $descripcion, $idUsuario)) {
            echo "<p>Se actualizó la tarea correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar la tarea</p>";
            return false;
        }
    }, 
    [
        2,                      // id
        "Poner un foco",        // nombre
        "Poner foco en la sala 3", // descripcion
        1                       // idUsuario
    ]
);

$runner->registerTest('delete', 
    function($idTarea, $idUsuario) {
        $tasks = new Tasks();
        echo "<p>Testing delete() para tarea ID $idTarea, usuario ID $idUsuario</p>";
        
        if ($tasks->delete($idTarea, $idUsuario)) {
            echo "<p>Se eliminó la tarea correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar la tarea</p>";
            return false;
        }
    }, 
    [
        5,  // idTarea
        1   // idUsuario
    ]
);

$runner->registerTest('changeState', 
    function($idTarea, $idUsuario) {
        $tasks = new Tasks();
        echo "<p>Testing changeState() para tarea ID $idTarea, usuario ID $idUsuario</p>";
        
        if ($tasks->changeState($idTarea, $idUsuario)) {
            echo "<p>Se cambió el estado de la tarea correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo cambiar el estado de la tarea</p>";
            return false;
        }
    }, 
    [
        6,  // idTarea
        1   // idUsuario
    ]
);


// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}