<?php 
require 'app/controllers/Router.php';

$router = new Router();

$router->add('/', 'index.php');
$router->add('/login', 'login.html');
$router->add('/404', 'not-found.html');

$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestUri);


// include 'app/controllers/Controller.class.php';
// $ctl = new Controller();
// $ctl->index();