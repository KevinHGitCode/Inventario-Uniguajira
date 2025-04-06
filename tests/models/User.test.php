<?php
require_once '../../app/models/User.php';

function runTests() {
    // testGetAllUsers();
    // testCreateUser();
    // testUpdateUser();
    // testUpdatePassword(1, "admin");
    // testUpdatePassword(6, "consul");
    // testDeleteUser();
   
    testAuthentication("renzo", "1234");
    testAuthentication("RENZO", "1234");
}

function testGetAllUsers() {
    $user = new User();
    try {
        $todosLosUsuarios = $user->getAllUsers();
        echo "Usuarios registrados:<br>";
        foreach ($todosLosUsuarios as $usuario) {
            echo "ID: " . $usuario['id'] . "<br>";
            echo "Nombre: " . $usuario['nombre'] . "<br>";
            echo "Nombre de usuario: " . $usuario['nombre_usuario'] . "<br>";
            echo "Email: " . $usuario['email'] . "<br>";
            echo "Rol: " . $usuario['rol'] . "<br>";
            echo "Fecha de creación: " . $usuario['fecha_creacion'] . "<br>";
            echo "Último acceso: " . $usuario['fecha_ultimo_acceso'] . "<br>";
            echo "Foto de perfil: " . $usuario['foto_perfil'] . "<br>";
            echo "------------------------<br>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function testCreateUser() {
    $user = new User();
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
}

function testUpdateUser() {
    $user = new User();
    try {
        $id = 4;
        $nombre = "makiabelico Actualizado";
        $email = "makiabelico.com";
        $contraseña = "nuevaPassword123";
        $rol = 2;

        $resultado = $user->updateUser($id, $nombre, $email, $contraseña);
        echo $resultado;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function testUpdatePassword($id, $nuevaContraseña) {
    $user = new User();
    try {
        $resultado = $user->updatePassword($id, $nuevaContraseña);
        echo $resultado . "<br>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function testDeleteUser() {
    $user = new User();
    try {
        $id = 2;

        $resultado = $user->deleteUser($id);
        echo $resultado;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


function testAuthentication($nombre, $contraseña) {
    
$user = new User();
try {
    $resultado = $user->authenticate($nombre, $contraseña);

    if ($resultado) {
        echo "Autenticación exitosa. Bienvenido, " . $resultado['nombre'] . "<br>";
        echo "Datos del usuario:<br>";
        echo "ID: " . $resultado['id'] . "<br>";
        echo "Email: " . $resultado['email'] . "<br>";
        echo "Rol: " . $resultado['rol'] . "<br>";
        echo "Fecha de creación: " . $resultado['fecha_creacion'] . "<br>";
        echo "Último acceso: " . $resultado['fecha_ultimo_acceso'] . "<br>";
        echo "Foto de perfil: " . $resultado['foto_perfil'] . "<br>";
    } else {
        echo "Error: Usuario o contraseña incorrectos.<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
}




runTests();
