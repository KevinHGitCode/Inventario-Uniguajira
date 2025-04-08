<?php
require_once '../../app/models/User.php';

function runTests() {
    testGetAllUsers();
    // testCreateUser("Kevin", "kevin@email.com", "1234", 1);
    // testUpdateUser(4, "Updated Name", "updated@email.com", "newPassword123");
    // testUpdatePassword(1, "newPassword");
    // testDeleteUser(2);
    // testAuthentication("username", "password");
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

function testCreateUser($nombre, $email, $contraseña, $rol) {
    $user = new User();
    try {
        $resultado = $user->createUser($nombre, $email, $contraseña, $rol, null, null, null);   
        echo $resultado;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function testUpdateUser($id, $nombre, $email, $contraseña) {
    $user = new User();
    try {
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

function testDeleteUser($id) {
    $user = new User();
    try {
        $resultado = $user->deleteUser($id);
        echo $resultado;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function testAuthentication($nombre, $contraseña) {
    $user = new User();
    try {
        $resultado = $user->authentication($nombre, $contraseña);
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
