<?php
/**
 * Clase Router
 * 
 * Esta clase gestiona las rutas de la aplicación y despacha las solicitudes
 * a los controladores y métodos correspondientes.
 */
class Router {
    private $routes = [];

    /**
     * Agrega una nueva ruta al enrutador.
     * 
     * @param string $route La ruta de la solicitud (URI).
     * @param string $controller El nombre de la clase del controlador.
     * @param string $method El nombre del método del controlador.
     */
    public function add($route, $controller, $method) {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Despacha la solicitud a la ruta correspondiente.
     * 
     * @param string $requestUri La URI de la solicitud.
     */
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