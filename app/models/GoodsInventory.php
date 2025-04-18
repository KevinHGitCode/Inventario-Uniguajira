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
     * Obtener todos los bienes de tipo cantidad de un inventario.
     * 
     * @param int $inventoryId ID del inventario.
     * @return array Arreglo asociativo con los bienes de tipo cantidad.
     */
    public function getAllQuantityGoodsByInventory($inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT 
                inventario_id, 
                bien_id, 
                inventario, 
                bien, 
                cantidad
            FROM vista_cantidades_bienes_inventario 
            WHERE inventario_id = ? AND tipo = 'Cantidad'
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
    public function addQuantityGoodToInventory($inventoryId, $goodId, $quantity) {
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
    public function addSerialGoodToInventory($inventoryId, $goodId, $details) {
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_equipos (bien_inventario_id, descripcion, marca, modelo, serial, estado, color, condiciones_tecnicas, fecha_ingreso)
            VALUES ((SELECT id FROM bienes_inventarios WHERE bien_id = ? AND inventario_id = ?), ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssssss", $goodId, $inventoryId, $details['description'], $details['brand'], $details['model'], $details['serial'], $details['state'], $details['color'], $details['technical_conditions'], $details['entry_date']);
        return $stmt->execute();
    }

    /**
     * Actualizar un bien de tipo cantidad.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @param int $newQuantity Nueva cantidad.
     * @return bool Resultado de la operación.
     */
    public function updateQuantityGood($inventoryGoodId, $newQuantity) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_cantidad
            SET cantidad = ?
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("ii", $newQuantity, $inventoryGoodId);
        return $stmt->execute();
    }

    /**
     * Actualizar un bien de tipo serial.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @param array $newData Nuevos datos del bien.
     * @return bool Resultado de la operación.
     */
    public function updateSerialGood($inventoryGoodId, $newData) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_equipos
            SET descripcion = ?, marca = ?, modelo = ?, serial = ?, estado = ?, color = ?, condiciones_tecnicas = ?, fecha_ingreso = ?, fecha_salida = ?
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("sssssssssi", $newData['description'], $newData['brand'], $newData['model'], $newData['serial'], $newData['state'], $newData['color'], $newData['technical_conditions'], $newData['entry_date'], $newData['exit_date'], $inventoryGoodId);
        return $stmt->execute();
    }

    /**
     * Transferir bienes de tipo cantidad entre inventarios.
     * 
     * @param int $sourceInventoryId ID del inventario origen.
     * @param int $targetInventoryId ID del inventario destino.
     * @param int $goodId ID del bien.
     * @param int $quantity Cantidad a transferir.
     * @throws Exception Si ocurre un error durante la transferencia.
     */
    public function transferQuantityGoods($sourceInventoryId, $targetInventoryId, $goodId, $quantity) {
        $this->connection->begin_transaction();
        try {
            // Restar del inventario origen
            $stmt = $this->connection->prepare("
                UPDATE bienes_cantidad
                SET cantidad = cantidad - ?
                WHERE bien_inventario_id = (SELECT id FROM bienes_inventarios WHERE bien_id = ? AND inventario_id = ?)
            ");
            $stmt->bind_param("iii", $quantity, $goodId, $sourceInventoryId);
            $stmt->execute();
            
            // Añadir al inventario destino
            $this->addQuantityGoodToInventory($targetInventoryId, $goodId, $quantity);
            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }

    /**
     * Transferir bienes de tipo serial entre inventarios.
     * 
     * @param int $sourceInventoryId ID del inventario origen.
     * @param int $targetInventoryId ID del inventario destino.
     * @param int $goodId ID del bien.
     * @return bool Resultado de la operación.
     */
    public function transferSerialGoods($sourceInventoryId, $targetInventoryId, $goodId) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_inventarios
            SET inventario_id = ?
            WHERE bien_id = ? AND inventario_id = ?
        ");
        $stmt->bind_param("iii", $targetInventoryId, $goodId, $sourceInventoryId);
        return $stmt->execute();
    }

    /**
     * Desactivar un bien de tipo cantidad.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @return bool Resultado de la operación.
     */
    public function deactivateQuantityGoods($inventoryGoodId) {
        $stmt = $this->connection->prepare("
            DELETE FROM bienes_cantidad
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryGoodId);
        return $stmt->execute();
    }

    /**
     * Desactivar un bien de tipo serial.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @return bool Resultado de la operación.
     */
    public function deactivateSerialGoods($inventoryGoodId) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_equipos
            SET estado = 'inactivo'
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("i", $inventoryGoodId);
        return $stmt->execute();
    }

    /**
     * Crear un bien en un inventario (por cantidad o equipo).
     * 
     * @param int $inventoryId ID del inventario.
     * @param int $goodId ID del bien.
     * @param mixed $quantityOrDetails Cantidad o detalles del bien.
     * @param string $type Tipo de bien ('quantity' o 'serial').
     * @return bool Resultado de la operación.
     */
    public function create($inventoryId, $goodId, $quantityOrDetails, $type = 'quantity') {
        if ($type === 'quantity') {
            return $this->addQuantityGoodToInventory($inventoryId, $goodId, $quantityOrDetails);
        } else {
            return $this->addSerialGoodToInventory($inventoryId, $goodId, $quantityOrDetails);
        }
    }

    /**
     * Actualizar un bien en un inventario.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @param mixed $newData Nuevos datos del bien.
     * @param string $type Tipo de bien ('quantity' o 'serial').
     * @return bool Resultado de la operación.
     */
    public function update($inventoryGoodId, $newData, $type = 'quantity') {
        if ($type === 'quantity') {
            return $this->updateQuantityGood($inventoryGoodId, $newData);
        } else {
            return $this->updateSerialGood($inventoryGoodId, $newData);
        }
    }

    /**
     * Eliminar un bien de un inventario.
     * 
     * @param int $inventoryGoodId ID del bien en el inventario.
     * @param string $type Tipo de bien ('quantity' o 'serial').
     * @return bool Resultado de la operación.
     */
    public function delete($inventoryGoodId, $type = 'quantity') {
        if ($type === 'quantity') {
            return $this->deactivateQuantityGoods($inventoryGoodId);
        } else {
            return $this->deactivateSerialGoods($inventoryGoodId);
        }
    }
}
?>
