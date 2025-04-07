<?php 
require 'app/controllers/Router.php';

$router = new Router();

// Rutas para vistas principales
$router->add('/', 'ctlView', 'index');
$router->add('/login', 'ctlView', 'login');
$router->add('/doc', 'ctlView', 'doc');
$router->add('/404', 'ctlView', 'notFound');

// Rutas para el sidebar
$router->add('/home', 'ctlSidebar', 'home');
$router->add('/goods', 'ctlSidebar', 'goods');
$router->add('/inventary', 'ctlSidebar', 'inventary');
$router->add('/users', 'ctlSidebar', 'users');

// Rutas para la API de usuarios
$router->add('/api/login', 'ctlUser', 'login');
$router->add('/api/logout', 'ctlUser', 'logout');


// Rutas para la API de bienes
$router->add('/api/goods/create', 'ctlGoods', 'create');
$router->add('/api/goods/delete/:id', 'ctlGoods', 'delete');




// Mas rutas...


// Despachar la solicitud según la URI
$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestUri);