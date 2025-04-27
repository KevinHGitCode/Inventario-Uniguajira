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
    public function addQuantity($inventoryId, $goodId, $quantity) {
        // Primero verificamos si el bien ya existe en el inventario
        $bienInventarioId = $this->getBienInventarioId($goodId, $inventoryId);
        
        if (!$bienInventarioId) {
            // Si no existe, creamos el registro en bienes_inventarios
            $stmt = $this->connection->prepare("
                INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                VALUES (?, ?)
            ");
            $stmt->bind_param("ii", $goodId, $inventoryId);
            if (!$stmt->execute()) {
                return false;
            }
            
            $bienInventarioId = $this->connection->insert_id;
        }
        
        // Ahora añadimos o actualizamos la cantidad
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_cantidad (bien_inventario_id, cantidad)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");
        $stmt->bind_param("ii", $bienInventarioId, $quantity);
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
    public function addSerial($inventoryId, $goodId, $details) {
        // Primero verificamos si el bien ya existe en el inventario
        $bienInventarioId = $this->getBienInventarioId($goodId, $inventoryId);
        
        if (!$bienInventarioId) {
            // Si no existe, creamos el registro en bienes_inventarios
            $stmt = $this->connection->prepare("
                INSERT INTO bienes_inventarios (bien_id, inventario_id) 
                VALUES (?, ?)
            ");
            $stmt->bind_param("ii", $goodId, $inventoryId);
            if (!$stmt->execute()) {
                return false;
            }
            
            $bienInventarioId = $this->connection->insert_id;
        }
        
        // Verificar que el serial no exista ya
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) as count FROM bienes_equipos 
            WHERE serial = ? AND bien_inventario_id = ?
        ");
        $stmt->bind_param("si", $details['serial'], $bienInventarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // El serial ya existe para este bien en este inventario
            return false;
        }
        
        // Insertar el bien de tipo serial
        $stmt = $this->connection->prepare("
            INSERT INTO bienes_equipos (
                bien_inventario_id, 
                descripcion, 
                marca, 
                modelo, 
                serial, 
                estado, 
                color, 
                condiciones_tecnicas, 
                fecha_ingreso
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssss", 
            $bienInventarioId, 
            $details['description'], 
            $details['brand'], 
            $details['model'], 
            $details['serial'], 
            $details['state'], 
            $details['color'], 
            $details['technical_conditions'], 
            $details['entry_date']
        );
        return $stmt->execute();
    }

    /**
     * Obtener el tipo de un producto.
     * 
     * @param int $goodId ID del bien.
     * @return array|false Datos del tipo de bien o false si no existe.
     */
    public function getTipoDeProducto($goodId) {
        $stmt = $this->connection->prepare("
            SELECT tipo FROM bienes WHERE id = ?
        ");
        $stmt->bind_param("i", $goodId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        return $result->fetch_assoc();
    }

    /**
     * Obtener el ID de bien_inventario para un bien y un inventario.
     * 
     * @param int $goodId ID del bien.
     * @param int $inventoryId ID del inventario.
     * @return int|false ID del bien_inventario o false si no existe.
     */
    private function getBienInventarioId($goodId, $inventoryId) {
        $stmt = $this->connection->prepare("
            SELECT id FROM bienes_inventarios 
            WHERE bien_id = ? AND inventario_id = ?
        ");
        $stmt->bind_param("ii", $goodId, $inventoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    /**
     * Eliminar un bien de un inventario.
     * 
     * @param int $id ID del bien en inventario.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        // Determinar si es bien de tipo cantidad o serial
        $stmt = $this->connection->prepare("
            SELECT tipo FROM bienes b
            JOIN bienes_inventarios bi ON b.id = bi.bien_id
            WHERE bi.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        $tipo = $row['tipo'];
        
        // Iniciar transacción
        $this->connection->begin_transaction();
        
        try {
            if ($tipo === 'Cantidad') {
                // Eliminar registro de bienes_cantidad
                $stmt = $this->connection->prepare("
                    DELETE FROM bienes_cantidad WHERE bien_inventario_id = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            } else if ($tipo === 'Serial') {
                // Eliminar registros de bienes_equipos
                $stmt = $this->connection->prepare("
                    DELETE FROM bienes_equipos WHERE bien_inventario_id = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
            
            // Eliminar registro de bienes_inventarios
            $stmt = $this->connection->prepare("
                DELETE FROM bienes_inventarios WHERE id = ?
            ");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Confirmar transacción
            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->connection->rollback();
            return false;
        }
    }

    /**
     * Actualizar la cantidad de un bien en el inventario.
     * 
     * @param int $bienId ID del bien en inventario.
     * @param int $cantidad Nueva cantidad.
     * @return bool Resultado de la operación.
     */
    public function updateQuantity($bienId, $cantidad) {
        $stmt = $this->connection->prepare("
            UPDATE bienes_cantidad 
            SET cantidad = ? 
            WHERE bien_inventario_id = ?
        ");
        $stmt->bind_param("ii", $cantidad, $bienId);
        return $stmt->execute();
    }

    /**
     * Mover un bien a otro inventario.
     * 
     * @param int $bienId ID del bien en inventario.
     * @param int $inventarioDestinoId ID del inventario de destino.
     * @return bool Resultado de la operación.
     */
    public function moveGood($bienId, $inventarioDestinoId) {
        // Obtener información del bien
        $stmt = $this->connection->prepare("
            SELECT bien_id, inventario_id, tipo FROM bienes_inventarios bi
            JOIN bienes b ON bi.bien_id = b.id
            WHERE bi.id = ?
        ");
        $stmt->bind_param("i", $bienId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $row = $result->fetch_assoc();
        $goodId = $row['bien_id'];
        $tipo = $row['tipo'];
        
        // Iniciar transacción
        $this->connection->begin_transaction();
        
        try {
            if ($tipo === 'Cantidad') {
                // Obtener la cantidad actual
                $stmt = $this->connection->prepare("
                    SELECT cantidad FROM bienes_cantidad 
                    WHERE bien_inventario_id = ?
                ");
                $stmt->bind_param("i", $bienId);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $cantidad = $row['cantidad'];
                
                // Añadir al inventario destino
                $this->addQuantity($inventarioDestinoId, $goodId, $cantidad);
                
                // Eliminar del inventario origen
                $this->delete($bienId);
            } else if ($tipo === 'Serial') {
                // Obtener todos los registros de bienes_equipos
                $stmt = $this->connection->prepare("
                    SELECT * FROM bienes_equipos 
                    WHERE bien_inventario_id = ?
                ");
                $stmt->bind_param("i", $bienId);
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Para cada equipo, moverlo al nuevo inventario
                while ($equipo = $result->fetch_assoc()) {
                    $detalles = [
                        'description' => $equipo['descripcion'],
                        'brand' => $equipo['marca'],
                        'model' => $equipo['modelo'],
                        'serial' => $equipo['serial'],
                        'state' => $equipo['estado'],
                        'color' => $equipo['color'],
                        'technical_conditions' => $equipo['condiciones_tecnicas'],
                        'entry_date' => $equipo['fecha_ingreso']
                    ];
                    
                    $this->addSerial($inventarioDestinoId, $goodId, $detalles);
                }
                
                // Eliminar del inventario origen
                $this->delete($bienId);
            }
            
            // Confirmar transacción
            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->connection->rollback();
            return false;
        }
    }
}
?>