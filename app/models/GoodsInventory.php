<?php
require_once __DIR__ . '/../config/db.php';

class GoodsInventory extends Database {

    /**
     * Constructor de la clase GoodsInventory.
     * Llama al constructor de la clase padre para inicializar la conexión a la base de datos.
     */
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
                imagen, 
                tipo,
                cantidad
            FROM vista_cantidades_bienes_inventario 
            WHERE inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtener todos los bienes de tipo serial de un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes de tipo serial.
     */
    // TODO: Not implement yet
    public function getAllSerialGoodsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                inventario_id, 
                bien_id, 
                inventario, 
                bien, 
                bienes_inventarios_id,
                bienes_equipos_id,
                descripcion,
                marca,
                modelo,
                serial,
                estado,
                color,
                condiciones_tecnicas,
                fecha_ingreso,
                fecha_salida
            FROM vista_bienes_serial_inventario 
            WHERE inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Añadir un bien de tipo cantidad a un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @param int $goodId ID del bien.
     * @param int $quantity Cantidad a añadir.
     * @return bool Resultado de la operación.
     */
    // TODO: Not implement yet
    public function addQuantity($inventoryId, $goodId, $quantity) {
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
            VALUES ((SELECT id FROM bienes_inventarios WHERE bien_id = ? AND inventario_id = ?), ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");
        $stmt->bind_param("iii", $goodId, $inventoryId, $quantity);
        return $stmt->execute();
    }

    /**
     * Añadir un bien de tipo serial a un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @param int $goodId ID del bien.
     * @param array $details Detalles del bien.
     * @return bool Resultado de la operación.
     */
    // TODO: Not implement yet
    public function addSerial($inventoryId, $goodId, $details) {
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_equipos (bien_inventario_id, descripcion, marca, modelo, serial, estado, color, condiciones_tecnicas, fecha_ingreso)
            VALUES ((SELECT id FROM bienes_inventarios WHERE bien_id = ? AND inventario_id = ?), ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssssss", $goodId, $inventoryId, $details['description'], $details['brand'], $details['model'], $details['serial'], $details['state'], $details['color'], $details['technical_conditions'], $details['entry_date']);
        return $stmt->execute();
    }


}
?>
