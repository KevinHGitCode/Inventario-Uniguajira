<?php
require_once __DIR__ . '/../config/db.php';

class GoodsInventory extends Database {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Obtener todos los bienes de un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes del inventario.
     */
    public function getAllGoodsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                inventario_id, 
                bien_id, 
                inventario, 
                bien, 
                cantidad
            FROM vista_cantidades_bienes_inventario 
            WHERE inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Firmas de los demás métodos CRUD:
    public function create($inventoryId, $goodId, $quantityOrDetails) {
        // Crear un bien en un inventario (por cantidad o equipo)
    }

    public function update($inventoryGoodId, $newData) {
        // Actualizar un bien en un inventario
    }

    public function delete($inventoryGoodId) {
        // Eliminar un bien de un inventario
    }
}
?>