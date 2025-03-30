<?php

require_once __DIR__ . '/../models/User.php';

$user = new User();
$userData = $user->getById($id);
$username = $userData['nombre'];

require 'app/views/home.php';