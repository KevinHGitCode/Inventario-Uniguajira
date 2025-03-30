<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method) {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($requestUri) {
        if (array_key_exists($requestUri, $this->routes)) {
            
            $controllerClass = $this->routes[$requestUri]['controller'];
            $Method = $this->routes[$requestUri]['method'];

            require_once __DIR__ . "/$controllerClass.php";
            $controllerInstance = new $controllerClass();
            $controllerInstance->$Method();
        } else {
            require 'app/views/not-found.html';
        }
    }
}
?>