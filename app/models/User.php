<?php

class User {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
