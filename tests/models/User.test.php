<?php
require_once '../../app/models/User.php';

$user = new User();

/*$id = 1;
$expectedResult = [
    'id' => 1,
    'nombre' => 'Administrador',
    'email' => 'admin@email.com',
    'contraseña' => 'administrador',
    'rol' => 'administrador'
];

$result = $user->getById($id);

if ($result == $expectedResult) {
    echo "Test passed!";
} else {
    echo "Test failed!";
}

echo "<br>";
*/



try {
    // Obtener todos los usuarios
    $todosLosUsuarios = $user->getAllUsers();

    echo "Usuarios registrados:<br>";
    foreach ($todosLosUsuarios as $usuario) {
        echo "ID: " . $usuario['id'] . "<br>";
        echo "Nombre: " . $usuario['nombre'] . "<br>";
        echo "Email: " . $usuario['email'] . "<br>";
        echo "Rol: " . $usuario['rol'] . "<br>";
        echo "------------------------<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} 



/*
// Ejemplo de uso crear nuevo usuario

try {
    $nombre = "Kevin";
    $email = "kevin@email.com";
    $contraseña = "1234";
    $rol = 1;

    $resultado = $user->createUser($nombre, $email, $contraseña, $rol);
    echo $resultado;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

//ejemplo de cambiar datos de un usuario

try {
    $id = 4; // ID del usuario a actualizar
    $nombre = "makiabelico Actualizado";
    $email = "makiabelico.com";
    $contraseña = "nuevaPassword123";
    $rol = 2;

    $resultado = $user->updateUser($id, $nombre, $email, $contraseña);
    echo $resultado;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

//ejemplo de cambio de contrseña
*/


// try {
//     $id = 8; // ID del usuario a actualizar
//     $nuevaContraseña = "1234";

//     $resultado = $user->updatePassword($id, $nuevaContraseña);
//     echo $resultado;
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }


/*
//ejemplo de eliminacion del usuario

try {
    $id = 2; // ID del usuario a eliminar (asegúrate de que no sea 1)

    $resultado = $user->deleteUser($id);
    echo $resultado;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}*/

// try {
//     $nombre = "makiabelico Actualizado";
//     $contraseña = "nuevaPassword123";

//     echo var_dump($user->authentication($nombre, $contraseña));  

// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
// ?>