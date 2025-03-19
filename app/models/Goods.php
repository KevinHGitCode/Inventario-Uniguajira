<?php

class Goods {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Obtener la lista de todos los bienes (nombre y tipo)
    public function getAll() {
        $stmt = $this->connection->prepare("SELECT id, nombre, tipo FROM bienes");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Crear un nuevo bien
    public function create($nombre, $tipo) {
        $stmt = $this->connection->prepare("INSERT INTO bienes (nombre, tipo) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $tipo);
        return $stmt->execute();
    }
    
    // Modificar un bien (solo el nombre)
    public function updateName($id, $nombre) {
        $stmt = $this->connection->prepare("UPDATE bienes SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    // Eliminar un bien (solo si no tiene relaciones)
    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM bienes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>
