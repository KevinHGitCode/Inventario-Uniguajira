<?php
class Router {
    private $routes = [];
    private $controllerRoutes = [];

    public function add($route, $view) {
        $this->routes[$route] = $view;
    }

    public function addController($route, $controller, $method) {
        $this->controllerRoutes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($requestUri) {
        // __DIR__ is 'C:\xampp\htdocs\Inventario-Uniguajira\app\controllers'
        $baseDir = __DIR__ . '/../views/';
        if (array_key_exists($requestUri, $this->routes)) {
            require $baseDir . $this->routes[$requestUri];
        } elseif (array_key_exists($requestUri, $this->controllerRoutes)) {
            $controllerInfo = $this->controllerRoutes[$requestUri];
            $controllerName = $controllerInfo['controller'];
            $methodName = $controllerInfo['method'];
            require_once __DIR__ . "/$controllerName.php";
            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            require $baseDir . 'not-found.html';
        }
    }
}
?>