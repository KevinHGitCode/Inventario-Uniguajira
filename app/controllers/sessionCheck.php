<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if no session exists
    $url = explode('/', $_SERVER['REQUEST_URI']);
    header("Location: /login");
    exit();
}

