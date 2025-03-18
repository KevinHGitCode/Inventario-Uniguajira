<?php

require_once '../../app/models/User.php';
require_once '../../app/config/config.php';

$database = new Database();
$connection = $database->getConnection();
$user = new User($connection);

$id = 1;
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
?>