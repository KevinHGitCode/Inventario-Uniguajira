<?php 
require 'app/controllers/Router.php';

$router = new Router();

$router->add('/', 'index.php');
$router->add('/login', 'login.html');
$router->add('/username', '/../controllers/ctlSidebar.php');
$router->add('/404', 'not-found.html');

$router->addController('/api/login', 'ctlUser', 'login');
$router->addController('/home', 'ctlSidebar', 'home');
$router->addController('/goods', 'ctlSidebar', 'goods');
$router->addController('/inventary', 'ctlSidebar', 'inventary');
$router->addController('/users', 'ctlSidebar', 'users');

$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestUri);