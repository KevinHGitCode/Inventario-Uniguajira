<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit();
}

// Configurar la zona horaria solo si existen las variables de sesión
if (isset($_SESSION['timezone_name'])) {
    date_default_timezone_set($_SESSION['timezone_name']);
}

setlocale(LC_TIME, 'es_CO.UTF-8', 'es_CO', 'es');
