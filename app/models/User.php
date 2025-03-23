<?php

class User {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getById($id) {
        // TODO: Cambiar * por los campos que se necesitan
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // TODO: Agregar más métodos

}
