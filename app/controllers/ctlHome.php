<?php

require_once __DIR__ . '/../models/User.php';

$user = new User();
$userData = $user->getById($id);
return $userData['nombre'];
