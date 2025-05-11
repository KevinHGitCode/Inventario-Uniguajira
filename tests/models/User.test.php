<?php
require_once '../../app/models/User.php';

$user = new User();

$database = Database::getInstance();
$database->setCurrentUser();

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Variable para almacenar IDs de registros temporales
$testData = [
    'userId' => null
];

// Prueba para obtener todos los usuarios
$runner->registerTest('getAllUsers', function() use ($user) {

    echo "<p>Testing getAllUsers()...</p>";
    
    $todosLosUsuarios = $user->getAllUsers();
    if (is_array($todosLosUsuarios)) {
        renderTable($todosLosUsuarios);
        return true;
    } else {
        echo "<p>No se pudo obtener los usuarios como un array</p>";
        return false;
    }
});

// Prueba para crear un usuario con datos únicos
$runner->registerTest('createUser_success', function() use (&$testData, $user) {

    $nombre = "Usuario Temporal " . time();
    $nombre_usuario = "usertemp" . time();
    $email = "temp" . time() . "@example.com";
    $contraseña = "1234";
    $rol = 1;
    $foto_perfil = null;

    echo "<p>Testing createUser('$nombre', '$nombre_usuario', '$email')...</p>";
    $userId = $user->createUser($nombre, $nombre_usuario, $email, $contraseña, $rol, $foto_perfil);

    if ($userId !== false) {
        echo "<p>Usuario creado exitosamente con ID: $userId.</p>";
        $testData['userId'] = $userId; // Guardar el ID para pruebas posteriores
        return true;
    } else {
        echo "<p>Error al crear el usuario.</p>";
        return false;
    }
});

// Prueba para crear un usuario con datos duplicados
$runner->registerTest('createUser_duplicate', function() use (&$testData, $user, $database) {
    if (!isset($testData['userId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createUser_success'.</p>";
        return false;
    }

    $stmt = $database->getConnection()->prepare("SELECT nombre, nombre_usuario, email FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $testData['userId']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo "<p>Testing createUser con datos duplicados...</p>";
    $duplicateUserId = $user->createUser($result['nombre'], $result['nombre_usuario'], $result['email'], "1234", 1, null);

    if ($duplicateUserId === false) {
        echo "<p>Correcto: No se permitió crear un usuario con datos duplicados.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió crear un usuario con datos duplicados.</p>";
        $user->deleteUser($duplicateUserId); // Limpieza
        return false;
    }
});

// Prueba para actualizar un usuario con datos válidos
$runner->registerTest('updateUser_success', function() use (&$testData, $user) {
    if (!isset($testData['userId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createUser_success'.</p>";
        return false;
    }

    $nuevoNombre = "Usuario Actualizado " . time();
    $nuevoEmail = "updated" . time() . "@example.com";
    $nuevoNombreUsuario = "updateduser" . time();

    echo "<p>Testing updateUser({$testData['userId']}, '$nuevoNombre', '$nuevoNombreUsuario', '$nuevoEmail')...</p>";
    $result = $user->updateUser($testData['userId'], $nuevoNombre, $nuevoNombreUsuario, $nuevoEmail);

    if ($result) {
        echo "<p>Usuario actualizado correctamente.</p>";
        return true;
    } else {
        echo "<p>Error al actualizar el usuario.</p>";
        return false;
    }
});

// Prueba para actualizar un usuario con datos inválidos
$runner->registerTest('updateUser_invalid', function() use (&$testData, $user) {
    if (!isset($testData['userId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createUser_success'.</p>";
        return false;
    }

    $nuevoEmail = "invalid-email"; // Email inválido

    echo "<p>Testing updateUser({$testData['userId']}, 'Nombre', 'usuario', '$nuevoEmail')...</p>";
    $result = $user->updateUser($testData['userId'], "Nombre", "usuario", $nuevoEmail);

    if (!$result) {
        echo "<p>Correcto: No se permitió actualizar con datos inválidos.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió actualizar con datos inválidos.</p>";
        return false;
    }
});

// Prueba para eliminar un usuario existente
$runner->registerTest('deleteUser_success', function() use (&$testData, $user) {
    if (!isset($testData['userId'])) {
        echo "<p>Error: Primero debe ejecutarse la prueba 'createUser_success'.</p>";
        return false;
    }

    echo "<p>Testing deleteUser({$testData['userId']})...</p>";
    $result = $user->deleteUser($testData['userId']);

    if ($result) {
        echo "<p>Usuario eliminado correctamente.</p>";
        $testData['userId'] = null; // Resetear el ID
        return true;
    } else {
        echo "<p>Error al eliminar el usuario.</p>";
        return false;
    }
});

// Prueba para eliminar un usuario inexistente
$runner->registerTest('deleteUser_nonexistent', function() use ($user) {

    $idInexistente = 999999; // ID que probablemente no exista

    echo "<p>Testing deleteUser($idInexistente) - usuario inexistente...</p>";
    $result = $user->deleteUser($idInexistente);

    if (!$result) {
        echo "<p>Correcto: No se permitió eliminar un usuario inexistente.</p>";
        return true;
    } else {
        echo "<p>Error: Se permitió eliminar un usuario inexistente.</p>";
        return false;
    }
});

// Prueba final de limpieza
$runner->registerTest('limpieza_final', function() use (&$testData, $user) {
    if ($testData['userId'] !== null) {

        echo "<p>Limpieza: Eliminando usuario temporal ID {$testData['userId']}...</p>";
        $result = $user->deleteUser($testData['userId']);
        if ($result) {
            echo "<p>Usuario temporal eliminado correctamente.</p>";
            $testData['userId'] = null;
        } else {
            echo "<p>Nota: El usuario temporal no pudo ser eliminado. Puede requerir limpieza manual.</p>";
        }
    } else {
        echo "<p>No hay usuarios temporales para limpiar.</p>";
    }

    return true; // Esta prueba siempre pasa, es solo para limpieza
});

// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}