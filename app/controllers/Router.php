<?php
/**
 * Clase Router
 * 
 * Esta clase gestiona las rutas de la aplicación y despacha las solicitudes
 * a los controladores y métodos correspondientes.
 */
class Router {
    private $routes = [];

    public function add($route, $controller, $method) {
        // Convertimos la ruta a una expresión regular para detectar parámetros tipo :id
        $pattern = preg_replace('/:[^\/]+/', '([^\/]+)', $route);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$pattern] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch($requestUri) {
        foreach ($this->routes as $pattern => $routeInfo) {
            if (preg_match($pattern, $requestUri, $matches)) {
                require_once __DIR__ . '/' . $routeInfo['controller'] . '.php';
                $controller = new $routeInfo['controller'];
                $method = $routeInfo['method'];

                // Quitamos el primer match completo (es toda la URI)
                array_shift($matches);

                // Llamamos al método del controlador con los parámetros capturados
                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }

        // Si no se encuentra ninguna ruta
        http_response_code(404); // Establecer el código de respuesta HTTP a 404
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require "app/views/errors/not-found.html";  // Para solicitudes GET, redirigir a la página 404
        } else {
            // Para otras solicitudes (POST, PUT, etc.), devolver respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
            'success' => false, 
            'message' => 'Ruta incorrecta', 
            'path' => $requestUri
            ]);
        }

        exit;
    }
}

?>