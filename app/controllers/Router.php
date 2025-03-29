<?php
class Router {
    private $routes = [];

    public function add($route, $view) {
        $this->routes[$route] = $view;
    }

    public function dispatch($requestUri) {
        // echo $requestUri;
        $baseDir = __DIR__ . '/../views/';
        if (array_key_exists($requestUri, $this->routes)) {
            require $baseDir . $this->routes[$requestUri];
            // require 'app/views/index.php';
        } else {
            require $baseDir . 'not-found.html';
        }
    }
}
?>