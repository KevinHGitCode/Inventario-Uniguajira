<?php
require_once '../../app/models/User.php';

// Crear una instancia del runner pero NO manejar la solicitud web automáticamente
$runner = new TestRunner();

// Registrar todas las pruebas disponibles
$runner->registerTest('getAllUsers', function() {
    $user = new User();
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

$runner->registerTest('createUser', 
    function($nombre, $nombre_usuario, $email, $contraseña, $rol, $foto_perfil) {
        $user = new User();
        echo "<p>Testing createUser() con nombre: '$nombre', nombre_usuario: '$nombre_usuario', email: '$email'</p>";
        
        $resultado = $user->createUser($nombre, $nombre_usuario, $email, $contraseña, $rol, $foto_perfil);
        if ($resultado) {
            echo "<p>Se creó el usuario correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo crear el usuario</p>";
            return false;
        }

    }, [ // Valores predeterminados para la prueba
        "Usuario Test", // Nombre
        "usertest", // Nombre de usuario
        "test@example.com", // Email
        "1234", // Contraseña
        1, // Rol (1 = admin, 2 = user)
        null // Foto de perfil (puede ser null o una ruta a una imagen)
    ]
);


$runner->registerTest('updateUser', 
    function($id, $nombre, $email, $contraseña) {
        $user = new User();
        echo "<p>Testing updateUser() para ID $id con nuevo nombre: '$nombre', email: '$email'</p>";
        
        $resultado = $user->updateUser($id, $nombre, $email, $contraseña);
        if ($resultado) {
            echo "<p>Se actualizó el usuario correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar el usuario</p>";
            return false;
        }
    }, [ // Valores predeterminados
        1, // id
        "Nombre Actualizado", // nombre
        "actualizado@example.com", // email
        "nuevapass123" // contraseña
    ]
);

$runner->registerTest('updatePassword', 
    function($id, $nuevaContraseña) {
        $user = new User();
        echo "<p>Testing updatePassword() para ID $id</p>";
        
        $resultado = $user->updatePassword($id, $nuevaContraseña);
        if ($resultado) {
            echo "<p>Se actualizó la contraseña correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo actualizar la contraseña</p>";
            return false;
        }
    }, [
        1, // id
        "nuevaPass456" // nueva contraseña
    ]
);

$runner->registerTest('deleteUser', 
    function($id) {
        $user = new User();
        echo "<p>Testing deleteUser() para ID $id</p>";
        
        $resultado = $user->deleteUser($id);
        if ($resultado) {
            echo "<p>Se eliminó el usuario correctamente</p>";
            return true;
        } else {
            echo "<p>No se pudo eliminar el usuario</p>";
            return false;
        }
    }, [
        1 // id
    ]
);

$runner->registerTest('authentication', 
    function($nombre_usuario, $contraseña) {
        $user = new User();
        echo "<p>Testing authentication() con usuario: '$nombre_usuario'</p>";
        
        $resultado = $user->authentication($nombre_usuario, $contraseña);
        if ($resultado) {
            echo "<p>Autenticación exitosa. Bienvenido, " . $resultado['nombre'] . "</p>";
            echo "<p>Datos del usuario:</p>";
            echo "<ul>";
            echo "<li>ID: " . $resultado['id'] . "</li>";
            echo "<li>Email: " . $resultado['email'] . "</li>";
            echo "<li>Rol: " . $resultado['rol'] . "</li>";
            echo "<li>Fecha de creación: " . $resultado['fecha_creacion'] . "</li>";
            echo "<li>Último acceso: " . $resultado['fecha_ultimo_acceso'] . "</li>";
            if (isset($resultado['foto_perfil'])) {
                echo "<li>Foto de perfil: " . $resultado['foto_perfil'] . "</li>";
            }
            echo "</ul>";
            return true;
        } else {
            echo "<p>Error: Usuario o contraseña incorrectos.</p>";
            return false;
        }
    }, [
        "admin", // nombre de usuario
        "admin123" // contraseña
    ]
);


// Si se accede directamente a este archivo (no a través de .init-tests.php), redirigir al índice
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    header('Location: /test');
    exit;
}