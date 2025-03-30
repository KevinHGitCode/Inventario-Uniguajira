<?php

/**
 * Clase ctlView
 * Controlador para manejar las vistas de la aplicación.
 */
class ctlView {

    /**
     * Muestra la vista principal (index).
     *
     * @return void
     */
    public function index() {
        include 'app/views/index.php';
    }

    /**
     * Muestra la vista de inicio de sesión (login).
     *
     * @return void
     */
    public function login() {
        include 'app/views/login.php';
    }

    /**
     * Muestra la vista de documentación (doc).
     *
     * @return void
     */
    public function doc() {
        include 'app/views/doc.php';
    }

    /**
     * Muestra la vista de error 404 (not found).
     *
     * @return void
     */
    public function notFound() {
        include 'app/views/not-found.php';
    }

    /**
     * Envía una respuesta en formato JSON.
     *
     * @param array $response Datos a enviar en la respuesta JSON.
     * @return void
     */
    private function sendJsonResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}