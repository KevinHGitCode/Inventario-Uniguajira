<?php 
require 'app/controllers/Router.php';

$router = new Router();

$router->add('/', 'index.php');
$router->add('/login', 'login.html');
$router->add('/404', 'not-found.html');

// $requestUri = $_SERVER['REQUEST_URI'];
// $folderPath = dirname($_SERVER['SCRIPT_NAME']);
$url = substr($requestUri, strlen($folderPath));

$router->dispatch($url);
// echo 'requestUri: '.$requestUri.'<br>';
// echo 'folderPath: '.$folderPath.'<br>';
// echo 'url: '.$url;

// include 'app/controllers/Controller.class.php';
// $ctl = new Controller();
// $ctl->index();