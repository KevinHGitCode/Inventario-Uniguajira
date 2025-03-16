<?php

class Controller {
    public function index() {
        $response = [
            'status' => 'success',
            'message' => 'Bienvenido a la página principal'
        ];
        $this->sendJsonResponse($response);
    }

    private function sendJsonResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}