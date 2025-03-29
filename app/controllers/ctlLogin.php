<?php
// session_start();
require_once '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;

    $user = new User();
    $dataUser = $user->authentication($username, $password);
    if ($dataUser) {

        session_start();
        $_SESSION['user_id'] = $dataUser['id'];
        $_SESSION['user_name'] = $dataUser['nombre'];
        $_SESSION['user_email'] = $dataUser['email'];
        $_SESSION['user_rol'] = $dataUser['rol'];
        // $_SESSION['user_img'] = $dataUser['img'];

        header("Location: /");

    } else {
        echo 'Identidicate, Quien eres perrooo, que no te tengo aqui anotado en mi celular';
    }
}
